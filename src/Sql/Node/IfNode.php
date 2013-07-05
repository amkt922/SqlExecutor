<?php

namespace SqlExecutor\Sql\Node;

use SqlExecutor\Sql\Node\AbstractNode;
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of RootNode
 *
 * @author p
 */
class IfNode extends AbstractNode {
    
    const MARK = 'IF';
    
    protected $condition = '';
    
    protected $sql = '';
    
    public function __construct() {
        ;
    }
}

