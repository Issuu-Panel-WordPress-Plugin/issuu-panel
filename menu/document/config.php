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
		$action = new IssuuPanelAction();

		echo '<div class="wrap">';

		$issuuDocument = $this->getConfig()->getIssuuServiceApi('IssuuDocument');

		$action->setParam('issuuDocument', $issuuDocument);

		if (isset($_GET['upload']) && !isset($_POST['delete']))
		{
			if ($_SERVER['REQUEST_METHOD'] == 'POST')
			{
				$action = $this->getConfig()->getHookManager()->triggerAction(
					'issuu-panel-document-upload',
					null,
					array(
						'postData' => filter_input_array(INPUT_POST),
						'config' => $this->getConfig()
					)
				);
			}
			else
			{
				try {
					$issuu_folder = $this->getConfig()->getIssuuServiceApi('IssuuFolder');
					$folders = $issuu_folder->issuuList();
				} catch (Exception $e) {
					$this->getConfig()->getIssuuPanelDebug()->appendMessage("Page Exception - " . $e->getMessage());
					return "";
				}

				$cnt_f = (isset($folders['folder']))? count($folders['folder']) : 0;
				include(ISSUU_PANEL_DIR . 'menu/document/forms/upload.php');
				$load = true;
			}
		}
		else if (isset($_GET['url_upload']) && !isset($_POST['delete']))
		{
			if ($_SERVER['REQUEST_METHOD'] == 'POST')
			{
				$action = $this->getConfig()->getHookManager()->triggerAction(
					'issuu-panel-document-url-upload',
					null,
					array(
						'issuuDocument' => $issuuDocument
					)
				);
			}
			else
			{
				try {
					$issuu_folder = $this->getConfig()->getIssuuServiceApi('IssuuFolder');
					$folders = $issuu_folder->issuuList();
				} catch (Exception $e) {
					$this->getConfig()->getIssuuPanelDebug()->appendMessage("Page Exception - " . $e->getMessage());
					return "";
				}

				$cnt_f = (isset($folders['folder']))? count($folders['folder']) : 0;
				include(ISSUU_PANEL_DIR . 'menu/document/forms/url-upload.php');
				$load = true;
			}
		}
		else if (isset($_GET['ip-update']) && strlen($_GET['ip-update']) > 0)
		{
			$load = true;

			if ($_SERVER['REQUEST_METHOD'] == 'POST')
			{
				$action = $this->getConfig()->getHookManager()->triggerAction(
					'issuu-panel-document-update',
					null,
					array(
						'issuuDocument' => $issuuDocument
					)
				);

				$doc = $action->getParam('result');
				$load = false;
			}
			else
			{
				$params['name'] = strtr($_GET['ip-update'], array('%20' => '+', ' ' => '+'));

				try {
					$doc = $issuuDocument->update($params);
				} catch (Exception $e) {
					$this->getConfig()->getIssuuPanelDebug()->appendMessage("Page Exception - " . $e->getMessage());
					return "";
				}
			}

			if ($doc['stat'] == 'ok' && !empty($doc['document']))
			{
				if ($load)
				{
					$doc = $doc['document'];
				}
				else
				{
					$doc = $doc['document'];
				}
			}
			else
			{
				echo '<div class="error"><p>' . get_issuu_message('No documents found') . '</p></div>';
				exit;
			}

			$tags = '';

			if (isset($doc->tags))
			{
				foreach ($doc->tags as $tag) {
					$tags .= $tag . ',';
				}
			}

			if (($length = strlen($tags)) > 0)
			{
				$tags = substr($tags, 0, $length - 1);
			}

			include(ISSUU_PANEL_DIR . 'menu/document/forms/update.php');
		}

		echo $action->getParam('message', '');

		if (!isset($load))
		{
			if (filter_input(INPUT_SERVER, 'REQUEST_METHOD') == 'POST' && filter_input(INPUT_POST, 'delete') == 'true')
			{
				try {
					$action = $this->getConfig()->getHookManager()->triggerAction(
						'issuu-panel-document-delete',
						null,
						array(
							'issuuDocument' => $issuuDocument,
							'postData' => filter_input_array(INPUT_POST),
						)
					);
					require(ISSUU_PANEL_DIR . 'menu/document/requests/delete.php');
				} catch (Exception $e) {
					$this->getConfig()->getIssuuPanelDebug()->appendMessage(
						"Document Delete Exception - " . $e->getMessage()
					);
					return "";
				}
			}

			$image = 'http://image.issuu.com/%s/jpg/page_1_thumb_large.jpg';
			$page = (isset($_GET['pn']))? $_GET['pn'] : 1;
			$per_page = 10;
			$params = array(
				'pageSize' => $per_page,
				'startIndex' => $per_page * ($page - 1)
			);

			try {
				$docs = $issuuDocument->issuuList($params);
			} catch (Exception $e) {
				$this->getConfig()->getIssuuPanelDebug()->appendMessage("Page Exception - " . $e->getMessage());
				return "";
			}
			
			if (isset($docs['totalCount']) && $docs['totalCount'] > $docs['pageSize'])
			{
				$number_pages = ceil($docs['totalCount'] / $per_page);
			}

			require(ISSUU_PANEL_DIR . 'menu/document/document-list.php');
		}
	}
}