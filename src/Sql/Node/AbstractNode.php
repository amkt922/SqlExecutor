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

abstract class AbstractNode {
    protected $children = array();
    
    public function getChild($index) {
        return $this->children[$index];
    } 

    public function getChildSize() {
        return count($this->children);
    }
    
    public function addChild($child) {
        array_push($this->children, $child);
    }
}

