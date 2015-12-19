<?php

class IssuuPanelPluginManager
{
	private $issuuPanelHookManager;

	public function __construct()
	{
		$entity = $this->initOptionEntity();
		// Debug
		$debug = new IssuuPanelDebug($entity->getDebug());
		// Scripts
		$script = new IssuuPanelScripts();
		// Cron
		$cron = new IssuuPanelCron();
		$cron->setActions($entity->getCron());
		// Hook Manager
		$this->issuuPanelHookManager = new IssuuPanelHookManager();
	}

	private function initOptionEntity()
	{
		$issuuPanelOptionEntity = new IssuuPanelOptionEntity();
		$issuuPanelOptionEntity->setApiKey(get_option(ISSUU_PANEL_PREFIX . 'api_key'));
		$issuuPanelOptionEntity->setApiSecret(get_option(ISSUU_PANEL_PREFIX . 'api_secret'));
		$issuuPanelOptionEntity->setEnabledUser(get_option(ISSUU_PANEL_PREFIX . 'enabled_user'));
		$issuuPanelOptionEntity->setDebug(get_option(ISSUU_PANEL_PREFIX . 'debug'));
		$issuuPanelOptionEntity->setShortcodeCache(get_option(ISSUU_PANEL_PREFIX . 'shortcode_cache'));
		$issuuPanelOptionEntity->setCacheStatus(get_option(ISSUU_PANEL_PREFIX . 'cache_status'));
		$issuuPanelOptionEntity->setReader(get_option(ISSUU_PANEL_PREFIX . 'reader'));
		$issuuPanelOptionEntity->setCron(get_option(ISSUU_PANEL_PREFIX . 'cron'));
		return $issuuPanelOptionEntity;
	}
}