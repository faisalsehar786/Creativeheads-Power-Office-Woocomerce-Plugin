jQuery(function($){
$('.sendAllproducts').on('click', function(el){
el.preventDefault();
var count_error = 0;

progress_bar_process('10','');
if(count_error == 0)
{
$.ajax({ 
url:ajaxurl,
method:"POST",
data:{action: 'my_ajax_action_products',sendAllproducts:'sendAllproducts'},
beforeSend:function()
{
$('#sendAllproducts').attr('disabled', 'disabled');
//$('#sendAllproducts').text('Importing');
$('#process').css('display', 'block');
},
success:function(data)
{

     
	//console.log(data);

	$('.collectresponse').html(data);
	//$(this).text('Products Imported successfully');
$('#sendAllcustomers').show();
var percentage = 0;
percentage = percentage + 100;
progress_bar_process(percentage,'');

},  
error: function(XMLHttpRequest, textStatus, errorThrown) {
	alert('Poweroffice Auth key or woocomerce Api Key Is invalid !')
console.log("Status: " + textStatus); console.log("Error: " + errorThrown);console.log("Error: " + errorThrown);
}
})
}
else
{
return false;
}
})



////////////////////////////////////////////////////////////////////////////////////


$('.sendAllcustomers').on('click', function(el){
el.preventDefault();
var count_error = 0;

progress_bar_process('10','');
if(count_error == 0)
{
$.ajax({
url:ajaxurl,
method:"POST",
data:{action: 'my_ajax_action_customer',sendAllcustomers:'sendAllcustomers'},
beforeSend:function()
{
$('#sendAllcustomers').attr('disabled', 'disabled');
//$('#sendAllcustomers').text('Importing');
$('#process').css('display', 'block');
},
success:function(data) 
{

$('.collectresponse').html(data);
$('#sendAllOrders').show();
var percentage = 0;
percentage = percentage + 100;
progress_bar_process(percentage,'');
//$('#sendAllproducts').text('Customer Imported successfully');
}
})
}
else
{
return false;
}
})




//////////////////////////////////////////////////////////////////////////////////////
$('.sendAllOrders').on('click', function(el){
el.preventDefault();
var count_error = 0;

progress_bar_process('10','');
if(count_error == 0)
{
$.ajax({
url:ajaxurl,
method:"POST",
data:{action: 'my_ajax_action_orders',sendAllOrders:'sendAllOrders'},
beforeSend:function()
{
$('#sendAllOrders').attr('disabled', 'disabled');
//$('#sendAllOrders').text('Importing');
$('#process').css('display', 'block');
},
success:function(data)
{
	$('.collectresponse').html(data);
$('#sendAllOrders').show();
var percentage = 0;
percentage = percentage + 100;
progress_bar_process(percentage,'');
//$('#sendAllproducts').text('Orders Imported successfully');
}
})
}
else
{
return false;
}
}) 


/////////////////////////////////////////////////////////////////////////////////////


function progress_bar_process(percentage, timer)
{
$('.progress-bar').css('width', percentage + '%');
$('.progress-bar').text(percentage + '%');
if(percentage > 100)
{
clearInterval(timer);
$('#sample_form')[0].reset();
$('#process').css('display', 'none');
$('.progress-bar').css('width', '0%');
$('#save').attr('disabled', false);
$('#success_message').html("<div class='alert alert-success'>Data Saved</div>");
setTimeout(function(){
$('#success_message').html('');
}, 5000);
}
}



$(".CHFS_Plugin_activate" ).click(function() {


let activation_key=$("#CHFS_VALIDATE_API_PLUGIN_KEY").val();
	$.ajax({
url:ajaxurl,
method:"POST",
datatype:'json',
data:{action: 'frontend_action_chfs_activation_plugin',activation_key:activation_key},
beforeSend:function()
{

},
success:function(data)
{

location.reload();
console.log(data)
}
})
 

 });




});