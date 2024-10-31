<?php
/**
 * plugz Admin Settings
 *
 * Admin settings for all plugz plugins
 *
 * @package plugz
 * @subpackage Functions
 */

$languages = array(
    'aa' => 'Afar',
    'ab' => 'Abkhaz',
    'ae' => 'Avestan',
    'af' => 'Afrikaans',
    'ak' => 'Akan',
    'am' => 'Amharic',
    'an' => 'Aragonese',
    'ar' => 'Arabic',
    'as' => 'Assamese',
    'av' => 'Avaric',
    'ay' => 'Aymara',
    'az' => 'Azerbaijani',
    'ba' => 'Bashkir',
    'be' => 'Belarusian',
    'bg' => 'Bulgarian',
    'bh' => 'Bihari',
    'bi' => 'Bislama',
    'bm' => 'Bambara',
    'bn' => 'Bengali',
    'bo' => 'Tibetan Standard, Tibetan, Central',
    'br' => 'Breton',
    'bs' => 'Bosnian',
    'ca' => 'Catalan; Valencian',
    'ce' => 'Chechen',
    'ch' => 'Chamorro',
    'co' => 'Corsican',
    'cr' => 'Cree',
    'cs' => 'Czech',
    'cu' => 'Old Church Slavonic, Church Slavic, Church Slavonic, Old Bulgarian, Old Slavonic',
    'cv' => 'Chuvash',
    'cy' => 'Welsh',
    'da' => 'Danish',
    'de' => 'German',
    'dv' => 'Divehi; Dhivehi; Maldivian;',
    'dz' => 'Dzongkha',
    'ee' => 'Ewe',
    'el' => 'Greek, Modern',
    'en' => 'English',
    'eo' => 'Esperanto',
    'es' => 'Spanish; Castilian',
    'et' => 'Estonian',
    'eu' => 'Basque',
    'fa' => 'Persian',
    'ff' => 'Fula; Fulah; Pulaar; Pular',
    'fi' => 'Finnish',
    'fj' => 'Fijian',
    'fo' => 'Faroese',
    'fr' => 'French',
    'fy' => 'Western Frisian',
    'ga' => 'Irish',
    'gd' => 'Scottish Gaelic; Gaelic',
    'gl' => 'Galician',
    'gn' => 'Guarani',
    'gu' => 'Gujarati',
    'gv' => 'Manx',
    'ha' => 'Hausa',
    'he' => 'Hebrew (modern)',
    'hi' => 'Hindi',
    'ho' => 'Hiri Motu',
    'hr' => 'Croatian',
    'ht' => 'Haitian; Haitian Creole',
    'hu' => 'Hungarian',
    'hy' => 'Armenian',
    'hz' => 'Herero',
    'ia' => 'Interlingua',
    'id' => 'Indonesian',
    'ie' => 'Interlingue',
    'ig' => 'Igbo',
    'ii' => 'Nuosu',
    'ik' => 'Inupiaq',
    'io' => 'Ido',
    'is' => 'Icelandic',
    'it' => 'Italian',
    'iu' => 'Inuktitut',
    'ja' => 'Japanese (ja)',
    'jv' => 'Javanese (jv)',
    'ka' => 'Georgian',
    'kg' => 'Kongo',
    'ki' => 'Kikuyu, Gikuyu',
    'kj' => 'Kwanyama, Kuanyama',
    'kk' => 'Kazakh',
    'kl' => 'Kalaallisut, Greenlandic',
    'km' => 'Khmer',
    'kn' => 'Kannada',
    'ko' => 'Korean',
    'kr' => 'Kanuri',
    'ks' => 'Kashmiri',
    'ku' => 'Kurdish',
    'kv' => 'Komi',
    'kw' => 'Cornish',
    'ky' => 'Kirghiz, Kyrgyz',
    'la' => 'Latin',
    'lb' => 'Luxembourgish, Letzeburgesch',
    'lg' => 'Luganda',
    'li' => 'Limburgish, Limburgan, Limburger',
    'ln' => 'Lingala',
    'lo' => 'Lao',
    'lt' => 'Lithuanian',
    'lu' => 'Luba-Katanga',
    'lv' => 'Latvian',
    'mg' => 'Malagasy',
    'mh' => 'Marshallese',
    'mi' => 'Maori',
    'mk' => 'Macedonian',
    'ml' => 'Malayalam',
    'mn' => 'Mongolian',
    'mr' => 'Marathi',
    'ms' => 'Malay',
    'mt' => 'Maltese',
    'my' => 'Burmese',
    'na' => 'Nauru',
    'nb' => 'Norwegian Bokmål',
    'nd' => 'North Ndebele',
    'ne' => 'Nepali',
    'ng' => 'Ndonga',
    'nl' => 'Dutch',
    'nn' => 'Norwegian Nynorsk',
    'no' => 'Norwegian',
    'nr' => 'South Ndebele',
    'nv' => 'Navajo, Navaho',
    'ny' => 'Chichewa; Chewa; Nyanja',
    'oc' => 'Occitan',
    'oj' => 'Ojibwe, Ojibwa',
    'om' => 'Oromo',
    'or' => 'Oriya',
    'os' => 'Ossetian, Ossetic',
    'pa' => 'Panjabi, Punjabi',
    'pi' => 'Pali',
    'pl' => 'Polish',
    'ps' => 'Pashto, Pushto',
    'pt' => 'Portuguese',
    'qu' => 'Quechua',
    'rm' => 'Romansh',
    'rn' => 'Kirundi',
    'ro' => 'Romanian, Moldavian, Moldovan',
    'ru' => 'Russian',
    'rw' => 'Kinyarwanda',
    'sa' => 'Sanskrit',
    'sc' => 'Sardinian',
    'sd' => 'Sindhi',
    'se' => 'Northern Sami',
    'sg' => 'Sango',
    'si' => 'Sinhala, Sinhalese',
    'sk' => 'Slovak',
    'sl' => 'Slovene',
    'sm' => 'Samoan',
    'sn' => 'Shona',
    'so' => 'Somali',
    'sq' => 'Albanian',
    'sr' => 'Serbian',
    'ss' => 'Swati',
    'st' => 'Southern Sotho',
    'su' => 'Sundanese',
    'sv' => 'Swedish',
    'sw' => 'Swahili',
    'ta' => 'Tamil',
    'te' => 'Telugu',
    'tg' => 'Tajik',
    'th' => 'Thai',
    'ti' => 'Tigrinya',
    'tk' => 'Turkmen',
    'tl' => 'Tagalog',
    'tn' => 'Tswana',
    'to' => 'Tonga (Tonga Islands)',
    'tr' => 'Turkish',
    'ts' => 'Tsonga',
    'tt' => 'Tatar',
    'tw' => 'Twi',
    'ty' => 'Tahitian',
    'ug' => 'Uighur, Uyghur',
    'uk' => 'Ukrainian',
    'ur' => 'Urdu',
    'uz' => 'Uzbek',
    've' => 'Venda',
    'vi' => 'Vietnamese',
    'vo' => 'Volapük',
    'wa' => 'Walloon',
    'wo' => 'Wolof',
    'xh' => 'Xhosa',
    'yi' => 'Yiddish',
    'yo' => 'Yoruba',
    'za' => 'Zhuang, Chuang',
    'zh' => 'Chinese',
    'zu' => 'Zulu',
);

asort($languages);

?>

<style type="text/css">
    .metabox-holder .postbox > form > h3 {
        font-size: 14px;
        padding: 8px 12px;
        margin: 0;
        line-height: 1.4;
    }
</style>

<div class="wrap plugz-page" id="plugz-dashboard" style=
     "margin: 10px 15px 0 5px">
    <h2 class="plugz-title">Plugz Settings</h2>

    <?php
    if ($plugzConnected) :

        if (isset($_REQUEST['settings-updated']) && $_REQUEST['settings-updated']) {
            $errors = '';
            foreach (get_settings_errors() as $message) {
                if ($message['type'] == 'error') {
                    $errors .= '<div class="error fade">' . $message['message'] . '</div>';
                }
            }

            if ($errors) {
                echo $errors;
            } else {
                echo '<div id="message" class="updated fade"><p><strong>Settings saved.</strong></p></div>';
            }
        }
        ?>
        <div class="metabox-holder has-right-sidebar" id="poststuff">
            <div class="inner-sidebar" id="side-info-column">
                <div class="meta-box-sortables ui-sortable" id=
                     "side-sortables">
                    <!-- Re-Index -->

                    <div class="postbox" id="plugz_reindex">
                        <h3 class="hndle"><span>Re-Index Your
                                Site:</span></h3>

                        <div class="inside">
                            <p>Use the button below to have plugz
                                reindex your website.</p>

                            <form action="" method="post">
                                <input class="reindex" id=
                                       "plugz_reindex_button" name="reindex"
                                       type="submit" value="Re-Index Website">
                                <script type="text/javascript">
                                    jQuery(function($) {
    <?php if ($status['status'] == '200') : ?>
                                            if (0 && $('#indexresponse').text().indexOf("Indexing complete") == -1) {
                                                $("#plugz_reindex_button").addClass('disabled').attr('disabled', 'disabled');
                                            } else {
                                                $("#plugz_reindex_button").removeClass('disabled').removeAttr('disabled');
                                            }
    <?php else : ?>
                                            $("#plugz_reindex_button").addClass('disabled').attr('disabled', 'disabled');
    <?php endif; ?>

                                        $('#plugz-rating').change(function() {
                                            if ($('#plugz-rating').val() == 'mainstream') {
                                                $('#plugz-website_type').closest('tr').hide();
                                                $('#plugz-sexual_orientation').closest('tr').hide();
                                                $('#plugz-content_type').closest('tr').hide();
                                                $('#plugz-main_category_adult_gay').closest('tr').hide();
                                                $('#plugz-main_category_adult_straight').closest('tr').hide();
                                                $('#plugz-main_category_mainstream').closest('tr').show();
                                            } else {
                                                $('#plugz-website_type').closest('tr').show();
                                                $('#plugz-sexual_orientation').closest('tr').show();
                                                $('#plugz-content_type').closest('tr').show();
                                                $('#plugz-main_category_mainstream').closest('tr').hide();
                                                if ($('#plugz-sexual_orientation').val() == 'straight') {
                                                    $('#plugz-main_category_adult_gay').closest('tr').hide();
                                                    $('#plugz-main_category_adult_straight').closest('tr').show();
                                                } else {
                                                    $('#plugz-main_category_adult_gay').closest('tr').show();
                                                    $('#plugz-main_category_adult_straight').closest('tr').hide();
                                                }
                                            }
                                        });
                                        $('#plugz-sexual_orientation').change(function() {
                                            $('#plugz-rating').trigger('change');
                                        });
                                        $('#plugz-rating').trigger('change');
                                    });
                                </script>
                            </form>

                            <p><strong>IMPORTANT: All plugz content
                                    will be temporarily removed from your
                                    website while we
                                    reindex.<br></strong></p><strong>Only use
                                when neccessary</strong><br>
                        </div><!-- .inside -->
                    </div><!-- #plugz_reindex -->
                    <?php if ($status['status'] == '200') : ?>

                        <div class="postbox" id="plugz_reindex">
                            <h3 class="hndle"><span>External links to your Plugz settings:</span></h3>

                            <div class="inside">
                                <ul>
                                    <li><a target="plugz" href="admin.php?page=plugz/settings&open=dashboard&noheader=true">Dashboard</a></li>
                                    <li><a target="plugz" href="admin.php?page=plugz/settings&open=edit-website&noheader=true">Edit Website</a></li>
                                    <li><a target="plugz" href="admin.php?page=plugz/settings&open=manage-widgets&noheader=true">Edit Widgets</a></li>
                                    <li><a target="plugz" href="admin.php?page=plugz/settings&open=manage-plugs&noheader=true">Manage Plugs</a></li>
                                    <li><a target="plugz" href="admin.php?page=plugz/settings&open=stat-traffic&noheader=true">Traffic</a></li>
                                    <li><a target="plugz" href="admin.php?page=plugz/settings&open=stat-income&noheader=true">Income</a></li>
                                    <li><a target="plugz" href="admin.php?page=plugz/settings&open=edit-profile&noheader=true">Edit Profile</a></li>
                                    <li><a target="plugz" href="admin.php?page=plugz/settings&open=support&noheader=true">Support</a></li>
                                </ul>
                            </div><!-- .inside -->
                        </div><!-- #plugz_reindex -->
                    <?php endif; ?>
                </div><!-- #side-sortables -->
            </div><!-- #side-info-column -->

            <div id="post-body">
                <div id="post-body-content">
                    <!-- Message -->

                    <div class="postbox" id="plugz-messages">
                        <h3 class="hndle"><span>Messages:</span></h3>

                        <ul class="inside">
                            <!-- Hook for PRIORITY admin messages from all plugz plugins -->
                            <!-- Show index status -->

                            <li class="nolist">
                                <div class="info" id="indexcheck">
                                    <div id="indexresponse">
                                        <?php if (!$missingCredentials && $status['status'] == '200') : ?>
                                            <?php if ($isIndexed) : ?>Indexing complete: Plugz plugin is ready to go and should now be appearing on your site.<?php else : ?>Indexing is in progress... <?php endif; ?>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </li><!-- Show service status -->

                            <li>
                                <div class="info" id="servicecheck">
                                    Service Status: <?php if ($plugzConnected) : ?><?php if ($status['status'] == '200') : ?>Connected to the API. <?php if ($widgetCount == 0) : ?><p style="color:#f22">Go to <a href="/wp-admin/admin.php?page=plugz/widgets">Widget menu</a> and add a widget to your pages.</p><?php endif; ?><?php else : ?><?php echo $status['message'] ?><?php endif; ?><?php else : ?>Unable to connect to Plugz API at the moment.<?php endif; ?>
                                </div>
                            </li>
                            <!-- Hook for admin messages from all plugz plugins -->

                            <li id="adverify"></li>

                            <li>
                                <div class="info" id="extra_message">
                                </div><!-- #extra_message -->
                            </li>
                        </ul><!-- .inside -->
                    </div><!-- #plugz-messages -->

                    <div class="postbox" id="plugz-admin-settings">
                        <h3 class="title">Credentials</h3>

                        <form action="options.php" method="post">
                            <?php settings_fields('plugz-settings'); ?>
                            <?php do_settings_fields('plugz-settings', ''); ?>
                            <div class="inside" style="margin-left: 2em">
                                <div>If you have already signed up to <a target="_blank" href="https://www.plugz.co/">www.plugz.co</a>, then enter your credentials here.</div>

                                <table class="form-table">
                                    <tbody>
                                        <tr valign="top">
                                            <th scope="row"><label for="plugz-user">Plugz Email</label></th>
                                            <td><input type="hidden" id="plugz_affid" name="plugz-settings[affid]"><input <?php if ($plugzConnected && $status['status'] == '200') : ?>disabled="disabled" <?php endif; ?>type="text" placeholder="your@email.com" class="regular-text" value="<?= @$plugz['user']; ?>" id="plugz-user" name="<?php if ($plugzConnected && $status['status'] == '200') : ?>disabled-user<?php else : ?>plugz-settings[user]<?php endif; ?>" style="width:320px;">
                                                <?php if ($plugzConnected && $status['status'] == '200') : ?><input type="hidden" value="<?= $plugz['user']; ?>" name="plugz-settings[user]"><?php endif; ?>
                                            </td>
                                        </tr>
                                        <tr valign="top">
                                            <th scope="row"><label for="plugz-password">Plugz Password</label></th>
                                            <td><input autocomplete="off" type="password" placeholder="choose a password" class="regular-text" value="<?= @$plugz['password']; ?>" id="plugz-password" name="plugz-settings[password]" style="width:320px;">
                                            </td>
                                        </tr>
                                        <tr valign="top">
                                            <th scope="row"><label for="plugz-api_status">API Status</label></th>
                                            <td>
                                                <p class="description" style="color:#<?= $status['status'] == '200' ? '090' : 'C30'; ?>"><?= $status['message']; ?>
                                                    <?php if ($status['status'] == '200') : ?> <a href="/wp-admin/admin.php?page=plugz/settings&logout=1&noheader=true">Logout from Plugz API</a><?php endif; ?>
                                                </p>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                                <script>
                                if(window.addEventListener){
                                    window.addEventListener("message", function(res){
                                        if (res.origin == 'https://www.plugz.co') {
                                            window.document.getElementById("plugz_affid").value = res.data;
                                        }
                                    }, false); 
                                } else if (window.attachEvent){
                                    window.attachEvent("message", function(res){
                                        if (res.origin == 'https://www.plugz.co') {
                                            window.document.getElementById("plugz_affid").value = res.data;
                                        }
                                    }, false);
                                }
                                </script>
                                <iframe border="0" style="display:none;width:0;height:0;border:none" height="0" width="0" src="https://www.plugz.co/aff.php" seamless="true"></iframe>
                            </div><!-- .inside -->
                            <hr>
                            <h3 class="title">Website</h3>
                            <div class="inside" style="margin-left: 2em">
                                <table class="form-table">
                                    <tbody>
                                        <tr valign="top">
                                            <th scope="row"><label for="plugz-rating">Website Rating</label></th>
                                            <td><select <?php if (!$plugzConnected || ($plugzConnected && $status['status'] == '200')) : ?>disabled="disabled" <?php endif; ?>id="plugz-rating" name="<?php if ($plugzConnected && $status['status'] == '200') : ?>disabled-rating<?php else : ?>plugz-settings[rating]<?php endif; ?>" style="width:320px;">
                                                    <option <?php echo ($plugz['rating'] == 'mainstream') ? 'selected="selected"' : '' ?> value="mainstream">Mainstream</option>
                                                    <option <?php echo ($plugz['rating'] == 'nsfw') ? 'selected="selected"' : '' ?> value="nsfw">NSFW</option>
                                                </select>
                                                <?php if (!$plugzConnected || ($plugzConnected && $status['status'] == '200')) : ?><input type="hidden" value="<?= $plugz['rating']; ?>" name="plugz-settings[rating]"><?php endif; ?>
                                            </td>
                                        </tr>
                                        <tr valign="top">
                                            <th scope="row"><label for="plugz-sexual_orientation">Sexual Orientation</label></th>
                                            <td><select id="plugz-sexual_orientation" name="plugz-settings[sexual_orientation]" style="width:320px;">
                                                    <option <?php echo ($plugz['sexual_orientation'] == 'straight') ? 'selected="selected"' : '' ?> value="straight">Straight</option>
                                                    <option <?php echo ($plugz['sexual_orientation'] == 'gay') ? 'selected="selected"' : '' ?> value="gay">Gay</option>
                                                </select>
                                            </td>
                                        </tr>
                                        <tr valign="top">
                                            <th scope="row"><label for="plugz-website_type">Website Type</label></th>
                                            <td><select id="plugz-website_type" name="plugz-settings[website_type]" style="width:320px;">
                                                    <option <?php echo ($plugz['website_type'] == 'G') ? 'selected="selected"' : '' ?> value="G">Gallery</option>
                                                    <option <?php echo ($plugz['website_type'] == 'M') ? 'selected="selected"' : '' ?> value="M">Tube</option>
                                                </select>
                                            </td>
                                        </tr>
                                        <tr valign="top">
                                            <th scope="row"><label for="plugz-website_language">Website Language</label></th>
                                            <td><select id="plugz-website_type" name="plugz-settings[website_language]" style="width:320px;">
                                                    <?php foreach ($languages as $languageCode => $language) : ?>
                                                    <option <?php echo ((!isset($plugz['website_language']) && $languageCode == 'en') || (isset($plugz['website_language']) && $plugz['website_language'] == $languageCode)) ? 'selected="selected"' : '' ?> value="<?php echo $languageCode ?>"><?php echo $language ?></option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </td>
                                        </tr>
                                        <tr valign="top">
                                            <th scope="row"><label for="plugz-content_type">Content Type</label></th>
                                            <td><select id="plugz-content_type" name="plugz-settings[content_type]" style="width:320px;">
                                                    <option <?php echo ($plugz['content_type'] == 'soft') ? 'selected="selected"' : '' ?> value="soft">Softcore</option>
                                                    <option <?php echo ($plugz['content_type'] == 'hard') ? 'selected="selected"' : '' ?> value="hard">Hardcore</option>
                                                    <option <?php echo ($plugz['content_type'] == 'mixed') ? 'selected="selected"' : '' ?> value="mixed">Mixed</option>
                                                </select>
                                            </td>
                                        </tr>
                                        <tr valign="top">
                                            <th scope="row"><label for="plugz-main_category_mainstream">Main Category</label></th>
                                            <td><select id="plugz-main_category_mainstream" name="plugz-settings[main_category_mainstream]" style="width:320px;">
                                                    <?php foreach ($categoriesMainstream as $parentCategory => $subcats) : ?>
                                                        <?php if (!is_numeric($parentCategory)) : ?><optgroup label="<?php echo $parentCategory ?>">
                                                                <?php foreach ($subcats as $category) : ?>
                                                                    <option <?php echo ($plugz['main_category_mainstream'] == $category) ? 'selected="selected"' : '' ?> value="<?php echo $category ?>"><?php echo $category ?></option>
                                                                <?php endforeach; ?>
                                                            </optgroup>
                                                        <?php else : ?>
                                                            <option <?php echo ($plugz['main_category_mainstream'] == $subcats) ? 'selected="selected"' : '' ?> value="<?php echo $subcats ?>"><?php echo $subcats ?></option>
                                                        <?php endif; ?>
                                                    <?php endforeach; ?>
                                                </select>
                                            </td>
                                        </tr>
                                        <tr valign="top">
                                            <th scope="row"><label for="plugz-main_category_adult_straight">Main Category</label></th>
                                            <td><select id="plugz-main_category_adult_straight" name="plugz-settings[main_category_adult_straight]" style="width:320px;">
                                                    <?php foreach ($categoriesAdultStraight as $parentCategory => $subcats) : ?>
                                                        <?php if (!is_numeric($parentCategory)) : ?><optgroup label="<?php echo $parentCategory ?>">
                                                                <?php foreach ($subcats as $category) : ?>
                                                                    <option <?php echo ($plugz['main_category_adult_straight'] == $category) ? 'selected="selected"' : '' ?> value="<?php echo $category ?>"><?php echo $category ?></option>
                                                                <?php endforeach; ?>
                                                            </optgroup>
                                                        <?php else : ?>
                                                            <option <?php echo ($plugz['main_category_adult_straight'] == $subcats) ? 'selected="selected"' : '' ?> value="<?php echo $subcats ?>"><?php echo $subcats ?></option>
                                                        <?php endif; ?>
                                                    <?php endforeach; ?>
                                                </select>
                                            </td>
                                        </tr>
                                        <tr valign="top">
                                            <th scope="row"><label for="plugz-main_category_adult_gay">Main Category</label></th>
                                            <td><select id="plugz-main_category_adult_gay" name="plugz-settings[main_category_adult_gay]" style="width:320px;">
                                                    <?php foreach ($categoriesAdultGay as $parentCategory => $subcats) : ?>
                                                        <?php if (!is_numeric($parentCategory)) : ?><optgroup label="<?php echo $parentCategory ?>">
                                                                <?php foreach ($subcats as $category) : ?>
                                                                    <option <?php echo ($plugz['main_category_adult_gay'] == $category) ? 'selected="selected"' : '' ?> value="<?php echo $category ?>"><?php echo $category ?></option>
                                                                <?php endforeach; ?>
                                                            </optgroup>
                                                        <?php else : ?>
                                                            <option <?php echo ($plugz['main_category_adult_gay'] == $subcats) ? 'selected="selected"' : '' ?> value="<?php echo $subcats ?>"><?php echo $subcats ?></option>
                                                        <?php endif; ?>
                                                    <?php endforeach; ?>
                                                </select>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div><!-- .inside -->
                            <?php /* if ($status['status'] == '200') { ?>
                              <hr>
                              <h3 class="title">Plugs</h3>
                              <div class="inside" style="margin-left: 2em">
                              <p>When you create posts in wordpress, you can have them automatically posted to your plugs in Plugz.</p>
                              <table class="form-table">
                              <tbody>
                              <tr valign="top">
                              <th scope="row">Autopost to Plugz</th>
                              <td>
                              <fieldset>
                              <legend class="screen-reader-text"><span>Autopost to Plugz</span></legend>
                              <label><input type="radio" name="plugz-settings[autopost]" value="1"<?= $plugz['autopost'] == 1 ? ' checked="checked"' : ''; ?>> Enabled</label><br>
                              <label><input type="radio" name="plugz-settings[autopost]" value="0"<?= $plugz['autopost'] == 0 ? ' checked="checked"' : ''; ?>> Disabled</label>
                              </fieldset>
                              <p class="description">Selecting 'Enabled' will pre-check the "Post to Plugz" option when you create or edit a post</p>
                              </td>
                              </tr>
                              </tbody>
                              </table>
                              </div><!-- .inside -->
                              <hr>
                              <h3 class="title">Widgets</h3>
                              <div class="inside" style="margin-left: 2em">
                              <p>Go to your <a href="widgets.php">widgets settings</a> page and place the widgets you've created in Plugz into your theme.</p>
                              </div><!-- .inside -->
                              <?php } else */if ($status['status'] == '401') { ?>
                                <p><?= plugz_domain_from_url(get_option('siteurl')); ?> is not in your Plugz account. Please add it to your account by going to <a href="//www.plugz.co/websites/add">Plugz.com</a></p>
                            <?php } ?>
                            <hr>
                            <h3 class="title">Update Settings</h3>
                            <div class="inside" style="margin-left: 2em">
                                <p>If you've enabled caching, you may have to clear it in order for these settings to take effect. You may also have to wait for cache on Plugz to update.</p>
                                <p class="submit"><input type="submit" value="Save Changes" class="button-primary" id="submit" name="submit"></p>
                            </div><!-- .inside -->
                        </form>
                    </div><!-- #plugz-admin-settings -->
                </div><!-- #post-body-content -->
            </div><!-- #post-body -->
            <br class="clear">
        </div><!-- #side-sortables -->
    <?php endif; ?>
</div><!-- #side-info-column -->    
