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

	public function addSql($sql = '') {
		$this->sql .= $sql;
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
		if (array_key_exists($name, $this->args)) {
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

	public function getEnabled() {
		return $this->enabled;
	}
}

