<?php
require_once dirname(__File__).DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'GharpayAPI.php';
class ViewOrderStatusTest extends PHPunit_Framework_TestCase
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
				'deliveryDate'=>'30-12-2012',
				'orderAmount'=>'15999'
		);

		$this->prod1 = array (
				'productID'=>884888,
				'productQuantity'=>1,
				'unitCost'=>1500,
				'productDescription'=>'Dell Vostro 1540'
		);
		$this->pDetails=array();
		array_push($this->pDetails,$this->prod1);
		$this->prod2 = array (
				'productID'=>88878755,
				'productQuantity'=>1,
				'unitCost'=>1566,
				'productDescription'=>'Dell Vostro 1540'
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
	 public function testOkGharpayOrderId()
	 {
	 	$resp=$this->gpapi->createOrder($this->cDetails,$this->oDetails,$this->pDetails);
	 	$response=$this->gpapi->viewOrderStatus($resp['gharpayOrderId']);
	 	$this->assertNotEmpty($response['gharpayOrderId']);
	 	$this->assertNotEmpty($response['status']);
	 } 
	
	 public function testNotOKGharpayOrderId()
	 {
	 	$response=$this->gpapi->viewOrderStatus('88747');
	 	$this->assertNotEmpty($response['gharpayOrderId']);
	 	$this->assertEmpty($response['status']);
	 }
	 public function testEmptyGharpayOrderId()
	 {
	 	$this->setExpectedException('InvalidArgumentException');
	 	$response=$this->gpapi->viewOrderStatus('  ');
	 }
	 public function testNullGharpayOrderId()
	 {
	 	$this->setExpectedException('InvalidArgumentException');
	 	$response=$this->gpapi->viewOrderStatus(null);
	 }
	 
}

	