<?php

namespace RozbehSharahi\Meedia\TreeBuilder;

use Ssh\Session;

class ImageTreeBuilder implements TreeBuilderInterface
{

    /**
     * @var string
     */
    protected $path;

    /**
     * @var Session
     */
    protected $ssh;

    /**
     * TreeBuilderInterface constructor.
     *
     * @param string $path
     * @param Session $ssh
     */
    public function __construct(string $path, Session $ssh)
    {
        $this->path = $path;
        $this->ssh = $ssh;
    }

    /**
     * @return array
     */
    public function getTree()
    {
        $fileDescriptions = str_replace('||||' . PHP_EOL, '||||', trim($this->ssh->getExec()
            ->run('cd ' . $this->path . ' && find . -type f \( -name "*.png" -o -name "*.gif" -o -name "*.jpg" \) -exec identify -format "%w||||%h||||" {} \; -exec echo {} \;')));

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
}