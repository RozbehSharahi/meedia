<?php

namespace RozbehSharahi\Meedia\TreeBuilder;

use Ssh\Session;

interface TreeBuilderInterface
{

    /**
     * TreeBuilderInterface constructor.
     *
     * @param string $path
     * @param Session $ssh
     */
    public function __construct(string $path, Session $ssh);

    /**
     * @return array
     */
    public function getTree();

    /**
     * @return boolean
     */
    public function supportsFileType($type);

}