<?php

/*
  Plugin Name: یک بیت شعر
  Plugin URI:http://ham3da.ir
  Description:  این افزونه یک بیت شعر به صورت تصادفی در هر بار مرور سایت به کاربر نمایش می‌دهد
  Version: 1.5.2
  Author: Javad Ahshamian
  Author URI: http://ham3da.ir
  License:GPL MIT License
 */

// Prevent loading this file directly
defined('ABSPATH') || exit;

define('YEK_BEYT_VER', '1.5');

define('OB4WP_DIR', plugin_dir_path(__FILE__));
define('OB4WP_INC_DIR', OB4WP_DIR . 'inc/');
define('OB4WP_URL', plugin_dir_url(__FILE__));



//includes
require_once (OB4WP_INC_DIR . 'widget.php');
require_once (OB4WP_INC_DIR . 'ob_shortcode.php');
//hooks
register_activation_hook(__FILE__, 'OB_init');
add_action('admin_menu', 'ob_admin_pages');

//Functions
function ob_admin_pages()
{
    add_menu_page("یک بیت شعر", "یک بیت شعر", 'manage_options', 'ob_page', 'ob_main', plugin_dir_url(__FILE__) . '/images/icon.png');
    add_submenu_page('ob_page', 'ربات یک بیت شعر', 'ربات یک بیت شعر', 'manage_options', 'ob_robot', 'ob_robot_func');
}

function ob_main()
{
    include dirname(__file__) . "/help.php";
}

function ob_robot_func()
{
    include dirname(__file__) . "/inc/robot.php";
}

function OB_init()
{
    global $wpdb;

    $table = $wpdb->prefix . "poems";

    $query = "CREATE TABLE IF NOT EXISTS $table (
            `ID` INT(11) NOT NULL AUTO_INCREMENT,
            `Verse1` VARCHAR(512) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
            `Verse2` VARCHAR(512) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
            `Des1`  VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
            `Type1` TINYINT(4) NOT NULL DEFAULT '0' ,
            PRIMARY KEY (`ID`)
          ) AUTO_INCREMENT=1 COLLATE utf8_general_ci;";

    $wpdb->query($query);

    $query = "DELETE FROM $table Where `Type1`='0'";
    $wpdb->query($query);


    $arrContextOptions = array(
        "ssl" => array(
            "verify_peer" => false,
            "verify_peer_name" => false,
        ),
    );

    $lines = file_get_contents(OB4WP_INC_DIR . 'Poems.sql', false, stream_context_create($arrContextOptions));
    $wpdb->query("INSERT INTO $table (`Verse1`, `Verse2`, `Des1`, `Type1`) VALUES 
    $lines");

    update_option('YEK_BEYT_VER', YEK_BEYT_VER);
}

add_action('admin_init', 'check_yek_beyt_ver');

function check_yek_beyt_ver()
{
    $ver = get_option('YEK_BEYT_VER', '0');
    if ($ver != YEK_BEYT_VER)
    {
        OB_init();
    }
}