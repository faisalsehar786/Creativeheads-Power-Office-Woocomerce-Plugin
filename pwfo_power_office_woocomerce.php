<?php
/**
* Plugin Name: PowerOffice Woocomerce
* Plugin Uri:
* Author: CreativeHeads
* Author Uri:
* Version: 1.0.0
* Description: This Plugin is  integration of  Power Office And Woocomerce Api
*
* Tags:
* License: GPL V2
*
*
*/
// wp-admin/admin.php?page=power_office_woocomerce

////////////////////////   Basic Plugin Constant Variables ////////////////////
defined('ABSPATH') || die("You Can't Access this File Directly");
define('POWER_OFFICE_PLUGIN_PATH', plugin_dir_path(__FILE__));
define('POWER_OFFICE_PLUGIN_URL', plugin_dir_url(__FILE__));
define('POWER_OFFICE_PLUGIN_FILE', __FILE__);
define('POWER_OFFICE_PLUGIN_SITE_URL',get_site_url());
define('POWER_OFFICE_PLUGIN_PO_API_URL',get_option('POWER_OFFICE_PLUGIN_PO_API_URL'));
define('POWER_OFFICE_PLUGIN_WOO_AUTH_KEY',get_option('pwspk_woo_api_key'));
define('POWER_OFFICE_PLUGIN_AUTH_KEY',get_option('pwspk_power_office_key'));
define('CHFS_VALIDATE_API_URL','http://abc4741.sg-host.com');
define('CHFS_VALIDATE_API_PLUGIN_ID',1);

 ///////////////////////////// Load plugin Files for Admin Dashboard //////////////////////////
if (is_admin()) {

add_action('admin_enqueue_scripts', 'admin_enqueue_scripts_pwf');
add_action('admin_menu', 'plugin_menu_pwfo');
add_action('admin_menu', 'process_form_settings_pwfo');
include POWER_OFFICE_PLUGIN_PATH."inc/admin_dashboard.php";

/////////////////////  Controll Plugin Upadtes From Git ////////////////////////////
require 'plugin-update-checker-master/plugin-update-checker.php';
$myUpdateChecker = Puc_v4_Factory::buildUpdateChecker(
	'https://github.com/faisalsehar786/Creativeheads-Power-Office-Woocomerce-Plugin/',
	__FILE__,
	'pwfo_power_office_woocomerce'
);
//Set the branch that contains the stable release.
$myUpdateChecker->setBranch('main');
//Optional: If you're using a private repository, specify the access token like this:
$myUpdateChecker->setAuthentication('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');



}  

////////////////////// Files For Front end  ///////////////////////////////////////////
include POWER_OFFICE_PLUGIN_PATH."inc/conection_to_pwf.php";
include POWER_OFFICE_PLUGIN_PATH."inc/woocomerce_apiconection.php";

////////////////////////////////////////////////////////////////////////////////////////////

/**
* Check if WooCommerce is active
**/
if ( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
// Put your plugin code here
add_action('woocommerce_loaded' , function (){
$tokenAccess=power_office_authorization(POWER_OFFICE_PLUGIN_PO_API_URL.'/OAuth/Token');
define('POWER_OFFICE_ACCESS_TOKEN',$tokenAccess);

if ($tokenAccess!=false ) {
 

   if ( ! session_id() ) {
        session_start();
     $postsDataplug = wp_remote_retrieve_body(wp_remote_get(CHFS_VALIDATE_API_URL.'/api/getSiteData?id='.CHFS_VALIDATE_API_PLUGIN_ID.'&domain='.$_SERVER['HTTP_HOST']));
	$postsgetPlug = json_decode($postsDataplug);
  
        $_SESSION["KEY_MATCH_REGISTER"]=$postsgetPlug->key;

    }






if (isset($_SESSION["KEY_MATCH_REGISTER"]) && !empty($_SESSION["KEY_MATCH_REGISTER"]) && $_SESSION["KEY_MATCH_REGISTER"]!=false) {
    define('KEY_MATCH_REGISTER',$_SESSION["KEY_MATCH_REGISTER"]);
   }else{

	   if (is_admin() && $_GET['page']=='power_office_woocomerce') {
   echo "<script>alert('This Message From PowerOffice Plugin Please Enter Vaild license Key And Register Your Domain on Plugin Provider Record')</script>";
	   }
	 //     
   	//  $postsDataplug = wp_remote_retrieve_body(wp_remote_get(CHFS_VALIDATE_API_URL.'/api/getSiteData?id='.CHFS_VALIDATE_API_PLUGIN_ID.'&domain='.$_SERVER['HTTP_HOST']));
	// $postsgetPlug = json_decode($postsDataplug);
    // $_SESSION["KEY_MATCH_REGISTER"]=$postsgetPlug->key;
    define('KEY_MATCH_REGISTER',$_SESSION["KEY_MATCH_REGISTER"]);

   }
	if (get_option('CHFS_VALIDATE_API_PLUGIN_KEY')==KEY_MATCH_REGISTER) {
		

	include POWER_OFFICE_PLUGIN_PATH."pwfo_woocomerce_to_poweroffice/pwfo_woocomerce_hooks_triger.php";
	}

}else{
}
});
}


///////////////////////////////////     Active plugin Hook /////////////////////////////
register_activation_hook(__FILE__, function(){
	
add_option('pwspk_woo_api_key', '');
add_option('Modedev', 'on');
add_option('pwspk_power_office_key', '');
add_option('POWER_OFFICE_PLUGIN_PO_API_URL', 'https://api-demo.poweroffice.net');
add_option('CHFS_VALIDATE_API_PLUGIN_KEY', '');
$postPluginData = wp_remote_retrieve_body(wp_remote_post(CHFS_VALIDATE_API_URL.'/api/sendSiteData', [
'body' =>['id' => CHFS_VALIDATE_API_PLUGIN_ID,
'domain' =>$_SERVER['HTTP_HOST'],
'is_del' => 'no',
'is_active' => 'yes'],
'method' => 'POST',
'content-type' => 'application/json',
]));
$resultPlugin=json_decode($postPluginData);
});

////////////////////////////////////  Deactivate Plugin hoook //////////////////////////
register_deactivation_hook(__FILE__, function(){
	$postPluginData = wp_remote_retrieve_body(wp_remote_post(CHFS_VALIDATE_API_URL.'/api/sendSiteData', [
		'body' =>['id' => CHFS_VALIDATE_API_PLUGIN_ID,
		'domain' =>$_SERVER['HTTP_HOST'],
		'is_del' => 'no',
		'is_active' => 'no'],
		'method' => 'POST',
		'content-type' => 'application/json',
		]));
		$resultPlugin=json_decode($postPluginData);
	
});

///////////////////////////  Update license key ajax /////////////////////////////////////
add_action("wp_ajax_frontend_action_chfs_activation_plugin" , "frontend_action_chfs_activation_plugin");
add_action("wp_ajax_nopriv_frontend_action_chfs_activation_plugin" , "frontend_action_chfs_activation_plugin");
function frontend_action_chfs_activation_plugin(){
if(isset($_POST['activation_key'])){
update_option('CHFS_VALIDATE_API_PLUGIN_KEY', sanitize_text_field($_POST['activation_key']));
echo  json_encode(['status'=>400]);
wp_die();
}
}

