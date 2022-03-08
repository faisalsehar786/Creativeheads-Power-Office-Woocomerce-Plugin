<?php
//  This file contain function  get authrized token from power office
include POWER_OFFICE_PLUGIN_PATH."pwfo_woocomerce_to_poweroffice/pwfo_customer.php";  //post customer to power office
include POWER_OFFICE_PLUGIN_PATH."pwfo_woocomerce_to_poweroffice/pwfo_orders.php";   //post orders to power office
include POWER_OFFICE_PLUGIN_PATH."pwfo_woocomerce_to_poweroffice/pwfo_products.php"; //post products to power office
add_action('profile_update','my_function_create_customer_pwf');
add_action('user_register','my_function_reg_create_customer_pwf');
add_action( 'woocommerce_created_customer', 'action_woocommerce_created_customer', 10, 3 );
add_action( 'woocommerce_thankyou', 'action_woocommerce_thankyou_Order_time', 10, 1 );
add_action('woocommerce_update_product', 'edit_productPublished_pwf');
add_action('woocommerce_new_product', 'productPublished_pwf');
add_action( 'woocommerce_update_order', 'action_woocommerce_update_order', 10, 1 );
add_action( 'woocommerce_new_order', 'action_woocommerce_new_order', 10, 1 );
//////////////////////////////////////  Add Update Product //////////////////////////////////////////  
function edit_productPublished_pwf($product_id){
$user = wp_get_current_user();
$allowed_roles = array('editor', 'administrator', 'author');
if( array_intersect($allowed_roles, $user->roles ) ) {
$returnDataProducts=power_office_woocomerce_api_get_products_data(POWER_OFFICE_PLUGIN_SITE_URL.'//wp-json/wc/v3/products/'.$product_id);
if (!empty($returnDataProducts)) {
$postProUrl=POWER_OFFICE_PLUGIN_PO_API_URL.'/product';
$respons=post_product_to_power_office_dashboard($postProUrl,true,POWER_OFFICE_ACCESS_TOKEN,$returnDataProducts,'put');
$check=json_decode($respons);
}
} 
}
function productPublished_pwf($product_id){
$user = wp_get_current_user();
$allowed_roles = array('editor', 'administrator', 'author');
if( array_intersect($allowed_roles, $user->roles ) ) {
$returnDataProducts=power_office_woocomerce_api_get_products_data(POWER_OFFICE_PLUGIN_SITE_URL.'//wp-json/wc/v3/products/'.$product_id);
if (!empty($returnDataProducts)) {
$postProUrl=POWER_OFFICE_PLUGIN_PO_API_URL.'/product';
$respons=post_product_to_power_office_dashboard($postProUrl,true,POWER_OFFICE_ACCESS_TOKEN,$returnDataProducts,'post');
$check=json_decode($respons);
}
}
}
////////////////////////////////////////////// Update Order///////////////////////////////////////
// define the woocommerce_update_order callback
function action_woocommerce_update_order( $order_get_id ) {
$user = wp_get_current_user();
$allowed_roles = array('editor', 'administrator', 'author');
if( array_intersect($allowed_roles, $user->roles ) ) {
$getOrderdata=power_office_woocomerce_api_get_orders_data(POWER_OFFICE_PLUGIN_SITE_URL.'//wp-json/wc/v3/orders/'.$order_get_id);
if (!empty($getOrderdata)) {
$post_order_url=POWER_OFFICE_PLUGIN_PO_API_URL.'/Voucher/OutgoingInvoiceVoucher/';
$responseOdr=post_order_to_power_office_dashboard($post_order_url,true,POWER_OFFICE_ACCESS_TOKEN,$getOrderdata,'put');
}
}
}
// add the action
////////////////////////////    Create New Order ////////////////////////////////////////////////
function action_woocommerce_new_order( $order_get_id ) {
$user = wp_get_current_user();
$allowed_roles = array('editor', 'administrator', 'author');
if( array_intersect($allowed_roles, $user->roles ) ) {
$getOrderdata=power_office_woocomerce_api_get_orders_data(POWER_OFFICE_PLUGIN_SITE_URL.'//wp-json/wc/v3/orders/'.$order_get_id);
if (!empty($getOrderdata)) {
$post_order_url=POWER_OFFICE_PLUGIN_PO_API_URL.'/Voucher/OutgoingInvoiceVoucher/';
$responseOdr=post_order_to_power_office_dashboard($post_order_url,true,POWER_OFFICE_ACCESS_TOKEN,$getOrderdata,'post');
}
}
}
///////////////////// customer Created ////////////////////////////////////
// define the woocommerce_created_customer callback
function action_woocommerce_created_customer( $customer_id, $new_customer_data, $password_generated ) {
$user = wp_get_current_user();
$allowed_roles = array('editor', 'administrator', 'author');
if( array_intersect($allowed_roles, $user->roles ) ) {
$getCustomerdata=power_office_woocomerce_api_get_customers_data(POWER_OFFICE_PLUGIN_SITE_URL.'//wp-json/wc/v3/customers/'.$customer_id);
if (!empty($getCustomerdata)) {
$postUrlCustomer=POWER_OFFICE_PLUGIN_PO_API_URL.'/customer';
$responseCus=post_customer_to_power_office_dashboard($postUrlCustomer,true,POWER_OFFICE_ACCESS_TOKEN,$getCustomerdata,'post');
}
}
};
function my_function_create_customer_pwf($user_id) {
$user = wp_get_current_user();
$allowed_roles = array('editor', 'administrator', 'author');
if( array_intersect($allowed_roles, $user->roles ) ) {
$getCustomerdata=power_office_woocomerce_api_get_customers_data(POWER_OFFICE_PLUGIN_SITE_URL.'//wp-json/wc/v3/customers/'.$user_id);
if (!empty($getCustomerdata)) {
$postUrlCustomer=POWER_OFFICE_PLUGIN_PO_API_URL.'/customer';
$responseCus=post_customer_to_power_office_dashboard($postUrlCustomer,true,POWER_OFFICE_ACCESS_TOKEN,$getCustomerdata,'put');
}
}
}
function my_function_reg_create_customer_pwf($user_id) {
$user = wp_get_current_user();
$allowed_roles = array('editor', 'administrator', 'author');
if( array_intersect($allowed_roles, $user->roles ) ) {
$getCustomerdata=power_office_woocomerce_api_get_customers_data(POWER_OFFICE_PLUGIN_SITE_URL.'//wp-json/wc/v3/customers/'.$user_id);
if (!empty($getCustomerdata)) {
$postUrlCustomer=POWER_OFFICE_PLUGIN_PO_API_URL.'/customer';
$responseCus=post_customer_to_power_office_dashboard($postUrlCustomer,true,POWER_OFFICE_ACCESS_TOKEN,$getCustomerdata,'post');
}
}
}
//////////////////////////////////////////////////////////// //////////////////
function action_woocommerce_thankyou_Order_time( $order_get_id ) {

$order = wc_get_order( $order_get_id );
$user = $order->get_user();
$user_id = $order->get_user_id();


$getCustomerdata=power_office_woocomerce_api_get_customers_data(POWER_OFFICE_PLUGIN_SITE_URL.'//wp-json/wc/v3/customers/'.$user_id);
if (!empty($getCustomerdata)) {
$postUrlCustomer=POWER_OFFICE_PLUGIN_PO_API_URL.'/customer';
$responseCus=post_customer_to_power_office_dashboard($postUrlCustomer,true,POWER_OFFICE_ACCESS_TOKEN,$getCustomerdata,'post');

   
}
if (!empty($responseCus)) {

	// print_r($responseCus);
	// die();
$getOrderdata=power_office_woocomerce_api_get_orders_data(POWER_OFFICE_PLUGIN_SITE_URL.'//wp-json/wc/v3/orders/'.$order_get_id);
if (!empty($getOrderdata)) {
$post_order_url=POWER_OFFICE_PLUGIN_PO_API_URL.'/Voucher/OutgoingInvoiceVoucher/';
$responseOdr=post_order_to_power_office_dashboard($post_order_url,true,POWER_OFFICE_ACCESS_TOKEN,$getOrderdata,'post');

}
}
}