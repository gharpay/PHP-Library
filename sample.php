<?php
require_once 'GharpayAPI.php';
        $gpapi= new GharpayAPI();
        $customerDetails= array(
            'address' => 'Aruna towers, flat No. 302, Sangeeth Nagar, Somajiguda',
            'contactNo'=>'8888888888',
            'firstName'=>'Khaja',
            'lastName'=>'Naquiuddin',
            'email'=>'khaja@gharpay.in'
        );
        
	$orderDetails = array(
            'pincode'=>'400057',
            'clientOrderID'=>'6100002',
            'deliveryDate'=>'30-03-2012',
            'orderAmount'=>'15999'
        );
        
	$productDetails[0] = array (
              'productID'=>557777,
              'productQuantity'=>1,
              'unitCost'=>1599,
	      'productDescription'=>'Sony Vaio E series'
            );
        $productDetails[1] = array (
              'productID'=>555555,
              'productQuantity'=>1,
              'unitCost'=>1134,
	      'productDescription'=>'Sony E series'
            );
	$parameters[0]=array(
		'name'=>'InvoiceURL',
		'value'=>'http://www.gharpay.in'	
	    );
	
	   $result = $gpapi->createOrder($customerDetails, $orderDetails, $productDetails, $parameters);
           var_dump($result);
?>
