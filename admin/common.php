<?php

/**
 * plugz Common functions
 *
 * Common functions
 *
 * @package plugz
 * @subpackage Functions
 */
function plugz_request($params) {
    $params['format'] = 'json';

    if (isset($params['action'])) {
        $action = $params['action'];
    } else {
        $action = '';
    }

    $plugz = get_option('plugz-settings');
    $params['email'] = (!empty($plugz['user']) ? $plugz['user'] : '');
    $params['password'] = (!empty($plugz['password']) ? $plugz['password'] : '');
    $params['affid'] = (int) @$plugz['affid'];
    $params['apiKey'] = get_option('plugz-api-key', '');
    $params['frid'] = get_option('plugz-frid');
    $params['domain'] = plugz_domain_from_url(get_option('siteurl'));
    $params['origin'] = $params['domain'];

    if (function_exists('curl_init')) {
        $plugin_version = $_ENV['plugz_author_plugindata']['Version'];

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_HEADER, TRUE);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($curl, CURLOPT_POST, TRUE);
        curl_setopt($curl, CURLOPT_USERAGENT, 'Plugz Plugin ' . $plugin_version . ' (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.13) Gecko/20080311 Firefox/2.0.0.13');
        curl_setopt($curl, CURLOPT_URL, 'https://' . API_DOMAIN . '/wpapi/v1/' . $action);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $params);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($curl, CURLOPT_SSLVERSION, 0);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
        $result = $headers = curl_exec($curl);
        $result = explode("\r\n\r\n", $result, 3);
        $result = (array) json_decode($result[2], TRUE);
        if (preg_match('/x-plugz-status: ([0-9]{3})/i', $headers, $match)) {
            $result['status'] = $match[1];
        }

        return $result;
    } elseif (function_exists('file_get_contents')) {
        $postdata = http_build_query($params);
        $opts = array('http' =>
            array(
                'method'  => 'POST',
                'header'  => 'Content-type: application/x-www-form-urlencoded',
                'content' => $postdata
            )
        );
        $context  = stream_context_create($opts);

        $result = file_get_contents('https://' . API_DOMAIN . '/wpapi/v1/' . $action, false, $context);
        $result = json_decode($result, true);

        if (!is_array($result)) {
        return array($result);
        }
        if (!empty($result['error'])) {
            return array('status' => '400', 'message' => $tmpResult['error']);
        }

        return $result;
    } else {
        return array('status' => '400', 'message' => 'CURL is required for this plugin to work');
    }
}


function plugz_get_image_meta($url) {
    $dimensions = null;
    $dimensions = @getimagesize($url);
    if ($dimensions === FALSE) {
        $dimensions = array(0, 0);
    }

    return $dimensions;
}

function plugz_connected() {
    $result = plugz_request(array('action' => 'hello'));

    if (isset($result[0]) && $result[0] == 'success') {
        return true;
    } else {
        return false;
    }
}

function plugz_reindex($limit = -1, $offset = 0) {
    if ($limit == -1) {
        $result = plugz_request(array('action' => 'reindex', 'posts' => http_build_query(array())));
    }

    $args = array(
        'posts_per_page' => $limit,
        'offset' => $offset,
        'category' => '',
        'orderby' => 'post_date',
        'order' => 'DESC',
        'include' => '',
        'exclude' => '',
        'meta_key' => '',
        'meta_value' => '',
        'post_type' => 'post',
        'post_mime_type' => '',
        'post_parent' => '',
        'post_status' => 'publish',
        'suppress_filters' => true);

    $posts_array = get_posts($args);

    $plugz = get_option('plugz-settings');

    $imageUrls = array();
    $data = array();

    if (count($posts_array)) {
        foreach ($posts_array as $post) {
            $image = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID), 'single-post-thumbnail');

            if (!empty($image[0])) {
                $imageUrls[$post->ID] = $image[0];
            } else {
                $dom = new domDocument;
                $dom->loadHTML($post->post_content);
                $dom->preserveWhiteSpace = false;
                $imagesDom = $dom->getElementsByTagName('img');

                $images = array();
                foreach ($imagesDom as $node) {
                    $images[] = $node;
                    break;
                }

                if (isset($images[0])) {
                    $imageUrls[$post->ID] = $images[0]->getAttribute('src');
                }
            }

            if (isset($imageUrls[$post->ID])) {
                $meta = plugz_get_image_meta($imageUrls[$post->ID]);
                add_post_meta($post->ID, '_plugz_posted', 1, true) || update_post_meta($post->ID, '_plugz_posted', true);

                $tags = wp_get_post_tags($post->ID);
                $plug['tags'] = array();

                foreach ($tags as $tag) {
                    $plug['tags'][] = $tag->name;
                }

                $plugz_post = get_post_meta($post->ID, '_plugz', TRUE);
                $plugz_post_content = get_post($post->ID);

                $plug['description'] = $plugz_post_content->post_excerpt;

                if (!isset($plugz_post['categories'])) {
                    $plugz_post['categories'] = '';
                }

                $plugz_post['categories'] = explode(',', $plugz_post['categories']);

                setup_postdata($post);

                $permalink = post_permalink($post->ID);
                if (empty($permalink)) {
                    $permalink = $post->guid;
                }

                $data[$post->ID] = array(
                    'title' => $post->post_title,
                    'name' => $post->post_name,
                    'url' => $permalink,
                    'descr' => $plug['description'],
                    'image' => $imageUrls[$post->ID],
                    'width' => @$meta[0],
                    'height' => @$meta[1],
                    'categories' => implode(',', $plugz_post['categories']),
                    'tags' => implode(',', $plug['tags']),
                    'posttype' => (isset($plugz['website_type']) && $plugz['website_type'] == 'M' ? 'TUBE' : 'GALLERY'),
                    'models' => '',
                    'action' => 'INSERT'
                );
            }
            wp_reset_postdata();
        }

        if ($limit > -1) {
            update_option('plugz-start-index-schedule', 1);
            update_option('plugz-index-schedule-limit', 10);
            update_option('plugz-index-schedule-offet', $offset + $limit);
            update_option('plugz-has-been-indexed', 0);
        }
    } else {
        update_option('plugz-start-index-schedule', 0);
        update_option('plugz-index-schedule-limit', 10);
        update_option('plugz-index-schedule-offet', 0);
        update_option('plugz-has-been-indexed', 1);
    }

    if (count($data)) {
        $result = plugz_request(array('action' => 'updatePost', 'posts' => http_build_query($data)));
        return true;
    } else {
        return true;
    }
}
