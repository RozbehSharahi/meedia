<?php

namespace RozbehSharahi\Meedia\Tests;

use PHPUnit\Framework\TestCase;
use RozbehSharahi\Meedia\TreeBuilder\ImageTreeBuilder;
use RozbehSharahi\Meedia\TreeCreator;
use Ssh\Authentication\Password;
use Ssh\Configuration;
use Ssh\Session;

class TreeCreatorTest extends TestCase
{

    /** @test */
    public function canCreateTreeCreator()
    {
        self::assertInstanceOf(TreeCreator::class, new TreeCreator([]));
    }

    /** @test */
    public function canCreateTreeWithTreeCreator()
    {
        $ssh = new Session(new Configuration('localhost', 2222), new Password('testuser', 'testpass'));
        $treeCreator = new TreeCreator(
            [
                new ImageTreeBuilder('~/test-server/', $ssh)
            ]
        );

        $tree = $treeCreator->create();

        usort($tree, function (array $a, array $b) {
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

    /** @test */
    public function canInformIfAnyTreeBuilderSupportsFileType()
    {
        $ssh = new Session(new Configuration('localhost', 2222), new Password('testuser', 'testpass'));
        $treeCreator = new TreeCreator(
            [
                new ImageTreeBuilder('~/test-server/', $ssh)
            ]
        );

        self::assertTrue($treeCreator->supportsFileType('jpg'));
        self::assertTrue($treeCreator->supportsFileType('gif'));
        self::assertTrue($treeCreator->supportsFileType('png'));
        self::assertFalse($treeCreator->supportsFileType('pdf'));
    }

}