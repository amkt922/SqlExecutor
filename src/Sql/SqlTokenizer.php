<?php

namespace SqlExecutor\Sql;

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of SQLTokenizer
 *
 * @author p
 */
class SqlTokenizer {

	const SQL = 1;
	const COMMENT = 2;
	const EL = 3;
	const BIND_VARIABLE = 4;
	const EOF = 99;

	private $sql = null;
	private $token = null;
	private $tokenType = self::SQL;
	private $nextTokenType = self::SQL;
	private $position = 0;

	public function __construct($sql = '') {
		$this->sql = $sql;
	}

	public function next() {
		if ($this->position >= mb_strlen($this->sql)) {
			$this->token = null;
			$this->tokenType = self::EOF;
			$this->nextTokenType = self::EOF;
			return $this->tokenType;
		}
		switch ($this->nextTokenType) {
		case self::SQL:
			$this->parseSql();
			break;
		case self::COMMENT:
			$this->parseComment();
			break;
		case self::EL:
			$this->parseElse();
			break;
		case self::BIND_VARIABLE:
			//parseBindVariable();
			break;
		default:
			parseEof();
			break;
		}
		return $this->tokenType;
	}

	protected function parseSql() {
		$commentStartPos = mb_strpos($this->sql, '/*', $this->position);
		if ($commentStartPos === false) {
			$commentStartPos = -1;
		}
        $elseCommentStartPos = -1;
        $elseCommentLength = -1;
        $elseCommentSearchCurrentPosition = $this->position;
        while (true) { 
            $lineCommentStartPos = mb_strpos($this->sql, '--', $elseCommentSearchCurrentPosition);
            if ($lineCommentStartPos === false) {
                break;
            }
            $skipPos = $this->skipWhitespaceFromCurrentPos($lineCommentStartPos + 2);
            if ($skipPos + 4 < mb_strlen($this->sql) 
					&& "ELSE" === mb_substr($this->sql, $skipPos, 4)) {
                $elseCommentStartPos = $lineCommentStartPos;
                $elseCommentLength = $skipPos + 4 - $lineCommentStartPos;
                break;
            }
            $elseCommentSearchCurrentPosition = $skipPos;
        }

		$nextCommentStartPos = $this->calculateNextStartPos($commentStartPos, $elseCommentStartPos);
		if ($nextCommentStartPos < 0) {
			$this->token = mb_substr($this->sql, $this->position);
			$this->nextTokenType = self::EOF;
			$this->position = mb_strlen($this->sql);
			$this->tokenType = self::SQL;
			return;
		}
		$this->token = mb_substr($this->sql, $this->position, $nextCommentStartPos - $this->position);
		$this->tokenType = self::SQL;
		$needNext = $this->position === $nextCommentStartPos ? true : false;
		if ($commentStartPos === $nextCommentStartPos) {
			$this->nextTokenType = self::COMMENT;
			$this->position = $commentStartPos + 2;
		} else if ($nextCommentStartPos === $elseCommentStartPos) {
			$this->nextTokenType = self::EL;
			$this->position = $elseCommentStartPos + $elseCommentLength;
		}
		if ($needNext) {
			$this->next();
		}
	}

	private function calculateNextStartPos($commentStartPos, $elseCommentStartPos) {
		$nextStartPos = -1;
		if ($commentStartPos >= 0) {
			$nextStartPos = $commentStartPos;
		}
        if ($elseCommentStartPos >= 0 && ($nextStartPos < 0 || $elseCommentStartPos < $nextStartPos)) {
            $nextStartPos = $elseCommentStartPos;
        }
		return $nextStartPos;
	}

	protected function parseComment() {
		$commentEndPos = mb_strpos($this->sql, '*/', $this->position);
		$this->token = mb_substr($this->sql, $this->position, $commentEndPos - $this->position);
		$this->nextTokenType = self::SQL;
		$this->position = $commentEndPos + 2;
		$this->tokenType = self::COMMENT;
	}

	protected function parseElse() {
        $this->token = null;
        $this->nextTokenType = self::SQL;
        $this->tokenType = self::EL;
	}

	protected function parseEof() {
		$this->token = null;
		$this->tokenType = self::EOF;
		$this->nextTokenType = self::EOF;
	}

	public function skipToken() {
		// todo extract date
		$sqlArray = str_split($this->sql);
		$firstChar = $sqlArray[$this->position];
		$quote = $firstChar === '(' ? ')' : $firstChar;
		$quoting = $quote === '\'' || $quote == ')';
		$index = mb_strlen($this->sql);
        for ($i = $quoting ? $this->position + 1 
				: $this->position; $i < mb_strlen($this->sql); ++$i) {
			$c = $sqlArray[$i];
			if ($c === ' ') {
				$index = $i;
				break;
			} else if ($c === '/' && $sqlArray[$i + 1] === '*') {
				$index = $i;
				break;
			} else if ($c === '-' && $sqlArray[$i + 1] === '-') {
				$index = $i;
				break;
			} else if ($quoting && $c === '\'') {
				$index = $i;
				break;
			} else if ($quoting && $c === $quote) {
				$index = $i;
				break;
			}

		}
		$this->token = mb_substr($this->sql, $this->position, $index - $this->position);
		$this->tokenType = self::SQL;
		$this->nextTokenType = self::SQL;
		$this->position = $index;
		return $this->token;
	}

	public function skipWhitespace() {
		$index = $this->skipWhitespaceFromCurrentPos($this->position);
		$this->token = mb_substr($this->sql, $this->position, $index - $this->position);
		$this->position = $index;
		return $this->token;
	}
	protected function skipWhitespaceFromCurrentPos($position) {
		$index = mb_strlen($this->sql);
		$sqlArray = str_split($this->sql);
        for ($i = $position; $i < mb_strlen($this->sql); ++$i) {
            $c = $sqlArray[$i];
            if ($c !== ' ') {
                $index = $i;
                break;
            }
        }
		return $index;
	}

	public function getToken() {
		return $this->token;
	}

	public function getTokenType() {
		return $this->tokenType;
	}

	public function getNextTokenType() {
		return $this->nextTokenType;
	}

	public function getPosition() {
		return $this->position;
	}

	public function getBefore() {
		return mb_substr($this->sql, 0, $this->position);
	}

	public function getAfter() {
		return mb_substr($this->sql, $this->position);
	}

}

