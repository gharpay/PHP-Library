<?php
require_once 'GharpayAPI.php';
        $gpapi= new GharpayAPI();
        $customerDetails= array(
            'address' => 'Aruna towers, flat No. 302, Sangeeth Nagar, Somajiguda',
            'contactNo'=>'8888888888',
            'firstName'=>'Khaja',
            'lastName'=>'Naquiuddin',
            'email'=>'tech@gharpay.in'
        );
        
	$orderDetails = array(
            'pincode'=>'400057',
            'clientOrderID'=>'6100002',
            'deliveryDate'=>'28-2-2013',
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
	
	// $result = $gpapi->createOrder($customerDetails, $orderDetails, $productDetails);
  $result = $gpapi->viewOrderStatus("GW-264-0019960-845");
  var_dump($result);



  /*  Optionally If you want to pass a custom invoice or other extra parameters to us then please send it to us using these lines of code.
      Donot add these lines of code if you don't have to pass custom invoice or other additional parameters.
  */

  $parameters[0]=array(
    'name'=>'InvoiceURL',
    'value'=>'http://www.gharpay.in'  
      );

  // $result = $gpapi->createOrder($customerDetails, $orderDetails, $productDetails);
  var_dump($result);

?>
