<?php

namespace RozbehSharahi\Meedia\TreeBuilder;

use Ssh\Session;

interface TreeBuilderInterface
{

    /**
     * TreeBuilderInterface constructor.
     *
     * @param \stdClass $configuration
     */
    public function __construct(\stdClass $configuration);

    /**
     * @return array
     */
    public function getTree();

    /**
     * @return boolean
     */
    public function supportsFileType($type);

}