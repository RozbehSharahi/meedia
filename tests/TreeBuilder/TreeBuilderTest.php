<?php

namespace RozbehSharahi\Meedia\Tests\TreeBuilder;

use PHPUnit\Framework\TestCase;
use RozbehSharahi\Meedia\TreeBuilder\ImageTreeBuilder;
use Ssh\Authentication\Password;
use Ssh\Configuration;
use Ssh\Session;

class TreeBuilderTest extends TestCase
{
    protected $testUser = 'testuser';
    protected $testPass = 'testpass';
    protected $testPort = 2222;
    protected $testHost = 'localhost';

    /** @test */
    public function canConnectToTestServer()
    {
        $ssh = new Session(
            new Configuration($this->testHost, $this->testPort),
            new Password($this->testUser, $this->testPass)
        );

        self::assertEquals('/home/testuser', trim($ssh->getExec()->run('pwd')));
    }

    /** @test */
    public function canCreateTree()
    {
        $ssh = new Session(
            new Configuration($this->testHost, $this->testPort),
            new Password($this->testUser, $this->testPass)
        );

        $treeBuilder = new ImageTreeBuilder('/home/' . $this->testUser . '/test-server/', $ssh);

        $tree = $treeBuilder->getTree();

        usort($tree,function(array $a, array $b) {
            return $a['path'] <=> $b['path'];
        });

        self::assertEquals([
            [
                'path' => './some-pic-1.png',
                'width' => "380",
                'height' => "332"
            ],
            [
                'path' => './some-pic-2.png',
                'width' => "356",
                'height' => "312"
            ],
            [
                'path' => './some-pic-3.png',
                'width' => "586",
                'height' => "473"
            ],
            [
                'path' => './some-pic-4.png',
                'width' => "421",
                'height' => "360"
            ],
            [
                'path' => './some-sub-folder/some-pic-5.png',
                'width' => "421",
                'height' => "360"
            ],
        ], $tree);
    }
}