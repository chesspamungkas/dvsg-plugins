<?php
/*
Plugin Name: Sidebar DFP Tags
Plugin URI: 
Description: This is customise DFP tag plugin for sidebar
Author: Catur "Chess" Pamungkas
Version: 1.0
Author URI:
*/

// register DFP_Tags
add_action('widgets_init', function () {
    register_widget('DFP_Tag');
});

class DFP_Tag extends WP_Widget
{
    // class constructor
    public function __construct()
    {
        $widget_ops = array(
            'classname' => 'dfp_tag',
            'description' => 'A plugin for Sidebar DFP Tag',
        );
        parent::__construct('dfp_tag', 'DFP Tag', $widget_ops);
    }

    // output the widget content on the front-end
    public function widget($args, $instance)
    {
        echo $args['before_widget'];

        $detect = new Mobile_Detect;
        $start1 = strtotime("2021-10-09 00:00:01");
        $end1 = strtotime("2021-11-05 23:59:59");

        $now = current_time('timestamp', 0);

        if ($now >= $start1 && $now <= $end1) {
            if ($detect->isMobile() || $detect->isTablet()) {
                echo '<div class="google-dfp" style="margin-bottom: 20px;">';
                // echo do_shortcode('[google-dfp name="Chanel_Mobile_300x600_VD" width=300 height=600 id="singlePostSideBar" code="88831039"]');
                echo do_shortcode('[custom-dfp size="300x600" image="https://uploads.dailyvanity.sg/wp-content/uploads/2020/10/banner0915_300x600.jpg" link="https://dailyvanity.sg/news/virtual-beauty-expo-taiwan-beauty-virtual?utm_source=website&utm_medium=Oct-banner-300x600&utm_campaign=taitra_oct20"]');
                echo '</div>';
            } else {
                echo '<div id="text-3">';
                echo '<div class="google-dfp" style="margin-bottom: 20px;">';
                // echo do_shortcode('[google-dfp name="Chanel_Desktop_300x600_VD" width=300 height=600 id="singlePostSideBar" code="88831039"]');
                echo do_shortcode('[custom-dfp size="300x600" image="https://uploads.dailyvanity.sg/wp-content/uploads/2020/10/banner0915_300x600.jpg" link="https://dailyvanity.sg/news/virtual-beauty-expo-taiwan-beauty-virtual?utm_source=website&utm_medium=Oct-banner-300x600&utm_campaign=taitra_oct20"]');
                echo '</div>';
                echo '</div>';
            }
        } else {
            if ($detect->isMobile() || $detect->isTablet()) {
                echo '<div class="google-dfp" style="cursor: pointer; margin-bottom: 20px;">';
                echo do_shortcode('[google-dfp name="300x600_Mobile" width=300 height=600 id="singlePostSideBar" code="88831039"]');
                echo '</div>';
            } else {
                echo '<div id="text-3">';
                echo '<div class="google-dfp" style="cursor: pointer; margin-bottom: 20px;">';
                echo do_shortcode('[google-dfp name="300x600" width=300 height=600 id="singlePostSideBar" code="88831039"]');
                echo '</div>';
                echo '</div>';
            }
        }

        echo $args['after_widget'];
    }

    // output the option form field in admin Widgets screen
    public function form($instance)
    {
    }

    // save options
    public function update($new_instance, $old_instance)
    {
    }
}
