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
		if ($_SERVER['REQUEST_METHOD'] == 'POST'  && !isset($_POST['delete']))
		{
			include(ISSUU_PAINEL_DIR . 'menu/pasta/requests/add.php');
		}
		else
		{
			$load = true;

			include(ISSUU_PAINEL_DIR . 'menu/pasta/forms/add.php');
		}
	}
	else if (isset($_GET['folder']) && strlen($_GET['folder']) > 1)
	{
		$fo = $issuu_folder->update(array('folderId' => $_GET['folder']));

		if ($fo['stat'] == 'ok')
		{
			$issuu_bookmark = new IssuuBookmark($api_key, $api_secret);
			$bookmarks = $issuu_bookmark->issuuList(array('folderId' => $_GET['folder']));

			$fo = $fo['folder'];
			$image = 'http://image.issuu.com/%s/jpg/page_1_thumb_large.jpg';
			$folders_documents = array();

			$folders_documents['name'] = $fo->name;
			$folders_documents['items'] = $fo->items;

			if ($bookmarks['stat'] == 'ok' && isset($bookmarks['bookmark']) && !empty($bookmarks['bookmark']))
			{
				$folders_documents['documentsId'] = $bookmarks['bookmark'];
			}
			else
			{
				$folders_documents['documentsId'] = array();
			}

			include(ISSUU_PAINEL_DIR . 'menu/pasta/forms/update.php');
		}
		else
		{
			echo '<div class="error"><p>Pasta inexistente</p></div>';
		}

		$load = true;
	}

	if (!isset($load))
	{
		if ($_SERVER['REQUEST_METHOD'] == 'POST' && (isset($_POST['delete']) && $_POST['delete'] == 'true'))
		{
			include(ISSUU_PAINEL_DIR . 'menu/pasta/requests/delete.php');
		}

		$image = 'http://image.issuu.com/%s/jpg/page_1_thumb_large.jpg';
		$folders_documents = array();

		$folders = $issuu_folder->issuuList(array('folderSortBy' => 'created'));

		if (isset($folders['folder']) && !empty($folders['folder']))
		{
			$issuu_bookmark = new IssuuBookmark($api_key, $api_secret);
	
			foreach ($folders['folder'] as $f) {
				$fId = $f->folderId;
				$folders_documents[$fId] = array();
				$folders_documents[$fId]['name'] = $f->name;
				$folders_documents[$fId]['items'] = $f->items;

				$bookmarks = $issuu_bookmark->issuuList(array('pageSize' => 3, 'folderId' => $fId));

				if ($bookmarks['stat'] == 'ok' && (isset($bookmarks['bookmark']) && !empty($bookmarks['bookmark'])))
				{
					$folders_documents[$fId]['documentsId'] = $bookmarks['bookmark'];
				}
				else
				{
					$folders_documents[$fId]['documentsId'] = array();
				}
			}
		}

		include(ISSUU_PAINEL_DIR . 'menu/pasta/folder-list.php');
	}

	echo '</div><!-- FIM wrap -->';
}