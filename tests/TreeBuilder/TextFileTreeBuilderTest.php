<?php

namespace RozbehSharahi\Meedia\Tests\TreeBuilder;

use PHPUnit\Framework\TestCase;
use RozbehSharahi\Meedia\TreeBuilder\TextFileTreeBuilder;

class TextFileTreeBuilderTest extends TestCase
{
    protected $testUser = 'testuser';
    protected $testPass = 'testpass';
    protected $testPort = 2222;
    protected $testHost = 'localhost';

    /** @test */
    public function canCreateTreeForTextFiles()
    {
        $treeBuilder = new TextFileTreeBuilder((object) [
            'host' => $this->testHost,
            'port' => $this->testPort,
            'user' => $this->testUser,
            'password' => $this->testPass,
            'source' => '~/test-server/'
        ]);

        $tree = $treeBuilder->getTree();

        usort($tree,function(array $a, array $b) {
            return $a['path'] <=> $b['path'];
        });

        self::assertEquals([
            [
                'path' => './some-file-1.txt',
            ],
        ], $tree);
    }
}