<?php
require_once dirname(__FILE__).'/../GharpayAPI.php';
class IsCityPresentTest extends PHPUnit_Framework_TestCase {
	private $gpapi;
	public function setUp()
	{
		$this->gpapi= new GharpayAPI();
	}
	public function tearDown()
	{
		unset($gpapi);
	}
	/*
	 *  Test isCityPresent
	 */
	
	public function testOKisCityPresent()
	{
		$response=$this->gpapi->isCityPresent('Mumbai');
		$this->assertTrue($response);
	}
	public function testNotOKisCityPresent()
	{
		$response=$this->gpapi->isCityPresent('karimnagar');
		$this->assertFalse($response);
	}
	public function testNullCityisCityPresent()
	{
		$this->setExpectedException("InvalidArgumentException");
		$response=$this->gpapi->isCityPresent(null);
	}
	public function testEmptyCityisCityPresent()
	{
		$this->setExpectedException("InvalidArgumentException");
		$response=$this->gpapi->isCityPresent(' ');
	}
	
	
}