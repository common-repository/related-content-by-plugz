<?php
/**
 * plugz Admin Settings
 *
 * Admin settings for all plugz plugins
 *
 * @package plugz
 * @subpackage Functions
 */

$widgetList = plugz_request(array('action' => 'getWidgets'));

if (isset($widgetList['error'])) {
    $errors = '<div class="error fade"><p><strong>' . $widgetList['error'] . '</strong></div>';
}
 
?>

<div class="wrap">
    <?php if ($plugzConnected && empty($errors)) : ?>
        <h2>Widgets <a class="add-new-h2" href="admin.php?page=plugz/widgets&create=1">Add New</a></h2>
        <?php if (is_array($widgetList) && count($widgetList)) : ?>
            <form id="widget_form" method="post" action="">
            <table class="wp-list-table widefat fixed posts">
                <thead>
                    <tr>
                        <th style="" class="manage-column column-title" id="title" scope="col"><span>Title</span></th>
                        <th style="" class="manage-column column-placement" id="placement" scope="col">Placement</th>
                        <th style="" class="manage-column column-shortcode" id="shortcode" scope="col">Shortcode</th>
                        <th style="" class="manage-column column-categories" id="categories" scope="col">Categories</th>
                        <th style="" class="manage-column column-tags" id="tags" scope="col">Tags</th>
                        <th style="" class="manage-column column-date" id="date" scope="col"><span>Date</span></th>
                    </tr>
                </thead>

                <tfoot>
                    <tr>
                        <th style="" class="manage-column column-title" id="title" scope="col"><span>Title</span></th>
                        <th style="" class="manage-column column-placement" id="placement" scope="col">Placement</th>
                        <th style="" class="manage-column column-shortcode" id="shortcode" scope="col">Shortcode</th>
                        <th style="" class="manage-column column-categories" id="categories" scope="col">Categories</th>
                        <th style="" class="manage-column column-tags" id="tags" scope="col">Tags</th>
                        <th style="" class="manage-column column-date" id="date" scope="col"><span>Date</span></th>
                    </tr>
                </tfoot>

                <tbody id="the-list">
                    <?php $c = false;
                    foreach ($widgetList as $widget) :
                        ?>
                        <tr data-widget-id="<?php echo $widget['id'] ?>" class="widget_settings widget-<?php echo $widget['id'] ?> <?php echo (($c = !$c) ? ' alternate' : '') ?>" id="widget-<?php echo $widget['id'] ?>">
                            <td class="post-title page-title column-title"><strong><a title="Edit “<?php echo $widget['name'] ?>”" href="admin.php?page=plugz/widgets&amp;edit=<?php echo $widget['id'] ?>" class="row-title"><?php echo $widget['name'] ?></a></strong>
                                <div class="locked-info"><span class="locked-avatar"></span> <span class="locked-text"></span></div>
                                <div class="row-actions">
                                    <span class="edit"><a title="Edit this item" href="admin.php?page=plugz/widgets&amp;edit=<?php echo $widget['id'] ?>">Edit</a> | </span>
                                    <span class="trash"><a href="admin.php?page=plugz/widgets&amp;delete=<?php echo $widget['id'] ?>&amp;noheader=true" onclick="return confirm('Are you sure, that you want to delete this widget?')" title="Move this item to the Trash" class="submitdelete">Trash</a></span>
                                </div>
                            </td>
                            <td class="placement column-placement">
                                <select class="widget_placement" name="widget_placement[<?php echo $widget['id'] ?>]">
                                    <option<?php echo isset($widgetPlacements[$widget['id']]['placement']) && $widgetPlacements[$widget['id']]['placement'] == 'on all posts' ? ' selected="selected"' : '' ?> value="on all posts">on all posts:</option>
                                    <option<?php echo isset($widgetPlacements[$widget['id']]['placement']) && $widgetPlacements[$widget['id']]['placement'] == 'on all pages' ? ' selected="selected"' : '' ?> value="on all pages">on all pages:</option>
                                    <option<?php echo isset($widgetPlacements[$widget['id']]['placement']) && $widgetPlacements[$widget['id']]['placement'] == 'on all posts and pages' ? ' selected="selected"' : '' ?> value="on all posts and pages">on all posts and pages:</option>
                                    <option<?php echo isset($widgetPlacements[$widget['id']]['placement']) && $widgetPlacements[$widget['id']]['placement'] == 'on single post' ? ' selected="selected"' : '' ?> value="on single post">on single post:</option>
                                    <option<?php echo isset($widgetPlacements[$widget['id']]['placement']) && $widgetPlacements[$widget['id']]['placement'] == 'on single page' ? ' selected="selected"' : '' ?> value="on single page">on single page:</option>
                                    <option<?php echo isset($widgetPlacements[$widget['id']]['placement']) && $widgetPlacements[$widget['id']]['placement'] == 'in sidebar' ? ' selected="selected"' : '' ?> value="in sidebar">in sidebar</option>
                                </select>
                                <div class="in_sidebar_notice" id="in_sidebar_notice_<?php echo $widget['id'] ?>">
                                    In order to place your widget to a sidebar: <ol><li>go to your <a href="/wp-admin/widgets.php">WP's Widget menu</a> and drag <b>Plugz Widget</b> to one of your sidebars and</li><li>select the widget you wish to add to the sidebar.</li></ol>
                                </div>
                                <div class="widget_page_id" id="widget_page_id_<?php echo $widget['id'] ?>">
                                    <select name="widget_page_id[<?php echo $widget['id'] ?>]">
                                    <?php $pages_array = get_pages( array() ); foreach ($pages_array as $pages_item) : ?> 
                                        <option<?php echo isset($widgetPlacements[$widget['id']]['page_id']) && $widgetPlacements[$widget['id']]['page_id'] == $pages_item->ID ? ' selected="selected"' : '' ?> value="<?php echo $pages_item->ID ?>"><?php echo $pages_item->post_title ?></option>
                                    <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="widget_post_id" id="widget_post_id_<?php echo $widget['id'] ?>">
                                    <select name="widget_post_id[<?php echo $widget['id'] ?>]">
                                    <?php $posts_array = get_posts( array('posts_per_page' => 20) ); foreach ($posts_array as $posts_item) : ?> 
                                        <option<?php echo isset($widgetPlacements[$widget['id']]['post_id']) && $widgetPlacements[$widget['id']]['post_id'] == $posts_item->ID ? ' selected="selected"' : '' ?> value="<?php echo $posts_item->ID ?>"><?php echo $posts_item->post_title ?></option>
                                    <?php endforeach; ?>
                                    </select>
                                </div>                
                                <div class="widget_after_paragraph" id="widget_after_paragraph_<?php echo $widget['id'] ?>">
                                    <select class="widget_after_paragraph_select" name="widget_placement_paragraph[<?php echo $widget['id'] ?>]">
                                        <option<?php echo isset($widgetPlacements[$widget['id']]['paragraph']) && $widgetPlacements[$widget['id']]['paragraph'] == 'bottom' ? ' selected="selected"' : '' ?> value="bottom">bottom</option>
                                        <option<?php echo isset($widgetPlacements[$widget['id']]['paragraph']) && $widgetPlacements[$widget['id']]['paragraph'] == '1' ? ' selected="selected"' : '' ?> value="1">after 1st paragraph</option>
                                        <option<?php echo isset($widgetPlacements[$widget['id']]['paragraph']) && $widgetPlacements[$widget['id']]['paragraph'] == '2' ? ' selected="selected"' : '' ?> value="2">after 2nd paragraph</option>
                                        <option<?php echo isset($widgetPlacements[$widget['id']]['paragraph']) && $widgetPlacements[$widget['id']]['paragraph'] == '3' ? ' selected="selected"' : '' ?> value="3">after 3rd paragraph</option>
                                        <option<?php echo isset($widgetPlacements[$widget['id']]['paragraph']) && $widgetPlacements[$widget['id']]['paragraph'] == '4' ? ' selected="selected"' : '' ?> value="4">after 4th paragraph</option>
                                        <option<?php echo isset($widgetPlacements[$widget['id']]['paragraph']) && $widgetPlacements[$widget['id']]['paragraph'] == '5' ? ' selected="selected"' : '' ?> value="5">after 5th paragraph</option>
                                        <option<?php echo isset($widgetPlacements[$widget['id']]['paragraph']) && $widgetPlacements[$widget['id']]['paragraph'] == 'top' ? ' selected="selected"' : '' ?> value="top">top</option>
                                    </select>
                                </div>
                                <div class="ajaxloader"></div>
                            </td>
                            <td class="shortcode column-shortcode" title="Copy and paste this code into your post editor to add widget">
                                [plugz id=<?php echo $widget['id'] ?>]
                            </td>
                            <td class="categories column-categories">
            <?php echo json_decode($widget['wdata'])->tags0 ?>
                            </td>
                            <td class="tags column-tags">
            <?php echo json_decode($widget['wdata'])->tags1 ?>
                            </td>
                            <td class="date column-date"><abbr title="<?php echo date('Y/d/m h:i:s A', strtotime($widget['added_date'])) ?>"><?php echo human_time_diff(strtotime($widget['added_date'])) ?></abbr></td>
                        </tr>
        <?php endforeach; ?>
                </tbody>
            </table>
            </form>
            <script>
                var loaderImg = '<?php echo PLUGZ_IMAGE_DIR ?>/ajax-loader.gif';
                var ajaxPath = '<?php echo $_SERVER['REQUEST_URI'].'&noheader=1' ?>';
            </script>
            <script src="<?php echo PLUGZ_JS_DIR ?>/widgetPosition.js" type="text/javascript"></script>
        <?php else : ?>
    <div class="metabox-holder" id="poststuff">
        <div id="post-body">
            <div id="post-body-content">
                <div class="postbox" id="plugz-about">
                    <h3 class="title">Messages:</h3>
                    <div class="inside">
                        <div>You don't have widgets yet. Click <a href="admin.php?page=plugz/widgets&create=1">Add New</a> to create one.</div>
                    </div>
            </div>
            </div>
        </div>
    </div>
        <?php endif; ?>
    <?php else : ?>
        <h2>Widgets</h2>
        <?php echo $errors ?>
    <?php endif; ?>
</div>
