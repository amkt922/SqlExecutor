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
class SqlConnectorNode extends AbstractNode {
   
	private $connector = null;
	private $sql = null;
	private $independent = false;

	public function __construct($connector, $sql) {
		$this->connector = $connector;
		$this->sql = $sql;
    }

	public static function createSqlConnectorNode($connector, $sql) {
		return new self($connector, $sql);	

	}

	public static function createSqlConnectorNodeAsIndependent($connector, $sql) {
		$obj = new self($connector, $sql);	
		$obj->asIndependent();
		return $obj;
	}

	private function asIndependent() {
		$this->independent = true;
		return $this;
	}

	public function getConnector() {
		return $this->connector;
	}

	public function getSql() {
		return $this->sql;
	}
}

