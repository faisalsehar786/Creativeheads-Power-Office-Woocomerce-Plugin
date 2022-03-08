<?php 


function CheckWoocomerceApiConect(){
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, POWER_OFFICE_PLUGIN_SITE_URL.'//wp-json/wc/v3/');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');

$headers = array();
$headers[] = 'Authorization:'.POWER_OFFICE_PLUGIN_WOO_AUTH_KEY;
$headers[] = 'Cache-Control: no-cache';
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
$result = curl_exec($ch);
if (curl_errno($ch)) {
echo 'Error:' . curl_error($ch);
}
curl_close($ch);
	$respWoowoocom = json_decode($result);
	if (!empty($respWoowoocom)) {
if ($respWoowoocom=='woocommerce_rest_authentication_error') {
	return false;
}else{
	return $respWoowoocom;
}
return $respWoowoocom;
}else{
return false;
}

}