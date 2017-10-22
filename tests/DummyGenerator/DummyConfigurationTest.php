<?php

namespace RozbehSharahi\Meedia\DummyGenerator;

use PHPUnit\Framework\TestCase;

class DummyConfigurationTest extends TestCase
{

    /** @test */
    public function canGetAllInfoByPassingPathToDummyConfiguration()
    {
        $configuration = new DummyConfiguration(
            'my-test-directory/my-test-file.jpg',
            [
                'width' => 100,
                'height' => 200
            ]
        );

        self::assertEquals('jpg', $configuration->getType());
        self::assertEquals('jpg', $configuration->getExtension());
        self::assertEquals('my-test-file', $configuration->getFileName());
        self::assertEquals('my-test-directory', $configuration->getDirectory());
        self::assertEquals('my-test-directory/my-test-file.jpg', $configuration->getFilePath());
        self::assertEquals('100', $configuration->getAttribute('width'));
        self::assertEquals('200', $configuration->getAttribute('height'));
    }

    /** @test */
    public function canGetAllInfoByPassingPathWithoutExtensionToDummyConfiguration()
    {
        $configuration = new DummyConfiguration(
            'my-test-directory/my-test-file',
            [
                'width' => 100,
                'height' => 200
            ],
            'png'
        );

        self::assertEquals('png', $configuration->getType());
        self::assertEquals(null, $configuration->getExtension());
        self::assertEquals('my-test-file', $configuration->getFileName());
        self::assertEquals('my-test-directory', $configuration->getDirectory());
        self::assertEquals('my-test-directory/my-test-file', $configuration->getFilePath());
        self::assertEquals('100', $configuration->getAttribute('width'));
        self::assertEquals('200', $configuration->getAttribute('height'));
    }


}