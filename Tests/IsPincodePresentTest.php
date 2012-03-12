<?php
require_once dirname(__FILE__).'/../GharpayAPI.php';
class IsPincodePresentTest extends PHPUnit_Framework_TestCase {
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
	 * Test isPincodePresent
	*/
	
	public function testWrongLengthPincodeisPincodePresent()
	{
		$this->setExpectedException('InvalidArgumentException');
		$response=$this->gpapi->isPincodePresent(8787);
	}
		
	public function testOKisPincodePresent()
	{
		$response=$this->gpapi->isPincodePresent(500008);
		$this->assertTrue($response);
	}
	public function testNotOKisPincodePresent()
	{
		$response=$this->gpapi->isPincodePresent(505001);
		$this->assertFalse($response);
	}
	public function testNullPincodeisPincodePresent()
	{
		$this->setExpectedException('InvalidArgumentException');
		$response=$this->gpapi->isPincodePresent(null);
		
	}
	public function testEmptyPincodeisPincodePresent()
	{
		$this->setExpectedException('InvalidArgumentException');
		$response=$this->gpapi->isPincodePresent('  ');
	}
	
}
