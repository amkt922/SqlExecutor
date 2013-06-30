<?php
namespace SqlExecutor\Config;

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Config
 *
 * @author amkt
 */
class Config {
    
    /**
     * Constructor
     */
    private function __construct() {
        ;
    }
    
    /**
     * configuration for the database.
     * @var array  
     */
    public $database = array('driver' => 'mysql',
                     'connect' => 'mysql_connect',
                     'host' => 'localhost', 
                     'login' => 'dbuser', 
                     'password' => 'dbpassword',
                     'database' => 'bnotebd',
                     'prefix' => '');
}
