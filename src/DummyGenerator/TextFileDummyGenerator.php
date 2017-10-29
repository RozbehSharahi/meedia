<?php

namespace RozbehSharahi\Meedia\DummyGenerator;

class TextFileDummyGenerator implements DummyGeneratorInterface
{

    /**
     * @param DummyConfiguration $configuration
     * @throws \Exception
     */
    public function generate(DummyConfiguration $configuration)
    {
        if (!$this->supportsFileType($configuration->getType())) {
            throw new \Exception(static::class . ' does not support text file generation for ' . $configuration->getType());
        }

        if (is_file($configuration->getDirectory())) {
            throw new \Exception($configuration->getDirectory() . 'is a destination directory but already exists as file');
        }

        // Create directory for file
        if (!is_dir($configuration->getDirectory())) {
            mkdir($configuration->getDirectory(), 0777, true);
        }

        file_put_contents($configuration->getFilePath(), '');
    }

    /**
     * @param string $extension
     * @return bool
     */
    public function supportsFileType($extension)
    {
        return in_array($extension, [
            'txt'
        ]);
    }

}