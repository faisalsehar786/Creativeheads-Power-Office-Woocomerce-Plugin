<?php
//  This file contain function  get authrized token from power office
include POWER_OFFICE_PLUGIN_PATH."pwfo_woocomerce_to_poweroffice/pwfo_customer.php";  //post customer to power office
include POWER_OFFICE_PLUGIN_PATH."pwfo_woocomerce_to_poweroffice/pwfo_orders.php";   //post orders to power office
include POWER_OFFICE_PLUGIN_PATH."pwfo_woocomerce_to_poweroffice/pwfo_products.php"; //post products to power office





/////////////////////////////  Front end Order Create /////////////////////////////////////////////////
// define the woocommerce_thankyou callback
function action_woocommerce_thankyou_Order_time( $order_get_id ) {





$getCustomerdata=power_office_woocomerce_api_get_customers_data(POWER_OFFICE_PLUGIN_SITE_URL.'//wp-json/wc/v3/customers?role=all');



if (!empty($getCustomerdata)) {




$postUrlCustomer=POWER_OFFICE_PLUGIN_PO_API_URL.'/customer';
$responseCus=post_customer_to_power_office($postUrlCustomer,true,POWER_OFFICE_ACCESS_TOKEN,$getCustomerdata);
$checkStatusCus=json_decode($responseCus);


if (!empty($checkStatusCus)) {
$getOrderdata=power_office_woocomerce_api_get_orders_data(POWER_OFFICE_PLUGIN_SITE_URL.'//wp-json/wc/v3/orders');


if (!empty($getOrderdata)) {


$post_order_url=POWER_OFFICE_PLUGIN_PO_API_URL.'/Voucher/OutgoingInvoiceVoucher/';
$responseOdr=post_order_to_power_office($post_order_url,true,POWER_OFFICE_ACCESS_TOKEN,$getOrderdata);


}

}
}

}


add_action( 'woocommerce_thankyou', 'action_woocommerce_thankyou_Order_time', 10, 1 ); 





//////////////////////////////////////  Add Update Product //////////////////////////////////////////
add_action('woocommerce_update_product', 'productPublished');
add_action('woocommerce_new_product', 'productPublished');
function productPublished($product_id){

    
$user = wp_get_current_user();
$allowed_roles = array('editor', 'administrator', 'author');
if( array_intersect($allowed_roles, $user->roles ) ) {

  
$returnDataProducts=power_office_woocomerce_api_get_products_data(POWER_OFFICE_PLUGIN_SITE_URL.'//wp-json/wc/v3/products');

   
if (!empty($returnDataProducts)) {

$postProUrl=POWER_OFFICE_PLUGIN_PO_API_URL.'/product';
$respons=post_product_to_power_office($postProUrl,true,POWER_OFFICE_ACCESS_TOKEN,$returnDataProducts);
$check=json_decode($respons);



}
}
}
 // print_r($check);
 // die();



////////////////////////////////////////////// Update Order///////////////////////////////////////
// define the woocommerce_update_order callback
function action_woocommerce_update_order( $order_get_id ) {

$user = wp_get_current_user();
$allowed_roles = array('editor', 'administrator', 'author');
if( array_intersect($allowed_roles, $user->roles ) ) {

$getCustomerdata=power_office_woocomerce_api_get_customers_data(POWER_OFFICE_PLUGIN_SITE_URL.'//wp-json/wc/v3/customers?role=all');
if (!empty($getCustomerdata)) {

$postUrlCustomer=POWER_OFFICE_PLUGIN_PO_API_URL.'/customer';
$responseCus=post_customer_to_power_office($postUrlCustomer,true,POWER_OFFICE_ACCESS_TOKEN,$getCustomerdata);
$checkStatusCus=json_decode($responseCus);


if (!empty($checkStatusCus)) {
$getOrderdata=power_office_woocomerce_api_get_orders_data(POWER_OFFICE_PLUGIN_SITE_URL.'//wp-json/wc/v3/orders');


if (!empty($getOrderdata)) {


$post_order_url=POWER_OFFICE_PLUGIN_PO_API_URL.'/Voucher/OutgoingInvoiceVoucher/';
$responseOdr=post_order_to_power_office($post_order_url,true,POWER_OFFICE_ACCESS_TOKEN,$getOrderdata);


}

}
}
}
}

// add the action
add_action( 'woocommerce_update_order', 'action_woocommerce_update_order', 10, 1 );







////////////////////////////    Create New Order ////////////////////////////////////////////////
function action_woocommerce_new_order( $order_get_id ) {

$user = wp_get_current_user();
$allowed_roles = array('editor', 'administrator', 'author');
 if( array_intersect($allowed_roles, $user->roles ) ) {




$getCustomerdata=power_office_woocomerce_api_get_customers_data(POWER_OFFICE_PLUGIN_SITE_URL.'//wp-json/wc/v3/customers?role=all');
if (!empty($getCustomerdata)) {


  

$postUrlCustomer=POWER_OFFICE_PLUGIN_PO_API_URL.'/customer';
$responseCus=post_customer_to_power_office($postUrlCustomer,true,POWER_OFFICE_ACCESS_TOKEN,$getCustomerdata);
$checkStatusCus=json_decode($responseCus);


if (!empty($checkStatusCus)) {
$getOrderdata=power_office_woocomerce_api_get_orders_data(POWER_OFFICE_PLUGIN_SITE_URL.'//wp-json/wc/v3/orders');


if (!empty($getOrderdata)) {


$post_order_url=POWER_OFFICE_PLUGIN_PO_API_URL.'/Voucher/OutgoingInvoiceVoucher/';
$responseOdr=post_order_to_power_office($post_order_url,true,POWER_OFFICE_ACCESS_TOKEN,$getOrderdata);


}

}
} 
}
}
add_action( 'woocommerce_new_order', 'action_woocommerce_new_order', 10, 1 );



  


///////////////////// customer Created ////////////////////////////////////
// define the woocommerce_created_customer callback
function action_woocommerce_created_customer( $customer_id, $new_customer_data, $password_generated ) {
$user = wp_get_current_user();
$allowed_roles = array('editor', 'administrator', 'author');
if( array_intersect($allowed_roles, $user->roles ) ) {


$getCustomerdata=power_office_woocomerce_api_get_customers_data(POWER_OFFICE_PLUGIN_SITE_URL.'//wp-json/wc/v3/customers?role=all');
if (!empty($getCustomerdata)) {

$postUrlCustomer=POWER_OFFICE_PLUGIN_PO_API_URL.'/customer';
$responseCus=post_customer_to_power_office($postUrlCustomer,true,POWER_OFFICE_ACCESS_TOKEN,$getCustomerdata);
}
}
};

// add the action
add_action( 'woocommerce_created_customer', 'action_woocommerce_created_customer', 10, 3 );