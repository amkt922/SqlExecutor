<?php

namespace SqlExecutor;

require_once "autoload.php";


use SqlExecutor\Sql\Context\CommandContext;

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of SqlExecutor
 *
 * @author amkt
 */
class SqlExecutor {
    private $pdo = null;
    
    private $dsn = '';
    
    private $user = '';
    
    private $password = '';    

	private $sqlDir = '';

    /**
     * @var SqlExecutor instance of myself 
     */
    private static $instance = null;
   
    /**
     * constructor
     */
    private function __construct() {}
    
    /**
     * create instance of myself and load config file.
     * 
     * @param array|string  $config  config file for accessing database.
     */
    public static function getExecutor($config) {
        if (is_null(self::$instance)) {
            self::$instance = new self;
			if (is_array($config)) {
				self::$instance->setConfigFromArray($config);
			}   
        }
        
        return self::$instance;
    }

    private function setConfigFromArray($config) {        
        if (!in_array('database', $config) 
                && !is_array($config['database'])) {
            throw new \InvalidArgumentException('The parameter sould include database and it should be an array.');
        }
        if (!array_key_exists('dsn', $config['database'])) {
            throw new \InvalidArgumentException('dsn value should be in database array.');
        }
        $database = $config['database'];
        $this->dsn = $database['dsn'];
        if (array_key_exists('user', $database)) {
            $this->user = $database['user'];
        }
        if (array_key_exists('password', $database)) {
            $this->password = $database['password'];
        }

		if (array_key_exists('sqlDir', $config)) {
			$this->sqlDir = $config['sqlDir'];
		}
    }

	public function setupSql($sql, $params) {
		$this->setupPDO();
		$rowSql = file_get_contents($this->sqlDir . $sql . '.sql');
		$analyzer = new \SqlExecutor\Sql\SqlAnalyzer($rowSql);
		$node = $analyzer->analyze();
		$context = CommandContext::createCommandContext($params);
		$node->acceptContext($context);
		return $context->getSql();
	}


	private function setupPDO() {
        if (is_null($this->pdo)) {
            $this->pdo = new \PDO($this->dsn, $this->user, $this->password);
        }
    }

	public function selectList($sql, $params, $entity = null) {
		$sql = $this->setupSql($sql, $params);
		$stmt = $this->pdo->query($sql);
		return $stmt->fetchAll(\PDO::FETCH_CLASS);
	}
}

