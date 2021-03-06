<?php

function admin_enqueue_scripts_pwf(){
		wp_enqueue_script('pwspk_dev_script', POWER_OFFICE_PLUGIN_URL."assets/js/custom.js", array(), '1.0.0', false);
	}
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