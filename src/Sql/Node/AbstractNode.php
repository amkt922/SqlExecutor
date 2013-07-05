<?php

namespace SqlExecutor\Sql\Node;
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of AbstractNode
 *
 * @author p
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

