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
class VariableNode extends AbstractNode {
    
    private $expression = null;
    private $testValue = null;
    
    public function __construct($expression, $testValue) {
		$this->expression = $expression;
		$this->testValue = $testValue;
    }
}

