<?php
require_once dirname(__FILE__).'/../GharpayAPI.php';
class GetPincodesInCityTest extends PHPUnit_Framework_TestCase {
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
	 * Test GetPincodesInCity
	*/
	public function testOK()
	{
		$response=$this->gpapi->getPincodesInCity('Mumbai');
		$this->assertArrayHasKey('0',$response);
	}
	public function  testNotOk()
	{
		$this->setExpectedException("GharpayAPIException");
		$response=$this->gpapi->getPincodesInCity('karimnagar');
	}
	public function  testNullCity()
	{
		$this->setExpectedException('InvalidArgumentException');
		$response=$this->gpapi->getPincodesInCity(null);
	}
	public function  testEmptyCity()
	{
		$this->setExpectedException("InvalidArgumentException");
		$response=$this->gpapi->getPincodesInCity('   ');
	}
}