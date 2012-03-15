<?php
//$path=$_SERVER['DOCUMENT_ROOT'].DIRECTORY_SEPARATOR.'PHP-Library'.DIRECTORY_SEPARATOR.'GharpayAPI.php';
// echo $path;

require_once dirname(__File__).DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'GharpayAPI.php';
class AddPrductsToOrder extends PHPunit_Framework_TestCase 
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

/*
 * Test addProductsToOrder
*/

	public function testOK()
	{
		$response=$this->gpapi->addProductsToOrder('GW-222-0006921-775',16999,$this->pDetails);
		$this->assertNotEmpty($response['gharpayOrderId']);
		$this->assertNotEmpty($response['result']);
	}
	//GharpayId
	public function testInvalidGharpayId()
	{
		$this->setExpectedException("GharpayAPIException");
		$response=$this->gpapi->addProductsToOrder('2-0006261-025',16999,$this->pDetails);
	}
	public function testNullGharpayOrderID()
	{
		$this->setExpectedException("InvalidArgumentException");
		$response=$this->gpapi->addProductsToOrder(null,16999,$this->pDetails);
	}
	public function testEmptyGharpayOrderID()
	{
		$this->setExpectedException("InvalidArgumentException");
		$response=$this->gpapi->addProductsToOrder(' ',16999,$this->pDetails);
	}
	
	//TotalorderAmount
	public function testNullOrderAmount()
	{
		$this->setExpectedException("InvalidArgumentException");
		$response=$this->gpapi->addProductsToOrder('GW-222-0006921-775',null,$this->pDetails);
	}
	public function testEmptyOrderAmount()
	{
		$this->setExpectedException("InvalidArgumentException");
		$response=$this->gpapi->addProductsToOrder('GW-222-0006921-775','  ',$this->pDetails);
	}
	
	//pDetails
	public function testNullpDetails(){
		$this->setExpectedException("InvalidArgumentException");
		$response=$this->gpapi->addProductsToOrder('GW-222-0006921-775',16999,null);
	}
	public function testEmptypDetails(){
		$this->setExpectedException("InvalidArgumentException");
		$response=$this->gpapi->addProductsToOrder('GW-222-0006921-775',16999,'  ');
	}
	public function testEmptyArraypDetails(){
		$this->pDetails = array();
		$this->setExpectedException("InvalidArgumentException");
		$response=$this->gpapi->addProductsToOrder('GW-222-0006921-775',16999,$this->pDetails);
	}
	
	//Product Details
	public function testNullProdUnitCost()
	{
		$this->pDetails['0']['unitCost']=null;
		$this->setExpectedException("InvalidArgumentException");
		$response=$this->gpapi->addProductsToOrder('GW-222-0006921-775',16000,$this->pDetails);
	}
	public function testEmptyProdUnitCost()
	{
		$this->pDetails['0']['unitCost']=null;
		$this->setExpectedException("InvalidArgumentException");
		$response=$this->gpapi->addProductsToOrder('GW-222-0006921-775',16000,$this->pDetails);
	}	
	public function testNullProdQty()
	{
		$this->pDetails['0']['productQuantity']=null;
		$this->setExpectedException("InvalidArgumentException");
		$response=$this->gpapi->addProductsToOrder('GW-222-0006921-775',16000,$this->pDetails);
	}
	public function testEmptyProdQty()
	{
		$this->pDetails['0']['productQuantity']='  ';
		$this->setExpectedException("InvalidArgumentException");
		$response=$this->gpapi->addProductsToOrder('GW-222-0006921-775',16000,$this->pDetails);
	}
	public function testNullProdId()
	{
		$this->pDetails['0']['productID']=null;
		$this->setExpectedException("InvalidArgumentException");
		$response=$this->gpapi->addProductsToOrder('GW-222-0006921-775',16000,$this->pDetails);
	}
	public function testEmptyProdId()
	{
		$this->pDetails['0']['productID']='  ';;
		$this->setExpectedException("InvalidArgumentException");
		$response=$this->gpapi->addProductsToOrder('GW-222-0006921-775',16000,$this->pDetails);
	}
}