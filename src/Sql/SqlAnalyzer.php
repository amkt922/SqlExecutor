<?php

namespace SqlExecutor\Sql;
	

use SqlExecutor\Sql\SqlTokenizer;
use SqlExecutor\Sql\Node\RootNode;

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
    
    private $nodeStack = array();

    /**
     * Constructor
     * @param string $sql
     */
    public function __construct($sql = '') {
        $this->sql = trim($sql);
        $this->tokenizer = new SqlTokenizer($sql);
    }
    
    public function analyze() {
    	array_push($this->nodeStack, $this->createRootNode());
        while (SqlTokenizer.EOF != $this->tokenizer->next()) {
            $this->parseToken();
        }
        return array_pop($this->nodeStack);
    }
    
    protected function createRootNode() {
		return new RootNode();			
    }

	protected function parseToken() {
        switch ($this->tokenizer->getTokenType()) {
        case SqlTokenizer::SQL:
            parseSql();
            break;
        case SqlTokenizer::COMMENT:
            parseComment();
            break;
        case SqlTokenizer::EL:
            //parseElse();
            break;
       }
	}

	protected function parseSql() {
		$node = $this->nodeStack[(count($this->nodeStack) - 1)];	
		$node->addChild(new Node\SqlNode($this->tokenizer->getToken()));	
	}

	protected function parseComment() {
	//	
	}
}

