<?php

namespace RozbehSharahi\Meedia\Tests\TreeBuilder;

use PHPUnit\Framework\TestCase;
use RozbehSharahi\Meedia\TreeBuilder\ImageTreeBuilder;

class ImageTreeBuilderTest extends TestCase
{
    protected $testUser = 'testuser';
    protected $testPass = 'testpass';
    protected $testPort = 2222;
    protected $testHost = 'localhost';

    /** @test */
    public function canCreateTree()
    {
        $treeBuilder = new ImageTreeBuilder((object) [
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