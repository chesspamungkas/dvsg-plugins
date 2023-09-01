<?php
/*
Plugin Name: DV Splash Popup
Plugin URI: https://dailyvanity.sg
Description: Create splash popup element
Author: Catur "Chess" Pamungkas
Version: 1.0
*/


// create CPT
function dv_splash_popup_cpt()
{
    $args = array(
        'labels'      => array(
            'name'          => __('Splash Popups'),
            'singular_name' => __('Splash Popup'),
        ),
        'capability_type'      => 'post',
        'public'      => true,
        'has_archive' => true,
        'exclude_from_search'   => true,
        'rewrite'   => array(
            'slug'  => 'dvspopup'
        ),
        'publicly_queryable'    => false,
        'show_ui'   => true,
        'show_in_menu'  => true,
        'show_in_nav_menus' => false,
        'supports' => array('title', 'editor'),
        'register_meta_box_cb' => 'add_dv_splash_popup_metaboxes',
    );

    register_post_type('dvspopup', $args);
}

add_action('init', 'dv_splash_popup_cpt');

/**
 * Adds a metabox
 */
function add_dv_splash_popup_metaboxes()
{
    add_meta_box(
        'dv_splash_popup_setting_metabox',
        'Splash Popup Settings',
        'dv_splash_popup_setting_metabox_html',
        'dvspopup',
        'normal',
        'high',
        null
    );
}

// add_action( 'add_meta_boxes', 'wpt_add_splash_popup_metaboxes' );

function dv_splash_popup_setting_metabox_html()
{
    global $post;

    // Nonce field to validate form request came from current site
    wp_nonce_field(basename(__FILE__), 'dv_splash_popup_nonce');
?>
    <style>
        .popup-setting-table {
            width: 100%;
            border: 0;
            padding: 0;
            margin: 0;
        }

        .popup-setting-table tr {
            width: 80%;
            border: 0;
            padding: 0;
            margin: 0;
        }

        .popup-setting-table tr td {
            border: 0;
            padding: 10px;
            margin: 0;
        }

        .popup-setting-table tr td:first-child {
            width: 10%;
        }

        .popup-setting-table tr td:nth-child(2) {
            width: 40%;
        }

        .popup-setting-table tr td input[ type="text"]:not(#aw_custom_image),
        .popup-setting-table tr td input[ type="date"],
        .popup-setting-table tr td select {
            width: 100%;
        }

        .popup-setting-table tr td input#aw_custom_image {
            width: 66.8%;
            margin-left: 5px;
        }

        @media(min-width:1441px) {
            .popup-setting-table tr td input#aw_custom_image {
                width: 78.8%;
                margin-left: 5px;
            }
        }
    </style>

    <script>
        (function($) {
            $(document).ready(
                function() {
                    $('body').on('click', '.aw_upload_image_button', function(e) {
                        e.preventDefault();

                        var button = $(this),
                            aw_uploader = wp.media({
                                title: 'Custom image',
                                library: {
                                    uploadedTo: wp.media.view.settings.post.id,
                                    type: 'image'
                                },
                                button: {
                                    text: 'Use this image'
                                },
                                multiple: false
                            }).on('select', function() {
                                var attachment = aw_uploader.state().get('selection').first().toJSON();
                                $('#aw_custom_image').val(attachment.url);
                            })
                            .open();
                    });
                }
            );
        })(jQuery);
    </script>

    <?php $status = esc_attr(get_post_meta(get_the_ID(), 'popup_status', true)); ?>

    <table class="popup-setting-table">
        <tr>
            <td>
                <label>Status</label>
            </td>
            <td>
                <select name="popup_status">
                    <option value="0" <?php echo $status == 0 ? 'selected' : ''; ?>>Draft</option>
                    <option value="1" <?php echo $status == 1 ? 'selected' : ''; ?>>Active</option>
                    <option value="2" <?php echo $status == 2 ? 'selected' : ''; ?>>Expired</option>
                    <option value="3" <?php echo $status == 3 ? 'selected' : ''; ?>>Scheduled</option>
                </select>
            </td>
            <td>&nbsp;</td>
        </tr>
        <tr>
            <td>
                <label>Link</label>
            </td>
            <td>
                <input type="text" name="popup_link" id="popup_link" value="<?php echo esc_attr(get_post_meta(get_the_ID(), 'popup_link', true)); ?>" required />
            </td>
            <td>&nbsp;</td>
        </tr>
        <tr>
            <td>
                <label>Image</label>
            </td>
            <td>
                <!-- input type="file" name="popup_img" id="popup_img" /-->
                <a href="#" class="aw_upload_image_button button button-secondary"><?php _e('Upload Image'); ?></a> <input type="text" name="aw_custom_image" id="aw_custom_image" value="<?php echo esc_attr(get_post_meta(get_the_ID(), 'aw_custom_image', true)); ?>" required />
            </td>
            <td>&nbsp;</td>
        </tr>
        <tr>
            <td>
                <label>Start Date</label>
            </td>
            <td>
                <input type="date" name="popup_sdate" id="popup_sdate" value="<?php echo esc_attr(get_post_meta(get_the_ID(), 'popup_sdate', true)); ?>" required />
            </td>
            <td>&nbsp;</td>
        </tr>
        <tr>
            <td>
                <label>End Date</label>
            </td>
            <td>
                <input type="date" name="popup_edate" id="popup_edate" value="<?php echo esc_attr(get_post_meta(get_the_ID(), 'popup_edate', true)); ?>" required />
            </td>
            <td>&nbsp;</td>
        </tr>
    </table>

<?php
}

function admin_scripts()
{
    //    wp_enqueue_script('media-upload');
    if (!did_action('wp_enqueue_media')) {
        wp_enqueue_media();
    }
    // wp_enqueue_script('thickbox');
}

function admin_styles()
{
    wp_enqueue_style('thickbox');
}

add_action('admin_print_scripts', 'admin_scripts');
// add_action('admin_print_styles', 'admin_styles');

/**
 * When the post is saved, saves our custom data.
 *
 * @param int $post_id
 */
function save_dv_splash_popup_setting_data($post_id)
{

    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;

    if ($parent_id = wp_is_post_revision($post_id)) {
        $post_id = $parent_id;
    }

    $fields = [
        'popup_status',
        'popup_link',
        'aw_custom_image',
        'popup_sdate',
        'popup_edate',
    ];

    foreach ($fields as $field) {
        if (array_key_exists($field, $_POST)) {
            update_post_meta($post_id, $field, sanitize_text_field($_POST[$field]));
        }
    }
}

add_action('save_post', 'save_dv_splash_popup_setting_data');

// adding admin page for the popup configuration
add_action('admin_menu', 'add_splash_popup_setting_menu');

function add_splash_popup_setting_menu()
{
    add_submenu_page('edit.php?post_type=dvspopup', 'Splash Popup Settings', 'Settings', 'manage_options', 'splash-popup-setting', 'splash_popup_setting_page');
    // add_submenu_page( 'Splash Popup Settings', 'Splash Popup', 'manage_options', 'splash-popup-setting', 'splash_popup_setting_page' );
}

function splash_popup_setting_page()
{
    if ($_POST['updated']) {
        handle_form();
    }

    if (get_option('dvspopup_setting_loading_time')) {
        $loadingTime = get_option('dvspopup_setting_loading_time');
    } else {
        $loadingTime = 0;
    }

    if (get_option('dvspopup_setting_frequency') == 2) {
        $checked2 = 'checked';
        $checked1 = '';
    } else {
        $checked1 = 'checked';
        $checked2 = '';
    }

    echo '<h1 style="padding-bottom: 30px; border-bottom: 1px solid #ccc;">Splash Popup Settings</h1>';
    echo '<ul style="margin-top: 30px;">';
    echo '<form action="edit.php?post_type=dvspopup&page=splash-popup-setting" method="post">';
    echo '<input type="hidden" name="updated" value="true" />';
    wp_nonce_field('splash_popup_update', 'splash_popup_form');
    echo '<li><p>Appear after <input type="number" value="' . $loadingTime . '" name="loadTime" id="loadTime" style="width:50px;" /> second(s) upon page loads.</p></li>';
    echo '<li><p>Appearance frequency:</p>';
    echo '<input type="radio" name="appFrequency" value="1" ' . $checked1 . ' /> Once a day<br/> ';
    echo '<input type="radio" name="appFrequency" value="2" ' . $checked2 . ' /> Once during current session</li>';
    // echo '<li><p><input name="Submit" type="submit" class="button button-primary" value="Save Changes" /></p></li>';
    echo '<li><p>' . submit_button() . '</p></li>';
    echo '</form>';
    echo '</ul>';
}

function handle_form()
{
    if (
        !isset($_POST['splash_popup_form']) ||
        !wp_verify_nonce($_POST['splash_popup_form'], 'splash_popup_update')
    ) {
        echo '<div class="notice notice-error is-dismissible">';
        echo '<p>Sorry, please try again later.</p>';
        echo '</div>';
        exit;
    } else {
        // Handle our form data
        $loadingTime = sanitize_text_field($_POST['loadTime']);
        $frequency = $_POST['appFrequency'];

        update_option('dvspopup_setting_loading_time', $loadingTime);
        update_option('dvspopup_setting_frequency', $frequency);

        echo '<div class="notice notice-success is-dismissible">';
        echo '<p>Settings updated.</p>';
        echo '</div>';
    }
}

// front end script
if (!function_exists('splash_popup_front_end_scripts')) {

    function splash_popup_front_end_scripts()
    {
        // Add CSS
        wp_enqueue_style('splash-popup-bootstrap-style', plugin_dir_url(__FILE__) . 'css/popup-style.min.css?v=' . DEPLOY_VERSION);
        // Add Scripts
        wp_register_script('cookie-js', 'https://cdnjs.cloudflare.com/ajax/libs/jquery-cookie/1.4.1/jquery.cookie.js', array(), DEPLOY_VERSION, true);
        wp_enqueue_script('cookie-js');
        wp_enqueue_style('magnific-popup-style', 'https://cdnjs.cloudflare.com/ajax/libs/magnific-popup.js/1.0.0/magnific-popup.min.css', array(), DEPLOY_VERSION);
        wp_register_script('magnific-popup-js', 'https://cdnjs.cloudflare.com/ajax/libs/magnific-popup.js/1.0.0/jquery.magnific-popup.min.js', array(), DEPLOY_VERSION, true);
        wp_enqueue_script('magnific-popup-js');
        wp_register_script('splash-popup-script', plugin_dir_url(__FILE__) . 'js/popup.js', 'jQuery', DEPLOY_VERSION, true);

        $args = array(
            'post_type' => 'dvspopup',
            'posts_per_page' => -1,
            'orderby' => 'date',
            'post_status' => 'publish',
            'meta_query' => array(
                'relation'  => 'AND',
                array(
                    'key' => 'popup_status',
                    'value' => '1'
                ),
                array(
                    'relation'  => 'AND',
                    array(
                        'key'   => 'popup_sdate',
                        'type' => 'DATE',
                        'value' => date('Y-m-d'),
                        'compare'   => '<='
                    ),
                    array(
                        'key'   => 'popup_edate',
                        'type' => 'DATE',
                        'value' => date('Y-m-d'),
                        'compare'   => '>='
                    )
                )
            )
        );

        $query = new WP_Query($args);
        if ($query->have_posts()) {
            while ($query->have_posts()) {
                $query->the_post();
                $title = get_the_title();
                $fields = get_post_meta(get_the_ID());
                $img_src = $fields['aw_custom_image'][0];
                if (strpos($fields['aw_custom_image'][0], '.gif') === false) :
                    $imgId = attachment_url_to_postid($img_src);
                    $img_srcset = wp_get_attachment_image_srcset($imgId, 'dv-splash-image');
                    $imgAttributes = wp_get_attachment_image_src($imgId, 'dv-splash-image');
                    $sizes = wp_get_attachment_image_sizes($imgId, 'dv-splash-image');
                    $thumbnail = '<img width="' . $imgAttributes[1] .  '" heght="' . $imgAttributes[2] . '" src="' .  esc_url($img_src) . '" srcset="' . esc_attr($img_srcset) . '" sizes="' . esc_attr($sizes) . '" title="' . $title . '" alt="' . $title . '" class="post-thumbnail" />';
                else :
                    $thumbnail = '<img width="' . $imgAttributes[1] .  '" heght="' . $imgAttributes[2] . '" src="' . esc_url($img_src) . '" title="' . $title . '" alt="' . $title . '" class="post-thumbnail" />';
                endif;

                if (get_option('dvspopup_setting_loading_time')) {
                    $loadingTime = get_option('dvspopup_setting_loading_time');
                } else {
                    $loadingTime = 0;
                }

                if (get_option('dvspopup_setting_frequency')) {
                    $frequency = get_option('dvspopup_setting_frequency');
                } else {
                    $frequency = 1;
                }

                $dataArr = array(
                    'loadingTime'   => $loadingTime,
                    'frequency' => $frequency,
                    'title' => $title,
                    'link'  => $fields['popup_link'][0],
                    'image' => $fields['aw_custom_image'][0],
                    'home_url'  => preg_replace("(^https?://)", "", get_home_url()),
                    'thumbnail' => $thumbnail
                );

                wp_localize_script('splash-popup-script', 'dataArr', $dataArr);
                wp_enqueue_script('splash-popup-script');
            }
        }
    }

    add_action('wp_enqueue_scripts', 'splash_popup_front_end_scripts');
}

// create a scheduled event (if it does not exist already)
function splash_popup_cron_activation()
{
    if (!wp_next_scheduled('dvSplashPopupCron')) {
        wp_schedule_event(time(), 'daily', 'dvSplashPopupCron');
    }
}
// and make sure it's called whenever WordPress loads
add_action('wp', 'splash_popup_cron_activation');

// unschedule event upon plugin deactivation
function splash_popup_cron_deactivate()
{
    // find out when the last event was scheduled
    $timestamp = wp_next_scheduled('dvSplashPopupCron');
    // unschedule previous event if any
    wp_unschedule_event($timestamp, 'dvSplashPopupCron');
}
register_deactivation_hook(__FILE__, 'splash_popup_cron_deactivate');

// here's the function we'd like to call with our cron job
function splash_popup_checking()
{
    $args = array(
        'post_type' => 'dvspopup',
        'posts_per_page' => -1,
        'orderby' => 'date',
        'post_status' => 'publish',
        'meta_query' => array(
            'relation'  => 'AND',
            array(
                'relation'  => 'OR',
                array(
                    'key' => 'popup_status',
                    'value' => '1'
                ),
                array(
                    'key' => 'popup_status',
                    'value' => '3'
                )
            )
        )
    );

    $query = new WP_Query($args);

    if ($query->have_posts()) {
        while ($query->have_posts()) {
            $query->the_post();
            $fields = get_post_meta(get_the_ID());

            if (strtotime($fields['popup_edate'][0]) < strtotime(date('Y-m-d'))) {
                update_post_meta(get_the_ID(), 'popup_status', '2');
                // echo 'done';
            }
            if ($fields['popup_status'][0] == 3 && strtotime($fields['popup_sdate'][0]) <= strtotime(date('Y-m-d'))) {
                update_post_meta(get_the_ID(), 'popup_status', '1');
                // echo 'done';
            } else {
                continue;
            }
        }
    }
}

// hook that function onto our scheduled event:
add_action('dvSplashPopupCron', 'splash_popup_checking');
