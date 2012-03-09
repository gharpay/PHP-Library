<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
//TODO : Add GharpayAPI Error handling in all Methods.
/**
 * Description of GharpayAPI
 *
 * @author khaja
 */
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
    private $_useSSL;
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
    public function setUseSSL($useSSL)
    {
        $this->_useSSL=$useSSL;

    }
    public function getUseSSL()
    {
        return $this->_useSSL;
    }
    private function callGharpayAPI($function,$method=null,$request_xml=null)
    {
        //echo  'sending this requiest<br/>'.$request_xml."\n";
        $url= $this->getUseSSL()?'https://':'http://';
        $url.= $this->_url;       
        $url.='/rest/GharpayService/'.$function;
        //echo $url
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
        var_dump($response);
        if($response)
        {
        	$response_array=XML2Array::createArray($response);
        	return $response_array;
        }
        else
        {
        	throw new GharpayAPIException('There is a connection error, please Check internet is available or not');
        	
        }
    }

    public function createOrder($customerDetailsArray, $orderDetailsArray,$productDetailsArray=null, $additionalParametersArray=null)
    {
        $arr=array();
        if($this->validateProductDetails($productDetailsArray)
        &&$this->validateOrderDetails($orderDetailsArray,'createOrder')
        //&&$this->validateCustomerDetails($customerDetailsArray)
        &&$this->validateAdditionalDetails($additionalParametersArray)
        )
        {   
        	$deliveryDate= strtotime($orderDetailsArray['deliveryDate']);
        	$deliveryDate= date('d-m-Y',$deliveryDate);
//        	echo 'delivery Date :'.$deliveryDate;
        	$orderDetailsArray['deliveryDate'] = $deliveryDate; 
        	$orderDetailsArray['productDetails']=$productDetailsArray;
        	$arr = array(
                'customerDetails'=>$customerDetailsArray,
                'orderDetails'=>$orderDetailsArray,
                );
				
        	if($additionalParametersArray!==null)
        	{
            $arr['additionalInformation']['parameters'] = $additionalParametersArray;
        	}        
        	$xml=Array2XML::createXML('transaction', $arr);
        	$xml=$xml->saveXML();
        	$response_arr = $this->callGharpayAPI('createOrder','post', $xml);
        	var_dump($response_arr);
        	if(!isset($response_arr['createOrderResponse']['errorCode']))
        	{
        		$response_mod['clientOrderId']=$response_arr['createOrderResponse']['clientOrderID'];
        		$response_mod['gharpayOrderId']=$response_arr['createOrderResponse']['orderID'];
        		return $response_mod;
        	}
        	else if(!empty($response_arr['createOrderResponse']['errorMessage'])||!empty($response_arr['createOrderResponse']['errorCode']))
        	{  
        		throw new GharpayAPIException($response_arr['createOrderResponse']['errorMessage'],$response_arr['createOrderResponse']['errorCode']);    
        	}
        	// TODO : Need to ask about this to arpit;
        	else 
        		throw new GharpayAPIException('There is an error in the server for your order. Please contact tech@gharpay.in');
        	
        }
    }
    
    //TODO : Check Add Products to Order
    public function addProductToOrder($orderID,$orderTotalAmount,$productDetailsArray)
    {
        $arr=array();
        if($this->validateProductDetails($productDetailsArray))
        {
        	 $orderDetails=array(
            'orderAmount'=>$orderTotalAmount,
            'orderID'=>$orderID,
        	'productDetails'=>$productDetailsArray
        	);
        	$xml=Array2XML::createXML('addProductsToOrder',$orderDetails);
    		$xml=$xml->saveXML();
    		var_dump($xml);
    		$response = $this->callGharpayAPI('addProductsToOrder','post', $xml);
    		var_dump($response);
    		if(!isset($response['addProductsToOrderResponse']['errorCode']))
    		{
    			return $response['addProductsToOrderResponse']['result']=='true'?true:false;
    		}
    		else if(!empty($response['addProductsToOrderResponse'])||!empty($response['addProductsToOrderResponse']['errorCode']))
    		{
    			//TODO : Add validation for Null message and ) error code
    			throw new GharpayAPIException($response['addProductsToOrderResponse']['errorMessage'],$response['addProductsToOrderResponse']['errorCode']);
    		}
    		else 
        		throw new GharpayAPIException('There is an error in the server');        	
        }
    }
    public function viewOrderStatus($gharpayOrderId)
    {
    	$response=$this->callGharpayAPI('viewOrderStatus?orderID='.$gharpayOrderId);
   		if(!isset($response_arr['viewOrderStatusResponse']['errorCode']))	
    	return !empty($response['viewOrderStatusResponse']['orderStatus'])?$response['viewOrderStatusResponse']['orderStatus']: null;
    }
    public function cancelOrder($gharpayOrderId)
    {	
    	$arr['orderID']=$gharpayOrderId;
    	$xml=Array2XML::createXML('cancelOrder',$arr);
    	$xml=$xml->saveXML();
    	var_dump($xml);
        $response = $this->callGharpayAPI('cancelOrder','post', $xml);
        var_dump($response);
        return $response['cancelOrderResponse']['result']=='true' ? true : false ;
    } 
    
    public function viewOrderDetails($gharpayOrderId)
    {
    	$response=$this->callGharpayAPI('viewOrderDetails?orderID='.$gharpayOrderId);
    	if(!isset($response['viewOrderDetailsResponse']['return']))
    	{
    		throw new GharpayAPIException("Cannot retrieve Order Details, Invalid Gharpay Order ID");
    	}
    	else
    	{
    		$response_mod['commission']=$response['viewOrderDetailsResponse']['commission'];
    		$response_mod['customerAddress']=$response['viewOrderDetailsResponse']['customerDetails']['address'];
    		$response_mod['customerContactNo']=$response['viewOrderDetailsResponse']['customerDetails']['contactNo'];
    		$response_mod['customerEmail']=$response['viewOrderDetailsResponse']['customerDetails']['email'];
    		$response_mod['customerFirstName']=$response['viewOrderDetailsResponse']['customerDetails']['firstName'];
    		$response_mod['customerLastName']=$response['viewOrderDetailsResponse']['customerDetails']['lastName'];
    		$response_mod['customerPrefix']=$response['viewOrderDetailsResponse']['customerDetails']['customerDetails']['prefix'];
    		$response_mod['deliveryDate']=$response['viewOrderDetailsResponse']['deliveryDate'];
    		$response_mod['executiveContactNo']=$response['viewOrderDetailsResponse']['executiveContactNo'];
    		$response_mod['executiveName']=$response['viewOrderDetailsResponse']['executiveName'];
    		$response_mod['orderStatus']=$response['viewOrderDetailsResponse']['orderStatus'];
    		return $response_mod;
    	}
    		
    }
    public function isPincodePresent($pincode)
    {
    	if(strlen((string)$pincode)<>6)
    		throw new InvalidArgumentException("Oops! Pincode is missing or Invalid");
    	$response = $this->callGharpayAPI('isPincodePresent?pincode='.$pincode);
    	return isset($response['isPincodePresentPresentResponse']['result'])&&$response['isPincodePresentPresentResponse']['result'] =='true'? True : False ;
    }
    public function getCityList()
    {
    	$response = $this->callGharpayAPI('getCityList');
    	return $response['getCityListResponse']['city'];
    	
    }
    public function getPincodesInCity($cityName)
    {
    	
    	$response = $this->callGharpayAPI('getPincodesInCity?cityName='.$cityName);
    	return $response['getPincodesInCityResponse']['pincode'];
    	
    }
	public function isCityPresent($cityName)
    {
    	$response = $this->callGharpayAPI('isCityPresent?cityName='.$cityName);
    	var_dump('city :'. $cityName."\n".$response);
    	return $response['isCityPresentResponse']['result']=='true' ? TRUE : FALSE;
    		
    }
    public function getAllPincodes()
    {
    	$response = $this->callGharpayAPI('getAllPincodes');
    	return $response['getAllPincodesResponse']['pincode'];
    }
    
    //Validation starts from Here
    
    private function validateOrderDetails($orderDetails,$function)
    { 	
    	if(!isset($orderDetails['deliveryDate'])||empty($orderDetails['deliveryDate'])
    	|| !$this->validateDate($orderDetails['deliveryDate']))
    	{
    		throw new InvalidArgumentException('Oops! Delivery Date is missing or Invalid');
    	}
    	else
    	{	$delivery_date= strtotime($orderDetails['deliveryDate']);
    		$now = date('Y-m-d');
    		$now = strtotime($now);
    		if($delivery_date<$now)
    		{
    			throw new InvalidArgumentException('Oops! Delivery Date is older than todays date');
    		}
    	}
    	//TODO : validate for 6 digit pincode
    	if(!isset($orderDetails['pincode'])||empty($orderDetails['pincode']) || strlen((string)$orderDetails['pincode'])<>6)
    		throw new InvalidArgumentException("Oops! Pincode is missing or Invalid");
    	if(!isset($orderDetails['orderAmount'])||empty($orderDetails['orderAmount']))
    		throw new InvalidArgumentException("Oops! Total Order Amount is missing");
    	if(!isset($orderDetails['clientOrderID'])||empty($orderDetails['clientOrderID']))
    		throw new InvalidArgumentException("Oops! Client Order ID is missing");
    	return true;   	
    }
    
    private function validateCustomerDetails($customerDetails)
    {
    	if(!isset($customerDetails['firstName'])||empty($customerDetails['firstName']))
    		throw new InvalidArgumentException("Oops! First Name is missing");
    	if(!isset($customerDetails['contactNo'])||empty($customerDetails['contactNo']))
    		throw new InvalidArgumentException("Oops! Contact No is missing");
    	if(!isset($customerDetails['address'])||empty($customerDetails['address']))
    		throw new InvalidArgumentException("Oops! Address is missing");
    	return true;   	
    	
    }
    
    private function validateAdditionalDetails($parameters)
    {
    	if($parameters!==null)
    	{
    		foreach($parameters as $param)
    		{
    			if(
    			isset($param['name'])&&!empty($param['name'])
    			&&isset($param['value'])&&!empty($param['value'])
    				)
    			{
    				echo 'returning true';
    				return true;			
    			}
    			else
    			{
		    		echo('throwing exception');
		    		throw new InvalidArgumentException("Parameter Name or Value is missing in Additional Information");
    			}
    		}
    	}
    	return true;
    	
    } 		
    private function validateProductDetails($productDetails)
        {
        	if($productDetails!==null)
        	{
        		foreach($productDetails as $pd)
        		{
        			if(isset($pd['productID'])&&isset($pd['productQuantity'])&&isset($pd['unitCost'])&&
        			  !empty($pd['productID'])&&!empty($pd['productQuantity'])&&!empty($pd['unitCost'])
        			  && is_int($pd['unitCost'])&&is_int($pd['productQuantity']))
        			{
        				return true;        					        				    			
        			}
        			else
        			{
        				throw new Exception("Mandatory Product Details are missing, cannot call Gharpay API");
        			}
        		}
        	}
        	return true;
        }
        
	public function validateDate($date) {
		$pattern = '/^(0?[1-9]|[12][0-9]|3[01])[- \/.](0?[1-9]|1[012])[- \/.](19|20)?[0-9]{2}$/';
		if(!preg_match($pattern,$date)) {
			return false;
		} else {
			return true;
		}
	}
}
?>
