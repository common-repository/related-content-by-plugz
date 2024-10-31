<?php

/**
 * plugz Admin Settings
 *
 * Admin settings for all plugz plugins
 *
 * @package plugz
 * @subpackage Functions
 */
function plugz_help_page() {
    $plugin_data = get_plugin_data( PLUGZ_PLUGIN_DIR.'/plugz.php' );
    $plugin_version = $plugin_data['Version'];
    include_once(dirname(__FILE__) . '/help/index.php');
}
