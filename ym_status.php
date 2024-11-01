<?php
/*
Plugin Name: Yahoo! Messenger Status Widget
Plugin URI: http://www.dreinavarro.com/blog/06272009/custom-yahoo-status-wpwidget/
Description: Adds a widget to display current Yahoo Messenger online status, displays default YM images + custom images.
Author: Andrei Navarro
Version: 0.1
Author URI: http://www.dreinavarro.com/
*/
define('Y_STATUS_BASE_DIR', substr(__FILE__, 0, strrpos(__FILE__, '/')));

function display_ym_status_widget() {
   $opts = get_option('ym_status');
   require(Y_STATUS_BASE_DIR.'/YmStatus.php');   
   include(Y_STATUS_BASE_DIR.'/ym_status_widget.phtml');    
}

function ym_status_widget_init() {
    if ( !function_exists('register_sidebar_widget') || !function_exists('register_widget_control') ) {
        return false;
    }   
    
    function ym_status_widget($args) {
        extract($args);
        $opts = get_option('ym_status');        
        $title = empty($opts['title']) ? $default_opts['title'] : $opts['title'];
        
        echo $before_widget;
        echo $before_title . $title . $after_title;
        display_ym_status_widget();
        echo $after_widget;
    }
    
    function ym_status_widget_control(){
        $opts = get_option('ym_status');

        $title = empty($opts['title']) ? $default_opts['title'] : $opts['title'];
        
        if ( $_POST['ym_status_widget_control_submit'] ) {
            $opts['title'] = $_POST['ym_status_widget_control_title'];
            update_option('ym_status', $opts);
        }
        
        include(Y_STATUS_BASE_DIR.'/ym_status_control.phtml');
    }        

    register_sidebar_widget('Yahoo Messenger Status', 'ym_status_widget');
    register_widget_control('Yahoo Messenger Status', 'ym_status_widget_control');
}

function ym_status_settings_init() {  
    function ym_status_options() {
        $opts = get_option('ym_status');
        if ($_POST['ym_status_settings_submit']) {                     
              $newopts['title'] = $_POST['ym_status_settings_title'];
              $newopts['yahooid'] = $_POST['ym_status_settings_yahooid'];
              $newopts['image'] = $_POST['ym_status_settings_image'];
              if ($newopts['image'] == 'custom') {
                  $newopts['custom']['online'] = $_POST['ym_status_settings_custom_online'];
                  $newopts['custom']['offline'] = $_POST['ym_status_settings_custom_offline'];
              }
              $newopts['action'] = $_POST['ym_status_settings_action'];
              $newopts['align'] = $_POST['ym_status_settings_align'];

              update_option('ym_status', $newopts);
              $opts = get_option('ym_status');          
        }
        
        include(Y_STATUS_BASE_DIR.'/ym_status_settings.phtml');
    }
    
    add_options_page('Yahoo! Messenger Status', 'Y!Messenger Status', 8, 'ym-status', 'ym_status_options');
}

add_action('plugins_loaded', 'ym_status_widget_init');
add_action('admin_menu', 'ym_status_settings_init');
?>