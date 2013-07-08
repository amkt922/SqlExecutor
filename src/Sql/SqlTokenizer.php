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

	private $sql = '';
	private $token = '';
	private $tokenType = self::SQL;
	private $nextTokenType = self::SQL;
	private $position = 0;

	public function __construct($sql = '') {
		$this->sql = $sql;
	}

	public function next() {
		if ($this->position >= mb_strlen($this->sql)) {
			$this->token = '';
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
			//parseElse();
			break;
		case self::BIND_VARIABLE:
			//parseBindVariable();
			break;
		default:
			//parseEof();
			break;
		}
		return $this->tokenType;
	}

	protected function parseSql() {
		$commentStartPos = mb_strpos($this->sql, '/*');
		if ($commentStartPos === false) {
			$commentStartPos = -1;
		}
		$nextCommentStartPos = $this->calculateNextStartPos($commentStartPos);
		if ($nextCommentStartPos < 0) {
			$this->token = mb_substr($this->sql, $this->position);
			$this->nextTokenType = self::EOF;
			$this->position = mb_strlen($this->sql);
			$this->tokenType = self::SQL;
			return;
		}
		$this->token = mb_substr($this->sql, $nextCommentStartPos);
		$this->tokenType = self::SQL;
		$needNext = $commentStartPos === $nextCommentStartPos ? true : false;
		if ($commentStartPos === $nextCommentStartPos) {
			$this->nextTokenType = self::COMMENT;
			$this->position = $commentStartPos + 2;
		}
		if ($needNext) {
			$this->next();
		}
	}

	private function calculateNextStartPos($commentStartPos) {
		$nextStartPos = -1;
		if ($commentStartPos >= 0) {
			$nextStartPos = $commentStartPos;
		}

		return $nextStartPos;
	}

	protected function parseComment() {
		$commentEndPos = mb_strpos($this->sql, '*/');
		$this->token = mb_substr($this->sql, $this->position, $commentEndPos - $this->position);
		$this->nextTokenType = self::SQL;
		$this->position = $this->position + 2;
		$this->tokenType = self::COMMENT;
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


}

