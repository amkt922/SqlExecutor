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
 * @author amkt
 */
class BeginNode extends AbstractNode implements SqlConnectorAdjustable {
    
    const MARK = 'BEGIN';
	private $nested = false;
    
    public function __construct() {
        ;
    }
	
    public function __construct($nested) {
		$this->nested = $nested;
    }

	public function isNested() {
		return $this->nested;
	}
		
}

