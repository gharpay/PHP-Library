<?php
require_once 'PHP-Library'.DIRECTORY_SEPARATOR.'GharpayAPI.php';
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
	
	public function testWrongLengthPincode()
	{
		$this->setExpectedException('InvalidArgumentException');
		$response=$this->gpapi->isPincodePresent(8787);
	}
		
	public function testOK()
	{
		$response=$this->gpapi->isPincodePresent(500008);
		$this->assertTrue($response);
	}
	public function testNotOK()
	{
		$response=$this->gpapi->isPincodePresent(505001);
		$this->assertFalse($response);
	}
	public function testNullPincode()
	{
		$this->setExpectedException('InvalidArgumentException');
		$response=$this->gpapi->isPincodePresent(null);
		
	}
	public function testEmptyPincode()
	{
		$this->setExpectedException('InvalidArgumentException');
		$response=$this->gpapi->isPincodePresent('  ');
	}
	
}
