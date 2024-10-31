<?php
/**
 * plugz Admin Settings
 *
 * Admin settings for all plugz plugins
 *
 * @package plugz
 * @subpackage Functions
 */
include_once(dirname(__FILE__) . '/header.php');
?>

<script src="<?php echo PLUGZ_JS_DIR ?>/widget.js" type="text/javascript"></script>
<div class="wrap">
    <h2 class="plugz-title">Add Widget <a class="add-new-h2" href="admin.php?page=plugz/widgets">Return to Widget List</a></h2>

    <?php if ($plugzConnected) : 
            $frid = get_option('plugz-frid');
        ?>
        <iframe width="100%" style="overflow:hidden;min-height:600px;height:1000px" src="<?php echo (APPLICATION_ENV == 'development' ? 'http://' : 'https://') . 'www.plugz'.(APPLICATION_ENV == 'development' ? '' : '.co').'/sign-in.plug?redirect=/publisher/widget/add.plug%3Fframe=1%3D1%26widget_website_id='.$frid.'&sig=' . $apiKey ?>"></iframe>
    <?php endif; include_once(dirname(__FILE__) . '/footer.php');
