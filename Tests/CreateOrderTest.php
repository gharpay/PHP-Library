<?php
require 'PHP-Library'.DIRECTORY_SEPARATOR.'GharpayAPI.php';
class CreateOrderTest extends PHPUnit_Framework_TestCase 
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
	
	public function testOKCreateOrder()
	{
		$response=$this->gpapi->createOrder($this->cDetails,$this->oDetails,$this->pDetails);
		$this->assertNotEmpty($response['gharpayOrderId']);
	}
	public function testnullcDetailsCreateOrder()
	{
		$this->cDetails['firstName']=null;
		$this->setExpectedException('InvalidArgumentException');
		$response=$this->gpapi->createOrder($this->cDetails,$this->oDetails,$this->pDetails);
	}
	public function testEmptycDetailsCreateOrder()
	{
		$this->cDetails['firstName']='  ';
		$this->setExpectedException('InvalidArgumentException');
		$response=$this->gpapi->createOrder($this->cDetails,$this->oDetails,$this->pDetails);
	}
	public function testNulloDetailsCreateOrder()
	{
		$this->oDetails['pincode']=null;
		$this->setExpectedException('InvalidArgumentException');
		$response=$this->gpapi->createOrder($this->cDetails,$this->oDetails,$this->pDetails);
	}
	public function testEmptyoDetailsCreateOrder()
	{
		$this->oDetails['clientOrderId']='  ';
		//$this->setExpectedException('InvalidArgumentException');
		$response=$this->gpapi->createOrder($this->cDetails,$this->oDetails,$this->pDetails);		
	}
	public function testNullpDetailsCreateOrder()
	{
		$this->pDetails['0']['productQuantity']=null;
		$this->setExpectedException('InvalidArgumentException');
		$response=$this->gpapi->createOrder($this->cDetails,$this->oDetails,$this->pDetails);
	}
	public function testEmptypDetailsCreateOrder()
	{
		$this->pDetais['0']['unitCost']=null;
		$this->setExpectedException('InvalidArgumentException');
		$response=$this->gpapi->createOrder($this->cDetails,$this->oDetails,$this->pDetails);
	}
}