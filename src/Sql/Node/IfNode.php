<?php

namespace SqlExecutor\Sql\Node;

use SqlExecutor\Sql\Node\AbstractNode;
use SqlExecutor\Sql\Node\SqlConnectorAdjustable;

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of RootNode
 *
 * @author p
 */
class IfNode extends AbstractNode implements SqlConnectorAdjustable {
    
    const PREFIX = 'IF';
    
    protected $condition = null;
    
    protected $sql = null;
    
    public function __construct($condition, $sql) {
		$this->condition = $condition;
		$this->sql = $sql;
    }
}

