<?php

namespace RozbehSharahi\Meedia\Tests\Command;

use org\bovigo\vfs\vfsStream;
use PHPUnit\Framework\TestCase;
use RozbehSharahi\Meedia\Command\InstallCommand;
use RozbehSharahi\Meedia\Command\UpdateCommand;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Input\StringInput;
use Symfony\Component\Console\Output\BufferedOutput;

class UpdateCommandTest extends TestCase
{
    protected $meediaTestFilePath = __DIR__ . '/../Fixtures/meedia-test-config.json';
    protected $meediaTestLockFilePath = 'vfs://dir/does-definitely-not-exist';

    /** @test */
    public function canRunInstall()
    {

        vfsStream::setup('dir');

        $application = new Application();
        $application->add(new InstallCommand());

        $command = new UpdateCommand();
        $command->setApplication($application);

        $input = new StringInput('--meedia-file=' . $this->meediaTestFilePath . ' --meedia-lock-file=' . $this->meediaTestLockFilePath);
        $output = new BufferedOutput();

        $command->run($input, $output);

        self::assertContains('Dummies have been created', $output->fetch());
        self::assertDirectoryExists(vfsStream::url('dir/test-data'));
        self::assertFileExists(vfsStream::url('dir/test-data/some-pic-1.png'));

    }

}