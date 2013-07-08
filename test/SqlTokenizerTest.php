<?php

namespace SqlExecutor\Sql;

require_once dirname(dirname(__FILE__)) . "/src/Sql/SqlTokenizer.php";
/**
 * Generated by PHPUnit_SkeletonGenerator 1.2.1 on 2013-07-05 at 10:09:50.
 */
class SqlTokenizerTest extends \PHPUnit_Framework_TestCase {

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

    /**
     * @covers SqlExecutor\Db\DbAccessor::query
     * @todo   Implement testQuery().
     */
    public function testNext() {
		$tn = new SqlTokenizer('');
		$result = $tn->next();
		$this->assertSame(SqlTokenizer::EOF, $result);
		$this->assertSame('', $tn->getToken());
		$this->assertSame(SqlTokenizer::EOF, $tn->getNextTokenType());
   }
   
   public function testNext2() {
		$sql = <<<SQL
SELECT * from user;
SQL;
		$tn = new SqlTokenizer($sql);
		$result = $tn->next();
		$this->assertSame(SqlTokenizer::SQL, $result);
		$this->assertSame($sql, $tn->getToken());
		$this->assertSame(SqlTokenizer::EOF, $tn->getNextTokenType());
	}

}
