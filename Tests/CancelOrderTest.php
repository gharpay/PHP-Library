<?php	
require_once dirname(__File__).DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'GharpayAPI.php';
class CancelOrderTest extends PHPUnit_Framework_TestCase {
	
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
				'email'=>'name@example.in'
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
	 * Test Cancel Order
	*/
	public function testOk()
	{
		$resp=$this->gpapi->createOrder($this->cDetails,$this->oDetails,$this->pDetails);
		$response=$this->gpapi->cancelOrder($resp['gharpayOrderId']);
		$this->assertNotEmpty($response['gharpayOrderId']);
		$this->assertTrue($response['result']);
	} 
	public function testInvalidGharpayId()
	{
		$this->setExpectedException("GharpayAPIException");
		$response=$this->gpapi->cancelOrder('GW-222-247');
	}
	public function testNullGharpayId()
	{
		$this->setExpectedException("InvalidArgumentException");
		$response=$this->gpapi->cancelOrder(null);
	}
	public function testEmptyGharpayId()
	{
		$this->setExpectedException("InvalidArgumentException");
		$response=$this->gpapi->cancelOrder('  ');
	}	
}
