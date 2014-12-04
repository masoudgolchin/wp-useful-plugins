<?php
/**
 * Plugin Name: WP useful plugins
 * Plugin URI: http://back-end.ir/
 * Description: Install the plugins I think are really awesome in one go!
 * Version: 1.0
 * Author: Masoud Golchin
 * Author URI: http://back-end.ir/
 * License: GPLv2
 */

function wp_plugins_menu() {
	add_menu_page('Useful Plugins', 'Useful Plugins', 'administrator', __FILE__, 'wp_plugins_settings_page');
    wp_register_style( 'wp-useful-plugins', plugins_url('/inc/css/main.css',__FILE__));
    wp_enqueue_style( 'wp-useful-plugins');
    wp_enqueue_script('jquery');
}
add_action('admin_menu', 'wp_plugins_menu');

function wp_plugins_settings_page() {

    include 'inc/forms.php';
}

function wp_plugins_ajax(){
    header( "Content-Type: application/json" );
    WP_Filesystem();

    $plugins_dir= plugin_dir_path(__FILE__);
    $plugins_dir= preg_replace('/mass-plugin-installer/', '$1', $plugins_dir);

    if( isset($_POST) && !empty($_POST)):

    foreach($_POST as $value) {
        $myplugin[] = $value;
    }

    // I had no choice :D haha :))
    array_pop($myplugin);
    $myplugin = implode($myplugin);
    $myplugin = explode("=",$myplugin);
    array_pop($myplugin);
    $myplugin = implode($myplugin);

    //echo json_encode($myplugin,true);

    $file_address = plugins_url('/inc/js/plugins.json',__FILE__);
    $json = file_get_contents($file_address);

    if($json):

        $myarray = json_decode($json, true);
        $myarray = $myarray['plugins'];

        foreach( $myarray as $value ) :
            if( in_array($myplugin, $value) ):
                $dllink = $value['dllink'];
            endif;
        endforeach;

    endif;

    $content = file_get_contents($dllink);
    $the_plugin = $plugins_dir . basename($dllink);
    $zipfile = fopen( $the_plugin , 'wb' );

    if( !$zipfile ):
        $error_m = 'Error in installation of ' . $myplugin;
        echo json_encode($error_m);
    else:
        fwrite( $zipfile, $content);
        fclose($zipfile);
        unzip_file($the_plugin,$plugins_dir);
        unlink($the_plugin);
        $MyMessage = $myplugin . ' installed successfuly.<br />';
        echo json_encode($MyMessage);
    endif;

endif;

die(1);
}

add_action('wp_ajax_wp_plugins_ajax', 'wp_plugins_ajax');
add_action('wp_ajax_nopriv_wp_plugins_ajax', 'wp_plugins_ajax');


?>
