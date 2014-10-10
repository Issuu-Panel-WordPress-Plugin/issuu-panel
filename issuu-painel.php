<?php
/*
Plugin Name: Issuu Painel
Plugin URI: https://github.com/pedromarcelojava/
Description: Painel de administração para Issuu.
Version: 1.0
Author: Pedro Marcelo
Author URI: https://github.com/pedromarcelojava/
License: GPL3
*/

define('ISSUU_PAINEL_DIR', plugin_dir_path(__FILE__));
define('ISSUU_PAINEL_URL', plugin_dir_url(__FILE__));
define('ISSUU_PAINEL_PREFIX', 'issuu_painel_');

add_option(ISSUU_PAINEL_PREFIX . 'api_key', '');
add_option(ISSUU_PAINEL_PREFIX . 'api_secret', '');

$api_key = get_option(ISSUU_PAINEL_PREFIX . 'api_key');
$api_secret = get_option(ISSUU_PAINEL_PREFIX . 'api_secret');

require(ISSUU_PAINEL_DIR . 'menu/principal/config.php');

if ((!is_null($api_key) && strlen($api_key) > 0) && (!is_null($api_secret) && strlen($api_secret) > 0))
{
	require(ISSUU_PAINEL_DIR . 'issuuservice/issuu-lib.php');

	$includes = glob(ISSUU_PAINEL_DIR . 'includes/*.php');

	foreach ($includes as $include) {
		require($include);
	}

	include(ISSUU_PAINEL_DIR . 'menu/documento/config.php');
	include(ISSUU_PAINEL_DIR . 'menu/pasta/config.php');
	include(ISSUU_PAINEL_DIR . 'shortcode/document-list.php');
}

add_action('wp_enqueue_scripts', 'issuu_painel_wp_enqueue_scripts');

function issuu_painel_wp_enqueue_scripts()
{
	wp_enqueue_style('issuu-painel-documents', ISSUU_PAINEL_URL . 'css/issuu-painel-documents.css');
	// wp_enqueue_style('')
}

add_action('admin_enqueue_scripts', 'issuu_painel_admin_enqueue_scripts');

function issuu_painel_admin_enqueue_scripts()
{
	wp_enqueue_style('document-list', ISSUU_PAINEL_URL . 'css/document-list.css', array(), null, 'screen, print');
	wp_enqueue_style('folder-list', ISSUU_PAINEL_URL . 'css/folder-list.css', array('dashicons'), null, 'screen, print');
	wp_enqueue_script('json2');
	wp_enqueue_script('jquery');
	wp_enqueue_script('issuu-painel-js', ISSUU_PAINEL_URL . 'js/js.php');
}

add_action('admin_menu', 'ip_menu_admin');

function ip_menu_admin()
{
	global $api_key, $api_secret;

	do_action(ISSUU_PAINEL_PREFIX . 'menu_page');

	if ((!is_null($api_key) && strlen($api_key) > 0) && (!is_null($api_secret) && strlen($api_secret) > 0))
	{
		do_action(ISSUU_PAINEL_PREFIX . 'submenu_pages');
	}
}