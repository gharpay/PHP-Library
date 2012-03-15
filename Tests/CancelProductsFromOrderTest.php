<?php
require_once dirname(__File__).DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'GharpayAPI.php';

class CancelProductsFromOrderTest extends PHPunit_Framework_TestCase
{
	private $cDetails;
	private $oDetails;
	private $pDetails;
	private $gpapi;
	private $parameters;
	private $productIds;
	
	public function setUp()
	{
		$this->gpapi= new GharpayAPI();
		$this->cDetails= array(
				'address' => 'Aruna towers, flat No. 302, Sangeeth Nagar, Somajiguda',
				'contactNo'=>'8888888888',
				'firstName'=>'Khaja',
				'lastName'=>'Naquiuddin',
				'email'=>'khaja@gharpay.in'
		);
		$this->oDetails = array(
				'pincode'=>'400057',
				'clientOrderID'=>'6100002',
				'deliveryDate'=>'30-03-2012',
				'orderAmount'=>'15999'
		);
	
		$this->prod1 = array (
				'productID'=>884888,
				'productQuantity'=>1,
				'unitCost'=>1599
		);
		$this->pDetails=array();
		array_push($this->pDetails,$this->prod1);
		$this->prod2 = array (
				'productID'=>88878755,
				'productQuantity'=>1,
				'unitCost'=>1599
		);
		array_push($this->pDetails,$this->prod2);
	
		$this->parameters[0]=array(
				'name'=>'somename',
				'value'=>'somevalue'
		);
		$this->parameters[1]=array(	  'name'=>'somename',
				'value'=>'somevalue'
		);
		$this->productIds=array(
				0=>'88878755',
				1=>'884888'
		);
	}
	public function tearDown(){
		$this->cDetails = null;
		$this->oDetails=null;
		$this->prod1=null;
		$this->prod2=null;
		$this->pDetails=null;
		$this->gpapi=null;
		$this->productIds=null;
	}

	/*
	 * Test cancelProductsFromOrder
	*/
	 
	public function testOk()
	{
		$response=$this->gpapi->cancelProductsFromOrder('GW-222-0006946-385', 4000, $this->productIds);
		$this->assertNotEmpty($response['gharpayOrderId']);
		$this->assertTrue($response['result']);
	}
	//Gharpay Id
	public function testInvalidGharpayId()
	{
		$this->setExpectedException("GharpayAPIException");
		$response=$this->gpapi->cancelProductsFromOrder('0006879-299',4000, $this->productIds);
	}
	public function testNullGharpayID()
	{
		$this->setExpectedException('InvalidArgumentException');
		$response=$this->gpapi->cancelProductsFromOrder(null,4000, $this->productIds);
	
	}
	public function testEmptyGharpayID()
	{
		$this->setExpectedException('InvalidArgumentException');
		$response=$this->gpapi->cancelProductsFromOrder('  ', 4000, $this->productIds);
	}
	// Total Order Amount Id
	public function testNullOrderAmount()
	{
		$this->setExpectedException('InvalidArgumentException');
		$response=$this->gpapi->cancelProductsFromOrder('GW-222-0006946-385', null, $this->productIds);
	}
	public function testEmptyOrderAmount()
	{
		$this->setExpectedException('InvalidArgumentException');
		$response=$this->gpapi->cancelProductsFromOrder('GW-222-0006946-385', '  ', $this->productIds);
	}
	public function testStringOrderAmount()
	{
		$response=$this->gpapi->cancelProductsFromOrder('GW-222-0006946-385', '5000', $this->productIds);
		$this->assertNotEmpty($response['gharpayOrderId']);
		$this->assertNotEmpty($response['result']);
	}
	//Product Ids array
	public function testEmptyProductIds()
	{
		$this->setExpectedException('InvalidArgumentException');
		$response=$this->gpapi->cancelProductsFromOrder('GW-222-0006946-385', 6000, '  ');
	}
	
	public function testNullProductIds()
	{
		$this->setExpectedException('InvalidArgumentException');
		$response=$this->gpapi->cancelProductsFromOrder('GW-222-0006946-385', 6000, null);
	}
	public function testEmptyArrayProductIds()
	{
		$this->setExpectedException('InvalidArgumentException');
		$response=$this->gpapi->cancelProductsFromOrder('GW-222-0006946-385', 6000, array());
	}
	//Product Ids
	public function testNullProductId()
	{
		$this->productIds[0]=null;
		$this->setExpectedException('InvalidArgumentException');
		$response=$this->gpapi->cancelProductsFromOrder('GW-222-0006946-385', null, $this->productIds);
	}
	public function testEmptyProductId()
	{
		$this->productIds[1]='  ';
		$this->setExpectedException('InvalidArgumentException');
		$response=$this->gpapi->cancelProductsFromOrder('GW-222-0006946-385', null, $this->productIds);
	}
}
