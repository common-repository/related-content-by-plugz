<?php

/**
 * plugz Admin Settings
 *
 * Admin settings
 *
 * @package plugz
 * @subpackage Functions
 */
function plugz_settings() {
    register_setting('plugz-settings', 'plugz-settings', 'plugz_save');
    add_settings_field('user', 'Plugz Email', 'plugz-user', 'plugz_settings_page');
    add_settings_field('affid', 'Plugz Affiliate Id', 'plugz-affid', 'plugz_settings_page');
    add_settings_field('password', 'Plugz Password', 'plugz-password', 'plugz_settings_page');
    add_settings_field('rating', 'Website Rating', 'plugz-rating', 'plugz_settings_page');
    add_settings_field('website_type', 'Website Type', 'plugz-website_type', 'plugz_settings_page');
    add_settings_field('website_language', 'Website Language', 'plugz-website_language', 'plugz_settings_page');
    add_settings_field('sexual_orientation', 'Sexual Orientation', 'plugz-sexual_orientation', 'plugz_settings_page');
    add_settings_field('content_type', 'Content Type', 'plugz-content_type', 'plugz_settings_page');
    add_settings_field('main_category_mainstream', 'Main Category', 'plugz-main_category_mainstream', 'plugz_settings_page');
    add_settings_field('main_category_adult_straight', 'Main Category', 'plugz-main_category_adult_straight', 'plugz_settings_page');
    add_settings_field('main_category_adult_gay', 'Main Category', 'plugz-main_category_adult_gay', 'plugz_settings_page');
//    add_settings_field('autopost', 'Autopost to Plugz', 'plugz_autopost', 'plugz_settings_page');
}

function plugz_settings_page() {
    if (!empty($_GET['reset'])) {
        delete_option('plugz-api-key');
        delete_option('plugz-frid');
        delete_option('plugz-has-been-indexed');
        delete_option('plugz-settings');
        
        die('Settings were reset.<meta http-equiv="refresh" content="0;URL=/wp-admin/admin.php?page=plugz/settings">');
    }

    $plugzConnected = false;
    $missingCredentials = false;

    if (isset($_GET['logout']) && $_GET['logout'] == 1) {
        delete_option('plugz-api-key');
        delete_option('plugz-frid');
        delete_option('plugz-has-been-indexed');
        delete_option('plugz-settings');

        header('location: /wp-admin/admin.php?page=plugz/settings');
        die();
    }

    $apiKey = get_option('plugz-api-key', '');

    $validPlugzPages = array(
        'dashboard' => '/publisher/',
        'manage-widgets' => '/publisher/widget/edit.plug',
        'add-plug' => '/publisher/plugs/add.plug',
        'manage-plugs' => '/publisher/plugs/index.plug',
        'stat-traffic' => '/publisher/stat/trade.plug',
        'stat-income' => '/publisher/stat/sold-traffic.plug',
        'edit-profile' => '/publisher/settings/edit-profile.plug',
        'edit-website' => '/publisher/website/edit.plug',
        'support' => '/publisher/support.plug',
    );

    if (isset($_GET['open'])) {
        if (array_key_exists($_GET['open'], $validPlugzPages)) {
            wp_redirect((APPLICATION_ENV == 'development' ? 'http://www.plugz' : 'https://www.plugz.co') . '/sign-in.plug?redirect=' . urlencode($validPlugzPages[$_GET['open']]) . '&sig=' . $apiKey, 301);
        } else {
            wp_redirect('admin.php?page=plugz/settings', 301);
        }

        exit;
    }

    if (isset($status['status']) && $status['status'] == '400') {
        echo '<div id="message" class="error fade"><p><strong>' . $status['message'] . '</strong></p></div>';
    } elseif (isset($status['status']) && $status['status'] == '200') {
        echo '<div id="message" class="updated fade"><p><strong>' . $status['message'] . '</strong></p></div>';
    }

    if (isset($_POST['reindex'])) {
        if (plugz_reindex()) {
            echo '<div id="message" class="updated fade"><p><strong>Your posts were sent for reindex.</strong></p></div>';
        } else {
            echo '<div id="message" class="error fade"><p><strong>An error has occured while trying to reindex.</strong></p></div>';
        }
    }

    $status = plugz_request(array('action' => 'isIndexed'));
    $plugzHasBeenIndexed = get_option('plugz-has-been-indexed', 0);

    if (isset($status['error'])) {
        echo '<div id="message" class="error fade"><p><strong>' . $status['error'] . '</strong></p></div>';
        $isIndexed = false;
    } else {
        if (!isset($status[0]) || $status[0] > 0) {
            $isIndexed = false;
        } else {
            $isIndexed = (boolean) $plugzHasBeenIndexed;
        }
    }

    $plugz = get_option('plugz-settings');
    $frid = get_option('plugz-frid', '');

    if (empty($plugz['rating'])) {
        $plugz['rating'] = 'mainstream';
    }

    $status = plugz_request(array('action' => 'getCategories', 'rating' => 'mainstream', 'sexual_orientation' => ''));

    if (isset($status['error'])) {
        echo '<div id="message" class="error fade"><p><strong>' . $status['error'] . '</strong></p></div>';
    } else {
        if (isset($status[0])) {
            $categoriesMainstream = json_decode($status[0]);
        } else {
            $categoriesMainstream = array();
        }

        $status = plugz_request(array('action' => 'getCategories', 'rating' => 'nsfw', 'sexual_orientation' => 'straight'));

        if (isset($status[0])) {
            $categoriesAdultStraight = json_decode($status[0]);
        } else {
            $categoriesAdultStraight = array();
        }

        $status = plugz_request(array('action' => 'getCategories', 'rating' => 'nsfw', 'sexual_orientation' => 'gay'));

        if (isset($status[0])) {
            $categoriesAdultGay = json_decode($status[0]);
        } else {
            $categoriesAdultGay = array();
        }
    }

    if (plugz_connected()) {
        $plugzConnected = true;
        if (!empty($plugz['user']) && !empty($plugz['password'])) {
            $status = plugz_request(array('action' => 'verifyKey'));

            if (isset($status['error'])) {
                $status = array('status' => '400', 'message' => $status['error']);
            } else {
                $webmaster = plugz_request(array('action' => 'getWebmaster'));

                if (isset($webmaster['status'])) {
                    $isActivated = ($webmaster['status'] == 'A' ? true : false);

                    if ($isActivated) {
                        $isValidWebsite = plugz_request(array('action' => 'validateWebsite'));
                        
                        if (!empty($isValidWebsite[0]) && !is_bool($isValidWebsite[0])) {
                            update_option('plugz-frid', (int) $isValidWebsite[0]);
                            $website = plugz_request(array('action' => 'getWebsite'));
                            $status = array('status' => '200', 'message' => 'Congratulation, you are connected to the Plugz API.');
                            
                            $widgetCount = plugz_request(array('action' => 'countWidgets'));
                            $widgetCount = (int)@$widgetCount[0];
                        } elseif (isset($isValidWebsite[0]) && $isValidWebsite[0] === false) {
                            delete_option('plugz-frid');
                            $status = array('status' => '400', 'message' => 'This website belongs to another user.');
                        } else {
                            $status = array('status' => '400', 'message' => 'We couldn\'t create record for this website. <a href="https://www.plugz.co/#contact">Please contact us</a> to resolve this problem.');
                        }
                    } else {
                        $status = array('status' => '400', 'message' => 'Please check your email. We have sent you an activation e-mail with the instructions about how to continue.');
                    }

                    if (isset($_GET['settings-updated']) && $_GET['settings-updated']) {
                        $isValidWebsite = plugz_request(array('action' => 'validateWebsite'));
                        
                        if (!empty($isValidWebsite[0]) && !is_bool($isValidWebsite[0])) {
                            update_option('plugz-frid', (int) $isValidWebsite[0]);
                            $website = plugz_request(array('action' => 'getWebsite'));
                        } elseif (isset($isValidWebsite[0]) && $isValidWebsite[0] === false) {
                            $status = array('status' => '400', 'message' => 'This website belongs to another user.');
                        }
                        
                        if (!isset($isValidWebsite[0]) || (isset($isValidWebsite[0]) && $isValidWebsite[0] !== false)) {
                            $categoriesMainstreamTmp = $categoriesMainstream;

                            foreach ($categoriesMainstreamTmp as $categoriesMainstreamTmpKey => $categoriesMainstreamTmpVal) {
                                if ($categoriesMainstreamTmpVal == $plugz['main_category_mainstream']) {
                                    unset($categoriesMainstreamTmp[$categoriesMainstreamTmpKey]);
                                }
                            }

                            $implodedMainstreamCategories = implode(',', $categoriesMainstreamTmp);
                            
                            $params = array(
                                'action' => 'updateWebsite',
                                'rating' => (isset($website['isadult']) ? ($website['isadult'] ? 'nsfw' : 'mainstream') : ($plugz['rating'] == 'nsfw' ? 'nsfw' : 'mainstream')),
                                'sexual_orientation' => $plugz['sexual_orientation'],
                                'language' => @$plugz['website_language'],
                                'siteurl' => PLUGZ_SITE_URL,
                                'name' => get_bloginfo('name'),
                                'sitetype' => @$plugz['site_type'] == 'M' ? 'M' : 'G',
                                'contenttype' => $plugz['content_type'],
                                'siterssurl' => network_site_url('/'),
                                'tags' => '',
                                'nonadult_tags' => '',
                                'tags_gay' => '',
                                'main_category_adult' => $plugz['rating'] == 'nsfw' ? $plugz['main_category_adult_straight'] : '',
                                'main_category_adult_gay' => $plugz['rating'] == 'nsfw' ? $plugz['main_category_adult_gay'] : '',
                                'main_category_nonadult' => $plugz['rating'] == 'mainstream' ? $plugz['main_category_mainstream'] . (strlen($implodedMainstreamCategories) > 0 ? ','.$implodedMainstreamCategories : '') : '',
                            );

                            $frid = plugz_request($params);
                            $webmaster = plugz_request(array('action' => 'getWebmaster'));
                            $apiKey = $webmaster['wptoken'];
                            update_option('plugz-api-key', $webmaster['wptoken']);
                            update_option('plugz-frid', (int) $frid[0]);

                            if (!$plugzHasBeenIndexed && $isActivated) {
                                update_option('plugz-start-index-schedule', 1);
                                update_option('plugz-index-schedule-limit', 10);
                                update_option('plugz-index-schedule-offet', 0);
                                update_option('plugz-has-been-indexed', 0);
                            }

                            $website = plugz_request(array('action' => 'getWebsite'));
                            $categories = explode(',', $website['tags']);

                            update_option('plugz-settings', array(
                                'user' => $plugz['user'],
                                'password' => $plugz['password'],
                                'sexual_orientation' => (isset($website['is_straight']) && !$website['is_straight'] ? 'gay' : 'straight'),
                                'website_language' => @$website['language'],
                                'website_type' => @$plugz['tradetype'],
                                'content_type' => @$website['category'],
                                'main_category_mainstream' => (!$website['isadult'] ? $categories[0] : ''),
                                'main_category_adult_straight' => ($website['isadult'] && !empty($website['is_straight']) ? $categories[0] : ''),
                                'main_category_adult_gay' => ($website['isadult'] && empty($website['is_straight']) ? $categories[0] : ''),
                                'rating' => ($website['isadult'] ? 'nsfw' : 'mainstream')
                                    )
                            );
                            $plugz = get_option('plugz-settings');
                        }
                    } else {
                        $website = plugz_request(array('action' => 'getWebsite'));
                        $categories = explode(',', $website['tags']);

                        if (!empty($_GET['reindex']) && !$plugzHasBeenIndexed && $isActivated) {
                            update_option('plugz-start-index-schedule', 1);
                            update_option('plugz-index-schedule-limit', 10);
                            update_option('plugz-index-schedule-offet', 0);
                            update_option('plugz-has-been-indexed', 0);
                        }

                        update_option('plugz-settings', array(
                            'user' => $plugz['user'],
                            'password' => $plugz['password'],
                            'sexual_orientation' => (isset($website['is_straight']) && !$website['is_straight'] ? 'gay' : 'straight'),
                            'website_language' => $website['language'],
                            'website_type' => @$plugz['tradetype'],
                            'content_type' => $website['category'],
                            'main_category_mainstream' => (!$website['isadult'] ? $categories[0] : ''),
                            'main_category_adult_straight' => ($website['isadult'] && !empty($website['is_straight']) ? $categories[0] : ''),
                            'main_category_adult_gay' => ($website['isadult'] && empty($website['is_straight']) ? $categories[0] : ''),
                            'rating' => ($website['isadult'] ? 'nsfw' : 'mainstream')
                                )
                        );
                        $plugz = get_option('plugz-settings');
                    }
                } else {
                    $status = array('status' => '400', 'message' => $webmaster[0]);
                }
            }
        } else {
            $missingCredentials = true;
            $status = array('status' => '400', 'message' => 'Please specify a user/password to continue');
        }
    } else {
        $plugzConnected = false;
        if (function_exists('curl_init') || function_exists('file_get_contents')) {
            echo '<div id="message" class="error fade"><p><strong>Cannot connect to Plugz API, please try again later. You might try to <a href="admin.php?page=plugz/settings&amp;reset=1">reset Plugz API settings</a>.</strong></p></div>';
        } else {
            echo '<div id="message" class="error fade"><p><strong>It seems you don\'t have php5-curl installed on your server. It is required to connect to Plugz API. Please ask your administrator to install it.</strong></p></div>';
        }
    }

    include_once(dirname(__FILE__) . '/settings/index.php');
}
