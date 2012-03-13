<?php
require_once ('PHP-Library'.DIRECTORY_SEPARATOR.'GharpayAPI.php');
class GetCityListTest extends PHPUnit_Framework_TestCase {
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
	 * Test GetCityList
	*/
	public function testOK()
	{
		$response=$this->gpapi->getCityList();
		$this->assertArrayHasKey('0',$response);
	}
	/*disconnect internet or wrong credentials*/
	public function testNotOk()
	{
		$this->gpapi->setPassword('');
		$this->setExpectedException('GharpayAPIException');
		$this->gpapi->getCityList();
	}
}