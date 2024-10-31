var $ = jQuery;
var isShowPreviewRunning = false;

function detectab()
{
    //create a iframe. Append the iframe to the body. And then after 100ms check if their offsetHeight, display or visibility is set such a way that user cannot see them.
    //In the URL use the words specific to advertising so that Adblock can do string matching.
    var iframe = document.createElement("iframe");
    iframe.height = "1px";
    iframe.width = "1px";
    iframe.id = "ads-text-iframe";
    iframe.src = "http://domain.com/ads.html";

    document.body.appendChild(iframe);

    setTimeout(function()
    {
        var iframe = document.getElementById("ads-text-iframe");
        if (iframe.style.display == "none" || iframe.style.display == "hidden" || iframe.style.visibility == "hidden" || iframe.offsetHeight == 0)
        {
            jAlert("Adblock is blocking widget preview on this page. Whitelist this URL or disable Adblock on this site in your browser!", "Adblock alert");
            iframe.outerHTML = '';
            delete iframe;
        }
        else
        {
            iframe.outerHTML = '';
            delete iframe;
        }
    }, 100);
}

function showPreview() {
    if (!isShowPreviewRunning && !isTemplateInProgress) {
        isShowPreviewRunning = true;
        $('iframe').iframeAutoHeight({resetToMinHeight: true});

        $.post(widgetUrl + '&action=save_preview&cb=' + (Math.random() * 10000000), $("form").serialize(), function(data) {
            $('#widget_id').val(data.id);
            $(".widget_settings").data('widget-id', data.id);
        }).done(function() {
            createEmbedCode();
            $(".span12").removeClass('span12').addClass('span8');
            $(".span4").show();
            $('#preview').show();
            $("#name_fieldset").show();
            $("#submit_fieldset").show();
            $(window).trigger("resize");
            first_run = false;
            isShowPreviewRunning = false;
        });
    }
}

function createEmbedCode() {
    if ($(".colorpicker:visible").length > 0) {
        return false;
    }

    var posttype = "post";
    var wtype = 'G';
    if (wtype == "" || posttype == "") {
        return;
    }

    var whereInstance = $("#widget_code");
    if (typeof whereInstance == 'undefined') {
        return;
    }

    var frame = document.getElementById("iframe");
    frame.src = widgetUrl + '&action=get_preview';
}
