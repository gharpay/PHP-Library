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
	public function testOKGetPincodesInCity()
	{
		$response=$this->gpapi->getPincodesInCity('Mumbai');
		$this->assertArrayHasKey('0',$response);
	}
	public function  testNotOkGetPincodesInCity()
	{
		$this->setExpectedException("GharpayAPIException");
		$response=$this->gpapi->getPincodesInCity('karimnagar');
	}
	public function  testNullCityGetPincodesInCity()
	{
		$this->setExpectedException('InvalidArgumentException');
		$response=$this->gpapi->getPincodesInCity(null);
	}
	public function  testEmptyCityGetPincodesInCity()
	{
		$this->setExpectedException("InvalidArgumentException");
		$response=$this->gpapi->getPincodesInCity('   ');
	}
}