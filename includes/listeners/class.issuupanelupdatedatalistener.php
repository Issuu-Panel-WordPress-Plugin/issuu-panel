<?php

class IssuuPanelUpdateDataListener
{
	public function __construct()
	{
		add_action('post-issuu-panel-config', array($this, 'postConfigData'));
	}

	public function postConfigData()
	{
		update_option(ISSUU_PANEL_PREFIX . 'api_key', trim($_POST['api_key']));
		update_option(ISSUU_PANEL_PREFIX . 'api_secret', trim($_POST['api_secret']));
		update_option(ISSUU_PANEL_PREFIX . 'reader', trim($_POST['issuu_panel_reader']));

		if (in_array($_POST['enabled_user'], array('Administrator', 'Editor', 'Author')))
		{
			update_option(ISSUU_PANEL_PREFIX . 'enabled_user', $_POST['enabled_user']);
		}
		else
		{
			$_POST['enabled_user'] = 'Administrator';
			update_option(ISSUU_PANEL_PREFIX . 'enabled_user', 'Administrator');
		}

		if (isset($_POST['issuu_panel_debug']) && $_POST['issuu_panel_debug'] == 'active')
		{
			update_option(ISSUU_PANEL_PREFIX . 'debug', 'active');
		}
		else
		{
			update_option(ISSUU_PANEL_PREFIX . 'debug', 'disable');
		}

		if (isset($_POST['issuu_panel_cache_status']) && $_POST['issuu_panel_cache_status'] == 'active')
		{
			update_option(ISSUU_PANEL_PREFIX . 'cache_status', 'active');
		}
		else
		{
			$_POST['issuu_panel_cache_status'] = 'disable';
			update_option(ISSUU_PANEL_PREFIX . 'cache_status', 'disable');
		}
	}
}

new IssuuPanelUpdateDataListener();