PHP-Library
===========

This library helps in integrating Gharpay's API in PHP applications easily

Using Gharpay PHP LIbrary is easy. Follow the simple 5 steps.

1. Add username, password and webservice URL in config.php file as given below.
<pre><code>   
define("USERNAME","your_username"); // add your username here; 
define("PASSWORD","your_password"); //add password here; 
define("URL","http://services.gharpay.in"); //Your web service URL goes here;
define("ERROR_ON",FALSE); //if you are developing app turn this to TRUE to enable error reporting.
</code></pre>

2. After this in your PHP file, include the file GharpayAPI.php. An example is shown below:
<pre><code>   
require_once ‘/path/to/GharpayAPI.php';
</code></pre>
3. Let us see how to create an order. The createOrder() takes four array parameters. They are 
   * Customer Details 
   * Order Details 
   * Product Details (optional, but highly recommended) 
   * Additional Parameters (optional).    
   It returns an associative array consisting of keys gharpayOrderId and clientOrderId. 
   Let’s create the four parameters easily as given below.
   <pre><code>
   //Add customer details 
   $customerDetails= array( 
   'address' => 'Aruna towers, 
   flat No. 302, Sangeeth Nagar, Somajiguda', 
   'contactNo'=>'8888888888', 'firstName'=>'Ravi', 
   'lastName'=>'Kumar', 
   'email'=>'ravi@example.com' );
   //Add order details
   $orderDetails = array( 
   'pincode'=>'400057', 
   'clientOrderID'=>'6100002', 
   'deliveryDate'=>'30-03-2012', 
   'orderAmount'=>'15999' 
   );
   //Adding two Products related to Order 
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
   // Adding an additional parameter which sends Invoice URL to us.
   $parameters[0]=array ( 
   'name'=>'InvoiceURL', 
   'value'=>'http://link/to/online/invoice'
   );
   </code></pre>

4. Create an Object of the GharpayAPI class and call the createOrder() function using the object. 
    <pre><code>
    $gpapi = new GharpayAPI()
    $result = $gpapi->createOrder($customerDetails, $orderDetails, $productDetails, $parameters);
     //printing the returned array
     var_dump($result);
     </code></pre>

5. Run the PHP file. You shoud see the output as an array 
    <pre><code>
   'clientOrderId' => string 'xxxxxxx' 
   'gharpayOrderId' => string 'GW-xxx-xxxxxxx-xxx'
   </code></pre>
   You can access the returned array as 
   <pre><code>
   $gharpayOrderId = $result[‘gharpayOrderId’]; 
   $clientOrderId = $result[‘clientOrderId’];
   </code></pre>

Further, You can read our wiki on [how to retreive real time updates from Gharpay](https://github.com/gharpay/PHP-Library/wiki/How-to-retreive-real-time-updates-from-Gharpay).
