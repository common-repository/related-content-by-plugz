var $ = jQuery;
$(document).ready(function(){
    function plugzSendChanges(widget_id) {
        $.ajax({
            type: "POST",
            url: ajaxPath,
            data: $("#widget_form").serialize(),
            dataType: 'json',
            beforeSend: function() {
                $("#widget-"+widget_id+" .ajaxloader").html('<img src="'+loaderImg+'" alt="Loading...">').fadeIn();
            }            
        })
        .success(function(data){
        })
        .always(function() {
            $("#widget-"+widget_id+" .ajaxloader").hide().empty();
        });
    }
    
    var firstRun = true;
    
    $(".widget_placement").change(function() {
        var widget_id = $(this).closest(".widget_settings").data('widget-id');
        
        $(".in_sidebar_notice, .widget_after_paragraph, .widget_post_id, .widget_page_id", $(this).closest(".widget_settings")).hide();
        
        if ($(this).val() == 'in sidebar') {
            $("#in_sidebar_notice_, #in_sidebar_notice_"+widget_id).show();
        } else if ($(this).val() == 'on single page') {
            $("#widget_after_paragraph_, #widget_after_paragraph_"+widget_id).show();
            $("#widget_page_id_, #widget_page_id_"+widget_id).show();
        } else if ($(this).val() == 'on single post') {
            $("#widget_after_paragraph_, #widget_after_paragraph_"+widget_id).show();
            $("#widget_post_id_, #widget_post_id_"+widget_id).show();
        } else {
            $("#widget_after_paragraph_, #widget_after_paragraph_"+widget_id).show();
        }
        
        if (!firstRun && widget_id != undefined) {
            plugzSendChanges(widget_id);
        }
        
        firstRun = false;
    });
    
    $(".widget_after_paragraph_select, .widget_page_id select, .widget_post_id select").change(function() {
        var widget_id = $(this).closest(".widget_settings").data('widget-id');
    
        if (widget_id != undefined) { 
            plugzSendChanges(widget_id);
        }
    });
    
    $(".widget_placement").trigger('change');
});