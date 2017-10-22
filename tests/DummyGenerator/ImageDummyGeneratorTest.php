<?php

namespace RozbehSharahi\Meedia\Tests\DummyGenerator;

use org\bovigo\vfs\vfsStream;
use PHPUnit\Framework\TestCase;
use RozbehSharahi\Meedia\DummyGenerator\DummyConfiguration;
use RozbehSharahi\Meedia\DummyGenerator\DummyGeneratorInterface;
use RozbehSharahi\Meedia\DummyGenerator\ImageDummyGenerator;

class ImageDummyGeneratorTest extends TestCase
{

    /** @test */
    public function dummyImageGeneratorImplementsDummyImageGeneratorInterface()
    {
        $generator = new ImageDummyGenerator();

        self::assertInstanceOf(DummyGeneratorInterface::class, $generator);
    }

    /** @test */
    public function canSeeGeneratedJpegImage()
    {
        vfsStream::setup('dir');

        $generator = new ImageDummyGenerator();

        $configuration = new DummyConfiguration(
            vfsStream::url('dir/test-directory/test-file.jpg'),
            [
                'width' => '100',
                'height' => '200',
            ]
        );

        $generator->generate($configuration);

        self::assertFileExists($configuration->getFilePath());

    }


}