<?php
// *****************************************
	// PowerOffice API Authorization Starts Here
	//******************************************

function power_office_woocomerce_api_get_products_data($url=''){

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
$headers = array();
$headers[] ="Authorization:".POWER_OFFICE_PLUGIN_WOO_AUTH_KEY;
$headers[] = 'Cache-Control: no-cache';
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
$result = curl_exec($ch);
if (curl_errno($ch)) {
echo 'Error:' . curl_error($ch);
}
curl_close($ch);
	$respWoo = json_decode($result);
	if (!empty($respWoo)) {
return $respWoo;
}else{
return false;
}
}
	
function post_product_to_power_office($url='', $status=true ,$token='',$dataObj=[]){

	if ($status) {
		
		
	// ********************************************
	// Post Products to PowerOffice API Starts Here
	//*********************************************
	$urlPro =$url;
	$curlPro = curl_init($urlPro);
	curl_setopt($curlPro, CURLOPT_URL, $urlPro);
	curl_setopt($curlPro, CURLOPT_POST, true);
	curl_setopt($curlPro, CURLOPT_RETURNTRANSFER, true);
	$headersPro = array(
		"Accept: application/json",
		"Authorization: Bearer {$token}",
		"Content-Type: application/json",
	);
	curl_setopt($curlPro, CURLOPT_HTTPHEADER, $headersPro);
	
	$finalPro_arr = [];
foreach ($dataObj as $key => $product) {
$dataPro = [
			'name' => $product->name,
			'description' => $product->short_description,
			'salesPrice' => $product->price,
			'code' => $product->id,
			'salesAccount' => 3000,
			'isActive' => true
];
$finalPro_arr[] = $dataPro;
}
$finalDataCusWoo = json_encode($finalPro_arr, JSON_NUMERIC_CHECK);
	
	curl_setopt($curlPro, CURLOPT_POSTFIELDS, json_encode($finalPro_arr[0], JSON_NUMERIC_CHECK));
	//for debug only!
	curl_setopt($curlPro, CURLOPT_SSL_VERIFYHOST, false);
	curl_setopt($curlPro, CURLOPT_SSL_VERIFYPEER, false);
	$respPro = curl_exec($curlPro);
	curl_close($curlPro);
	return $respPro;
}else{
	return false;
}

}



function postallProducts($url,$token,$Data){
		
	// ********************************************
	// Post Products to PowerOffice API Starts Here
	//*********************************************
	$urlPro =$url;
	$curlPro = curl_init($urlPro);
	curl_setopt($curlPro, CURLOPT_URL, $urlPro);
	curl_setopt($curlPro, CURLOPT_POST, true);
	curl_setopt($curlPro, CURLOPT_RETURNTRANSFER, true);
	$headersPro = array(
		"Accept: application/json",
		"Authorization: Bearer {$token}",
		"Content-Type: application/json",
	);

	curl_setopt($curlPro, CURLOPT_HTTPHEADER, $headersPro);

	curl_setopt($curlPro, CURLOPT_POSTFIELDS, json_encode($Data, JSON_NUMERIC_CHECK));
	//for debug only!
	curl_setopt($curlPro, CURLOPT_SSL_VERIFYHOST, false);
	curl_setopt($curlPro, CURLOPT_SSL_VERIFYPEER, false);
	$respPro = curl_exec($curlPro);
	
	curl_close($curlPro);
	
    
	return	$respPro;

}
  

function post_product_to_power_office_all($url='', $status=true ,$token='',$dataObj=[]){

	if ($status) {
		
	$retrunres='';

	
	
	$finalPro_arr = [];
foreach ($dataObj as $key => $product) {
$dataPro = [
			'name' => $product->name,
			'description' => $product->short_description,
			'salesPrice' => $product->price,
			'code' => $product->id,
			'salesAccount' => 3000,
			'isActive' => true
];
$finalPro_arr[] = $dataPro;
}
$finalDataCusWoo = json_encode($finalPro_arr, JSON_NUMERIC_CHECK);
	
	$countTotal=0;
	$countsuccess=0;
	foreach ($finalPro_arr as $key => $value) {

   $countTotal++;
     $res=postallProducts($url,$token,$value);

	 	$retrunres.="<pre>".print_r($res )."</pre>";
	}

  	
return $retrunres;

}else{
	return false;
}

}
  
add_action('wp_ajax_my_ajax_action_products', 'pwspk_ajax_action_products');


function pwspk_ajax_action_products(){
	if(isset($_POST['action']) && isset($_POST['sendAllproducts'])){
	 	
		
$returnDataProducts=power_office_woocomerce_api_get_products_data(POWER_OFFICE_PLUGIN_SITE_URL.'//wp-json/wc/v3/products');

   
 if (!empty($returnDataProducts)) {

$postProUrl=POWER_OFFICE_PLUGIN_PO_API_URL.'/product';
$respons=post_product_to_power_office_all($postProUrl,true,POWER_OFFICE_ACCESS_TOKEN,$returnDataProducts);
$check=json_decode($respons);


print_r($check);
}

	}else{ 
echo "no";
  

	}
  
	
	wp_die();
}
 