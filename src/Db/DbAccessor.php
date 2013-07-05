<?php
namespace SqlExecutor\Db;
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of DbAccessor
 *
 * @author amkt
 */
class DbAccessor {

    private $pdo = null;
    
    private $dsn = '';
    
    private $user = '';
    
    private $password = '';    
    
    /**
     * Constructor
     * @param array $config
     */
    public function __construct($config) {
        if (is_array($config)) {
            $this->setConfigFromArray($config);
        } else if (is_string($config)) {
            $this->setConfigFromYaml($config);
        }       
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
    }
    
    public function setConfigFromYaml($config) {
        // not implementd yet.
    }    
    
    /**
     * 
     * @param string $sql
     */
    public function query($sql = '') {
        $this->setupPDO();        
        $stmt = $this->pdo->query($sql);
        return $stmt->fetch();
    }
    
    /**
     * 
     * @param string $sql
     */
    public function exec($sql = '') {
        
    }
    
    private function setupPDO() {
        if (is_null($this->pdo)) {
            $this->pdo = new \PDO($this->dsn, $this->user, $this->password);
        }
    }
}

