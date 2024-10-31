<?php

/**
 * plugz Main Menu
 *
 * @package plugz
 * @subpackage Functions
 */
function plugz_menu() {
    add_menu_page('Plugz Settings', 'Plugz', 'manage_options', 'plugz/settings', 'plugz_settings_page', PLUGZ_IMAGE_DIR . '/logo16.png');
    add_submenu_page('plugz/settings', 'Settings', 'Settings', 'manage_options', 'plugz/settings');
    add_submenu_page('plugz/settings', 'Widget', 'Widget', 'manage_options', 'plugz/widgets', 'plugz_widgets_page');
    add_submenu_page('plugz/settings', 'Help', 'Help', 'manage_options', 'plugz/help', 'plugz_help_page');
}
