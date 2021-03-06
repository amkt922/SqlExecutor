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

namespace SqlExecutor\Sql\Context;

/**
 * @author reimplement in PHP and modified by amkt <amkt922@gmail.com> (originated in Java in dbflute) 
 */

class CommandContext {

	private $args = array();

	private $sql = null;

	private $parent = null;

	private $isBeginChild = false;

	private $enabled = true;

	private $bindVariables = array();

	private $bindVariableTypes = array();

	public static function createCommandContext($args) {
		return new CommandContext($args, null);
	}

	public static function createCommandContextAsBeginChild($context) {
		$obj = new CommandContext($context->getArgs(), $context);
		$obj->asBeginChild();
		return $obj;
	}

	private function __construct($args, $parent) {
		$this->args = $args;
		$this->enabled = true;
		if (!is_null($parent)) {
			$this->parent = $parent;
			$this->enabled = false;
		}
	}

	public function addSql($sql = '', $bindVariable = null, $bindVariableType = null) {
		$this->sql .= $sql;
		if (!empty($bindVariable)) {
			if (is_array($bindVariable)) {
				foreach ($bindVariable as $v) {
					array_push($this->bindVariables, $v);
					array_push($this->bindVariableTypes, $bindVariableType);
				}	
			} else {
				array_push($this->bindVariables, $bindVariable);
				array_push($this->bindVariableTypes, $bindVariableType);
			}
		}
	}

	public function addBindVariables($bindVariables) {
		array_push($this->bindVariables, $bindVariables);
	}

	public function addBindVariableTypes($bindVariableType) {
		array_push($this->bindVariableTypes, $bindVariableType);
	}

	public function getBindVariables() {
		return $this->bindVariables;
	}

	public function getBindVariableTypes() {
		return $this->bindVariableTypes;
	}

	public function getSql() {
		return $this->sql;
	}

	private function asBeginChild() {
		$this->isBeginChild = true;
		return $this;
	}

	public function getArgs() {
		return $this->args;
	}

	public function getArg($name) {
		$name = trim($name);
		if (($dotpos = mb_strpos($name, '.')) !== false) {
			$name = mb_substr($name, $dotpos + 1);
		}
		if (is_object($this->args)) {
			if (($pos = mb_strpos($name, '(')) !== false) {
				$method = mb_substr($name, 0, $pos);
				$methodParam = mb_substr($name, $pos + 1);
				$methodParam = mb_substr($methodParam, 0, mb_strlen($methodParam) - 1);
				if (mb_strpos($methodParam, '\'') === false) {
					$methodParam = (int)$methodParam;
				}
			} else {
				$methodParam = null;
				$method = "get" . strtoupper(mb_substr($name,0,1)) . mb_substr($name, 1);
			}
			return $this->args->$method($methodParam);
		} else if (array_key_exists($name, $this->args)) {
			return $this->args[$name];
		}
		return null;
	}

	public function getArgType($name) {
		if (array_key_exists($name, $this->args)) {
			return gettype($this->args[$name]);
		}
		return null;
	}

	public function setEnabled($enabled) {
		$this->enabled = $enabled;
	}

	public function isEnabled() {
		return $this->enabled;
	}
}

