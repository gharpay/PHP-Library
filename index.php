<?php
ini_set('display_errors', 1);
ini_set('log_errors', 1);
ini_set('error_log', dirname('error') . '/error_log.txt'); 
error_reporting(E_ALL);
require_once 'GharpayAPI.php';
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
//             array_push($pDetails, $pDet1);
//             array_push($pDetails, $pDet2);
//
//        $result = $gpapi->createOrder($cDetails, $oDetails, $pDetails);
//        var_dump($result);
//        $resp = $gpapi->viewOrderStatus('GW-222-0006435-356');
//        var_dump($resp);
		//$resp=$gpapi->createOrder($cDetails,$oDetails,$pDetails,$parameters);
//		$resp=$gpapi->validDate('12.12.12');

         //TODO : Check View Order Details;
//          $response=$gpapi->viewOrderDetails('GW-222-0006887-375');
//          var_dump($response);
         //TODO : View Order Status
//         $response=$gpapi->viewOrderStatus('GW-222-0006887');
//         var_dump($response);
         //TODO : view City List;
        // $response=$gpapi->getPincodesInCity('karimnagar');
//       TODO : Get All Pincodes
// 	 $resp=$gpapi->cancelOrder('GW-222-0006247-910');
		 $response=$gpapi->isCityPresent('Karimnagar');
		 var_dump($response);
?>