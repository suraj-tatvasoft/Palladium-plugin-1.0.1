/* JQuery for admin for reorder */
jQuery(document).ready(function () {
    jQuery('.sync-order').click(function () {
        // jQuery('.loader-wrapper').css('display','block'); 
        var order_id = jQuery(this).attr('data-order');
        console.log("test");
        formData = {};
        formData.action = 'order_sync';
        formData.order_id = order_id;
        // console.log(pld_order_ajax.ajaxurl);
        jQuery.ajax({ 
            type: "post",
            url: ajax_pld_order.ajaxurl,
            data: formData,
            dataType: "json",
            // contentType: "application/json",
            success: function(data) {
                // console.log("data");
                // console.log(data);
                if (data != "") {
                    var test  = JSON.stringify(data);
                    var objJson = JSON.parse(test);
                    // console.log(objJson);
                    if (objJson.status == 1 && objJson.sync_order == 'Yes') {
                        // jQuery('.loader-wrapper').css('display','none');
                        var idName = objJson.order_tr_id;
                        jQuery('#'+idName+' .column-sync-order').html('Yes')
                        jQuery('#'+idName+' .column-sync-order-button p').remove();
                    }
                } else {}
            },
            error: function(xhr){
                alert('Request Status: ' + xhr.status + ' Status Text: ' + xhr.statusText + ' ' + xhr.responseText);
            }
        })
    });
});