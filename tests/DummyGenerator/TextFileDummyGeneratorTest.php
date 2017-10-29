<?php

namespace RozbehSharahi\Meedia\Tests\DummyGenerator;

use org\bovigo\vfs\vfsStream;
use PHPUnit\Framework\TestCase;
use RozbehSharahi\Meedia\DummyGenerator\DummyConfiguration;
use RozbehSharahi\Meedia\DummyGenerator\TextFileDummyGenerator;

class TextFileDummyGeneratorTest extends TestCase
{

    /** @test */
    public function canSeeGeneratedTextFile()
    {
        vfsStream::setup('dir');

        $generator = new TextFileDummyGenerator();

        $configuration = new DummyConfiguration(
            vfsStream::url('dir/test-directory/sub-folder/test-file.txt'),
            []
        );

        $generator->generate($configuration);

        self::assertFileExists($configuration->getFilePath());

    }


}