<?php
// ******************************************
// Post Orders to PowerOffice API Starts Here
//*******************************************
function power_office_woocomerce_api_get_orders_data($url){
$urlOrWoo = $url;
$curlOrWoo = curl_init($urlOrWoo);
curl_setopt($curlOrWoo, CURLOPT_URL, $urlOrWoo);
curl_setopt($curlOrWoo, CURLOPT_RETURNTRANSFER, true);
$headersOrWoo = array(
"Accept: */*",
"Authorization:".POWER_OFFICE_PLUGIN_WOO_AUTH_KEY,
);
curl_setopt($curlOrWoo, CURLOPT_HTTPHEADER, $headersOrWoo);
//for debug only!
curl_setopt($curlOrWoo, CURLOPT_SSL_VERIFYHOST, false);
curl_setopt($curlOrWoo, CURLOPT_SSL_VERIFYPEER, false);
$respOrWoo = curl_exec($curlOrWoo);
curl_close($curlOrWoo);
//var_dump($respOrWoo);
$finalRespOrWoo = json_decode($respOrWoo);
if (!empty($finalRespOrWoo)) {
usort($finalRespOrWoo, function($x, $y) {
return $x->id > $y->id ? -1 : 1;
});
return $finalRespOrWoo;
}else{
return false;
}
}
function post_order_to_power_office($url='', $status=true ,$token='',$dataObj=[]){
if ($status) {

$urlOrPost =$url;
$curlOrPost = curl_init($urlOrPost);
curl_setopt($curlOrPost, CURLOPT_URL, $urlOrPost);
curl_setopt($curlOrPost, CURLOPT_POST, true);
curl_setopt($curlOrPost, CURLOPT_RETURNTRANSFER, true);
$headersOrPost = array(
"Accept: application/json",
"Authorization: Bearer {$token}",
"Content-Type: application/json",
);
curl_setopt($curlOrPost, CURLOPT_HTTPHEADER, $headersOrPost);
$finalOr_arr = [];
foreach ($dataObj as $key => $order) {
$orgDate = $order->date_created;
$newDate = date("Y-m-d", strtotime($orgDate));
$order_details = [
'customerCode' => $order->customer_id+10007,
'voucherDate' => $newDate,
'currencyCode' => "NOK",
'currencyRate' => 1,
'invoiceNo' => $order->id
];
$lines_items = [];
if (!empty($order->line_items)) {
foreach ($order->line_items as $item) {
$lines_items[] = [
'amount' => floatval($item->subtotal),
'productCode' => strval($item->product_id),
'quantity' => $item->quantity,
'accountCode' => 3000,
'vatCode' => "3",
'description' => $item->name
];
}
}
$order_details['lines'] = $lines_items;
$finalOr_arr[] = $order_details;
}
$finalDataOrWoo = json_encode($finalOr_arr);
curl_setopt($curlOrPost, CURLOPT_POSTFIELDS, json_encode($finalOr_arr[0]));


//for debug only!
curl_setopt($curlOrPost, CURLOPT_SSL_VERIFYHOST, false);
curl_setopt($curlOrPost, CURLOPT_SSL_VERIFYPEER, false);
$respOrPost = curl_exec($curlOrPost);
curl_close($curlOrPost);
return $curlOrPost;
}else{
	return false;
}
}

function postallOrders($url,$token,$Data){
$urlOrPost =$url;
$curlOrPost = curl_init($urlOrPost);
curl_setopt($curlOrPost, CURLOPT_URL, $urlOrPost);
curl_setopt($curlOrPost, CURLOPT_POST, true);
curl_setopt($curlOrPost, CURLOPT_RETURNTRANSFER, true);
$headersOrPost = array(
"Accept: application/json",
"Authorization: Bearer {$token}",
"Content-Type: application/json",
);
curl_setopt($curlOrPost, CURLOPT_HTTPHEADER, $headersOrPost);
curl_setopt($curlOrPost, CURLOPT_POSTFIELDS, json_encode($Data));
//for debug only!
curl_setopt($curlOrPost, CURLOPT_SSL_VERIFYHOST, false);
curl_setopt($curlOrPost, CURLOPT_SSL_VERIFYPEER, false);
$respOrPost = curl_exec($curlOrPost);
curl_close($curlOrPost);
return "<pre>".print_r($respOrPost )."</pre>";

}
function post_order_to_power_office_all($url='', $status=true ,$token='',$dataObj=[]){
if ($status) {


$finalOr_arr = [];
foreach ($dataObj as $key => $order) {
$orgDate = $order->date_created;
$newDate = date("Y-m-d", strtotime($orgDate));
$order_details = [
'customerCode' => $order->customer_id+10007,
'voucherDate' => $newDate,
'currencyCode' => "NOK",
'currencyRate' => 1,
'invoiceNo' => $order->id
];
$lines_items = [];
if (!empty($order->line_items)) {
foreach ($order->line_items as $item) {
$lines_items[] = [
'amount' => floatval($item->subtotal),
'productCode' => strval($item->product_id),
'quantity' => $item->quantity,
'accountCode' => 3000,
'vatCode' => "3",
'description' => $item->name
];
}
}
$order_details['lines'] = $lines_items;
$finalOr_arr[] = $order_details;
}
$finalDataOrWoo = json_encode($finalOr_arr);
$res='';
foreach ($finalOr_arr as $key => $value) {
	 $res.=postallOrders($url,$token,$value);
//return true;
}
return $res;
}else{
	return false;
}
}




  add_action('wp_ajax_my_ajax_action_orders', 'pwspk_ajax_action_orders');


function pwspk_ajax_action_orders(){

	if(isset($_POST['action']) && isset($_POST['sendAllOrders'])){
	 	
		  
$getOrderdata=power_office_woocomerce_api_get_orders_data(POWER_OFFICE_PLUGIN_SITE_URL.'//wp-json/wc/v3/orders');


if (!empty($getOrderdata)) {


$post_order_url=POWER_OFFICE_PLUGIN_PO_API_URL.'/Voucher/OutgoingInvoiceVoucher/';
$responseOdr=post_order_to_power_office_all($post_order_url,true,POWER_OFFICE_ACCESS_TOKEN,$getOrderdata);

$check=json_decode($responseOdr);

  
print_r($check);
} 
  
	}else{
echo "no";
  

	}
  
	
	wp_die();
}