<?php

namespace RozbehSharahi\Meedia;

use RozbehSharahi\Meedia\DummyGenerator\DummyConfiguration;
use RozbehSharahi\Meedia\DummyGenerator\DummyGeneratorInterface;

class DummyCreator
{

    /**
     * @var array
     */
    protected $tree;

    /**
     * @var string
     */
    protected $destination;

    /**
     * @var DummyGeneratorInterface[]
     */
    protected $dummyGenerators;

    /**
     * DummyCreator constructor.
     *
     * @param string $destination
     * @param array $tree
     * @param DummyGeneratorInterface[] $dummyGenerators Array of dummy generator class paths
     */
    public function __construct($destination, array $tree, array $dummyGenerators)
    {
        $this->destination = $destination;
        $this->tree = $tree;
        $this->dummyGenerators = $dummyGenerators;
    }

    /**
     * Create dummies
     *
     * Will create the dummy files, configured by tree.
     */
    public function create()
    {
        array_map(function ($file) {
            $dummyConfiguration = new DummyConfiguration(
                $this->destination . '/' . $file['path'],
                $file
            );
            $dummyGenerator = $this->findDummyGenerator($dummyConfiguration->getType());

            // assertion
            if (!$dummyGenerator instanceof DummyGeneratorInterface) {
                throw new \Exception('Could not find dummy generator for type '. $dummyConfiguration->getType());
            }

            $dummyGenerator->generate($dummyConfiguration);
        }, $this->tree);
    }

    /**
     * @param string $type
     * @return DummyGeneratorInterface|null
     */
    protected function findDummyGenerator($type)
    {
        foreach ($this->dummyGenerators as $dummyGenerator) {
            if ($dummyGenerator->supportsFileType($type)) {
                return $dummyGenerator;
            }
        }
        return null;
    }

}