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

use SqlExecutor\Sql\Node\SqlConnectorAdjustable;
use SqlExecutor\Sql\Node\IfCommentEvaluator;
use SqlExecutor\Sql\Node\ParameterFinder;

/**
 * @author reimplement in PHP and modified by amkt <amkt922@gmail.com> (originated in Java in dbflute) 
 */
class IfNode extends ScopeNode implements SqlConnectorAdjustable {
    
    const PREFIX = 'IF';
    
    private $condition = null;
    
    private $sql = null;

	private $elseNode = null;
    
    public function __construct($condition, $sql) {
		$this->condition = $condition;
		$this->sql = $sql;
    }

	public function setElseNode($elseNode) {
		$this->elseNode = $elseNode;
	}

	public function acceptContext($context) {
		$parameterFinder = new ParameterFinder($context);
		$evaluator = new IfCommentEvaluator($this->condition, $this->sql, $parameterFinder);
		$result = $evaluator->evaluate();
		if ($result) {
			$this->processAcceptingChilden($context);
			$context->setEnabled(true);
		} else {
			if ($this->elseNode != null) {
				$this->elseNode->acceptContext($context);
			}
		}
	}
}

