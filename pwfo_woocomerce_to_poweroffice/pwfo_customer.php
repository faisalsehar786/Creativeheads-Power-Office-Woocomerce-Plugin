<?php
// *********************************************
	// Post Customers to PowerOffice API Starts Here
	//**********************************************
function  power_office_woocomerce_api_get_customers_data($url){
$urlWooCus = $url;
	$curlWooCus = curl_init($urlWooCus);
	curl_setopt($curlWooCus, CURLOPT_URL, $urlWooCus);
	curl_setopt($curlWooCus, CURLOPT_RETURNTRANSFER, true);
	$headersWooCus = array(
	"Accept: */*",
	"Authorization:".POWER_OFFICE_PLUGIN_WOO_AUTH_KEY,
	);
	curl_setopt($curlWooCus, CURLOPT_HTTPHEADER, $headersWooCus);
	
	//for debug only!
	curl_setopt($curlWooCus, CURLOPT_SSL_VERIFYHOST, false);
	curl_setopt($curlWooCus, CURLOPT_SSL_VERIFYPEER, false);
	$respWooCus = curl_exec($curlWooCus);
	curl_close($curlWooCus);
	//var_dump($respWooCus);
	$finalRespWooCus = json_decode($respWooCus);
	if (!empty($finalRespWooCus)) {
// usort($finalRespWooCus, function($x, $y) {
// return $x->id > $y->id ? -1 : 1;
// });
return $finalRespWooCus;
}else{
	
return false;
}
	
}
function post_customer_to_power_office($url='', $status=true,$token='',$dataObj=[]){
if ($status) {
$urlCusPost = $url;
	$curlCusPost = curl_init($urlCusPost);
	curl_setopt($curlCusPost, CURLOPT_URL, $urlCusPost);
	curl_setopt($curlCusPost, CURLOPT_POST, true);
	curl_setopt($curlCusPost, CURLOPT_RETURNTRANSFER, true);
	$headersCusPost = array(
		"Accept: application/json",
		"Authorization: Bearer {$token}",
		"Content-Type: application/json",
	);
	curl_setopt($curlCusPost, CURLOPT_HTTPHEADER, $headersCusPost);
	
	$final_arr = [];
foreach ($dataObj as $key => $customer) {
$customer_details = [
			'firstName' => $customer->first_name,
			'lastName' => $customer->last_name,
'emailAddress' => $customer->email,

'code' => $customer->id+10007,
'isPerson' => true
];
$final_arr[] = $customer_details;
}
$finalDataCusWoo = json_encode($final_arr, JSON_NUMERIC_CHECK);
	
	curl_setopt($curlCusPost, CURLOPT_POSTFIELDS, json_encode($final_arr[0], JSON_NUMERIC_CHECK));
	//for debug only!
	curl_setopt($curlCusPost, CURLOPT_SSL_VERIFYHOST, false);
	curl_setopt($curlCusPost, CURLOPT_SSL_VERIFYPEER, false);
	$respCusPost = curl_exec($curlCusPost);
	curl_close($curlCusPost);
return $respCusPost;
	
}
}

function get_customers_from_power_office($url='',$token=''){

$curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_URL => $url,
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => '',
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => 'GET',

  CURLOPT_HTTPHEADER => array(
    'Authorization: Bearer '.$token,
    'Content-Type: application/json'
  ),
));

$response = curl_exec($curl);

curl_close($curl);
 $response;

 $productObject = json_decode($response);
	if (!empty($productObject) && $productObject->error!='invalid_client') {

	
			return  $productObject->data;
	}else{
		return false;
	}

}


function post_customer_to_power_office_dashboard($url='', $status=true,$token='',$dataObj=[],$method=''){
if ($status) {
$urlCusPost = $url;
	$curlCusPost = curl_init($urlCusPost);
	curl_setopt($curlCusPost, CURLOPT_URL, $urlCusPost);
	curl_setopt($curlCusPost, CURLOPT_POST, true);
	curl_setopt($curlCusPost, CURLOPT_RETURNTRANSFER, true);
	$headersCusPost = array(
		"Accept: application/json",
		"Authorization: Bearer {$token}",
		"Content-Type: application/json",
	);
	curl_setopt($curlCusPost, CURLOPT_HTTPHEADER, $headersCusPost);
	
	$final_arr = [];

	if ($method=='put') {
			$powerOfficeData=get_customers_from_power_office($url,$token);


      $IdFind='';
   foreach ($powerOfficeData as $key => $val) {

   	
     if ($val->code==$dataObj->id+10007) {
          	
        $IdFind=$val;
       }


  
   }
 			
			$customer_details = [
			'id'=>(!empty($IdFind))? $IdFind->id:0,
			'firstName' => $dataObj->first_name,
			'lastName' => $dataObj->last_name,
'emailAddress' => $dataObj->email,

'code' => $dataObj->id+10007,
'isPerson' => true
];


	}else{

		$customer_details = [

			'firstName' => $dataObj->first_name,
			'lastName' => $dataObj->last_name,
'emailAddress' => $dataObj->email,

'code' => $dataObj->id+10007,
'isPerson' => true
];
	}


	

$final_arr[] = $customer_details;

$finalDataCusWoo = json_encode($final_arr, JSON_NUMERIC_CHECK);
	
	curl_setopt($curlCusPost, CURLOPT_POSTFIELDS, json_encode($final_arr[0], JSON_NUMERIC_CHECK));
	//for debug only!
	curl_setopt($curlCusPost, CURLOPT_SSL_VERIFYHOST, false);
	curl_setopt($curlCusPost, CURLOPT_SSL_VERIFYPEER, false);
	$respCusPost = curl_exec($curlCusPost);
	curl_close($curlCusPost);
return $respCusPost;
	
}
}




function postallCustomers($url,$token,$Data){
$urlCusPost = $url;
	$curlCusPost = curl_init($urlCusPost);
	curl_setopt($curlCusPost, CURLOPT_URL, $urlCusPost);
	curl_setopt($curlCusPost, CURLOPT_POST, true);
	curl_setopt($curlCusPost, CURLOPT_RETURNTRANSFER, true);
	$headersCusPost = array(
		"Accept: application/json",
		"Authorization: Bearer {$token}",
		"Content-Type: application/json",
	);
	curl_setopt($curlCusPost, CURLOPT_HTTPHEADER, $headersCusPost);
   curl_setopt($curlCusPost, CURLOPT_POSTFIELDS, json_encode($Data, JSON_NUMERIC_CHECK));
	//for debug only!
	curl_setopt($curlCusPost, CURLOPT_SSL_VERIFYHOST, false);
	curl_setopt($curlCusPost, CURLOPT_SSL_VERIFYPEER, false);
	$respCusPost = curl_exec($curlCusPost);
	curl_close($curlCusPost);
	return "<pre>".print_r($respCusPost )."</pre>";

}

function post_customer_to_power_office_all($url='', $status=true,$token='',$dataObj=[]){
if ($status) {

	
	$final_arr = [];
foreach ($dataObj as $key => $customer) {
$customer_details = [
			'firstName' => $customer->first_name,
			'lastName' => $customer->last_name,
'emailAddress' => $customer->email,

'code' => $customer->id+10007,
'isPerson' => true
];
$final_arr[] = $customer_details;
}
$finalDataCusWoo = json_encode($final_arr, JSON_NUMERIC_CHECK);
	

	//print_r($finalDataCusWoo);
	//die();
   $res='';
	foreach ($final_arr as $key => $value) {
			
	   $res.=postallCustomers($url,$token,$value);
	}

  	
return $res;

	
}
}

add_action('wp_ajax_my_ajax_action_customer', 'pwspk_ajax_action_products_customer');


function pwspk_ajax_action_products_customer(){
	if(isset($_POST['action']) && isset($_POST['sendAllcustomers'])){
	 	
		
$getCustomerdata=power_office_woocomerce_api_get_customers_data(POWER_OFFICE_PLUGIN_SITE_URL.'//wp-json/wc/v3/customers?role=all');



if (!empty($getCustomerdata)) {
$postUrlCustomer=POWER_OFFICE_PLUGIN_PO_API_URL.'/customer';
$responseCus=post_customer_to_power_office_all($postUrlCustomer,true,POWER_OFFICE_ACCESS_TOKEN,$getCustomerdata);
$checkStatusCus=json_decode($responseCus);
print_r($checkStatusCus);
}
	}else{ 
echo "no";
  

	}
  
	
	wp_die();
}