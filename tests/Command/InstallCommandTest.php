<?php

namespace RozbehSharahi\Meedia\Tests\Command;

use org\bovigo\vfs\vfsStream;
use PHPUnit\Framework\TestCase;
use RozbehSharahi\Meedia\Command\InstallCommand;
use Symfony\Component\Console\Input\StringInput;
use Symfony\Component\Console\Output\BufferedOutput;

class InstallCommandTest extends TestCase
{
    protected $meediaTestFilePath = __DIR__ . '/../Fixtures/meedia-test-config.json';
    protected $meediaTestLockFilePath = 'vfs://dir/does-definitely-not-exist';

    /** @test */
    public function canRunInstall()
    {

        vfsStream::setup('dir');

        $command = new InstallCommand();

        $input = new StringInput('--meedia-file=' . $this->meediaTestFilePath . ' --meedia-lock-file=' . $this->meediaTestLockFilePath);
        $output = new BufferedOutput();

        $command->run($input, $output);

        self::assertContains('Dummies have been created', $output->fetch());
        self::assertDirectoryExists(vfsStream::url('dir/test-data'));
        self::assertFileExists(vfsStream::url('dir/test-data/some-pic-1.png'));

    }

}