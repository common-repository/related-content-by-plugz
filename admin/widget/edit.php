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
    <h2 class="plugz-title">Edit Widget <a class="add-new-h2" href="admin.php?page=plugz/widgets">Return to Widget List</a></h2>

    <?php if ($plugzConnected) : ?>
        <iframe width="100%" style="overflow:hidden;min-height:600px;height:1000px" src="<?php echo (APPLICATION_ENV == 'development' ? 'http://' : 'https://') . 'www.plugz'.(APPLICATION_ENV == 'development' ? '' : '.co').'/sign-in.plug?redirect=/publisher/widget/edit.plug%3Fid%3D' . $_GET['edit'] . '%3D1%26frame=1&sig=' . $apiKey ?>"></iframe>
    <?php endif; include_once(dirname(__FILE__) . '/footer.php');
