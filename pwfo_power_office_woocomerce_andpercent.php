<?php
/**
* Plugin Name: Power Office woocomerce andpercent
* Plugin Uri:
* Author: Faisal Abbas Khan
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

 add_action('admin_enqueue_scripts', 'admin_enqueue_scripts');

  function admin_enqueue_scripts(){

		wp_enqueue_script('pwspk_dev_script', POWER_OFFICE_PLUGIN_URL."assets/js/custom.js", array(), '1.0.0', false);
	}


add_action('admin_menu', 'plugin_menu_pwfo');
add_action('admin_menu', 'process_form_settings_pwfo');

function plugin_menu_pwfo(){
	add_menu_page( 'Power Office woocomerce', 'Power Office woocomerce', 'manage_options', 'power_office_woocomerce','options_func_pwfo', $icon_url = '', $position = null);
	add_submenu_page( 'power_office_woocomerce', 'License and Activation', 'License and Activation', 'manage_options','CHFS_ACTIVATION','CHFS_ACTIVATION_FUNCATION');
}   
function CHFS_ACTIVATION_FUNCATION(){

	$postsDataplug = wp_remote_retrieve_body(wp_remote_get(CHFS_VALIDATE_API_URL.'/api/getSiteData?id='.CHFS_VALIDATE_API_PLUGIN_ID.'&domain='.$_SERVER['HTTP_HOST']));

	 $postsgetPlug = json_decode($postsDataplug);
	
	
?>
<div class="wrap">
<h1>License and Activation </h1>
<label for="">

	<?php

if (get_option('CHFS_VALIDATE_API_PLUGIN_KEY')==$postsgetPlug->key) {
echo '<label style="background: green; border-radius: 6px; padding: 10px;color: white;">Activated</label> ';
}else{

echo '<label style="background: red; border-radius: 6px; padding: 10px;color: white;">Unregister Key </label> ';
}
	?>
	
	<input type="text" name="CHFS_VALIDATE_API_PLUGIN_KEY" id="CHFS_VALIDATE_API_PLUGIN_KEY" value="<?php echo esc_html(get_option('CHFS_VALIDATE_API_PLUGIN_KEY'));  ?>" style="width:100%;" required / placeholder="Activation Key">
</label>
<div style="margin-top: 30px;">
	<button class="button button-primary CHFS_Plugin_activate">Activate</button>
</div>

</div>
<?php
}
function process_form_settings_pwfo(){
	register_setting('pwspk_option_group', 'pwspk_option_name' );
	if(isset($_POST['action']) && current_user_can('manage_options') && isset($_POST['plugin_set_pwfo'])){
		
		update_option('pwspk_woo_api_key', sanitize_text_field($_POST['pwspk_woo_api_key']));
		update_option('pwspk_power_office_key', sanitize_text_field($_POST['pwspk_power_office_key']));
update_option('POWER_OFFICE_PLUGIN_PO_API_URL', sanitize_text_field($_POST['POWER_OFFICE_PLUGIN_PO_API_URL']));
update_option('Modedev', sanitize_text_field($_POST['Modedev']));

	

	


		
	}
}
function options_func_pwfo(){ ?>
<div class="wrap">
	<h1>Power Office woocomerce Credentials  </h1>
	<?php settings_errors(); ?>
	<form id="ajax_form" action="options.php" method="post">
		<?php settings_fields('pwspk_option_group'); ?>


		<input type="hidden" name="plugin_set_pwfo" value="plugin_set_pwfo">
		
		<label for="">
			<a href="https://www.blitter.se/utils/basic-authentication-header-generator/" target="_blank" style="text-transform:capitalize ;">
				Woocomerce consumer and Secret key base64_encode genrator     (put below input filed )
			</a>
			<input type="text" name="pwspk_woo_api_key" value="<?php echo esc_html(get_option('pwspk_woo_api_key'));  ?>" style="width:100%;" required />
		</label>
		
		<br>
		<br>
		<label for="">
			<a href="https://www.blitter.se/utils/basic-authentication-header-generator/" target="_blank" style="text-transform:capitalize ;">
			Power Office  base64_encode key genrator     (put below input filed )</a>
			<input type="text" name="pwspk_power_office_key" value="<?php echo esc_html(get_option('pwspk_power_office_key')); ?>"  style="width:100%;"required/>
		</label>
		<br>
		<br>
		<label for="">
			
			Power Office  Api Url
			<input type="text" name="POWER_OFFICE_PLUGIN_PO_API_URL" value="<?php echo esc_html(get_option('POWER_OFFICE_PLUGIN_PO_API_URL')); ?>"  style="width:100%;"required/>
		</label>
		<br>
		<br>
		
		
		<label for="">
			
			Setting On Sidebar:
			
			<select name="Modedev">
				
				<option value="on"<?php if (get_option('Modedev')=='on') { echo "Selected"; } ?>>Enable</option>
				<option value="off" <?php if (get_option('Modedev')=='off') { echo "Selected"; } ?>>Disable</option>
			</select>
		</label>

		
		<?php submit_button('Save Credentials');
		// echo  'Basic ' . base64_encode(get_option('pwspk_username').':'.get_option('pwspk_password'));
		?>
		
	</form>

   <style type="text/css">
 #adminmenu .toplevel_page_power_office_woocomerce{
   	display: <?php if (get_option('Modedev')=='off') { echo "none"; } ?>;
   }
      	#submit{
float: right;
    background: #11a111;
   	}
   	.progress-bar{display: flex;
    -ms-flex-direction: column;
    flex-direction: column;
    -ms-flex-pack: center;
    justify-content: center;
    overflow: hidden;
    color: #fff;
    text-align: center;
    white-space: nowrap;
    background-color: #007bff;
    transition: width .6s ease;}
   </style>
      
       <div class="form-group" id="process" style="display:none; margin-bottom: 15px;">
        <div class="progress" style="    display: flex;
    height: 1rem;
    overflow: hidden;
    line-height: 0;
    font-size: .75rem;
    background-color: #e9ecef;
    border-radius: 0.25rem;">
       <div class="progress-bar" role="progressbar" aria-valuemin="0" aria-valuemax="100" style="">
       </div>
      </div>
       </div>
 
   <h3>Step 1:</h3>
	<button class="button button-primary sendAllproducts" id="sendAllproducts" >All Products</button>
	<h3>Step 2:</h3>
	<button class="button button-primary sendAllcustomers"id="sendAllcustomers" >All Customer</button>
	<h3>Step 3:</h3>
	<button class="button button-primary sendAllOrders"id="sendAllOrders" >All Orders</button>

	<br><br>

    <h3>Logs</h3>
	<div class="collectresponse" style="background: white;
    padding: 2%;
    border: 2px solid;">
		

	</div>
</div>
<?php
}
/**
* Check if WooCommerce is active
**/
if ( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
// Put your plugin code here
add_action('woocommerce_loaded' , function (){
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

$tokenAccess=power_office_authorization(POWER_OFFICE_PLUGIN_PO_API_URL.'/OAuth/Token');
define('POWER_OFFICE_ACCESS_TOKEN',$tokenAccess);


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

//$woocomerceApiConect=CheckWoocomerceApiConect();
if ($tokenAccess!=false ) {
	$postsDataplug = wp_remote_retrieve_body(wp_remote_get(CHFS_VALIDATE_API_URL.'/api/getSiteData?id='.CHFS_VALIDATE_API_PLUGIN_ID.'&domain='.$_SERVER['HTTP_HOST']));
	
	$postsgetPlug = json_decode($postsDataplug);

	if (get_option('CHFS_VALIDATE_API_PLUGIN_KEY')==$postsgetPlug->key) {

	include POWER_OFFICE_PLUGIN_PATH."pwfo_woocomerce_to_poweroffice/pwfo_woocomerce_hooks_triger.php";
	}
}else{


	//include POWER_OFFICE_PLUGIN_PATH."pwfo_woocomerce_to_poweroffice/pwfo_woocomerce_hooks_triger.php";

// 	echo '<div class="wrap" style="
//     text-align: center;
//     color: red;
// "><div class="alert alert-danger">
//   <strong>Poweroffice Auth key or woocomerce Api Key Is invalid !</strong> </a>.
// </div></div>';

}

});  
}


   

  


 

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
// delete_option('pwspk_woo_api_key');
// 	 delete_option('pwspk_power_office_key');
//   delete_option('POWER_OFFICE_PLUGIN_PO_API_URL');
	
});


add_action("wp_ajax_frontend_action_chfs_activation_plugin" , "frontend_action_chfs_activation_plugin");
add_action("wp_ajax_nopriv_frontend_action_chfs_activation_plugin" , "frontend_action_chfs_activation_plugin");

function frontend_action_chfs_activation_plugin(){


if(isset($_POST['activation_key'])){

update_option('CHFS_VALIDATE_API_PLUGIN_KEY', sanitize_text_field($_POST['activation_key']));
echo  json_encode(['status'=>400]);
 wp_die();

}

}



if( ! class_exists( 'mishaUpdateChecker' ) ) {

	class mishaUpdateChecker{

		public $plugin_slug;
		public $version;
		public $cache_key;
		public $cache_allowed;

		public function __construct() {

			$this->plugin_slug = plugin_basename( __DIR__ );
			$this->version = '1.0';
			$this->cache_key = 'misha_custom_upd';
			$this->cache_allowed = false;

			add_filter( 'plugins_api', array( $this, 'info' ), 20, 3 );
			add_filter( 'site_transient_update_plugins', array( $this, 'update' ) );
			add_action( 'upgrader_process_complete', array( $this, 'purge' ), 10, 2 );

		}

	
		

		public function request(){




			$remote = get_transient( $this->cache_key );

			if( false === $remote || ! $this->cache_allowed ) {

				$remote = wp_remote_get(
					CHFS_VALIDATE_API_URL.'/api/updatePlugindata?id='.CHFS_VALIDATE_API_PLUGIN_ID,
					array(
						'timeout' => 10,
						'headers' => array(
							'Accept' => 'application/json'
						)
					)
				);

				if(
					is_wp_error( $remote )
					|| 200 !== wp_remote_retrieve_response_code( $remote )
					|| empty( wp_remote_retrieve_body( $remote ) )
				) {
					return false;
				}

				set_transient( $this->cache_key, $remote, DAY_IN_SECONDS );

			}

			$remote = json_decode( wp_remote_retrieve_body( $remote ) );


               
			
			return $remote;



		}


		function info( $res, $action, $args ) {

			

			// do nothing if you're not getting plugin information right now
			if( 'plugin_information' !== $action ) {
				return false;
			}

			// do nothing if it is not our plugin
			if( $this->plugin_slug !== $args->slug ) {
				return false;
			}

			// get updates
			$remote = $this->request();

			if( ! $remote ) {
				return false;
			}

			$res = new stdClass();

			$res->name = $this->plugin_slug;
			$res->slug = $this->plugin_slug;
			$res->version = $remote->version;
			$res->tested = $remote->version;
			$res->requires =$remote->version;
			$res->author = 'faisal Khan';
			//$res->author_profile = $remote->author_profile;
			$res->download_link =CHFS_VALIDATE_API_URL.'/public/'.$remote->file;
			$res->trunk = CHFS_VALIDATE_API_URL.'/public/'.$remote->file;
			$res->requires_php = 5.3;
			$res->last_updated = $remote->updated_at;

			// $res->sections = array(
			// 	'description' =>"This simple plugin does nothing, only gets updates from a custom server",
			// 	'installation' =>"Click the activate button and that's it.",
			// 	'changelog' => "<h4>1.0 –  1 august 2021</h4><ul><li>Bug fixes.</li><li>Initital release.</li></ul>"
			// );

			  
				$res->banners = array(
					'low' =>CHFS_VALIDATE_API_URL.'/public/plugin_img/'.$remote->img,
					'high' => CHFS_VALIDATE_API_URL.'/public/plugin_img/'.$remote->img
				);
			

			return $res;

		}

		public function update( $transient ) {

			if ( empty($transient->checked ) ) {
				return $transient;
			}

			$remote = $this->request();
    
			if(
				$remote
				&& version_compare( $this->version, $remote->version, '<' )
				&& version_compare( $remote->requires, get_bloginfo( 'version' ), '<' )
				&& version_compare( $remote->requires_php, PHP_VERSION, '<' )
			) {
				$res = new stdClass();
				$res->slug = $this->plugin_slug;
				$res->plugin = plugin_basename( __FILE__ ); // misha-update-plugin/misha-update-plugin.php
				$res->new_version = $remote->version;
				$res->tested = 5.8;
				$res->package = CHFS_VALIDATE_API_URL.'/public/'.$remote->file;

			

				$transient->response[ $res->plugin ] = $res;

	    }

			return $transient;

		}

		public function purge(){

			if (
				$this->cache_allowed
				&& 'update' === $options['action']
				&& 'plugin' === $options[ 'type' ]
			) {
				// just clean the cache when new plugin version is installed
				delete_transient( $this->cache_key );
			}

		}


	}

	new mishaUpdateChecker();

 }

