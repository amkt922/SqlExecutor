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
		$sql = $this->tokenizer->getToken();
		if ($this->isConnectorAdjustable($node)) {
			$this->processSqlConnectorAdjustable($node, $sql);		
		}
	}
	
	protected function processSqlConnectorAdjustable($node, $sql) {
		$st = new SqlTokenizer($sql);
		$st->skipWhitespace();
		$skippedToken = $st->skipToken();
		$st->skipWhitespace();

		if ($this->processSqlConnectorCondition($node, $sql, $skippedToken)) {
			return;
		}

		$node->addChild($this->createSqlNode($node, $sql));	
	}

	protected function processSqlConnectorCondition($node, $st, $skippedToken) {
		if ($skippedToken === 'AND' || $skippedToken === 'and'
				|| $skippedToken === 'OR' || $skippedToken === 'or') {
			$this->createSqlConnectorNode($node, $st->getBefore(), $st->getAfter());			
			return true;
		}	
		return false;
	}

	protected function createSqlConnectorNode($node, $connector, $sql) {
		if ($this->isNestedBegin($node)) {
			return Node\SqlConnectorNode::createSqlConnectorNodeAsIndependent($connector, $sql);
		} else {
			return Node\SqlConnectorNode::createSqlConnectorNode($connector, $sql);
		}
	}

	protected function createSqlNode($node, $sql) {
		if ($this->isNestedBegin($node)) {
			return Node\SqlNode::createSqlConnectorNodeAsIndependent($sql);
		} else {
			return Node\SqlNode::createSqlConnectorNode($sql);
		}
	}
	
    protected function isNestedBegin($node) {
        if (!($node instanceof BeginNode)) {
            return false;
        }
        return $node->isNested();
    }

	protected function isConnectorAdjustable($node) {
		if ($node->getChildSize() > 0) {
			return false;
		}

        return ($node instanceof SqlConnectorAdjustable) 
					&& !$this->isTopBegin($node);
	}

    protected function isTopBegin($node) {
        if (!($node instanceof BeginNode)) {
            return false;
        }
        return !$node->isNested();
    }

	protected function parseComment() {
	//	
	}
}

