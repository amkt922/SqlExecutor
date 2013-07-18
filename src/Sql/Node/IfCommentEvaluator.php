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

/**
 * @author reimplement in PHP and modified by amkt <amkt922@gmail.com> (originated in Java in dbflute) 
 */
class IfCommentEvaluator {
    
    
    private $condition = null;
    
    private $sql = null;

	private $parameterFinder = null;
    
    public function __construct($condition, $sql, $parameterFinder) {
		$this->condition = $condition;
		$this->sql = $sql;
		$this->parameterFinder = $parameterFinder;
    }

	public function evaluate() {
		// todo AND, OR
		$values = split('=', $this->condition);
		$parameterValue = $this->parameterFinder->getParameter($values[0]);
		$v = is_numeric(trim($values[1])) ? (int)trim($values[1]) : $values[1];
		return $parameterValue === $v;
	}
}

