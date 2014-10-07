<?php

add_action(ISSUU_PAINEL_PREFIX . 'submenu_pages', 'issuu_painel_menu_document');

function issuu_painel_menu_document()
{
	add_submenu_page(
		'issuu-painel-admin',
		'Documento',
		'Documento',
		'manage_options',
		'issuu-document-admin',
		'issuu_painel_menu_document_init'
	);
}

function issuu_painel_menu_document_init()
{
	global $api_key, $api_secret;

	echo '<div class="wrap">';

	$issuu_document = new IssuuDocument($api_key, $api_secret);

	if (isset($_GET['upload']) && !isset($_POST['delete']))
	{
		if ($_SERVER['REQUEST_METHOD'] == 'POST')
		{
			$date = date_i18n('Y-m-d') . 'T';
			$time = date_i18n('H:i:s') . 'Z';
			$datetime = $date . $time;
			
			include(ISSUU_PAINEL_DIR . 'menu/documento/requests/upload.php');

			// sleep(20 * 1000);
		}
		else
		{
			$issuu_folder = new IssuuFolder($api_key, $api_secret);
			$folders = $issuu_folder->issuuList();
			$cnt_f = (isset($folders['folder']))? count($folders['folder']) : 0;
			include(ISSUU_PAINEL_DIR . 'menu/documento/forms/upload.php');
			$load = true;
		}
	}
	else if (isset($_GET['url_upload']) && !isset($_POST['delete']))
	{
		if ($_SERVER['REQUEST_METHOD'] == 'POST')
		{
			$date = date_i18n('Y-m-d') . 'T';
			$time = date_i18n('H:i:s') . 'Z';
			$datetime = $date . $time;

			include(ISSUU_PAINEL_DIR . 'menu/documento/requests/url-upload.php');
		}
		else
		{
			$issuu_folder = new IssuuFolder($api_key, $api_secret);
			$folders = $issuu_folder->issuuList();
			$cnt_f = (isset($folders['folder']))? count($folders['folder']) : 0;
			include(ISSUU_PAINEL_DIR . 'menu/documento/forms/url-upload.php');
			$load = true;
		}
	}
	else if (isset($_GET['update']) && strlen($_GET['update']) > 0)
	{
		$load = true;

		if ($_SERVER['REQUEST_METHOD'] == 'POST')
		{
			$date = date_i18n('Y-m-d') . 'T';
			$time = date_i18n('H:i:s') . 'Z';
			$datetime = $date . $time;

			include(ISSUU_PAINEL_DIR . 'menu/documento/requests/update.php');

			$doc = $result;
			$load = false;
		}
		else
		{
			$params['orgDocName'] = strtr($_GET['update'], array('%20' => '+', ' ' => '+'));
			$params['pageSize'] = 1;

			$doc = $issuu_document->issuuList($params);
		}

		if ($doc['stat'] == 'ok' && !empty($doc['document']))
		{
			if ($load)
			{
				$doc = $doc['document'][0];
			}
			else
			{
				$doc = $doc['document'];
			}
		}
		else
		{
			echo '<div class="error"><p>Nenhum documento encontrado ou ao atualizá-lo</p></div>';
			exit;
		}

		$tags = '';

		if ($doc->tags)
		{
			foreach ($doc->tags as $tag) {
				$tags .= $tag . ',';
			}
		}

		if (($length = strlen($tags)) > 0)
		{
			$tags = substr($tags, 0, $length - 1);
		}

		include(ISSUU_PAINEL_DIR . 'menu/documento/forms/update.php');
	}

	if (!isset($load))
	{
		if ($_SERVER['REQUEST_METHOD'] == 'POST' && (isset($_POST['delete']) && $_POST['delete'] == 'true'))
		{
			include(ISSUU_PAINEL_DIR . 'menu/documento/requests/delete.php');
		}

		$image = 'http://image.issuu.com/%s/jpg/page_1_thumb_large.jpg';

		$docs = $issuu_document->issuuList();

		include(ISSUU_PAINEL_DIR . 'menu/documento/document-list.php');
	}
}