jQuery(document).ready( function($){

  // Some event will trigger the ajax call, you can push whatever data to the server, 
  // simply passing it to the "data" object in ajax call
  var importKey = getUrlVars()["importKey"];
  var importType = getUrlVars()["importType"];
  if(importKey == 'pld-customers-data' && importType == 'palladium'){	
		if(jQuery('body #overlay').length > 0){

		} else {		
			jQuery('body').append('<div id="overlay"><div class="cv-spinner"><span class="spinner"></span></div></div>');	
		}	
		getCustomerRecords('load'); // Call the function at initial time
  } else if(importKey == 'pld-product-data' && importType == 'palladium') {
		if(jQuery('body #overlay').length > 0){	

		} else {		
			jQuery('body').append('<div id="overlay"><div class="cv-spinner"><span class="spinner"></span></div></div>');	
		}
		getProductRecords('load'); // Call the function at initial time
  } else if(importKey == 'pld-product-variant-data' && importType == 'palladium') {
		if(jQuery('body #overlay').length > 0){	

		} else {		
			jQuery('body').append('<div id="overlay"><div class="cv-spinner"><span class="spinner"></span></div></div>');	
		}
		getProductVariantRecords('load'); // Call the function at initial time
	} else if(importKey == 'pld-price-data' && importType == 'palladium') {
		if(jQuery('body #overlay').length > 0){	

		}	else	{		
			console.log('esle');
			jQuery('body').append('<div id="overlay"><div class="cv-spinner"><span class="spinner"></span></div></div>');	
		}
			console.log("Key matched successfully");    
			getPriceRecords('load'); // Call the function at initial time
	} 
	else {	
  }
});

function getUrlVars()
{
    var vars = [], hash;
    var hashes = window.location.href.slice(window.location.href.indexOf('?') + 1).split('&');
    for(var i = 0; i < hashes.length; i++)
    {
        hash = hashes[i].split('=');
        vars.push(hash[0]);
        vars[hash[0]] = hash[1];
    }
    return vars;
}

function getCustomerRecords(requestType = '', pageIndex = 1){
	jQuery("#overlay").fadeIn(300);　
	jQuery.ajax({
		url: ajax_object.ajaxurl, // this is the object instantiated in wp_localize_script function
		type: 'POST',
		dataType   : 'json',
		data:{ 
		  action: 'customer_data', // this is the function in your functions.php that will be triggered
		  requestType: requestType,
		  pageIndex: pageIndex
		},
		success: function( result ){
			if(result.status == 'success'){
				//Do something with the result from server
				if(result.pageIndex <= result.totalPages){
					getCustomerRecords('', result.pageIndex);
				}else{
					jQuery("#overlay").fadeOut();
					alert("Import completed successfully");
				}
			}else{
				jQuery("#overlay").fadeOut();
				alert(result.response);
			}
		}
	});
}


function getProductVariantRecords(requestType = '', pageIndex = 1){
	jQuery("#overlay").fadeIn(300);　
	jQuery.ajax({
		url: ajax_object.ajaxurl, // this is the object instantiated in wp_localize_script function
		type: 'POST',
		dataType   : 'json',
		data:{ 
		  action: 'product_variation_data', // this is the function in your functions.php that will be triggered
		  requestType: requestType,
		  pageIndex: pageIndex
		},
		success: function( result ){
			if(result.status == 'success'){
				console.log(result.product_id);
				//Do something with the result from server
				if(result.pageIndex <= result.totalPages){
					getProductVariantRecords('', result.pageIndex);
				}else{
					jQuery("#overlay").fadeOut();
					alert("Import completed successfully");
				}
			}else{
				jQuery("#overlay").fadeOut();
			}
		}
	});
}
function getProductRecords(requestType = '', pageIndex = 1){
	jQuery("#overlay").fadeIn(300);　
	jQuery.ajax({
		url: ajax_object.ajaxurl, // this is the object instantiated in wp_localize_script function
		type: 'POST',
		dataType   : 'json',
		data:{ 
		  action: 'product_data', // this is the function in your functions.php that will be triggered
		  requestType: requestType,
		  pageIndex: pageIndex
		},
		success: function( result ){
			if(result.status == 'success'){
				console.log(result.pageIndex);
				//Do something with the result from server
				if(result.pageIndex <= result.totalPages){
					getProductRecords('', result.pageIndex);
				}else{
					jQuery("#overlay").fadeOut();
					alert("Import completed successfully");
				}
			}else{
				jQuery("#overlay").fadeOut();
				alert(result.response);
			}
		}
	});
}


function getPriceRecords(requestType = '', pageIndex = 1){
	jQuery("#overlay").fadeIn(300);　
	jQuery.ajax({
		url: ajax_object.ajaxurl,
		type: 'POST',
		dataType   : 'json',
		data:{ 
		  action: 'price_data',
		  requestType: requestType,
		  pageIndex: pageIndex
		},
		success: function( result ){
			if(result.status == 'success'){
				console.log(result.pageIndex +" = "+ result.totalPages);
				//Do something with the result from server
				if(result.pageIndex <= result.totalPages){
				    console.log("Enter for new loop");
					getPriceRecords('', result.pageIndex);
				}else{
					jQuery("#overlay").fadeOut();
					alert("Import completed successfully");
				}
			}else{
				jQuery("#overlay").fadeOut();
				alert(result.response);
			}
		}
	});
}
