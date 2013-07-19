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
class VariableNode extends AbstractNode {
    
    private $expression = null;
    private $testValue = null;
    
    public function __construct($expression, $testValue) {
		$this->expression = $expression;
		$this->testValue = $testValue;
    }

	public function acceptContext($context) {
		if (mb_strpos($this->testValue, '\'') === 0 
) {
			$value = $context->getArg($this->expression);
			$context->addSql('\'' . $value . '\'');
		} else {
			$value = $context->getArg($this->expression);
			if (is_array($value)) {
				if (mb_strpos($this->testValue, '\'') !== false) {
					$glue = '\',\'';
					$inClause = implode($glue, $value);
					$inClause = "'{$inClause}'";
				} else {
					$glue = ',';
					$inClause = implode($glue, $value);
				}
				$context->addSql("({$inClause})");
			} else {
				$context->addSql($value);
			}
		}
	}
}

