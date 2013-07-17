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
class SqlNode extends AbstractNode {
   
	private $sql = null;
	private $independent = false;

	public function __construct($sql = '') {
		$this->sql = $sql;
    }

	public static function createSqlNode($sql) {
		return new self($sql);	

	}

	public static function createSqlNodeAsIndependent($sql) {
		$obj = new self($sql);	
		$obj->asIndependent();
		return $obj;
	}

	private function asIndependent() {
		$this->independent = true;
		return $this;
	}
}

