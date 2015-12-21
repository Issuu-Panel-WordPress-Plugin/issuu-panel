<?php

class IssuuPanelShortcodes implements IssuuPanelService
{
	private $config;

	public function __construct()
	{
		add_shortcode('issuu-painel-document-list', array($this, 'documentsList'));
		add_shortcode('issuu-painel-folder-list', array($this, 'folderList'));
		add_shortcode('issuu-panel-last-document', array($this, 'lastDocument'));
	}

	public function documentsList($atts)
	{

	}

	public function folderList($atts)
	{

	}

	public function lastDocument($atts)
	{

	}

	public function setConfig(IssuuPanelConfig $config)
	{
		$this->config = $config;
	}

	public function getConfig()
	{
		return $this->config;
	}
}