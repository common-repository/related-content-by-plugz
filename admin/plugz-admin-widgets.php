<?php

/**
 * plugz Admin Settings
 *
 * Admin settings for all plugz plugins
 *
 * @package plugz
 * @subpackage Functions
 */

/*add_action('admin_footer', 'plugz_widget_javascript');

function plugz_widget_javascript() {
    ?>
    <script type="text/javascript" >
        jQuery(document).ready(function ($) {
            var data = {
                'action': 'plugz_widget_tmp_save',
                'whatever': 1234
            };

            // since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
            $.post(ajaxurl, data, function (response) {
                alert('Got this from the server: ' + response);
            });
        });
    </script> <?php
}

add_action('wp_ajax_plugz_widget_tmp_save', 'plugz_widget_tmp_save');

function plugz_widget_tmp_save() {
    global $wpdb; // this is how you get access to the database

    $whatever = intval($_POST['whatever']);

    $whatever += 10;

    echo $whatever;

    die(); // this is required to terminate immediately and return a proper response
}*/

function plugz_widgets_page() {
    $frid = get_option('plugz-frid');

    if (!plugz_connected() || empty($frid)) {
        $plugzConnected = false;
        if (function_exists('curl_init')) {
            echo '<div id="message" class="error fade"><p><strong>Cannot connect to Plugz API, please try again later.</strong></p></div>';
        } else {
            echo '<div id="message" class="error fade"><p><strong>It seems you don\'t have php5-curl installed on your server. It is required to connect to Plugz API. Please ask your administrator to install it.</strong></p></div>';
        }
    } else {
        $webmaster = plugz_request(array('action' => 'getWebmaster'));

        if (isset($webmaster['status'])) {
            $isActivated = ($webmaster['status'] == 'A' ? true : false);

            if ($isActivated) {
                $plugzConnected = true;
            } else {
                $plugzConnected = false;
            }
        } else {
            $plugzConnected = false;
        }

        if (!$plugzConnected) {
            echo '<div id="message" class="error fade"><p><strong>You have to activate before you add a widget. Please click the activation link in the email, that we have sent during registration.</strong></p></div>';
        }
    }

    $apiKey = get_option('plugz-api-key', '');
    $sessionHash = md5('plugz_'.date('Y-m-d').  $apiKey);

    if (isset($_GET['action']) && $_GET['action'] == 'get_preview') {
        $result = plugz_request(
            array(
                'action' => 'getWidgetTmp',
                'id' => $sessionHash
            )
        );
        
        if (isset($result['id'])) {
            $widget = $result;
            include_once(dirname(__FILE__) . '/widget/preview.php');
            die();
        } else {
            die('');
        }
    }
    
    if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
        if (isset($_GET['q'])) {
            die(json_encode(array(array('id' => $_GET['q'], 'text' => $_GET['q']))));
        } elseif (isset($_GET['action']) && $_GET['action'] == 'save_preview') {
            $result = plugz_request(
                array(
                    'action' => 'saveWidget', 
                    'data' => http_build_query(
                        array(
                            'is_tmp' => true, 
                            'sessionHash' => $sessionHash, 
                            'post' => $_POST
                        )
                    )
                )
            );
            header("Content-type: application/json");
            die(json_encode($result));
        } elseif ($_POST['action'] == 'save' && isset($_GET['create'])) {
            $result = plugz_request(array('action' => 'saveWidget', 'data' => http_build_query(array('post' => $_POST))));
            header("Content-type: application/json");
            die(json_encode($result));
        } elseif (isset($_POST['widget_placement'])) {
            header('Content-Type: application/json');

            $widgetPlacements = get_option('plugz-widget-placements', array());

            foreach ($_POST['widget_placement'] as $widgetId => $widgetPlacement) {
                $placement = array(
                    'placement' => $widgetPlacement
                );

                if (isset($_POST['widget_post_id'][$widgetId])) {
                    $placement['post_id'] = $_POST['widget_post_id'][$widgetId];
                }

                if (isset($_POST['widget_page_id'][$widgetId])) {
                    $placement['page_id'] = $_POST['widget_page_id'][$widgetId];
                }

                if (isset($_POST['widget_placement_paragraph'][$widgetId])) {
                    $placement['paragraph'] = $_POST['widget_placement_paragraph'][$widgetId];
                }

                if ($widgetId == 0) {
                    $widgetId = $_POST['widget_id'];
                }

                $widgetPlacements[$widgetId] = $placement;
            }

            update_option('plugz-widget-placements', $widgetPlacements);

            die(json_encode($widgetPlacements));
        } else {
            die();
        }
    }

    if (isset($_GET['create'])) {
        $plugz = get_option('plugz-settings');
        include_once(dirname(__FILE__) . '/widget/add.php');
    } else if (isset($_GET['delete'])) {
        if (is_numeric($_GET['delete'])) {
            $result = plugz_request(array('action' => 'deleteWidget', 'id' => $_GET['delete']));
        }

        wp_redirect('admin.php?page=plugz/widgets', 301);
        exit;
    } else if (isset($_GET['edit'])) {
        if (is_numeric($_GET['edit'])) {
            include_once(dirname(__FILE__) . '/widget/edit.php');
        } else {
            wp_redirect('admin.php?page=plugz/widgets', 301);
        }

        exit;
    } else {
        $widgetPlacements = get_option('plugz-widget-placements', array());

        include_once(dirname(__FILE__) . '/widget/index.php');
    }
}
