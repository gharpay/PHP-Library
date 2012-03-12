<?php

require_once 'PHP-Library'.DIRECTORY_SEPARATOR.'GharpayAPI.php';
class MiscTest extends PHPunit_Framework_TestCase
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
		$this->gpapi->setUsername('test_api');
		$this->gpapi->setPassword('test_api');
		$this->gpapi->setURL('services.gharpay.in');
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
				'unitCost'=>1500
		);
		$this->pDetails=array();
		array_push($this->pDetails,$this->prod1);
		$this->prod2 = array (
				'productID'=>88878755,
				'productQuantity'=>1,
				'unitCost'=>1566
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
		$this->pDetails=null;
		$this->pDetails=null;
		$this->gpapi=null;
		$this->productIds=null;
	}

/*
* Test ViewOrderStatus
*
*/
	 //TODO: Null & empty checks
	 public function testOKViewOrderStatus()
	 {
	 	$resp=$this->gpapi->createOrder($this->cDetails,$this->oDetails,$this->pDetails);
	 	$response=$this->gpapi->viewOrderStatus($resp['gharpayOrderId']);
	 	$this->assertNotEmpty($response['gharpayOrderId']);
	 	$this->assertNotEmpty($response['status']);
	 } 
	
	 public function testNotOKViewOrderStatus()
	 {
	 	$response=$this->gpapi->viewOrderStatus('88747');
	 	$this->assertNotEmpty($response['gharpayOrderId']);
	 	$this->assertEmpty($response['status']);
	 }
	 public function testEmptyGharpayOrderIdViewOrderStatus()
	 {
	 	$this->setExpectedException('InvalidArgumentException');
	 	$response=$this->gpapi->viewOrderStatus('  ');
	 }
	 public function testNullGharpayOrderIdViewOrderStatus()
	 {
	 	$this->setExpectedException('InvalidArgumentException');
	 	$response=$this->gpapi->viewOrderStatus(null);
	 }
	 
}

	