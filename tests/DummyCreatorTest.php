<?php

namespace RozbehSharahi\Meedia\Tests;

use org\bovigo\vfs\vfsStream;
use PHPUnit\Framework\TestCase;
use RozbehSharahi\Meedia\DummyCreator;
use RozbehSharahi\Meedia\DummyGenerator\ImageDummyGenerator;

class DummyCreatorTest extends TestCase
{

    /** @test */
    public function canSeeDummiesBeeingCreated()
    {
        vfsStream::setup('dir');

        $dummyCreator = new DummyCreator(
            vfsStream::url('dir/my-folder/'),
            [
                [
                    'path' => 'test-image-1.jpg',
                    'width' => '100',
                    'height' => '200'
                ],
                [
                    'path' => 'test-image-2.jpg',
                    'width' => '1000',
                    'height' => '2000'
                ],
                [
                    'path' => 'sub-folder/test-image-3.jpg',
                    'width' => '1',
                    'height' => '2'
                ]
            ],
            [
                new ImageDummyGenerator
            ]
        );

        $dummyCreator->create();

        self::assertFileExists(vfsStream::url('dir/my-folder/test-image-1.jpg'));
        self::assertFileExists(vfsStream::url('dir/my-folder/test-image-2.jpg'));
        self::assertTrue(
            filesize(vfsStream::url('dir/my-folder/test-image-1.jpg')) <
            filesize(vfsStream::url('dir/my-folder/test-image-2.jpg'))
        );

    }

}