<?php
/**
 * plugz Admin Settings
 *
 * Admin settings for all plugz plugins
 *
 * @package plugz
 * @subpackage Functions
 */
?>

<style type="text/css">
    #plugz-help li {
        list-style-type: square;
        margin-left:20px;
    }
</style>

<div class="wrap plugz-page" id="plugz-dashboard" style=
     "margin: 10px 15px 0 5px">
    <h2 class="plugz-title">Plugz Help</h2>

    <div class="metabox-holder" id="poststuff">
        <div id="post-body">
            <div id="post-body-content">
                <div class="postbox" id="plugz-about">
                    <h3 class="title">About</h3>

                    <div class="inside">
                        <p>Related Content by Plugz version <?php echo $plugin_version ?></p>
                        <p>Get Premium quality traffic with Plugz. Display related posts on your blog. Boost your site with new visitors or earn money with sponsored content.</p>
                        <p>Plugz widget displays content related posts on your blog. We are analyzing your pages continously and promote your articles on thousands of related partner sites in our network.</p>
                    </div>
                </div><!-- #plugz-about -->
                <div class="postbox" id="plugz-help">
                    <h3 class="title">Key Features</h3>

                    <div class="inside">
                        <ul>
                        <li>Unique algorithm displays related posts from other WordPress users and from any other content management systems or displays related content from across your site, depends on your strategy</li>
                        <li>customize thumbnail layout</li>
                        <li>monetize your traffic with high quality sponsored content</li>
                        <li>detailed traffic report with geo stat and return ratio</li>
                        <li>detailed income report</li>
                        <li>geo filter</li>
                        <li>niche/hashtag filter </li>
                        <li>search engine traffic from landing page</li>
                        <li>extra money via affiliate system</li>
                        <li>24/7 support </li>
                        </ul>

                        <p>All features are optional! Choose your ABC strategy, set your widget by that! </p>

                        <ul>
                            <li>A. Keep your visitors on your pages, increase your pageview</li>
                            <li>B. Drive new visitors to your blog from the entire network via other related pages</li>
                            <li>C. Earn premium revenue rates while displaying contextual ads and buy traffic from your revenue</li>
                        </ul>

                        <p>System is free to use and accepts all kind of blogs. It runs on our servers, not cause additional load on your system.</p>

                        <p>Find more at <a href="<?php echo (APPLICATION_ENV == 'development' ? 'http://' : 'https://') ?>www.plugz.co">Plugz.co</a></p>
                    </div><!-- .inside -->
                </div><!-- #plugz-help -->
            </div><!-- #post-body-content -->
        </div><!-- #post-body -->
        <br class="clear">
    </div><!-- #side-sortables -->
</div><!-- #side-info-column -->    
