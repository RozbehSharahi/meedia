<?php

namespace RozbehSharahi\Meedia\TreeBuilder;

class LockTreeBuilder implements TreeBuilderInterface
{

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
    }

    /**
     * @return array
     * @throws \Exception
     */
    public function getTree()
    {
        if (!file_exists($this->configuration->lockFile)) {
            throw new \Exception('The lock file' . $this->configuration->lockFile . ' does not exist!');
        }

        return json_decode(file_get_contents($this->configuration->lockFile), true);
    }

    /**
     * Will always return true, since the lock file decides what it serves
     *
     * @return boolean
     */
    public function supportsFileType($type)
    {
        return true;
    }
}