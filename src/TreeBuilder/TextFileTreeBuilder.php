<?php

namespace RozbehSharahi\Meedia\TreeBuilder;

use Ssh\Authentication\Agent;
use Ssh\Authentication\Password;
use Ssh\Configuration;
use Ssh\Session;

class TextFileTreeBuilder implements TreeBuilderInterface
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
     */
    public function __construct(\stdClass $configuration)
    {
        $this->configuration = $configuration;
        $this->source = $configuration->source;
        $this->ssh = $this->getSsh();
    }

    /**
     * @return array
     */
    public function getTree()
    {
        $filePaths =trim($this->ssh->getExec()
            ->run('cd ' . $this->source . ' && find . -type f \( -name "*.txt" \) -exec echo {} \;'));

        return array_map(function ($filePath) {

            if(empty($filePath)) {
                throw new \Exception('Empty line returned on text file lookup');
            }

            return [
                'path' => $filePath
            ];
        }, explode(PHP_EOL, $filePaths));
    }

    /**
     * @return boolean
     */
    public function supportsFileType($type)
    {
        return in_array($type, ['txt']);
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
}