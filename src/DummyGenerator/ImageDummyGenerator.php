<?php

namespace RozbehSharahi\Meedia\DummyGenerator;

class ImageDummyGenerator implements DummyGeneratorInterface
{

    /**
     * @param DummyConfiguration $configuration
     * @throws \Exception
     */
    public function generate(DummyConfiguration $configuration)
    {
        if (!static::supportsFileType($configuration->getType())) {
            throw new \Exception(static::class . ' does not support image generation for ' . $configuration->getType());
        }

        if (is_file($configuration->getDirectory())) {
            throw new \Exception($configuration->getDirectory() . 'is a destination directory but already exists as file');
        }

        $this->createFile(
            $this->createDummy($configuration),
            $configuration
        );
    }

    /**
     * @param string $extension
     * @return bool
     */
    public function supportsFileType($extension)
    {
        return in_array($extension, [
            'png',
            'gif',
            'jpg'
        ]);
    }

    /**
     * @param DummyConfiguration $dummyConfiguration
     * @return resource
     */
    protected function createDummy(DummyConfiguration $dummyConfiguration)
    {
        $dummy = imagecreatetruecolor(
            $dummyConfiguration->getAttribute('width'),
            $dummyConfiguration->getAttribute('height')
        );
        return $dummy;
    }

    /**
     * @param $dummy
     * @param DummyConfiguration $configuration
     */
    protected function createFile($dummy, DummyConfiguration $configuration)
    {
        // Create directory for file
        if (!is_dir($configuration->getDirectory())) {
            mkdir($configuration->getDirectory(), 0777, true);
        }

        switch ($configuration->getType()) {
            case 'jpg':
                imagejpeg($dummy, $configuration->getFilePath());
                break;
            case 'gif':
                imagegif($dummy, $configuration->getFilePath());
                break;
            case 'png':
                imagegif($dummy, $configuration->getFilePath());
                break;
        }
    }

}