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
	public function setUp()
	{
		$this->gpapi= new GharpayAPI();
        $this->gpapi->setUsername('redbus');
        $this->gpapi->setPassword('redbus');
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
        
                 
	}
	
    public function tearDown(){
    	$this->cDetails = null;
    	$this->oDetails=null;
    	$this->prod1=null;
    	$this->prod2=null;
    	$this->pDetails=null;
		$this->gpapi=null;   	    	
    }
    //TODO: check create Order response if failed.
//    public function testOKCreateOrder()
//    {
//    	$response=$this->gpapi->createOrder($this->cDetails,$this->oDetails,$this->pDetails);
//    	$this->assertNotEmpty($response['gharpayOrderId']); 	
//    }
//   
   public function testNotOKCreateOrder()
   {
   	$this->cDetails['firstName']=null;
   	$this->setExpectedException('GharpayAPIException');    	
   	$response=$this->gpapi->createOrder($this->cDetails,$this->oDetails,$this->pDetails);   	   
   }
//	TODO : test View Order Details	
//	 p 	ublic function testNotOkViewOrderDetails()
//	 {
//	 	$this->setExpectedException('GharpayAPIException');
//	 	$response=$this->gpapi->viewOrderDetails(3456);
//	 	
//	 }
//	 public function testNotOkisPincodePresent()
//	 {
//	 	$this->setExpectedException('InvalidArgumentException');
//	 	$response=$this->gpapi->isPincodePresent(8787);	 		 	
//	 }
//	 public function testOKisPincodePresent()
//	 {
//	 	$response=$this->gpapi->isPincodePresent(500008);
//	 	$this->assertTrue($response);		 	
//	 }
	 //TODO : check isCityPresent Unit Test
//	 public function testOKisCityPresent()
//	 {
//	 	$response=$this->gpapi->isCityPresent('hyderabad');
//	 	$this->assertTrue($response);
//	 }
//	 public function testNotOKisCityPresent()
//	 {
//	 	$response=$this->gpapi->isCityPresent('Karimnagar');
//	 	$this->assertFalse($response);
//	 }
//	 public function testOkCancelOrder()
//	 {
//	 	$response=$this->gpapi->cancelOrder('GW-222-0006247-910');
//	 	$this->assertTrue($response);
//	 }
// 	 public function testAddProductsToOrder()
// 	 {
// 	 	$response=$this->gpapi->addProductToOrder('GW-222-0006261-025',16999,$this->pDetails);
// 	 	$this->assertTrue($response);
// 	 }
	 
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
