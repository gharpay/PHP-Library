<?php
//require_once ('PHP-Library'.DIRECTORY_SEPARATOR.'GharpayAPI.php');
require_once dirname(__File__).DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'GharpayAPI.php';
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
				'productDescription'=>'Sony Vaio E series 13 inches'
		);
		array_push($this->pDetails,$this->prod2);
	
		$this->parameters[0]=array(
				'name'=>'somename',
				'value'=>'somevalue'
		);
		$this->parameters[1]=array(	  
				'name'=>'somename',
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
	
	public function testOK()
	{
		$response=$this->gpapi->createOrder($this->cDetails,$this->oDetails,$this->pDetails);
		$this->assertNotEmpty($response['gharpayOrderId']);
	}
	//customer Details
	public function testnullcDetails()
	{
		$this->setExpectedException('InvalidArgumentException');
		$response=$this->gpapi->createOrder(null,$this->oDetails,$this->pDetails);
	}
	public function testEmptyArraycDetails()
	{
		$this->cDetails=array();
		$this->setExpectedException('InvalidArgumentException');
		$response=$this->gpapi->createOrder($this->cDetails,$this->oDetails,$this->pDetails);
	
	}
	public function testEmptycDetails()
	{
		$this->cDetails='  ';
		$this->setExpectedException('InvalidArgumentException');
		$response=$this->gpapi->createOrder($this->cDetails,$this->oDetails,$this->pDetails);
	}
	public function testCustomerFirstNameEmpty()
	{
		$this->cDetails['firstName']='  ';
		$this->setExpectedException('InvalidArgumentException');
		$response=$this->gpapi->createOrder($this->cDetails,$this->oDetails,$this->pDetails);
	}
	public function testCustomerFirstNameNull()
	{
		$this->cDetails['firstName']=null;
		$this->setExpectedException('InvalidArgumentException');
		$response=$this->gpapi->createOrder($this->cDetails,$this->oDetails,$this->pDetails);
	}
	public function testCustomerContactNoEmpty()
	{
		$this->cDetails['contactNo']='  ';
		$this->setExpectedException('InvalidArgumentException');
		$response=$this->gpapi->createOrder($this->cDetails,$this->oDetails,$this->pDetails);
	}
	public function testCustomerContactNoNull()
	{
		$this->cDetails['contactNo']=null;
		$this->setExpectedException('InvalidArgumentException');
		$response=$this->gpapi->createOrder($this->cDetails,$this->oDetails,$this->pDetails);
	}
	//TODO : Validating Invalid contact Number;
	public function testCustomerContactNoInvalid()
	{
		$this->cDetails['contactNo']='  ';
		$this->setExpectedException('InvalidArgumentException');
		$response=$this->gpapi->createOrder($this->cDetails,$this->oDetails,$this->pDetails);		
	}
	public function testCustomerAddressEmpty()
	{	
		$this->cDetails['address']='  ';
		$this->setExpectedException('InvalidArgumentException');
		$response=$this->gpapi->createOrder($this->cDetails,$this->oDetails,$this->pDetails);
	}
	public function testCustomerAddressNull()
	{
		$this->cDetails['address']=null;
		$this->setExpectedException('InvalidArgumentException');
		$response=$this->gpapi->createOrder($this->cDetails,$this->oDetails,$this->pDetails);
	}
	//Order Details
	public function testnullODetails()
	{	
		$this->oDetails=null;
		$this->setExpectedException('InvalidArgumentException');
		$response=$this->gpapi->createOrder($this->cDetails,$this->oDetails,$this->pDetails);
	}
	public function testEmptyODetails()
	{
		$this->oDetails='   ';
		$this->setExpectedException('InvalidArgumentException');
		$response=$this->gpapi->createOrder($this->cDetails,$this->oDetails,$this->pDetails);
	}
	public function testEmptyArrayODetails()
	{
		$this->oDetails=array();
		$this->setExpectedException('InvalidArgumentException');
		$response=$this->gpapi->createOrder($this->cDetails,$this->oDetails,$this->pDetails);
	}
	
	// Order Details Pincode 
	public function testNullPincodeInOrder()
	{
		$this->oDetails['pincode']=null;
		$this->setExpectedException('InvalidArgumentException');
		$response=$this->gpapi->createOrder($this->cDetails,$this->oDetails,$this->pDetails);
	}
	public function testEmptyPincodeInOrder()
	{
		$this->oDetails['pincode']=' ';
		$this->setExpectedException('InvalidArgumentException');
		$response=$this->gpapi->createOrder($this->cDetails,$this->oDetails,$this->pDetails);
	}
	public function testInvalid4PincodeInOrder()
	{
		$this->oDetails['pincode']='1234';
		$this->setExpectedException('InvalidArgumentException');
		$response=$this->gpapi->createOrder($this->cDetails,$this->oDetails,$this->pDetails);
	}
	public function testInvalid7PincodeInOrder()
	{
		$this->oDetails['pincode']='1234567890';
		$this->setExpectedException('InvalidArgumentException');
		$response=$this->gpapi->createOrder($this->cDetails,$this->oDetails,$this->pDetails);
	}

	public function testEmptyClientIdInODetails()
	{
		$this->oDetails['clientOrderID']='  ';
		$this->setExpectedException('InvalidArgumentException');
		$response=$this->gpapi->createOrder($this->cDetails,$this->oDetails,$this->pDetails);		
	}
	public function testNullClientIdInODetails()
	{
		$this->oDetails['clientOrderID']=null;
		$this->setExpectedException('InvalidArgumentException');
		$response=$this->gpapi->createOrder($this->cDetails,$this->oDetails,$this->pDetails);
	}
	public function testEmptyOrderAmountODetails()
	{
		$this->oDetails['orderAmount']= '   ';
		$this->setExpectedException('InvalidArgumentException');
		$response=$this->gpapi->createOrder($this->cDetails,$this->oDetails,$this->pDetails);		
	}
	public function testNullOrderAmountODetails()
	{
		$this->oDetails['orderAmount']= null;
		$this->setExpectedException('InvalidArgumentException');
		$response=$this->gpapi->createOrder($this->cDetails,$this->oDetails,$this->pDetails);
	}
	public function testBeforeDeliveryDateODetails()
	{
		$this->oDetails['deliveryDate']='09-03-2012';
		$this->setExpectedException('InvalidArgumentException');
		$response=$this->gpapi->createOrder($this->cDetails,$this->oDetails,$this->pDetails);		
	}
	public function  testInvalidDeliveryDateODetails()
	{
		$this->oDetails['deliveryDate']='9th April 2012';
		$this->setExpectedException('InvalidArgumentException');
		$response=$this->gpapi->createOrder($this->cDetails,$this->oDetails,$this->pDetails);
	}
	public function testEmptyDeliveryDateODetails()
	{
		$this->oDetails['deliveryDate']='  ';
		$this->setExpectedException('InvalidArgumentException');
		$response=$this->gpapi->createOrder($this->cDetails,$this->oDetails,$this->pDetails);		
	}
	public function testNullDeliveryDateODetails()
	{
		$this->oDetails['deliveryDate']=null;
		$this->setExpectedException('InvalidArgumentException');
		$response=$this->gpapi->createOrder($this->cDetails,$this->oDetails,$this->pDetails);
	}
	//product Details
	public function testEmptyPDetails()
	{
		$this->pDetails='  ';
		$response=$this->gpapi->createOrder($this->cDetails,$this->oDetails,$this->pDetails);
		$this->assertNotEmpty($response['gharpayOrderId']);
	}
//  Additional Parameters can be null
	public function testEmptyaddDetails()
	{
		$this->parameters='  ';
		$response=$this->gpapi->createOrder($this->cDetails,$this->oDetails,$this->pDetails, $this->parameters);
		$this->assertNotEmpty($response['gharpayOrderId']);
	}	
	public function testNulladdDetails()
	{
		$this->parameters=null;
		$response=$this->gpapi->createOrder($this->cDetails,$this->oDetails,$this->pDetails, $this->parameters);
		$this->assertNotEmpty($response['gharpayOrderId']);
	}	
//  pDetails canbe null;
	public function testNullPDetails()
	{
		$this->pDetails=null;
		//$this->setExpectedException('InvalidArgumentException');
		$response=$this->gpapi->createOrder($this->cDetails,$this->oDetails,$this->pDetails);
		$this->assertNotEmpty($response['gharpayOrderId']);
	}
	public function testEmptyArrayPDetails()
	{
		$this->pDetails=array();
		$response=$this->gpapi->createOrder($this->cDetails,$this->oDetails,$this->pDetails);
		$this->assertNotEmpty($response['gharpayOrderId']);
	}
	public function testNullProdQty()
	{
		$this->pDetails['0']['productQuantity']=null;
		$this->setExpectedException('InvalidArgumentException');
		$response=$this->gpapi->createOrder($this->cDetails,$this->oDetails,$this->pDetails);
	}
	public function testEmptyProdQty()
	{
		$this->pDetails['0']['productQuantity']= '   ';
		$this->setExpectedException('InvalidArgumentException');
		$response=$this->gpapi->createOrder($this->cDetails,$this->oDetails,$this->pDetails);
	}
	public function testNullProdId()
	{
		$this->pDetails['0']['productID']= null;
		$this->setExpectedException('InvalidArgumentException');
		$response=$this->gpapi->createOrder($this->cDetails,$this->oDetails,$this->pDetails);
	}
	public function testEmptyProdId()
	{
		$this->pDetails['0']['productID']= '  ';
		$this->setExpectedException('InvalidArgumentException');
		$response=$this->gpapi->createOrder($this->cDetails,$this->oDetails,$this->pDetails);
	}
	public function testNullProdUnitCost()
	{
		$this->pDetails['0']['unitCost']=null;
		$this->setExpectedException('InvalidArgumentException');
		$response=$this->gpapi->createOrder($this->cDetails,$this->oDetails,$this->pDetails);
	}
	public function testEmptyProdUnitCost()
	{
		$this->pDetails['0']['unitCost']='   ';
		$this->setExpectedException('InvalidArgumentException');
		$response=$this->gpapi->createOrder($this->cDetails,$this->oDetails,$this->pDetails);
	}
	public function testNullProdDesc()
	{
		$this->pDetails['0']['productDescription']=null;
		$this->setExpectedException('InvalidArgumentException');
		$response=$this->gpapi->createOrder($this->cDetails,$this->oDetails,$this->pDetails);
	}
	public function testEmptyProdDesc()
	{
		$this->pDetails['0']['productDescription']='  ';
		$this->setExpectedException('InvalidArgumentException');
		$response=$this->gpapi->createOrder($this->cDetails,$this->oDetails,$this->pDetails);
	}
}
?>
