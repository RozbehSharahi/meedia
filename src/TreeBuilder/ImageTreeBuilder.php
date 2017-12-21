<?php

namespace RozbehSharahi\Meedia\TreeBuilder;

use Ssh\Authentication\Agent;
use Ssh\Authentication\Password;
use Ssh\Configuration;
use Ssh\Session;

class ImageTreeBuilder implements TreeBuilderInterface
{

    /**
     * @var string
     */
    protected $source;

    /**
     * @var Session
     */
    protected $ssh;

    /**
     * @var \stdClass
     */
    protected $configuration;

    /**
     * TreeBuilderInterface constructor.
     *
     * @param \stdClass $configuration
     * @throws \Exception
     */
    public function __construct(\stdClass $configuration)
    {
        if (empty($configuration->source)) {
            throw new \Exception('Configuration misses a source property for ' . static::class);
        }

        if (empty($configuration->host)) {
            throw new \Exception('Configuration misses a host property for ' . static::class);
        }

        if (empty($configuration->port)) {
            throw new \Exception('Configuration misses a port property for ' . static::class);
        }

        $this->configuration = $configuration;
        $this->source = $configuration->source;
        $this->ssh = $this->getSsh();
    }

    /**
     * @return array
     */
    public function getTree()
    {
        // The command to get file sizes
        $commandOutput = $this->ssh
            ->getExec()
            ->run(
                'cd ' . $this->source . ' && ' .
                'find . -type f \( -name "*.png" -o -name "*.gif" -o -name "*.jpg" \)'.
                ' -exec ' . $this->getIdentifyCommand() . ' -format "%w||||%h||||" {} \; -exec echo {} \;');

        // Prepare output since there might be unwanted line breaks
        $fileDescriptions = str_replace('||||' . PHP_EOL, '||||', trim($commandOutput));

        return array_map(function ($fileDescription) {
            $info = explode('||||', $fileDescription);

            $width = $info[0];
            $height = $info[1];

            // gifs will return for every frame width, and height, therefor we have to take the last part of the array
            // to get the path
            $path = $info[count($info) - 1];

            // Assert correct format
            if (empty($width) || empty($height) || empty($path)) {
                throw new \Exception('File description: ' . $fileDescription . ' could not be interpreted.');
            }

            return [
                'width' => $width,
                'height' => $height,
                'path' => $path
            ];
        }, explode(PHP_EOL, $fileDescriptions));
    }

    /**
     * @return boolean
     */
    public function supportsFileType($type)
    {
        return in_array($type, ['gif', 'png', 'jpg']);
    }

    /**
     * @return Session
     */
    protected function getSsh()
    {
        return new Session(
            new Configuration($this->configuration->host, $this->configuration->port ?? 22),
            $this->getAuthentication()
        );
    }

    /**
     * Get authentication
     *
     * Will get the right form of authentication by configuration
     *
     * @return Agent|Password
     */
    protected function getAuthentication()
    {
        if (!empty($this->configuration->password)) {
            return new Password($this->configuration->user, $this->configuration->password);
        }

        return new Agent($this->configuration->user);
    }

    /**
     * @return string
     */
    protected function getIdentifyCommand()
    {
        return empty($this->configuration->useGraphicsMagick) ? 'identify' : 'gm identify';
    }
}