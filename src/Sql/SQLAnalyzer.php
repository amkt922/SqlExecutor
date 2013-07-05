<?php

namespace SqlExecutor\Sql;


use SqlExecutor\Sql\SqlTokenizer;
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of SQLAnalyzer
 *
 * @author p
 */
class SqlAnalyzer {
    
    private $sql = '';
    
    private $tokenizer = null;
    
    private $node = null;

    /**
     * Constructor
     * @param string $sql
     */
    public function __construct($sql = '') {
        $this->sql = trim($sql);
        $this->tokenizer = new SqlTokenizer($sql);
    }
    
    public function analyze() {
        
    }
    
}

