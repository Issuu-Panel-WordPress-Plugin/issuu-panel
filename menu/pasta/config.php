<?php

add_action(ISSUU_PAINEL_PREFIX . 'submenu_pages', 'issuu_painel_menu_folder');

function issuu_painel_menu_folder()
{
	add_submenu_page(
		'issuu-painel-admin',
		'Pasta',
		'Pasta',
		'manage_options',
		'issuu-folder-admin',
		'issuu_painel_menu_folder_init'
	);
}

function issuu_painel_menu_folder_init()
{
	global $api_key, $api_secret;

	echo '<div class="wrap">';

	$issuu_folder = new IssuuFolder($api_key, $api_secret);
	$issuu_document = new IssuuDocument($api_key, $api_secret);

	if (isset($_GET['add']))
	{
		if ($_SERVER['REQUEST_METHOD'] == 'POST')
		{

		}
		else
		{
			$load = true;
		}
	}

	if (!isset($load))
	{
		$folders = $issuu_folder->issuuList(array('folderSortBy' => 'created'));
		$documents = $issuu_document->issuuList();

		$image = 'http://image.issuu.com/%s/jpg/page_1_thumb_large.jpg';
		$folders_documents = array();

		if (isset($folders['folder']) && !empty($folders['folder']))
		{
			foreach ($folders['folder'] as $f) {
				$fId = $f->folderId;
				$folders_documents[$fId] = array();
				$folders_documents[$fId]['name'] = $f->name;
				$folders_documents[$fId]['documentsId'] = array();
				$folders_documents[$fId]['items'] = $f->items;
			}
		}

		if (isset($documents['document']) && !empty($documents['document']))
		{
			foreach ($documents['document'] as $doc) {
				if (isset($doc->folders))
				{
					foreach ($doc->folders as $f) {
						$folders_documents[$f]['documentsId'][] = $doc->documentId;
					}
				}
			}
		}

		include(ISSUU_PAINEL_DIR . 'menu/pasta/folder-list.php');
	}

	echo '</div><!-- FIM wrap -->';
}