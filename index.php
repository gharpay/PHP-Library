<?php
ini_set('display_errors', 1);
ini_set('log_errors', 1);
ini_set('error_log', dirname('error') . '/error_log.txt'); 
error_reporting(E_ALL);
require_once 'GharpayAPI.php';
require_once 'System.php';
        $gpapi= new GharpayAPI();
        $gpapi->setUsername('test_api');
        $gpapi->setPassword('test_api');
        $gpapi->setURL('services.gharpay.in');
        $cDetails= array(
            'address' => 'Aruna towers, flat No. 302, Sangeeth Nagar, Somajiguda',
            'contactNo'=>'8888888888',
            'firstName'=>'Khaja',
            'lastName'=>'Naquiuddin',
            'email'=>'khaja@gharpay.in'
        );
        $oDetails = array(
            'pincode'=>'400057',
            'clientOrderID'=>'6100002',
            'deliveryDate'=>'10-03-2012',
            'orderAmount'=>'15999'
        );
        #$pDetails=array();
        $pDetails[0] = array (
              'productID'=>557777,
              'productQuantity'=>1,
              'unitCost'=>1599
            );
        $pDetails[1] = array (
              'productID'=>555555,
              'productQuantity'=>1,
              'unitCost'=>1134
            );
         $parameters[0]=array(
            						'name'=>'somename',
            						'value'=>'somevalue'
            					);
         $parameters[1]=array(	  'name'=>'somename',
            					  'value'=>'somevalue'
            					);
         $productIds=array(
         		0=>'88878755',
         		1=>'884888'
         );
//             array_push($pDetails, $pDet1);
//             array_push($pDetails, $pDet2);
//        $response= $gpapi->cancelProductsFromOrder('GW-222-0006947-994', 4000, $productIds);
//        $result = $gpapi->createOrder($cDetails, $oDetails, $pDetails);
//        var_dump($result);
//        $resp = $gpapi->viewOrderStatus('GW-222-0006435-356');
//        var_dump($resp);
		//$resp=$gpapi->createOrder($cDetails,$oDetails,$pDetails,$parameters);
//		$resp=$gpapi->validDate('12.12.12');

//          $response=$gpapi->viewOrderDetails('GW-222-0006887-375');
//          var_dump($response);
//         $response=$gpapi->viewOrderStatus('GW-222-0006887');
//         var_dump($response);
        
        // $response=$gpapi->getPincodesInCity('karimnagar');
// 	 $resp=$gpapi->cancelOrder('GW-222-0006247-910');
		 //$response=$gpapi->isCityPresent('Karimnagar');
       // $pDetails[0]['unitCost']=' ';
        //$response=$gpapi->addProductsToOrder('GW-222-0006921-775',16000,$pDetails);
		 echo $_SERVER['DOCUMENT_ROOT'];
		// var_dump(class_exists('System', false));
?>