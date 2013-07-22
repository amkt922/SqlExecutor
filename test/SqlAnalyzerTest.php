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
SELECT * from user /*BEGIN*/ where /*IF a > 3000 && a < 4000*/ a = /*a*/1/*END*/ /*IF b != null*/ and b = /*b*/b/*END*/ /*END*/
SQL;
		$an = new SqlAnalyzer($sql);
		$node = $an->analyze();
		$param = array();
		//$param = array('a' => 4999, 'b' => 'hoge');
		$context = Context\CommandContext::createCommandContext($param);
		$node->acceptContext($context);
		//echo $context->getSql();
	}

   public function testAnalyze2() {
		$sql = <<<SQL
/*IF paging*/
SELECT * 
-- ELSE select count(*)
/*END*/
	from user
	/*BEGIN*/ where 
		/*IF a > 3000 && a < 4000*/ a = /*a*/1/*END*/ 
		/*IF b != null*/ and b = /*b*/b/*END*/ 
		/*IF c != null*/ and c in /*c*/('a', 'b', 'c')/*END*/ 
		/*IF d != null*/ and d in /*d*/(1, 2, 3)/*END*/ 
	/*END*/
SQL;
		$an = new SqlAnalyzer($sql);
		$node = $an->analyze();
		$param = array('paging' => false, 'a' => 4999
						, 'b' => 'hoge', 'c' => array('x', 'y')
						, 'd' => array(1,10,1000));
		$context = Context\CommandContext::createCommandContext($param);
		$node->acceptContext($context);
		//echo $context->getSql();
	}

   public function testAnalyze3() {
		$sql = <<<SQL
/*IF pmb.isPaging()*/
SELECT * 
-- ELSE select count(*)
/*END*/
	from user
SQL;
		$an = new SqlAnalyzer($sql);
		$node = $an->analyze();
		$param = new Param();
		$context = Context\CommandContext::createCommandContext($param);
		$node->acceptContext($context);
		echo $context->getSql();
	}
}

class Param {

	public function isPaging() {
		return true;
	}
}
