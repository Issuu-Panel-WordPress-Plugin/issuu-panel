<?php

class IssuuPanelPageFolders extends IssuuPanelSubmenu
{
	protected $slug = 'issuu-folder-admin';

	protected $page_title = 'Folders';

	protected $menu_title = 'Folders';

	protected $priority = 2;

	public function page()
	{
		$this->getConfig()->getIssuuPanelDebug()->appendMessage("Issuu Panel Page (Folders)");

		echo '<div class="wrap">';

		try {
			$issuu_folder = $this->getConfig()->getIssuuServiceApi('IssuuFolder');
			$issuu_document = $this->getConfig()->getIssuuServiceApi('IssuuDocument');
		} catch (Exception $e) {
			$this->getConfig()->getIssuuPanelDebug()->appendMessage("Page Exception - " . $e->getMessage());
			return "";
		}

		if (isset($_GET['add']))
		{
			if ($_SERVER['REQUEST_METHOD'] == 'POST'  && !isset($_POST['delete']))
			{
				try {
					require(ISSUU_PANEL_DIR . 'menu/folder/requests/add.php');
				} catch (Exception $e) {
					$this->getConfig()->getIssuuPanelDebug()->appendMessage("Folder Add Exception - " . $e->getMessage());
					return "";
				}
			}
			else
			{
				$load = true;

				include(ISSUU_PANEL_DIR . 'menu/folder/forms/add.php');
			}
		}
		else if (isset($_GET['folder']) && strlen($_GET['folder']) > 1)
		{
			try {
				$fo = $issuu_folder->update(array('folderId' => $_GET['folder']));
			} catch (Exception $e) {
				$this->getConfig()->getIssuuPanelDebug()->appendMessage("Page Exception - " . $e->getMessage());
				return "";
			}

			if ($fo['stat'] == 'ok')
			{
				try {
					$issuu_bookmark = $this->getConfig()->getIssuuServiceApi('IssuuBookmark');
					$bookmarks = $issuu_bookmark->issuuList(array('folderId' => $_GET['folder']));
				} catch (Exception $e) {
					$this->getConfig()->getIssuuPanelDebug()->appendMessage("Page Exception - " . $e->getMessage());
					return "";
				}

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

				include(ISSUU_PANEL_DIR . 'menu/folder/forms/update.php');
			}
			else
			{
				echo '<div class="error"><p>' . get_issuu_message('The folder does not exist') . '</p></div>';
			}

			$load = true;
		}

		if (!isset($load))
		{
			if ($_SERVER['REQUEST_METHOD'] == 'POST' && (isset($_POST['delete']) && $_POST['delete'] == 'true'))
			{
				try {
					require(ISSUU_PANEL_DIR . 'menu/folder/requests/delete.php');
				} catch (Exception $e) {
					$this->getConfig()->getIssuuPanelDebug()->appendMessage("Page Exception - " . $e->getMessage());
					return "";
				}
			}

			$image = 'http://image.issuu.com/%s/jpg/page_1_thumb_large.jpg';
			$page = (isset($_GET['pn']))? $_GET['pn'] : 1;
			$per_page = 10;
			$params = array(
				'pageSize' => $per_page,
				'folderSortBy' => 'created',
				'startIndex' => $per_page * ($page - 1)
			);
			$folders_documents = array();

			try {
				$folders = $issuu_folder->issuuList($params);
			} catch (Exception $e) {
				$this->getConfig()->getIssuuPanelDebug()->appendMessage("Page Exception - " . $e->getMessage());
				return "";
			}

			if (isset($folders['totalCount']) && $folders['totalCount'] > $folders['pageSize'])
			{
				$number_pages = ceil($folders['totalCount'] / $per_page);
			}

			if (isset($folders['folder']) && !empty($folders['folder']))
			{
				try {
					$issuu_bookmark = $this->getConfig()->getIssuuServiceApi('IssuuBookmark');
			
					foreach ($folders['folder'] as $f) {
						$fId = $f->folderId;
						$folders_documents[$fId] = array(
							'name' => $f->name,
							'items' => $f->items
						);

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
				} catch (Exception $e) {
					$this->getConfig()->getIssuuPanelDebug()->appendMessage("Page Exception - " . $e->getMessage());
					return "";
				}
			}

			include(ISSUU_PANEL_DIR . 'menu/folder/folder-list.php');
		}

		echo '</div><!-- FIM wrap -->';
	}
}