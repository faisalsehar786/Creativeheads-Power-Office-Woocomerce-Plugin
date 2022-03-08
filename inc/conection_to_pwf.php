<?php  

/////////////////  Token PowerOffice Access/////////////////////////////////////////
function power_office_authorization($url){
try {
$urlAuth =$url;
	$curlAuth = curl_init($urlAuth);
	curl_setopt($curlAuth, CURLOPT_URL, $urlAuth);
	curl_setopt($curlAuth, CURLOPT_POST, true);
	curl_setopt($curlAuth, CURLOPT_RETURNTRANSFER, true);
	$headersAuth = array(
		"Authorization:".POWER_OFFICE_PLUGIN_AUTH_KEY,
		"Content-Type: text/plain",
	);
	curl_setopt($curlAuth, CURLOPT_HTTPHEADER, $headersAuth);
	$dataAuth = "grant_type=client_credentials";
	curl_setopt($curlAuth, CURLOPT_POSTFIELDS, $dataAuth);
	//for debug only!
	curl_setopt($curlAuth, CURLOPT_SSL_VERIFYHOST, false);
	curl_setopt($curlAuth, CURLOPT_SSL_VERIFYPEER, false);
	$respAuth = curl_exec($curlAuth);
	curl_close($curlAuth);
	//var_dump($respAuth);
	// Convert JSON string to Object
	$tokenObject = json_decode($respAuth);
	if (!empty($tokenObject) && $tokenObject->error!='invalid_client') {
	
			return $accessTokken = $tokenObject->access_token;
	}else{
		return false;
	}
	
	
} catch (Exception $e) {
	print_r($e);
	
}
}