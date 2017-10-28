<?php

namespace RozbehSharahi\Meedia;

use RozbehSharahi\Meedia\TreeBuilder\TreeBuilderInterface;
use Ssh\Session;

class TreeCreator
{
    /**
     * @var Session
     */
    protected $ssh;

    /**
     * @var array|TreeBuilderInterface[]
     */
    protected $treeBuilders;

    /**
     * TreeBuilderProvider constructor.
     *
     * @param TreeBuilderInterface[] $treeBuilders
     */
    public function __construct(array $treeBuilders)
    {
        $this->treeBuilders = $treeBuilders;
    }

    /**
     * @return array
     */
    public function create()
    {
        $tree = [];
        foreach ($this->treeBuilders as $treeBuilder) {
            $tree = array_merge($tree, $treeBuilder->getTree());
        }
        return $tree;
    }

    /**
     * @param string $type
     * @return bool
     */
    public function supportsFileType(string $type)
    {
        foreach ($this->treeBuilders as $treeBuilder) {
            if ($treeBuilder->supportsFileType($type)) {
                return true;
            }
        }
        return false;
    }

}