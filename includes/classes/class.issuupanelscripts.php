<?php

class IssuuPanelScripts
{
	public function __construct()
	{
		add_action('wp_enqueue_scripts', array($this, 'wpScripts'));
		add_action('admin_enqueue_scripts', array($this, 'adminScripts'));
	}

	public function wpScripts()
	{
		$jsDependences = array('jquery');
		wp_enqueue_style('issuu-painel-documents', ISSUU_PANEL_URL . 'assets/css/issuu-painel-documents.min.css');

		switch (IssuuPanelConfig::getVariable('issuu_panel_reader')) {
			case 'issuu_embed':
				wp_enqueue_script(
					'issuu-panel-swfobject',
					ISSUU_PANEL_URL . 'assets/js/swfobject/swfobject.js',
					array('jquery'),
					null,
					true
				);
				$jsDependences[] = 'issuu-panel-swfobject';
				break;
			case 'issuu_panel_simple_reader':
				wp_enqueue_script(
					'issuu-panel-simple-reader',
					ISSUU_PANEL_URL . 'includes/reader/assets/js/jquery.issuupanelreader.min.js',
					array('jquery'),
					null,
					true
				);
				$jsDependences[] = 'issuu-panel-simple-reader';
				break;
		}
		
		wp_enqueue_script(
			'issuu-panel-reader',
			ISSUU_PANEL_URL . 'assets/js/issuu-panel-reader.min.js',
			$jsDependences,
			null,
			true
		);

		wp_localize_script(
			'issuu-panel-reader',
			'issuuPanelReaderObject',
			array(
				'adminAjax' => admin_url('admin-ajax.php')
			)
		);
		issuu_panel_debug("Hook wp_enqueue_scripts");
	}

	public function adminScripts()
	{
		wp_enqueue_style(
			'issuu-painel-pagination',
			ISSUU_PANEL_URL . 'assets/css/issuu-painel-pagination.min.css',
			array(),
			null,
			'screen, print'
		);
		wp_enqueue_style('document-list', ISSUU_PANEL_URL . 'assets/css/document-list.min.css', array(), null, 'screen, print');
		wp_enqueue_style('folder-list', ISSUU_PANEL_URL . 'assets/css/folder-list.min.css', array('dashicons'), null, 'screen, print');
		wp_enqueue_script('json2');
		wp_enqueue_script('jquery');

		if (isset($_GET['page']) && $_GET['page'] == 'issuu-document-admin')
		{
			if (isset($_GET['upload']))
			{
				wp_enqueue_script(
					'issuu-painel-document-upload-js',
					ISSUU_PANEL_URL . 'assets/js/document-upload.min.js',
					array('jquery'),
					null,
					true
				);
			}
			else if (isset($_GET['update']))
			{
				wp_enqueue_script(
					'issuu-painel-document-update-js',
					ISSUU_PANEL_URL . 'assets/js/document-update.min.js',
					array('jquery'),
					null,
					true
				);
			}
			else if (isset($_GET['url_upload']))
			{
				wp_enqueue_script(
					'issuu-painel-document-url-upload-js',
					ISSUU_PANEL_URL . 'assets/js/document-url-upload.min.js',
					array('jquery'),
					null,
					true
				);
			}
		}
		issuu_panel_debug("Hook admin_enqueue_scripts");
	}
}