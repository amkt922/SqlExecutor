<?php

namespace SqlExecutor;

require_once "autoload.php";


use SqlExecutor\Db\DbAccessor;


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
    
    /**
     * @var SqlExecutor instance of myself 
     */
    private static $instance = null;
    
    /**
     * @var DbAccessor accesing database object. 
     */
    private $dbAccessor = null;
    
    /**
     * constructor
     */
    private function __construct() {}
    
    /**
     * create instance of myself and load config file.
     * 
     * @param array|string  $config  config file for accessing database.
     */
    public static function load($config) {
        if (is_null(self::$instance)) {
            self::$instance = new self;
            self::$instance->dbAccessor = new DbAccessor($config);
        }
        
        return self::$instance;
    }

    
    /**
     * 
     * 
     * @param string $sqlFile sql file name 
     * @param array $params parameters for sql
     */
    public function from($sqlFile, $params = array()) {
        
    }
}

