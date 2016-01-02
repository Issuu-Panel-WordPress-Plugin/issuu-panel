<?php

class IssuuPanelPageDocuments extends IssuuPanelSubmenu
{
	protected $slug = 'issuu-document-admin';

	protected $page_title = 'Documents';

	protected $menu_title = 'Documents';

	protected $priority = 1;

	public function page()
	{
		$this->getConfig()->getIssuuPanelDebug()->appendMessage("Issuu Panel Page (Documents)");
		$issuuDocument = $this->getConfig()->getIssuuServiceApi('IssuuDocument');
		$issuuFolder = $this->getConfig()->getIssuuServiceApi('IssuuFolder');
		$subpage = filter_input(INPUT_GET, 'issuu-panel-subpage');
		$validPages = array('upload', 'url-upload', 'update');
		try {
			switch ($subpage) {
				case 'upload':
					$folders = $issuuFolder->issuuList();
					$cnt_f = (isset($folders['folder']))? count($folders['folder']) : 0;
				case 'url-upload':
					include(ISSUU_PANEL_DIR . "menu/document/forms/$subpage.php");
					break;
				case 'update':
					$doc = $issuuDocument->update(array(
						'name' => filter_input(INPUT_GET, 'document')
					));
					$tags = '';

					if ($doc['stat'] == 'ok' && !empty($doc['document']))
					{
						$doc = $doc['document'];

						if (isset($doc->tags))
						{
							$tags = implode(',', $doc->tags);
						}
					}
					else
					{
						$this->getErrorMessage(get_issuu_message('No documents found'));
						return;
					}
					include(ISSUU_PANEL_DIR . 'menu/document/forms/update.php');
					break;
				case null:
					$image = 'http://image.issuu.com/%s/jpg/page_1_thumb_large.jpg';
					$page = (intval(filter_input(INPUT_GET, 'pn')))? : 1;
					$per_page = 10;
					$docs = $issuuDocument->issuuList(array(
						'pageSize' => $per_page,
						'startIndex' => $per_page * ($page - 1)
					));
					
					if (isset($docs['totalCount']) && $docs['totalCount'] > $docs['pageSize'])
					{
						$number_pages = ceil($docs['totalCount'] / $per_page);
					}
					require(ISSUU_PANEL_DIR . 'menu/document/document-list.php');
					break;
				default:
					$this->getConfig()->getIssuuPanelDebug()->appendMessage("Page not found");
					$this->getErrorMessage(get_issuu_message('This page not exists'));
					return;
			}
		} catch (Exception $e) {
			$this->getConfig()->getIssuuPanelDebug()->appendMessage("Page Exception - " . $e->getMessage());
			$this->getErrorMessage(get_issuu_message('An error occurred while we try connect to Issuu'));
		}
	}
}