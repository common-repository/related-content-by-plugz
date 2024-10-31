<?php
/*
  Plugin Name: Related Content by Plugz
  Plugin Script: plugz.php
  Plugin URI: http://www.plugz.co
  Description: Get Premium quality traffic with Plugz. Display related posts on your blog. Boost your site with new visitors or earn money with sponsored content.
  Version: 1.6.2
  Author: Plugz.co Team
  Author URI: http://www.plugz.co
  Text Domain: plugzl18n
  Domain Path: /lang
  License: GPL2

  === RELEASE NOTES ===
  2016-05-30 - v1.6.2 - fixes wordfence conflict
  2016-05-26 - v1.6.1 - widget https support
  2016-03-08 - v1.6 - improved widget editor
  2016-01-12 - v1.5.8 - added website language option
  2016-01-11 - v1.5.7 - widget browser cache fix
  2015-11-27 - v1.5.6 - api connection/reconnection fixes
  2015-11-05 - v1.5.5 - ssl fixes
  2015-07-09 - v1.5.4 - api call bugfixes
  2015-06-18 - v1.5.3 - support added for Wordpress 4.2 (fixed major issue of posts disappearing when plugin was enabled)
  2014-12-08 - v1.5.2 - added user agent string on curl requests
  2014-12-03 - v1.5.1 - minor fixes
  2014-11-01 - v1.5 - affiliate support
  2014-10-29 - v1.4.6 - fixes bug on plugin removal
  2014-10-23 - v1.4.5 - remove all plugz options on plugz plugin uninstall
  2014-10-15 - v1.4.4 - minor improvements on plugz plugin install/uninstall
  2014-10-08 - v1.4.3 - suppressing php warnings
  2014-10-07 - v1.4.2 - post update url bug fix for permalinks
  2014-09-25 - v1.4.1 - widget preview related improvements
  2014-09-25 - v1.4 - fixes widget preview with long custom css bug
  2014-09-09 - v1.3.6 - fixes monetize and gay widget preview bugs
  2014-09-01 - v1.3.5 - bug fixes
  2014-08-06 - v1.3.4 - improvements, bug fixes
  2014-08-01 - v1.3.3.1 - guid/permalink fix
  2014-07-28 - v1.3.3 - background indexing
  2014-07-27 - v1.3.2 - reindex speedup, minor fixes, widget improvements
  2014-07-27 - v1.3.1 - php warning bug fix
  2014-07-22 - v1.3 - important image indexing bug was fixed
  2014-07-22 - v1.2 - bug fixes, minor changes
  2014-07-17 - v1.1.1 - support for existing domains
  2014-07-03 - v1.1 - new features: shortcodes, new templates, custom css and easy widget placement on pages and posts
  2014-06-27 - v1.0.1 - bug fixes
  2014-06-22 - v1.0 - first version
 */
define('PLUGZ_ADMIN_SETTINGS_PAGE', 'plugz');
defined('APPLICATION_ENV') || define('APPLICATION_ENV', (getenv('APPLICATION_ENV') ? getenv('APPLICATION_ENV') : 'production'));

define('API_DOMAIN', 'www.plugz.co');

// Plugin specific
define('PLUGZ_PLUGIN_BASENAME', plugin_basename(__FILE__));
define('PLUGZ_PLUGIN_NAME', trim(dirname(PLUGZ_PLUGIN_BASENAME), '/'));
define('PLUGZ_PLUGIN_DIR', untrailingslashit(plugin_dir_path(__FILE__)));
define('PLUGZ_PLUGIN_URL', plugins_url(PLUGZ_PLUGIN_NAME));
define('PLUGZ_SITE_URL', get_site_url());
define('PLUGZ_SETTINGS_DIR', PLUGZ_PLUGIN_DIR . '/settings');
define('PLUGZ_SETTINGS_URL', PLUGZ_PLUGIN_URL . '/settings');
define('PLUGZ_ADMIN_DIR', PLUGZ_PLUGIN_DIR . '/admin');
define('PLUGZ_IMAGE_DIR', PLUGZ_PLUGIN_URL . '/images');
define('PLUGZ_CSS_DIR', PLUGZ_PLUGIN_URL . '/css');
define('PLUGZ_JS_DIR', PLUGZ_PLUGIN_URL . '/js');

if (isset($_REQUEST['pr_api'])) {
    $settings = get_option('plugz-settings');
    $apiKey = get_option('plugz-api-key');

    $plugz = new Plugz();
    $plugz->init($settings, $apiKey);
    if (!empty($_REQUEST["action"]) && $_REQUEST["action"] == "update") {
        $plugz->update();
    } elseif (isset($_REQUEST["action"]) && $_REQUEST["action"] == "status") {
        $plugz->status();
    }
    exit();
}

add_action('wp_head', 'plugz_head');
add_theme_support('post-thumbnails');
add_action('plugz_schedule_event_hook', 'plugz_do_this_every_half_minute');
add_action('wp', 'plugz_setup_schedule');

function plugz_setup_schedule() {
    $timestamp = get_option('plugz_start-index-schedule-timestamp', 0);

    if (!$timestamp || $timestamp < time()) {
        plugz_do_this_every_half_minute();
        update_option('plugz_start-index-schedule-timestamp', time() + 30);
    }
}

require_once(PLUGZ_ADMIN_DIR . '/common.php');

if (is_admin()) {
    require_once(PLUGZ_ADMIN_DIR . '/plugz-main-menu.php');
    require_once(PLUGZ_ADMIN_DIR . '/plugz-admin-settings.php');
    require_once(PLUGZ_ADMIN_DIR . '/plugz-admin-widgets.php');
    require_once(PLUGZ_ADMIN_DIR . '/plugz-admin-help.php');
    add_action('admin_menu', 'plugz_menu');
    add_action('admin_init', 'plugz_settings');
    register_activation_hook(__FILE__, 'plugz_activate');
    register_deactivation_hook(__FILE__, 'plugz_deactivate');
    register_uninstall_hook(__FILE__, 'plugz_uninstall');
    $plugin = plugin_basename(__FILE__);
    add_filter("plugin_action_links_$plugin", 'plugz_settings_link');
    add_action('post_submitbox_misc_actions', 'plugz_publish_box');
    add_filter('manage_edit-post_columns', 'plugz_post_header_columns', 10, 1);
    add_action('manage_posts_custom_column', 'plugz_post_data_row', 10, 2);
    add_action('save_post', 'plugz_post', 10, 2);
    add_action('before_delete_post', 'plugz_delete_post');
}

function plugz_author_admin_init() {
    /* Set plugin version data for use elsewhere in the plugin */
    if (function_exists('get_plugin_data')) {
        $_ENV['plugz_author_plugindata'] = get_plugin_data(PLUGZ_PLUGIN_DIR . '/plugz.php', false);
    } else { // If the function get_plugin_data does not exist, return empty array
        $_ENV['plugz_author_plugindata'] = array(
            'Version' => ''
        );
    }
}

function plugz_do_this_every_half_minute() {
    $scheduledIndexing = get_option('plugz-start-index-schedule', 0);
    $limit = get_option('plugz-index-schedule-limit');
    $offset = get_option('plugz-index-schedule-offet');
    $isIndexed = get_option('plugz-has-been-indexed', 0);

    if ($scheduledIndexing && !$isIndexed) {
        plugz_reindex($limit, $offset);
    }
}

function plugz_is_service_up() {
    if (function_exists('curl_init')) {
        $agent = "Mozilla/4.0 (compatible; MSIE 5.01; Windows NT 5.0)";
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://www.plugz.co');
        curl_setopt($ch, CURLOPT_USERAGENT, $agent);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_VERBOSE, false);
        curl_setopt($ch, CURLOPT_TIMEOUT, 5);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSLVERSION, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        $page = curl_exec($ch);
        $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        if ($httpcode >= 200 && $httpcode < 300) {
            return true;
        } else {
            return false;
        }
    } else {
        return true;
    }
}

add_shortcode('plugz', 'plugz_widget_shortcode');

// Configure defaults and extract the attributes into variables
function plugz_widget_shortcode($atts) {
    if (plugz_is_service_up()) {
        extract(shortcode_atts(
                        array(
            'type' => 'plugz_widget',
            'title' => 'Plugz',
            'scheme' => 'dark'
                        ), $atts
        ));

        $args = array(
            'before_widget' => '<div class="box widget scheme-' . $scheme . ' ">',
            'after_widget' => '</div>',
            'before_title' => '<div class="widget-title">',
            'after_title' => '</div>',
        );

        if (isset($atts['id'])) {
            $atts['widget_id'] = $atts['id'];
        }

        if (is_singular() && !is_admin()) {
            ob_start();
            the_widget($type, $atts, $args);
            $output = ob_get_clean();
        } else {
            $output = '';
        }
    } else {
        $output = '';
    }

    return $output;
}

//Insert plugz after x paragraph of single post content.
add_filter('the_content', 'plugz_insert_post_filter');

function plugz_insert_post_filter($content) {
    if (plugz_is_service_up()) {
        $placements = get_option('plugz-widget-placements', array());
        foreach ($placements as $widgetId => $placement) {
            if (is_numeric($widgetId)) {
                if ($placement['placement'] == 'on single page' &&
                        is_page() &&
                        !is_admin() &&
                        get_the_ID() == $placement['page_id']) {
                    $ad_code = '<div>' . do_shortcode("[plugz id=$widgetId]") . '</div>';
                    $content = plugz_insert_post($ad_code, $placement['paragraph'], $content);
                } elseif ($placement['placement'] == 'on single post' &&
                        is_single() &&
                        !is_admin() &&
                        get_the_ID() == $placement['post_id']) {
                    $ad_code = '<div>' . do_shortcode("[plugz id=$widgetId]") . '</div>';
                    $content = plugz_insert_post($ad_code, $placement['paragraph'], $content);
                } elseif ($placement['placement'] == 'on all posts and pages' &&
                        is_singular() &&
                        !is_admin()) {
                    $ad_code = '<div>' . do_shortcode("[plugz id=$widgetId]") . '</div>';
                    $content = plugz_insert_post($ad_code, $placement['paragraph'], $content);
                } elseif ($placement['placement'] == 'on all posts' &&
                        is_single() &&
                        !is_admin()) {
                    $ad_code = '<div>' . do_shortcode("[plugz id=$widgetId]") . '</div>';
                    $content = plugz_insert_post($ad_code, $placement['paragraph'], $content);
                } elseif ($placement['placement'] == 'on all pages' &&
                        is_page() &&
                        !is_admin()) {
                    $ad_code = '<div>' . do_shortcode("[plugz id=$widgetId]") . '</div>';
                    $content = plugz_insert_post($ad_code, $placement['paragraph'], $content);
                }
            }
        }
    }

    return $content;
}

function plugz_insert_post($insertion, $paragraph_id, $content) {
    if ($paragraph_id == 'top') {
        $content = $insertion . $content;

        return $content;
    } elseif ($paragraph_id == 'bottom') {
        $content .= $insertion;

        return $content;
    } else {
        $closing_p = '</p>';
        $paragraphs = explode($closing_p, $content);
        foreach ($paragraphs as $index => $paragraph) {

            if (trim($paragraph)) {
                $paragraphs[$index] .= $closing_p;
            }

            if ($paragraph_id == $index + 1) {
                $paragraphs[$index] .= $insertion;
            }
        }

        return implode('', $paragraphs);
    }
}

function plugz_error($key, $error) {
    $errors = array();
    $errors['descr'] = 'Description is too short. Use the Excerpt field to make a description with at least 10 characters';
    $errors['thumbnail'] = 'No valid thumbnail was submitted. Please set a featured image for your post';
    if (isset($errors[$key])) {
        return $errors[$key];
    }
    return $error;
}

function plugz_box($post) {
    $plugz = get_option('plugz-settings');
    $apiKey = get_option('plugz-api-key');
    $status = plugz_request(array('action' => 'getCategories', 'rating' => $plugz['rating']));
    $categories = json_decode($status[0]);

    wp_nonce_field(plugin_basename(__FILE__), 'plugz_noncename');
    $plugz_post = get_post_meta($post->ID, '_plugz', TRUE);

    if (!isset($plugz_post['categories'])) {
        $plugz_post['categories'] = '';
    }

    $plugz_post['categories'] = explode(',', $plugz_post['categories']);
    ?>
    <?php if (!empty($plugz_post['message'])) { ?>
        <div style="padding-top:5px;" class="postbox">
            <div class="<?= $plugz_post['posted'] == 1 ? 'updated' : 'error'; ?>"><p>
                    <strong>Plugz:</strong> <?= $plugz_post['message']; ?>
                    <?php if (!empty($plugz_post['errors'])) { ?>
                    <ol>
                        <?php foreach ($plugz_post['errors'] as $key => $error) { ?>
                            <li><?= plugz_error($key, $error); ?></li>
                        <?php } ?>
                    </ol>
                <?php } ?>
                </p></div>
        </div>
    <?php } ?>
    <?php $thumb = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID), 'full'); ?>
    <div class="categorydiv" id="pr_cats">
        <?php if (!empty($categories)) { ?>
            <ul class="category-tabs" id="plugz-category-tabs">
                <li class="tabs"><a tabindex="3" href="#plugz-category-all">Categories</a></li>
            </ul>                
            <div class="tabs-panel" id="pr_categories">
                <?php
                $selected = get_post_meta($post->ID, '_plugz_category', TRUE);
                ?>
                <ul class="list:category categorychecklist form-no-clear" id="plugzcategorychecklist">
                    <?php foreach ($categories as $parent => $subcats) { ?>
                        <?php if (!is_numeric($parent)) : ?>
                            <li><input id="pr-category-<?= $parent; ?>" type="checkbox" name="_plugz_category[]"<?= is_array($plugz_post['categories']) && in_array($parent, $plugz_post['categories']) ? ' checked="checked"' : ''; ?> value="<?= $parent; ?>" /> <strong><?php echo $parent ?></strong></li>
                            <?php foreach ($subcats as $key => $val) { ?>
                                <li id="plugz_category-<?= $key; ?>">
                                    <label>
                                        <input id="pr-category-<?= $val; ?>" type="checkbox" name="_plugz_category[]"<?= is_array($plugz_post['categories']) && in_array($val, $plugz_post['categories']) ? ' checked="checked"' : ''; ?> value="<?= $val; ?>" />
                                        <?= $val; ?>
                                    </label>
                                </li>
                            <?php } ?>
                        <?php else : ?>
                            <li id="plugz_category-<?= $parent; ?>">
                                <label>
                                    <input id="pr-category-<?= $parent; ?>" type="checkbox" name="_plugz_category[]"<?= is_array($plugz_post['categories']) && in_array($subcats, $plugz_post['categories']) ? ' checked="checked"' : ''; ?> value="<?= $subcats; ?>" />
                                    <?= $subcats; ?>
                                </label>
                            </li>
                        <?php endif; ?>
                    <?php } ?>
                </ul>
            </div>
        <?php } else { ?>
            Error: Check your Plugz settings
        <?php } ?>
    </div>
    <?php
}

// Add settings link on plugin page
function plugz_settings_link($links) {
    $settings_link = '<a href="admin.php?page=plugz/settings">Settings</a>';
    array_unshift($links, $settings_link);
    return $links;
}

function plugz_head() {
    $plugz = get_option('plugz-settings');
    if (isset($plugz['integration_library'])) {
        echo htmlspecialchars_decode($plugz['integration_library']);
    }
}

function plugz_activate() {
    add_action('admin_notices', 'plugz_admin_notice');
    plugz_request(array('action' => 'installWpPlugin'));
}

function plugz_deactivate() {
    plugz_request(array('action' => 'uninstallWpPlugin'));

    delete_option('plugz-affid');
    delete_option('plugz-api-key');
    delete_option('plugz-frid');
    delete_option('plugz-has-been-indexed');
    delete_option('plugz-settings');
}

function plugz_uninstall() {
    plugz_request(array('action' => 'uninstallWpPlugin'));

    delete_option('plugz-affid');
    delete_option('plugz-api-key');
    delete_option('plugz-frid');
    delete_option('plugz-has-been-indexed');
    delete_option('plugz-settings');
}

function plugz_admin_notice() {
    echo '<div class="updated"><p>Plugz has been activated. Go to the Plugz settings page in the admin menu to configure your settings.</p></div>';
}

function plugz_delete_post($post_id) {
    $data[$post_id] = array(
        'title' => '',
        'name' => '',
        'url' => '',
        'image' => '',
        'width' => 0,
        'height' => 0,
        'categories' => '',
        'tags' => '',
        'posttype' => 'GALLERY',
        'models' => null,
        'action' => 'DELETE'
    );
    $result = plugz_request(array('action' => 'updatePost', 'posts' => http_build_query($data)));

    $plug = get_post_meta($post_id, '_plugz', TRUE);
    $plug['message'] = FALSE;
    $plug['errors'] = FALSE;
    $plug['autopost'] = '0';
    update_post_meta($post_id, '_plugz', $plug);
}

function plugz_post($post_id, $post) {
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }
    $plugz = get_option('plugz-settings');

    /* Get the post type object. */
    $real_post_id = wp_is_post_revision($post_id);
    if (!empty($real_post_id)) {
        $post_id = $real_post_id;
    }
    $thumb = wp_get_attachment_image_src(get_post_thumbnail_id($post_id), 'full');
    if (!empty($_POST['_plugz_post'])) {
        $post_type = get_post_type_object($post->post_type);
        $plug = array();
        $plug['title'] = $_POST['post_title'];
        $plug['description'] = !empty($_POST['excerpt']) ? $_POST['excerpt'] : $_POST['post_title'];
        $plug['link'] = get_permalink($post_id);
        $plug['thumbnail'] = $thumb[0];
        if (is_array($_POST['_plugz_category'])) {
            $plug['categories'] = implode(',', $_POST['_plugz_category']);
        }

        $image = wp_get_attachment_image_src(get_post_thumbnail_id($post_id), 'single-post-thumbnail');

        if (!empty($image[0])) {
            $imageUrls[$post_id] = $image[0];
        } elseif (!empty($post->post_content)) {
            $dom = new domDocument;
            $dom->loadHTML($post->post_content);
            $dom->preserveWhiteSpace = false;
            $imagesDom = $dom->getElementsByTagName('img');

            $images = array();
            foreach ($imagesDom as $node) {
                $images[] = $node;
            }

            if (isset($images[0])) {
                $imageUrls[$post->ID] = $images[0]->getAttribute('src');
            }
        }

        $tags = wp_get_post_tags($post->ID);
        $plug['tags'] = array();

        foreach ($tags as $tag) {
            $plug['tags'][] = $tag->name;
        }

        if (isset($imageUrls[$post_id])) {
            $meta = plugz_get_image_meta($imageUrls[$post_id]);
            add_post_meta($post_id, '_plugz_posted', 1, true) || update_post_meta($post_id, '_plugz_posted', 1);

            $permalink = post_permalink($post->ID);
            if (empty($permalink)) {
                $permalink = $post->guid;
            }

            $data[$post_id] = array(
                'title' => $post->post_title,
                'name' => $post->post_name,
                'url' => $permalink,
                'descr' => $plug['description'],
                'image' => $imageUrls[$post->ID],
                'width' => @$meta[0],
                'height' => @$meta[1],
                'categories' => $plug['categories'],
                'tags' => implode(',', $plug['tags']),
                'posttype' => (isset($plugz['website_type']) && $plugz['website_type'] == 'M' ? 'TUBE' : 'GALLERY'),
                'models' => '',
                'action' => 'UPDATE'
            );
        }

        if (isset($data) && is_array($data)) {
            $result = plugz_request(array('action' => 'updatePost', 'posts' => http_build_query($data)));
        } else {
            $result = array();
        }

        $status = array();

        if (isset($result[0]) && $result[0] == 'success') {
            $status['status'] = '200';
        } else {
            $status['status'] = '400';
            $status['errors'] = 'Couldn\'t send this post to Plugz.';
        }

        if ($status['status'] == '200') {
            $plug['posted'] = 1;
            $plug['message'] = $status['message'];
            $plug['errors'] = FALSE;
        } else {
            $plug['posted'] = 0;
            $plug['message'] = $status['message'];
            $plug['errors'] = isset($status['errors']) ? $status['errors'] : FALSE;
        }
        $plug['autopost'] = $_POST['_plugz_autopost'];
        update_post_meta($post_id, '_plugz', $plug);
    } else {
        $plug = get_post_meta($post_id, '_plugz', TRUE);
        $plug['message'] = FALSE;
        $plug['errors'] = FALSE;
        $plug['autopost'] = '1';
        update_post_meta($post_id, '_plugz', $plug);
    }
}

function plugz_messages($messages) {
    global $post, $post_ID;
    return $messages;
}

function plugz_save($params) {
    return $params;
    if (!empty($params['user']) && !empty($params['api_key'])) {
        $status = plugz_request(array('action' => 'verify', 'user' => $params['user'], 'api_key' => $params['api_key']));
    }
    if (isset($status['status']) && $status['status'] == '200') {
        $params['action'] = 'post-update';
        $response = plugz_request($params);
        if ($response['status'] == '200') {
            if (isset($response['data']['integration_library'])) {
                $params['integration_library'] = $response['data']['integration_library'];
                wp_cache_flush();
            }
        } else {
            add_settings_error('plugz-settings', 'plugz', 'Failed to update settings: ' . $response['message']);
            return (array) get_option('plugz-settings');
        }
        return $params;
    } else {
        return array('user' => $params['user'], 'api_key' => $params['api_key']);
    }
}

function plugz_domain_from_url($url, $returnTopDomain = FALSE) {
    preg_match('_^(?:([^:/?#]+):)?(?://([^/?#]*))?' . '([^?#]*)(?:\?([^#]*))?(?:#(.*))?$_', $url, $uri_parts);
    $domain = $uri_parts[2];
    $exp = explode('.', $domain);
    if ($exp[0] == 'www') {
        $domain = substr($domain, 4);
    }
    if ($returnTopDomain) {
        $exp = explode('.', $domain);
        $nr = count($exp);
        switch ($nr) {
            case 4:
                $domain = $exp[1] . '.' . $exp[2] . '.' . $exp[3];
                break;
            case 3:
                if (strlen($exp[1]) > 3)
                    $domain = $exp[1] . '.' . $exp[2];
                else
                    $domain = $exp[0] . '.' . $exp[1] . '.' . $exp[2];
                break;
            default:
                $domain = $domain;
                break;
        }
    }
    return strtolower($domain);
}

class Plugz_Widget extends WP_Widget {

    /**
     * Register widget with WordPress.
     */
    public function __construct() {
        parent::__construct(
                'plugz_widget', // Base ID
                'Plugz Widget', // Name
                array('description' => __('Lets you display a widget from Plugz in your sidebar or in other locations in your theme', 'text_domain'),) // Args
        );
    }

    /**
     * Front-end display of widget.
     *
     * @see WP_Widget::widget()
     *
     * @param array $args     Widget arguments.
     * @param array $instance Saved values from database.
     */
    public function widget($args, $instance) {
        extract($args);

        if (isset($instance['title'])) {
            $title = apply_filters('widget_title', $instance['title']);
        }
        
        echo $before_widget;
        
        if (isset($title) && !empty($title)) {
            echo $before_title . $title . $after_title;
        }

        if (isset($instance['widget_code'])) {
            echo htmlspecialchars_decode($instance['widget_code']);
        } elseif (isset($instance['widget_id'])) {
            $plugz = get_option('plugz-settings');
            $apiKey = get_option('plugz-api-key', '');

            if (!empty($plugz['user']) && !empty($apiKey)) {
                $plugz = get_option('plugz-settings');

                if ($plugz['rating'] == 'nsfw') {
                    echo '<script type="text/javascript" src="//plug.plugerr.com/widget/' . base_convert($instance['id'], 10, 36) . '?r='.time().'"></script>';
                } elseif ($plugz['rating'] == 'mainstream') {
                    echo '<script type="text/javascript" src="//plug.plugs.co/widget/' . base_convert($instance['id'], 10, 36) . '?r='.time().'"></script>';
                } else {
                    $params = array('action' => 'getWidgetCode', 'id' => $instance['widget_id']);
                    $w = (array) plugz_request($params);

                    if (!empty($w['id'])) {
                        $instance['widget_id'] = $w['id'];
                        $instance['widget_code'] = $w['code'];
                        echo htmlspecialchars_decode($instance['widget_code']);
                    }
                }
            }
        }

        echo $after_widget;
    }

    /**
     * Sanitize widget form values as they are saved.
     *
     * @see WP_Widget::update()
     *
     * @param array $new_instance Values just sent to be saved.
     * @param array $old_instance Previously saved values from database.
     *
     * @return array Updated safe values to be saved.
     */
    public function update($new_instance, $old_instance) {
        $instance = array();
        $instance['title'] = $new_instance['title'];
        $plugz = get_option('plugz-settings');
        $apiKey = get_option('plugz-api-key', '');
        if (!empty($plugz['user']) && !empty($apiKey)) {
            $status = plugz_request(array('action' => 'verify', 'user' => $plugz['user'], 'api_key' => $apiKey));
        }
        if ($status['status'] == '200') {
            if (!empty($new_instance['widget_id'])) {
                $params = array('action' => 'getWidgetCode', 'id' => $new_instance['widget_id']);
                $w = (array) plugz_request($params);
                if (!empty($w)) {
                    $instance['widget_id'] = $w['id'];
                    $instance['widget_code'] = $w['code'];
                }
            }
        }
        return $instance;
    }

    /**
     * Back-end widget form.
     *
     * @see WP_Widget::form()
     *
     * @param array $instance Previously saved values from database.
     */
    public function form($instance) {
        $plugz = get_option('plugz-settings');
        $apiKey = get_option('plugz-api-key', '');
        if (!empty($plugz['user']) && !empty($apiKey)) {
            $status = plugz_request(array('action' => 'verify', 'user' => $plugz['user'], 'api_key' => $apiKey));
        }
        if (isset($status['status']) && $status['status'] == '200') {
            $widgets = plugz_request(array('action' => 'getWidgets'));
        }
        ?>
        <?php if (count($widgets)) { ?>
            <p>
                <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:'); ?></label> 
                <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($instance['title']); ?>" />
            </p>
            <p>
                <label for="<?php echo $this->get_field_id('widget_id'); ?>"><?php _e('Plugz Widget:'); ?></label>
                <select name="<?php echo $this->get_field_name('widget_id'); ?>" style="width:218px;">
                    <option>Please select a widget</option>
                    <?php
                    foreach ($widgets as $row) {
                        $wdata = json_decode($row['wdata']);
                        ?>
                        <option value="<?= $row['id']; ?>"<?= $instance['widget_id'] == $row['id'] ? ' selected="selected"' : ''; ?>><?= $row['name']; ?> (<?= $wdata->widget_width; ?>px)</option>
                    <?php } ?>
                </select>
            </p>
        <?php } elseif (isset($status['status']) && $status['status'] == '200') { ?>
            <p>No Plugz Widgets found. Go to <a href="admin.php?page=plugz/settings&open=dashboard&noheader=true" target="plugz">Plugz.co</a> and create some first.
            <?php } else { ?>
            <p>Unable to connect to Plugz API. Please check your <a href="admin.php?page=plugz/settings">Plugz settings</a>.
                <?php
            }
        }

    }

    add_action('widgets_init', create_function('', 'register_widget( "plugz_widget" );'));
    add_action('admin_init', 'plugz_author_admin_init'); // Hook plugin admin initialization

    class Plugz {

        var $domain;
        var $settings;
        var $apiKey;

        function init($settings, $apiKey) {
            $this->settings = $settings;
            $this->apiKey = $apiKey;
            $this->domain = plugz_domain_from_url(get_option('siteurl'));
        }

        function status() {
            $status = array("status" => 1, "message" => "Script has been installed successfully");

            if (isset($_GET["format"]) && $_GET["format"] == "json") {
                header("Content-type: application/json");
                echo json_encode($status);
            } else {
                echo $status["message"];
            }

            exit();
        }

        function update() {
            $status = array("status" => 0, "message" => "Script has not been installed");

            if (empty($_POST["secret"]) || empty($_POST["script"])) {
                $status = array("status" => -1, "message" => "Could not update script");
            } elseif ($_POST["secret"] != $this->apiKey) {
                $status = array("status" => -1, "message" => "api_key does not match");
            } elseif (empty($_POST["script"])) {
                $status = array("status" => -1, "message" => "An error occured");
            }

            if ($status["status"] == 0) {
                $this->settings['adblock_code'] = htmlspecialchars($_POST['script']);
                if (update_option('plugz-settings', $this->settings)) {
                    $status = array("status" => 1, "message" => "Script updated successfully to your server");
                } else {
                    $status = array("status" => -1, "message" => "Could not update. js.php is not writable");
                }
            }
            if (isset($_GET["format"]) && $_GET["format"] == "json") {
                header("Content-type: application/json");
                echo json_encode($status);
            } else {
                echo $status["message"];
            }
            exit();
        }

    }

    function plugz_publish_box() {
        global $post;
        // only display for authorized users
        if (!current_user_can('publish_posts')) {
            return;
        }
        // don't display for pages
        if ($post->post_type == 'page') {
            return;
        }

        $frid = get_option('plugz-frid');

        if (empty($frid)) {
            return;
        }

        $plugz = get_option('plugz-settings');
        $plugz_post = get_post_meta($post->ID, '_plugz', TRUE);
        ?>
    <div class="misc-pub-section plugz">
        <input type="hidden" name="_plugz_post" value="0" />
        <span id="plugz" style="background-image: url('<?= plugins_url('/logo16.png', 'related-content-by-plugz/plugz.php'); ?>'); background-repeat:no-repeat; padding-left:20px;">
            <?php
            $checked = (1) ? ' checked="checked"' : ''; //(isset($plugz['autopost'])) && $plugz['autopost'] == 1
            if (isset($plugz_post['autopost']) && $plugz_post['autopost'] == '0') {
                $checked = '';
            }
            ?>
            Post to Plugz: <b><input type="checkbox" name="_plugz_post"<?= $checked; ?> value="1" /></b></span>
    </div>
    <?php
}

function plugz_post_header_columns($columns) {
    if (!isset($columns['_plugz_posted'])) {
        $columns['_plugz_posted'] = '<img src="' . plugins_url('/images/logo16.png', 'related-content-by-plugz/plugz.php') . '" title="Posted to Plugz" alt="Plugz" />';
    }
    return $columns;
}

function plugz_post_data_row($column_name, $post_id) {
    switch ($column_name) {
        case '_plugz_posted':
            $plugz_post = get_post_meta($post_id, '_plugz_posted', TRUE);
            if (isset($plugz_post) && $plugz_post == '1') {
                echo '<img src="' . plugins_url('/images/tick16.png', 'related-content-by-plugz/plugz.php') . '" title="Posted to Plugz" alt="Yes" />';
            } else {
                echo '<img src="' . plugins_url('/images/delete16.png', 'related-content-by-plugz/plugz.php') . '" title="Not yet posted to Plugz" alt="No" />';
            }
            break;
        default:
            break;
    }
}
