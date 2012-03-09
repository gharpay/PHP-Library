<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description
 *
 * @author khaja Naquiuddin khaja@gharpay.in

 */
require_once('GharpayAPI.php');
class GharpayAPITest extends PHPUnit_Framework_TestCase{
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
            'deliveryDate'=>'10-03-2012',
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
    }
    
/*
 * Test createOrder
 */    
    //TODO: check create Order response if failed.
   public function testOKCreateOrder()
   {
   	$response=$this->gpapi->createOrder($this->cDetails,$this->oDetails,$this->pDetails);
   	$this->assertNotEmpty($response['gharpayOrderId']);
   }   
   public function testNotOKCreateOrder()
   {
   	$this->cDetails['firstName']=null;
   	$this->setExpectedException('GharpayAPIException');    	
   	$response=$this->gpapi->createOrder($this->cDetails,$this->oDetails,$this->pDetails);   	   
   }
 
/*
 * Test cancelProductsFromOrder
 */
   
   public function testOkCancelProductsFromOrder()
   {
   	$response=$this->gpapi->cancelProductsFromOrder('GW-222-0006879-299', 4000, $this->productIds);
    $this->assertTrue($response);
   }
   public function testNotOkCancelProductsFromOrder()
   {
   	$this->setExpectedException("GharpayAPIException");
   	$response=$this->gpapi->cancelProductsFromOrder('0006879-299', 4000, $this->productIds);
   }
	 
/*
 * Test ViewOrderDetails
 */	 
	 
	 public function testOkViewOrderDetails()
	 {
	    $response=$this->gpapi->viewOrderDetails('GW-222-0006921-775');
	    $this->assertNotEmpty($response);
	 }
	public function testNotOkViewOrderDetails()
	 {
	 	$this->setExpectedException('GharpayAPIException');
	 	$response=$this->gpapi->viewOrderDetails('3456');
	 	
	 }
	 
/*
 * Test isPincodePresent
 */
	 
	 public function testNotOkisPincodePresent()
	 {
	 	$this->setExpectedException('InvalidArgumentException');
	 	$response=$this->gpapi->isPincodePresent(8787);	 		 	
	 }
 	public function testOKisPincodePresent()
	 {
	 	$response=$this->gpapi->isPincodePresent(500008);
	 	$this->assertTrue($response);		 	
	 }
	 
	 
	
/*
 *  Test isCityPresent
 */
	 
	 public function testOKisCityPresent()
	 {
	 	$response=$this->gpapi->isCityPresent('Chennai');
	 	$this->assertTrue($response);
	 }
	 public function testNotOKisCityPresent()
	 {
	 	$response=$this->gpapi->isCityPresent('karimnagar');
	 	$this->assertFalse($response);
	 }

/*
 * Test Cancel Order
 */	 
	 public function testOkCancelOrder()
	 {
	 	$response=$this->gpapi->cancelOrder('GW-222-0006894-991');
	 	$this->assertTrue($response);
	 }
	 public function testNotOkCancelOrder()
	 {
   		$this->setExpectedException("GharpayAPIException");
		$response=$this->gpapi->cancelOrder('GW-222-0006247');
	 }

/*
 * Test addProductsToOrder
 */
	 
	 public function testOKAddProductsToOrder()
	 {
	 	$response=$this->gpapi->addProductsToOrder('GW-222-0006921-775',16999,$this->pDetails);
	 	$this->assertTrue($response);
	 }
	 public function testNotOkAddProductsToOrder()
	 {
	 	$this->setExpectedException("GharpayAPIException");
	 	$response=$this->gpapi->addProductsToOrder('2-0006261-025',16999,$this->pDetails); 	
	 }
/*
 * Test ViewOrderStatus
 * 
 */	 
	 public function testOKViewOrderStatus()
	 {
	 	$response=$this->gpapi->viewOrderStatus('GW-222-0006887-375');
	 	$this->assertNotNull($response);
	 }
	 public function testNotOKViewOrderStatus()
	 {
	 	$response=$this->gpapi->viewOrderStatus('88747');
	 	$this->assertNull($response);
	 }

/*
 * Test GetCityList
 */	 
	 public function testOKGetCityList()
	 {
	 	$response=$this->gpapi->getCityList();
	 	$this->assertArrayHasKey('0',$response);
	 }
	 //disconnect internet or wrong credentials
	 public function testNotOkCityList()
	 {
	 	$this->setExpectedException("GharpayAPIException");
	 	$this->gpapi->getCityList();
	 }
	 
	 
/*
 * Test GetPincodesInCity
 */
	 public function testOKGetPincodesInCity()
	 {
	 	$response=$this->gpapi->getPincodesInCity('Mumbai');
	 	$this->assertArrayHasKey('0',$response);
	 }
	 public function  testNotOkGetPincodesInCity()
	 {
	 	$this->setExpectedException("GharpayAPIException");
	 	$response=$this->gpapi->getPincodesInCity('karimnagar');
	 }	 

/*
 * Test getAllPincodes
 */	
 	 public function testOkGetAllPincodes()
 	 {
 		$response=$this->gpapi->getAllPincodes();
 		$this->assertArrayHasKey('0',$response);
 	 }
 	 public function testNotOkGetAllPincodes()
 	 {
 	 	$this->setExpectedException("GharpayAPIException");
 	 	$this->gpapi->getAllPincodes();
 	 }

	 

//	public function testContactNoMandInACreateOrder()
//	{
//		$this->setExpectedException('InvalidArgumentException');	
//	}
//	public function testEmailMandInCreateOrder()
//	{
//		$this->setExpectedException('Oops! Email is either invalid or missing');
//	}
//	 /**
//     * @expectedException
//     */
//	public function testAddressMandInCreateOrder()
//	{
//		$this->setExpectedException('Oops ! Address is missing');
//		
//	}
//	public function testOrderDetailsMandCreaterOrder()
//	{
//		$this->setExpectedException('Oops ! orderDetails are missing');
//	}
//	public function testCityPincodeMandCreateOder()
//	{
//		$this->setExpectedException('InvalidArgumentException','Oops!');
//	}
//	public function testIfAvailableProductIDMandCreateOrder()
//	{
//		
//	}
//	public function testProductIfAvailableUnitCostMandCreateOrder()
//	{
//	}
//	public function testIfAvailableAddInfoNameMandCreateOrder()
//	{			
//	}
//	public function testIfAvailableAddInfoValueMandCreator()
//	{	
//	}
}
?>
