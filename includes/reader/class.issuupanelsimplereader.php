<?php

class IssuuPanelSimpleReader
{
	public function __construct()
	{
		add_action('wp_ajax_nopriv_open_issuu_panel_reader', array($this, 'reader'));
	}

	public function reader()
	{
		die();
	}
}