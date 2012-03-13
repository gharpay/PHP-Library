<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
/**
 * GharpayAPI is a class Library that helps in integrating Gharpay API into clients
 * PHP applications easily.
 * Offline payments. 
 *
 * @author khaja Naquiuddin khaja@gharpay.in
 */
require_once 'config.php';
if(ERROR_ON)
{
	ini_set('display_errors', 1);
	ini_set('log_errors', 1);
	ini_set('error_log', dirname('error') . '/error_log.txt');
	error_reporting(E_ALL);
}
require_once 'Array2Xml.php';
require_once 'Xml2Array.php';
class GharpayAPIException extends Exception
{
	
}
class GharpayAPI
{
    private $_username;
    private $_password;
    private $_url;
    function __construct()
    {
    	$this->_username = USERNAME;
    	$this->_password= PASSWORD;
    	$this->_url= URL; 	
    }
    public function getUsername()
    {
        return $this->_username;
    }
    public function setUsername($username)
    {
        $this->_username=$username;
    }
    private function getPassword()
    {
        return $this->_password;
    }
    public function setPassword($password)
    {
        $this->_password=$password;
    }
    public function setURL($url)
    {
        $this->_url=$url;
    }
    public function getURL($url)
    {
        return $this->_url;
    }
    /**
     * 
     * @param string $function
     * @param string $method
     * @param string $request_xml
     * @throws GharpayAPIException
     * @return array of Response from API Server.
     */
    private function callGharpayAPI($function,$method="get",$request_xml=null)
    {
        $url= $this->_url.'/rest/GharpayService/'.$function;
        
        //Setting the curl properties in order to invoke the function
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_HEADER,false);
        if($method=='post')
        {
        	curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $request_xml);
        }
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1 );
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                                    'username:'.$this->getUsername(),
                                    'password:'.$this->getPassword(),
                                    'Content-Type:application/xml')
       				);
        $response = curl_exec($ch);
        
        if($response)
        {
        	$response_array=XML2Array::createArray($response);
        	return $response_array;
        }
        else
        {
        	throw new GharpayAPIException('There is a connection error, please check your internet connectivity');
        	
        }
    }
    
	/**
	 * 
	 * @param array $customerDetailsArray 
	 * eg:$cDetails= array(
     *       'address' => 'Aruna towers, flat No. 302, Sangeeth Nagar, Somajiguda',
     *       'contactNo'=>'8888888888',
     *       'firstName'=>'Khaja',
     *       'lastName'=>'Naquiuddin',
     *       'email'=>'khaja@gharpay.in'
     *   );
	 * 
	 * @param array $orderDetailsArray 
	 * eg:
	 * $oDetails = array(
     *       'pincode'=>'400057',
     *       'clientOrderID'=>'6100002',
     *       'deliveryDate'=>'10-03-2012',
     *       'orderAmount'=>'15999'
     *   );
	 * 
	 * @param array $productDetailsArray eg: $productDetailsArray[0] = array (
     *         'productID'=>557777,
     *         'productQuantity'=>1,
     *         'unitCost'=>1599
     *       );
     *   $productDetailsArray[1] = array (
     *         'productID'=>555555,
     *         'productQuantity'=>1,
     *         'unitCost'=>1134
     *       );
	 * @param array $additionalParametersArray  eg: $additionalParametersArray[0]=array('name'=>'somename','value'=>'somevalue');
	 * $additionalParametersArray[1]=array('name'=>'somename1','value'=>'somevalue1');							
	 * @throws GharpayAPIException
	 */
    public function createOrder($customerDetailsArray, $orderDetailsArray,$productDetailsArray=null, $additionalParametersArray=null)
    {
        $arr=array();
        if(!empty($customerDetailsArray)&&!empty($orderDetailsArray)&&)
        {	
	        if($this->validateProductDetails($productDetailsArray)
	        	&& $this->validateOrderDetails($orderDetailsArray)
	        	&& $this->validateCustomerDetails($customerDetailsArray)
	        	&& $this->validateAdditionalDetails($additionalParametersArray)
	        )	
	        {   
	        	$deliveryDate= strtotime($orderDetailsArray['deliveryDate']);
	        	$deliveryDate= date('d-m-Y',$deliveryDate);
	
	        	$orderDetailsArray['deliveryDate'] = $deliveryDate; 
	        	$orderDetailsArray['productDetails']=$productDetailsArray;
	        	$arr = array(
	                'customerDetails'=>$customerDetailsArray,
	                'orderDetails'=>$orderDetailsArray
	                );
					
	        	if($additionalParametersArray!==null)
	        	{
	           	 $arr['additionalInformation']['parameters'] = $additionalParametersArray;
	        	}        
	        	$xml=Array2XML::createXML('transaction', $arr);
	        	$xml=$xml->saveXML();
	        	
	        	$response_arr = $this->callGharpayAPI('createOrder','post', $xml);
	      		
	        	if(!isset($response_arr['createOrderResponse']['errorCode']))
	        	{
	        		$response_mod = array() ;
	        		$response_mod['clientOrderId']=$response_arr['createOrderResponse']['clientOrderID'];
	        		$response_mod['gharpayOrderId']=$response_arr['createOrderResponse']['orderID'];
	        		return $response_mod;
	        	}
	        	else if(!($response_arr['createOrderResponse']['errorMessage']=='null')|| !($response_arr['createOrderResponse']['errorCode'])=='0')
	        	{  
	        		throw new GharpayAPIException($response_arr['createOrderResponse']['errorMessage'],$response_arr['createOrderResponse']['errorCode']);    
	        	}
	        	else 
	        		throw new GharpayAPIException('Error occurred while invoking the API.',0);
	        	
        }
    }
    /**
     * 
     * @param string $gharpayOrderId i.e Gharpay Order Id
     * @param float $orderTotalAmount
     * @param array $productDetailsArray eg: $productDetailsArray[0] = array (
     *         'productID'=>557777,
     *         'productQuantity'=>1,
     *         'unitCost'=>1599
     *       );
     *   $productDetailsArray[1] = array (
     *         'productID'=>555555,
     *         'productQuantity'=>1,
     *         'unitCost'=>1134
     *       ); 
     * @throws GharpayAPIException
     * @return array $resp_mod i.e. $resp_mod['gharpayOrderId], $resp_mod['result']
     */
    public function addProductsToOrder($gharpayOrderId,$orderTotalAmount,$productDetailsArray)
    {
    	$gharpayOrderId=trim($gharpayOrderId);
    	$orderTotalAmount= trim($orderTotalAmount);
    	if(is_string($productDetailsArray)) $productDetailsArray = trim($productDetailsArray);
    	  	$arr=array();
	        if(!empty($productDetailsArray)&& (!empty($gharpayOrderId) && !empty($orderTotalAmount)))
	        {
	        	if($this->validateProductDetails($productDetailsArray))
	        	{
		        	 $orderDetails=array(
		            'orderAmount'=>$orderTotalAmount,
		            'orderID'=>$gharpayOrderId,
		        	'productDetails'=>$productDetailsArray
		        	);
		        	$xml=Array2XML::createXML('addProductsToOrder',$orderDetails);
		    		$xml=$xml->saveXML();
		    		$response = $this->callGharpayAPI('addProductsToOrder','post', $xml);
		    		if(!isset($response['addProductsToOrderResponse']['errorCode']))
		    		{
		    			$resp_mod['gharpayOrderId'] = $response['addProductsToOrderResponse']['orderID'];
		    			$resp_mod['result'] = $response['addProductsToOrderResponse']['result'];
		    			return $resp_mod;
		    		}
		    		else if(!empty($response['addProductsToOrderResponse']['errorMessage'] 	) || !empty($response['addProductsToOrderResponse']['errorCode']))
		    		{
		    			throw new GharpayAPIException($response['addProductsToOrderResponse']['errorMessage'],$response['addProductsToOrderResponse']['errorCode']);
		    		}
		    		else 
		        		throw new GharpayAPIException('Error occurred while invoking the API',0);		    
	        	}	        	        	
	        }
	        else
	        	throw new InvalidArgumentException('arguments are either null or empty');   	
    }
    
    /**
     * @param string $gharpayOrderId
     * @throws GharpayAPIException
     * @return array $response i.e. $response['status'],$response['gharpayOrderId']
     */
    public function viewOrderStatus($gharpayOrderId)
    {
    	$gharpayOrderId = trim($gharpayOrderId);
    	if(!empty($gharpayOrderId))
    	{
	    	$response=$this->callGharpayAPI('viewOrderStatus?orderID='.$gharpayOrderId);
	   		if(!isset($response_arr['viewOrderStatusResponse']['errorCode']))
	   		{	
	    		$resp_mod['status']= $response['viewOrderStatusResponse']['orderStatus'];
	   		    $resp_mod['gharpayOrderId']=$response['viewOrderStatusResponse']['orderID'];  		
	   			return $resp_mod;
	    	}
	   		else if(!($response['viewOrderStatusResponse']['errorMessage']=='null')||!($response['viewOrderStatusResponse']['errorCode']=='0'))
	        {
	        		throw new GharpayAPIException($response['viewOrderStatusResponse']['errorMessage'],$response['viewOrderStatusResponse']['errorCode']);
	        }
	        else
	        	throw new GharpayAPIException('Error occurred while invoking the API',0);
    	}
    	else 
    		throw new InvalidArgumentException('$gharpayOrderId is null or empty');
    }
    
    /**
     * 
     * @param string $gharpayOrderId
     * @throws GharpayAPIException
     * @return array $response['result], $response['gharpayOrderId']
     */
    public function cancelOrder($gharpayOrderId)
    {	$gharpayOrderId=trim($gharpayOrderId);
    	if(!empty($gharpayOrderId))
    	{
	    	$arr['orderID']=$gharpayOrderId;
	    	$xml=Array2XML::createXML('cancelOrder',$arr);
	    	
	    	$xml=$xml->saveXML();
	        $response = $this->callGharpayAPI('cancelOrder','post', $xml);
	        
	        if(!isset($response['cancelOrderResponse']['errorCode']))
	        {
	        	$resp_mod['gharpayOrderId'] = $response['cancelOrderResponse']['orderID'];
	        	$resp_mod['result'] = $response['cancelOrderResponse']['result'];
	        	return $resp_mod;
	        }
	        else if(!($response['cancelOrderResponse']['errorMessage']=='null')||!($response['cancelOrderResponse']['errorCode']=='0'))
	        {
	        		throw new GharpayAPIException($response['cancelOrderResponse']['errorMessage'],$response['cancelOrderResponse']['errorCode']);
	        }
	        else
	        	throw new GharpayAPIException('Error occurred while invoking the API',0);
    	}
    	else 
    		throw new InvalidArgumentException("gharpayOrderId is either null or empty");
    }
    /**
     * 
     * @param string $gharpayOrderId
     * @param float $orderTotalAmount
     * @param array $productIdArray eg: $productIdArray eg: $productIdArray=array(
     *    		0=>'88878755',
     *    		1=>'884888'
     *    		);
     * @throws GharpayAPIException
     * @return array $response['result], $response['gharpayOrderId']
     */

    public function cancelProductsFromOrder($gharpayOrderId,$orderTotalAmount,$productIdArray)
    {
    	$gharpayOrderId=trim($gharpayOrderId); 
    	$orderTotalAmount = trim($orderTotalAmount);
    	if(!empty($gharpayOrderId)&&!empty($orderTotalAmount)&&$this->validateProductIds($productIdArray))
    	{
	    	$arr=array(
	    					'orderAmount'=>$orderTotalAmount,
	    					'orderID'=>$gharpayOrderId,
	    					'productId'=>$productIdArray
	    	);
	    	$xml=Array2XML::createXML('cancelProductsFromOrder',$arr);
	    	$xml=$xml->saveXML();
	    	
	    	$response=$this->callGharpayAPI('cancelProductsFromOrder','post',$xml);
	    	if(!isset($response['cancelProductsFromOrderResponse']['errorCode']))
	    	{
	    		$resp_mod['gharpayOrderId'] = $response['cancelProductsFromOrderResponse']['orderID'];
	    		$resp_mod['result']=$response['cancelProductsFromOrderResponse']['result'];
	    		return $resp_mod;
	    	}
	    	else if(!($response['cancelProductsFromOrderResponse']['errorMessage']=='null')||!($response['cancelProductsFromOrderResponse']['errorCode']=='0'))
	    	{
	    		throw new GharpayAPIException($response['cancelProductsFromOrderResponse']['errorMessage'],$response['cancelProductsFromOrderResponse']['errorCode']);
	    	}
	    	else
	    		throw new GharpayAPIException('Error occurred while invoking the API',0);
    	}
    	else throw new InvalidArgumentException('Arguments are either invalid or null');
   }
    /**
     * 
     * @param string $gharpayOrderId
     * @throws GharpayAPIException
     * @return array eg: $response[commission],$response['customerAddress'],..etc.
     */
    public function viewOrderDetails($gharpayOrderId)
    { 
    	$gharpayOrderId=  trim($gharpayOrderId);
    	if(!empty($gharpayOrderId))
    	{
	    	$response=$this->callGharpayAPI('viewOrderDetails?orderID='.$gharpayOrderId);
	    	
	    	if(!isset($response['viewOrderDetailsResponse']['errorCode']) && sizeof($response['viewOrderDetailsResponse']) > 1)
	    	{
	    		$response_mod['commission']=$response['viewOrderDetailsResponse']['commission'];
	    		$response_mod['customerAddress']=$response['viewOrderDetailsResponse']['customerDetails']['address'];
	    		$response_mod['customerContactNo']=$response['viewOrderDetailsResponse']['customerDetails']['contactNo'];
	    		$response_mod['customerEmail']=$response['viewOrderDetailsResponse']['customerDetails']['email'];
	    		$response_mod['customerFirstName']=$response['viewOrderDetailsResponse']['customerDetails']['firstName'];
	    		$response_mod['customerLastName']=$response['viewOrderDetailsResponse']['customerDetails']['lastName'];
	    		
	    		if(isset($response['viewOrderDetailsResponse']['customerDetails']['prefix']))
	    			$response_mod['customerPrefix']=$response['viewOrderDetailsResponse']['customerDetails']['prefix'];
	    		
	    		$response_mod['deliveryDate']=$response['viewOrderDetailsResponse']['deliveryDate'];
	    		$response_mod['ReconAmount']=$response['viewOrderDetailsResponse']['reconAmount'];
	    		$response_mod['orderStatus']=$response['viewOrderDetailsResponse']['orderStatus'];
	    		$response_mod['serviceTax']=$response['viewOrderDetailsResponse']['serviceTax'];
	    		return $response_mod;
	    	}
	    	else if(isset($response['viewOrderDetailsResponse']['errorCode'])
	    			&& ($response['viewOrderDetailsResponse']['errorMessage'] != 'null' || $response['viewOrderDetailsResponse']['errorCode']!='0'))
	    	{
	    		throw new GharpayAPIException($response['viewOrderDetailsResponse']['errorMessage'],$response['viewOrderDetailsResponse']['errorCode']);
	    	}
	    	else if(!isset($response['viewOrderDetailsResponse']['errorCode']) && sizeof($response['viewOrderDetailsResponse']) == 1)
	    	{
	    		throw new GharpayAPIException("Unable to locate the order in the system");
	    	}
	    	else
	    		throw new GharpayAPIException('Error occurred while invoking the API',0);
    	}
    	else throw new InvalidArgumentException('argument is either null or empty');
    }
    /**
     * 
     * @param int $pincode
     * @throws InvalidArgumentException
     * @throws GharpayAPIException
     * @return boolean
     */
    public function isPincodePresent($pincode)
    {
    	$pincode=trim($pincode);
    	if(!empty($pincode))
    	{
	    	if(strlen((string)$pincode)<>6)
	    		throw new InvalidArgumentException("Oops! Pincode is missing or Invalid");
	    	
	    	$response = $this->callGharpayAPI('isPincodePresent?pincode='.$pincode);
	    	
	    	if(!isset($response['isPincodePresentPresentResponse']['errorCode']))
	        {	
	    		return isset($response['isPincodePresentPresentResponse']['result']) && $response['isPincodePresentPresentResponse']['result'] == 'true' ? True : False ;
	        }
	        
	    	else if(!($response['isPincodePresentPresentResponse']['errorMessage']=='null')||!($response['isPincodePresentPresentResponse']['errorCode']=='0'))
	    	{
	    			throw new GharpayAPIException($response['isPincodePresentPresentResponse']['errorMessage'],$response['isPincodePresentPresentResponse']['errorCode']);
	    	}
	    	else
	    			throw new GharpayAPIException('Error occurred while invoking the API',0);
    	}
    	else throw new InvalidArgumentException('argument is either null or empty');
    }
    /**
     * 
     * @throws GharpayAPIException
     */
    public function getCityList()
    {
    	$response = $this->callGharpayAPI('getCityList');
    	if(!isset($response['getCityListResponse']['errorCode']))
    	{
    		return $response['getCityListResponse']['city'];
    	}
    	else if(!($response['getCityListResponse']['errorMessage']=='null') || !($response['getCityListResponse']['errorCode']=='0'))
    	{
    		throw new GharpayAPIException($response['getCityListResponse']['errorMessage'],$response['getCityListResponse']['errorCode']);
    	}
    	else
    		throw new GharpayAPIException('Error occurred while invoking the API',0);
    	
    }
    /**
     * 
     * @param string $cityName
     * @throws GharpayAPIException
     */
    public function getPincodesInCity($cityName)
    {
    	$cityName=trim($cityName);
    	if(!empty($cityName))
    	{
	    	$response = $this->callGharpayAPI('getPincodesInCity?cityName='.$cityName);	
	    	if(!isset($response['getPincodesInCityResponse']['errorCode']))
	    	{
	    		return $response['getPincodesInCityResponse']['pincode'];
	    	}
	    	else if(!($response['getPincodesInCityResponse']['errorMessage']=='null')||!($response['getPincodesInCityResponse']['errorCode']=='0'))
	    	{
	    		throw new GharpayAPIException($response['getPincodesInCityResponse']['errorMessage'],$response['getPincodesInCityResponse']['errorCode']);
	    	}
	    	else
	    		throw new GharpayAPIException('Error occurred while invoking the API',0);
    	}
    	else  throw new InvalidArgumentException('argument is either null or empty');
    }
    /**
     * 
     * @param string $cityName
     * @throws GharpayAPIException
     * @return boolean
     */
	public function isCityPresent($cityName)
    {
		$cityName=trim($cityName);
		if(!empty($cityName))
		{
	    	$response = $this->callGharpayAPI('isCityPresent?cityName='.$cityName);
	    	if(!isset($response['isCityPresentResponse']['errorCode']))
	    	{
	    		return $response['isCityPresentResponse']['result']=='true' ? TRUE : FALSE;
	    	}
	    	else if(!($response['isCityPresentResponse']['errorMessage']=='null')||!($response['isCityPresentResponse']['errorCode']=='0'))
	    	{
	    		throw new GharpayAPIException($response['isCityPresentResponse']['errorMessage'],$response['isCityPresentResponse']['errorCode']);
	    	}
	    	else
	    		throw new GharpayAPIException('Error occurred while invoking the API',0);
		}
		else throw new InvalidArgumentException('argument is either null or empty');
    }
    /**
     * 
     * @throws GharpayAPIException
     */
    public function getAllPincodes()
    {
    	$response = $this->callGharpayAPI('getAllPincodes');
    	if(!isset($response['getAllPincodesResponse']['errorCode']))
    	{
    	return $response['getAllPincodesResponse']['pincode'];
    	}
    	else if(!($response['getAllPincodesResponse']['errorMessage']=='null')||!($response['getAllPincodesResponse']['errorCode']=='0'))
    	{
    		throw new GharpayAPIException($response['getAllPincodesResponse']['errorMessage'],$response['getAllPincodesResponse']['errorCode']);
    	}
    	else
    		throw new GharpayAPIException('Error occurred while invoking the API',0);
    }
    
    //Validation starts from Here
    /**
     * 
     * @param array $orderDetails
     * @throws InvalidArgumentException
     */
    private function validateOrderDetails($orderDetails)
    { 	
    	if(!isset($orderDetails['deliveryDate'])|| empty($orderDetails['deliveryDate'])
    	|| !$this->validateDate($orderDetails['deliveryDate']))
    	{
    		throw new InvalidArgumentException('Oops! Delivery date is missing or invalid');
    	}
    	else
    	{	$delivery_date= strtotime($orderDetails['deliveryDate']);
    		$now = date('Y-m-d');
    		$now = strtotime($now);
    		if($delivery_date<$now)
    		{
    			throw new InvalidArgumentException("Oops! Delivery date is before today's date");
    		}
    	}
    	if(is_null($orderDetails['pincode']) || !isset($orderDetails['pincode']) || empty($orderDetails['pincode']) || strlen((string)$orderDetails['pincode'])<>6)
    		throw new InvalidArgumentException("Oops! Pincode is missing or Invalid");
    	if(!isset($orderDetails['orderAmount'])||empty($orderDetails['orderAmount']))
    		throw new InvalidArgumentException("Oops! Total Order Amount is missing");
    	if(!isset($orderDetails['clientOrderID'])||empty($orderDetails['clientOrderID']))
    		throw new InvalidArgumentException("Oops! Client Order ID is missing");
    	return true;   	
    }
    /**
     * 
     * @param array $customerDetails
     * @throws InvalidArgumentException
     * @return boolean
     */
    private function validateCustomerDetails($customerDetails)
    {   if(empty($customerDetails))
    		throw new InvalidArgumentException('Customer Details array is either empty or null');
    	$customerDetails['firstName']=trim($customerDetails['firstName']);
    	$customerDetails['contactNo']=trim($customerDetails['contactNo']);
    	$customerDetails['address']=trim($customerDetails['address']);
    	if(!isset($customerDetails['firstName'])|| empty($customerDetails['firstName']))
    		throw new InvalidArgumentException("Oops! First Name is missing");
    	if(!isset($customerDetails['contactNo'])|| empty($customerDetails['contactNo']))
    		throw new InvalidArgumentException("Oops! Contact No is missing");
    	if(!isset($customerDetails['address'])|| empty($customerDetails['address']))
    		throw new InvalidArgumentException("Oops! Address is missing");
    	return true;   	
    	
    }
    /**
     * 
     * @param array $additionalParametersArray
     * @throws InvalidArgumentException
     */
    private function validateAdditionalDetails($additionalParametersArray)
    {
    	if($additionalParametersArray['parameters']!==null)
    	{
    		foreach($parameters as $param)
    		{
    			$param['name']=trim($param['name']);
    			$param['value']=trim($param['value']);    			
    			if(
    			isset($param['name'])&&!empty($param['name'])
    			&&isset($param['value'])&&!empty($param['value'])
    			)
    			{
    				return true;			
    			}
    			else
    			{
		    		throw new InvalidArgumentException("Parameter Name or Value is missing in Additional Information");
    			}
    		}
    	}
    	return true;
    }
    /**
     * 
     * @param array $productDetails
     * @throws Exception
     * @return boolean
     */ 		
    private function validateProductDetails($productDetails)
        {
        	if($productDetails!==null && !empty($productDetails))
        	{
        		foreach($productDetails as $pd)
        		{
        			$pd['productID']=trim($pd['productID']);
        			$pd['productQuantity']=trim($pd['productQuantity']);
        			$pd['unitCost']=trim($pd['unitCost']);
        			if(isset($pd['productID'])&&isset($pd['productQuantity'])&&isset($pd['unitCost'])&&
        			!empty($pd['productID'])&&!empty($pd['productQuantity'])&&!empty($pd['unitCost'])
        			)
        			{
        				return true;        					        				    			
        			}
        			else
        			{
        				throw new InvalidArgumentException("Product details array has missing or incorrect keys. Cannot call the Gharpay API");
        			}
        		}
        	}
        	
        	//return true;
        }
        /**
         * @param string $date
         * @return boolean
         */
	private function validateDate($date) 
	{
		$date = trim($date);
		$pattern = '/^(0?[1-9]|[12][0-9]|3[01])[- \/.](0?[1-9]|1[012])[- \/.](19|20)?[0-9]{2}$/';
		if(!preg_match($pattern,$date)) {
			return false;
		} else {
			return true;
		}
	}
	/**
	 * @param array $productIdArray
	 * @throws InvalidArgumentException
	 * @return boolean
	 */
	private function validateProductIds($productIdArray)
	{
		if($productIdArray!=null)
		{
			foreach($productIdArray as $pid)
			{
				trim($pid);
				if(empty($pid))
				{
					throw new InvalidArgumentException('values in product ids are null or empty',0);
				}
				
			}
			return true;
		}
		else {
			throw new InvalidArgumentException('product ID array is missing or invalid');
		}	
	}
}