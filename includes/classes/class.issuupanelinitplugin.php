<?php

class IssuuPanelInitPlugin
{
	private static $options = array(
		'api_key' => '',
		'api_secret' => '',
		'enabled_user' => 'Administrator',
		'debug' => 'disable',
		'shortcode_cache' => array(),
		'cache_status' => 'disable',
		'reader' => 'issuu_embed',
		'cron' => array()
	);

	public static function init()
	{
		add_action('plugins_loaded', array('IssuuPanelInitPlugin', 'loadTextdomain'));
		add_action('init', array('IssuuPanelInitPlugin', 'initHook'));
		register_activation_hook(ISSUU_PANEL_PLUGIN_FILE, array('IssuuPanelInitPlugin', 'activePlugin'));
		register_uninstall_hook(ISSUU_PANEL_PLUGIN_FILE, array('IssuuPanelInitPlugin', 'uninstallPlugin'));
	}

	public static function loadTextdomain()
	{
		load_plugin_textdomain(ISSUU_PANEL_DOMAIN_LANG, false, ISSUU_PANEL_PLUGIN_FILE_LANG);
		issuu_panel_debug("Text domain loaded");
	}

	public static function activePlugin()
	{
		$action = new IssuuPanelAction();
		$action->setName('pre-active-issuu-panel');
		do_action($action->getName(), $action);
		foreach (self::$options as $key => $value) {
			if (is_array($value))
			{
				add_option(ISSUU_PANEL_PREFIX . $key, serialize($value));
			}
			else
			{
				add_option(ISSUU_PANEL_PREFIX . $key, $value);
			}
		}
		issuu_panel_debug("Issuu Panel options initialized");
		$action->setName('pos-active-issuu-panel');
		do_action($action->getName(), $action);
	}

	public static function uninstallPlugin()
	{
		$action = new IssuuPanelAction();
		$action->setName('pre-uninstall-issuu-panel');
		do_action($action->getName(), $action);
		foreach (self::$options as $key => $value) {
			delete_option(ISSUU_PANEL_PREFIX . $key);
		}
		$action->setName('pos-uninstall-issuu-panel');
		do_action($action->getName(), $action);
	}

	public static function initHook()
	{
		$action = new IssuuPanelAction();
		IssuuPanelConfig::setVariable(
			'issuu_panel_shortcode_cache',
			unserialize(get_option(ISSUU_PANEL_PREFIX . 'shortcode_cache'))
		);

		if (isset($_GET['issuu_panel_flush_cache']))
		{
			$action->setName('pre-flush-issuu-panel-cache');
			do_action($action->getName(), $action);
			IssuuPanelConfig::flushCache();
			$action->setName('pos-flush-issuu-panel-cache');
			do_action($action->getName(), $action);
		}

		if ($_SERVER['REQUEST_METHOD'] == 'POST' && (isset($_GET['page']) && $_GET['page'] == ISSUU_PANEL_MENU))
		{
			$action->setName('post-issuu-panel-config');
			do_action($action->getName(), $action);

			IssuuPanelConfig::setVariable('issuu_panel_api_key', trim($_POST['api_key']));
			IssuuPanelConfig::setVariable('issuu_panel_api_secret', trim($_POST['api_secret']));
			IssuuPanelConfig::setVariable('issuu_panel_reader', trim($_POST['issuu_panel_reader']));
			IssuuPanelConfig::setVariable('issuu_panel_cache_status', trim($_POST['issuu_panel_cache_status']));
			$issuu_painel_capacity = IssuuPanelConfig::getCapability($_POST['enabled_user']);
			IssuuPanelConfig::setVariable('issuu_panel_capacity', $issuu_painel_capacity);
			issuu_panel_debug("Issuu Panel options updated in init hook");
		}
		else
		{
			IssuuPanelConfig::setVariable('issuu_panel_api_key', get_option(ISSUU_PANEL_PREFIX . 'api_key'));
			IssuuPanelConfig::setVariable('issuu_panel_api_secret', get_option(ISSUU_PANEL_PREFIX . 'api_secret'));
			IssuuPanelConfig::setVariable('issuu_panel_reader', get_option(ISSUU_PANEL_PREFIX . 'reader'));
			IssuuPanelConfig::setVariable('issuu_panel_cache_status', get_option(ISSUU_PANEL_PREFIX . 'cache_status'));
			$issuu_painel_capacity = IssuuPanelConfig::getCapability(get_option(ISSUU_PANEL_PREFIX . 'enabled_user'));
			IssuuPanelConfig::setVariable('issuu_panel_capacity', $issuu_painel_capacity);
			issuu_panel_debug("Issuu Panel options initialized in init hook");
		}
	}
}

IssuuPanelInitPlugin::init();