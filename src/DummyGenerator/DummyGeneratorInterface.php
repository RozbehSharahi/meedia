<?php

namespace RozbehSharahi\Meedia\DummyGenerator;

interface DummyGeneratorInterface {

    /**
     * @param DummyConfiguration $configuration
     */
    public function generate(DummyConfiguration $configuration);

    /**
     *
     *
     * @param string $extension
     * @return boolean
     */
    public function supportsFileType($extension);

}