<?php

class IssuuPanelUpdateDataListener
{
	public function __construct()
	{
		add_action('post-issuu-panel-config', array($this, 'postConfigData'));
		add_action('on-flush-issuu-panel-cache', array($this, 'onFlushCache'));
		add_action('on-cron-flush-issuu-panel-cache', array($this, 'onFlushCache'));
		add_action('on-cron-update-folder-documents', array($this, 'onUpdateFolderDocuments'));
		add_action('on-construct-issuu-panel-plugin-manager', array($this, 'initListener'));
		add_action('on-shutdown-issuu-panel', array($this, 'persistConfigData'));
	}

	public function postConfigData(IssuuPanelHook $hook)
	{
		$config = $hook->getParam('config');
		$postData = $hook->getParam('postData');

		$config->getOptionEntity()->setApiKey($postData['api_key']);
		$config->getOptionEntity()->setApiSecret($postData['api_secret']);
		$config->getOptionEntity()->setReader($postData['issuu_panel_reader']);
		$config->getOptionEntity()->setEnabledUser($postData['enabled_user']);

		if (isset($postData['issuu_panel_debug']) && $postData['issuu_panel_debug'] == 'active')
		{
			$config->getOptionEntity()->setDebug('active');
		}
		else
		{
			$postData['issuu_panel_debug'] = 'disable';
			$config->getOptionEntity()->setDebug('disable');
		}

		if (isset($postData['issuu_panel_cache_status']) && $postData['issuu_panel_cache_status'] == 'active')
		{
			$config->getOptionEntity()->setCacheStatus('active');
		}
		else
		{
			$postData['issuu_panel_cache_status'] = 'disable';
			$config->getOptionEntity()->setCacheStatus('disable');
		}
		$config->getIssuuPanelDebug()->appendMessage("Issuu Panel options updated in init hook");
		$hook->setParam('postData', $postData);
	}

	public function initListener(IssuuPanelHook $hook)
	{
		$config = $hook->getParam('config');
		$config->getIssuuPanelCron()->addScheduledAction('on-cron-flush-issuu-panel-cache', 'hour');
		$config->getIssuuPanelCron()->addScheduledAction('on-cron-update-folder-documents', 600);
	}

	public function persistConfigData(IssuuPanelHook $hook)
	{
		$config = $hook->getParam('config');
		$config->getOptionEntityManager()->updateOptionEntity(
			$config->getOptionEntity()
		);
	}

	public function onFlushCache(IssuuPanelHook $hook)
	{
		$config = $hook->getParam('config');
		$config->getOptionEntity()->setShortcodeCache(array());
	}

	public function onUpdateFolderDocuments(IssuuPanelHook $hook)
	{
		$documents = array();
		$config = $hook->getParam('config');
		$debug = $config->getIssuuPanelDebug();
		$filepath = $debug->getLogDir() . 'folder-documents.txt';
		$issuuFolder = $config->getIssuuServiceApi('IssuuFolder');
		$issuuBookmark = $config->getIssuuServiceApi('IssuuBookmark');
		$issuuDocument = $config->getIssuuServiceApi('IssuuDocument');
		$pageFolder = 1;
		$pageBookmark = 1;
		$perPage = 25;
		do {
			try {
				$folders = $issuuFolder->issuuList(array(
					'pageSize' => $perPage,
					'startIndex' => $perPage * ($pageFolder - 1),
				));

				if ($folders['stat'] == 'ok')
				{
					foreach ($folders['folder'] as $folder) {
						$docs = array();
						do {
							$bookmarks = $issuuBookmark->issuuList(array(
								'folderId' => $folder->folderId,
								'pageSize' => $perPage,
								'startIndex' => $perPage * ($pageBookmark - 1),
							));

							if ($bookmarks['stat'] == 'ok')
							{
								foreach ($bookmarks['bookmark'] as $bookmark) {
									$document = $issuuDocument->update(array('name' => $bookmark->name));

									if ($document['stat'] == 'ok')
									{
										$docs[] = array(
											'id' => $bookmark->documentId,
											'thumbnail' => 'http://image.issuu.com/' . $bookmark->documentId .
												'/jpg/page_1_thumb_large.jpg',
											'url' => 'http://issuu.com/' . $bookmark->username . '/docs/' . $bookmark->name,
											'title' => $bookmark->title,
											'date' => date_i18n('d/F/Y', strtotime($document['document']->publishDate)),
											'pubTime' => strtotime($document['document']->publishDate),
											'pageCount' => $document['document']->pageCount
										);
									}
								}
							}
							$pageBookmark++;
						} while (isset($bookmarks['more']) && $bookmarks['more']);
						$documents[$folder->folderId] = $docs;
					}
				}
			} catch (Exception $e) {
				$debug->appendMessage(
					'Exception on IssuuPanelUpdateDataListener->onUpdateFolderDocuments - ' . $e->getMessage()
				);
			}
			$pageFolder++;
		} while (isset($folders['more']) && $folders['more']);
		file_put_contents($filepath, serialize($documents));
	}
}