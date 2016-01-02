<?php

class IssuuPanelFolderListener
{
	public function __construct()
	{
		add_action('on-issuu-panel-add-folder', array($this, 'addFolder'));
		add_action('on-issuu-panel-update-folder', array($this, 'updateFolder'));
		add_action('on-issuu-panel-delete-folder', array($this, 'deleteFolder'));
	}

	public function addFolder(IssuuPanelHook $hook)
	{
		
	}

	public function updateFolder(IssuuPanelHook $hook)
	{
		
	}

	public function deleteFolder(IssuuPanelHook $hook)
	{

	}
}