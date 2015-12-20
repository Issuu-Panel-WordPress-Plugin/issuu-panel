<?php

class IssuuPanelInitPlugin implements IssuuPanelService
{
	private $config;

	public function __construct()
	{
		add_action('plugins_loaded', array($this, 'loadTextdomain'));
		add_action('init', array($this, 'initHook'));
		register_activation_hook(ISSUU_PANEL_PLUGIN_FILE, array($this, 'activePlugin'));
		register_uninstall_hook(ISSUU_PANEL_PLUGIN_FILE, array($this, 'uninstallPlugin'));
	}

	public function loadTextdomain()
	{
		load_plugin_textdomain(ISSUU_PANEL_DOMAIN_LANG, false, ISSUU_PANEL_PLUGIN_FILE_LANG);
		$this->getConfig()->getIssuuPanelDebug()->appendMessage("Text domain loaded");
	}
	
	public function initHook()
	{
		if (!is_null(filter_input(INPUT_GET, 'issuu_panel_flush_cache')))
		{
			$this->getConfig()->getHookManager()->triggerAction(
				'pre-flush-issuu-panel-cache',
				null,
				array(
					'config' => $this->getConfig(),
				)
			);
			$this->getConfig()->getHookManager()->triggerAction(
				'on-flush-issuu-panel-cache',
				null,
				array(
					'config' => $this->getConfig(),
				)
			);
			$this->getConfig()->getHookManager()->triggerAction(
				'pos-flush-issuu-panel-cache',
				null,
				array(
					'config' => $this->getConfig(),
				)
			);
		}

		if (filter_input(INPUT_SERVER, 'REQUEST_METHOD') == 'POST' &&
			(filter_input(INPUT_GET, 'page') == ISSUU_PANEL_MENU))
		{
			$this->getConfig()->getHookManager()->triggerAction(
				'post-issuu-panel-config',
				null,
				array(
					'config' => $this->getConfig(),
					'postData' => filter_input_array(INPUT_POST),
				)
			);
		}
	}

	public function activePlugin()
	{
		$this->getConfig()->getHookManager()->triggerAction(
			'pre-active-issuu-panel',
			null,
			array(
				'config' => $this->getConfig(),
			)
		);
		$this->getConfig()->getHookManager()->triggerAction(
			'on-active-issuu-panel',
			null,
			array(
				'config' => $this->getConfig(),
			)
		);
		$this->getConfig()->getHookManager()->triggerAction(
			'pos-active-issuu-panel',
			null,
			array(
				'config' => $this->getConfig(),
			)
		);
	}

	public function uninstallPlugin()
	{
		$this->getConfig()->getHookManager()->triggerAction(
			'pre-uninstall-issuu-panel',
			null,
			array(
				'config' => $this->getConfig(),
			)
		);
		$this->getConfig()->getHookManager()->triggerAction(
			'on-uninstall-issuu-panel',
			null,
			array(
				'config' => $this->getConfig(),
			)
		);
		$this->getConfig()->getHookManager()->triggerAction(
			'pos-uninstall-issuu-panel',
			null,
			array(
				'config' => $this->getConfig(),
			)
		);
	}

	public function setConfig($config)
	{
		$this->config = $config;
	}

	public function getConfig()
	{
		return $this->config;
	}
}