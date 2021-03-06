<?php
/*
  Copyright 2013, amkt <amkt922@gmail.com>

   Licensed under the Apache License, Version 2.0 (the "License");
   you may not use this file except in compliance with the License.
   You may obtain a copy of the License at

       http://www.apache.org/licenses/LICENSE-2.0

   Unless required by applicable law or agreed to in writing, software
   distributed under the License is distributed on an "AS IS" BASIS,
   WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
   See the License for the specific language governing permissions and
   limitations under the License.
 */

namespace SqlExecutor\Sql\Node;

use SqlExecutor\Sql\Node\AbstractNode;

/**
 * @author reimplement in PHP and modified by amkt <amkt922@gmail.com> (originated in Java in dbflute) 
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

	public function acceptContext($context) {
		$context->addSql($this->sql);		
	}
}

