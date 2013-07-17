<?php

namespace SqlExecutor\Sql;

require_once dirname(dirname(__FILE__)) . "/src/SqlExecutor.php";
/**
 * Generated by PHPUnit_SkeletonGenerator 1.2.1 on 2013-07-05 at 10:09:50.
 */
class SqlAnalyzerTest extends \PHPUnit_Framework_TestCase {

    /**
     * @var DbAccessor
     */
    protected $object;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
	protected function setUp() {
  	}

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown() {
        
    }
  
   public function testAnalyze() {
		$sql = <<<SQL
SELECT * from user /*BEGIN*/ where /*IF a = 1*/ a = /*a*/1/*END*/ /*IF b = 'b'*/ and b = /*b*/'b'/*END*/ /*END*/
SQL;
		$an = new SqlAnalyzer($sql);
		$node = $an->analyze();
		print_r($node);
	}
}
