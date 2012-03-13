<?php
require_once dirname(__FILE__).'/../GharpayAPI.php';
class GetAllPincodesTest extends PHPUnit_Framework_TestCase {
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
	 * Test getAllPincodes
	*/
	public function testOk()
	{
		$response=$this->gpapi->getAllPincodes();
		$this->assertArrayHasKey('0',$response);
	}
	public function testNotOk()
	{
		$this->gpapi->setPassword('');
		$this->setExpectedException("GharpayAPIException");
		$this->gpapi->getAllPincodes();
	}
}