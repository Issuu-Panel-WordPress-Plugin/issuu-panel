<?php

class IssuuPanelShortcodes implements IssuuPanelService
{
	private $config;

	private $shortcodeGenerator;

	public function __construct(IssuuPanelShortcodeGenerator $shortcodeGenerator)
	{
		$this->shortcodeGenerator = $shortcodeGenerator;
		add_shortcode('issuu-painel-document-list', array($this, 'deprecatedDocumentsList'));
		add_shortcode('issuu-painel-folder-list', array($this, 'deprecatedFolderList'));
		add_shortcode('issuu-panel-document-list', array($this, 'documentsList'));
		add_shortcode('issuu-panel-folder-list', array($this, 'folderList'));
		add_shortcode('issuu-panel-last-document', array($this, 'lastDocument'));
	}

	public function deprecatedDocumentsList($atts)
	{
		$content = '';
		$content .= $this->documentsList($atts);
		$content = "<p><em>" .
			get_issuu_message(
				"The [issuu-painel-document-list] shortcode is deprecated. Please, use [issuu-panel-document-list] using the same parameters."
			) .
			"</em></p>" . $content;
		return $content;
	}

	public function deprecatedFolderList($atts)
	{
		$content = '';
		$content .= $this->folderList($atts);
		$content = "<p><em>" .
			get_issuu_message(
				"The [issuu-painel-folder-list] shortcode is deprecated. Please, use [issuu-panel-folder-list] using the same parameters."
			) .
			"</em></p>" . $content;
		return $content;
	}

	public function documentsList($atts)
	{
		$content = '';
		$shortcodeData = $this->getShortcodeData('issuu-panel-document-list');
		$atts = shortcode_atts(array(
			'order_by' => 'publishDate',
			'result_order' => 'desc',
			'per_page' => '12',
		), $atts);
		$params = array(
			'pageSize' => $atts['per_page'],
			'startIndex' => ($atts['per_page'] * ($shortcodeData['page'] - 1)),
			'resultOrder' => $atts['result_order'],
			'documentSortBy' => $atts['order_by']
		);
		$content .= $this->shortcodeGenerator->getFromCache($shortcodeData['shortcode'], $atts, $shortcodeData['page']);

		if (empty($content))
		{
			try {
				$issuuDocument = $this->getConfig()->getIssuuServiceApi('IssuuDocument');
				$result = $issuuDocument->issuuList($params);
				$this->getConfig()->getIssuuPanelDebug()->appendMessage(
					"Shortcode [issuu-panel-document-list]: Request Data - " . json_encode($issuuDocument->getParams())
				);

				if ($result['stat'] == 'ok')
				{
					$docs = $this->getDocs($result);
					$content = $this->shortcodeGenerator->getFromRequest($shortcodeData, $atts, $result, $docs);
				}
				else
				{
					$this->getConfig()->getIssuuPanelDebug()->appendMessage(
						"Shortcode [issuu-panel-document-list]: " . $results['message']
					);
					$content = '<em><strong>Issuu Panel:</strong> E' . $results['code'] . ' '
						. get_issuu_message($documents['message']) . '</em>';
				}
			} catch (Exception $e) {
				$content = "<em><strong>Issuu Panel:</strong> ";
				$content .= get_issuu_message("An error occurred while we try list your publications");
				$content .= "</em>";
				$this->getConfig()->getIssuuPanelDebug()->appendMessage(
					"Shortcode [issuu-panel-document-list]: Exception - " . $e->getMessage()
				);
			}
		}
		return $content;
	}

	public function folderList($atts)
	{
		$content = '';
		$shortcodeData = $this->getShortcodeData('issuu-panel-folder-list');
		$atts = shortcode_atts(array(
			'id' => '',
			'order_by' => 'publishDate',
			'result_order' => 'desc',
			'per_page' => '12',
		), $atts);
		$params = array(
			'folderId' => $atts['id'],
			'pageSize' => $atts['per_page'],
			'startIndex' => ($atts['per_page'] * ($shortcodeData['page'] - 1)),
			'resultOrder' => $atts['result_order'],
			'bookmarkSortBy' => $atts['order_by']
		);
		$content .= $this->shortcodeGenerator->getFromCache($shortcodeData['shortcode'], $atts, $shortcodeData['page']);

		if (empty($content))
		{
			if ($atts['order_by'] == 'publishDate')
			{
				unset($params['resultOrder']);
				unset($params['bookmarkSortBy']);
				$content .= $this->listOrderedByDate($params, $shortcodeData, $atts);
			}
			else
			{
				$content .= $this->listNotOrderedByDate($params, $shortcodeData, $atts);
			}
		}
		return $content;
	}

	public function lastDocument($atts)
	{
		$content = '';
		$shortcodeData = $this->getShortcodeData('issuu-panel-last-document');
		$atts = shortcode_atts(array(
			'id' => '',
			'link' => '',
			'order_by' => 'publishDate',
			'result_order' => 'desc',
			'per_page' => '12'
		), $atts);
		$content .= $this->shortcodeGenerator->getFromCache($shortcodeData['shortcode'], $atts, 1);

		if (empty($content))
		{
			try {
				if ($atts['id'] == '')
				{

				}
				else
				{

				}
			} catch (Exception $e) {
				$this->getConfig()->getIssuuPanelDebug()->appendMessage(
					"Shortcode [issuu-panel-last-document]: Exception - " . $e->getMessage()
				);
			}
		}
		return $content;
	}

	public function setConfig(IssuuPanelConfig $config)
	{
		$this->config = $config;
	}

	public function getConfig()
	{
		return $this->config;
	}

	private function getShortcodeData($shortcode)
	{
		$post = get_post();
		$postID = (!is_null($post) && $this->getConfig()->getIssuuPanelCatcher()->inContent())? $post->ID : 0;
		$issuu_shortcode_index = $this->getConfig()->getNextIteratorByTemplate();
		$inHook = $this->getConfig()->getIssuuPanelCatcher()->getCurrentHookIs();
		$page_query_name = 'ip_shortcode' . $issuu_shortcode_index . '_page';
		$this->getConfig()->getIssuuPanelDebug()->appendMessage("Shortcode [$shortcode]: Init");
		$this->getConfig()->getIssuuPanelDebug()->appendMessage(
			"Shortcode [$shortcode]: Index " . $issuu_shortcode_index . ' in hook ' . $inHook
		);
		$shortcode = $shortcode . $issuu_shortcode_index . $inHook . $postID;
		return array(
			'shortcode' => $shortcode,
			'page_query_name' => $page_query_name,
			'in_hook' => $inHook,
			'issuu_shortcode_index' => $issuu_shortcode_index,
			'post' => $post,
			'page' => (isset($_GET[$page_query_name]) && is_numeric($_GET[$page_query_name]))?
				intval($_GET[$page_query_name]) : 1,
		);
	}

	private function getDocs($results)
	{
		$docs = array();
		foreach ($results['document'] as $doc) {
			$docs[] = array(
				'id' => $doc->documentId,
				'thumbnail' => 'http://image.issuu.com/' . $doc->documentId . '/jpg/page_1_thumb_large.jpg',
				'url' => 'http://issuu.com/' . $doc->username . '/docs/' . $doc->name,
				'title' => $doc->title,
				'date' => date_i18n('d/F/Y', strtotime($doc->publishDate)),
				'pubTime' => strtotime($doc->publishDate),
				'pageCount' => $doc->pageCount
			);
		}
		return $docs;
	}

	private function getDocsFolder($results)
	{
		$docs = array();
		foreach ($results['bookmark'] as $book) {
			try {
				$issuuDocument = $this->getConfig()->getIssuuServiceApi('IssuuDocument');
				$document = $issuuDocument->update(array('name' => $book->name));
				$doc = array(
					'id' => $book->documentId,
					'thumbnail' => 'http://image.issuu.com/' . $book->documentId . '/jpg/page_1_thumb_large.jpg',
					'url' => 'http://issuu.com/' . $book->username . '/docs/' . $book->name,
					'title' => $book->title,
				);

				if (isset($document['document']))
				{
					$doc = array_merge($doc, array(
						'date' => date_i18n('d/F/Y', strtotime($document['document']->publishDate)),
						'pubTime' => strtotime($document['document']->publishDate),
						'pageCount' => $document['document']->pageCount
					));
				}
				$docs[] = $doc;
			} catch (Exception $e) {
				$this->getConfig()->getIssuuPanelDebug()->appendMessage(
					"IssuuDocument->update Exception - " . $e->getMessage()
				);
			}
		}
		return $docs;
	}

	private function listOrderedByDate($params, $shortcodeData, $atts)
	{
		$content = '';
		try {
			$issuuBookmark = $this->getConfig()->getIssuuServiceApi('IssuuBookmark');
			$result = $issuuBookmark->issuuList($params);
			$this->getConfig()->getIssuuPanelDebug()->appendMessage(
				"Shortcode [issuu-panel-folder-list]: Request Data - " . json_encode($issuuBookmark->getParams())
			);

			if ($result['stat'] == 'ok')
			{
				$docs = $this->getConfig()->getFolderCacheEntity()->getFolder($atts['id']);

				if (empty($docs) && !empty($result['bookmark']))
				{
					$docs = $this->getDocsFolder($result);
				}
				$docs = issuu_panel_quick_sort($docs, $atts['result_order']);
				$content = $this->shortcodeGenerator->getFromRequest(
					$shortcodeData,
					$atts,
					$result,
					$docs
				);
			}
			else
			{
				$this->getConfig()->getIssuuPanelDebug()->appendMessage(
					"Shortcode [issuu-panel-folder-list]: " . $results['message']
				);
				$content = '<em><strong>Issuu Panel:</strong> E' . $results['code'] . ' '
					. get_issuu_message($documents['message']) . '</em>';
			}
		} catch (Exception $e) {
			$content = "<em><strong>Issuu Panel:</strong> ";
			$content .= get_issuu_message("An error occurred while we try list your publications");
			$content .= "</em>";
			$this->getConfig()->getIssuuPanelDebug()->appendMessage(
				"Shortcode [issuu-panel-folder-list]: Exception - " . $e->getMessage()
			);
		}
		return $content;
	}

	private function listNotOrderedByDate($params, $shortcodeData, $atts)
	{
		$content = '';
		try {
			$issuuBookmark = $this->getConfig()->getIssuuServiceApi('IssuuBookmark');
			$result = $issuuBookmark->issuuList($params);
			$this->getConfig()->getIssuuPanelDebug()->appendMessage(
				"Shortcode [issuu-panel-folder-list]: Request Data - " . json_encode($issuuBookmark->getParams())
			);

			if ($result['stat'] == 'ok')
			{
				$docs = $this->getConfig()->getFolderCacheEntity()->getFolder($atts['id']);

				if (empty($docs) && !empty($result['bookmark']))
				{
					$docs = $this->getDocsFolder($result);
				}
				$content = $this->shortcodeGenerator->getFromRequest(
					$shortcodeData,
					$atts,
					$result,
					$docs
				);
			}
			else
			{
				$this->getConfig()->getIssuuPanelDebug()->appendMessage(
					"Shortcode [issuu-panel-folder-list]: " . $results['message']
				);
				$content = '<em><strong>Issuu Panel:</strong> E' . $results['code'] . ' '
					. get_issuu_message($documents['message']) . '</em>';
			}
		} catch (Exception $e) {
			$content = "<em><strong>Issuu Panel:</strong> ";
			$content .= get_issuu_message("An error occurred while we try list your publications");
			$content .= "</em>";
			$this->getConfig()->getIssuuPanelDebug()->appendMessage(
				"Shortcode [issuu-panel-folder-list]: Exception - " . $e->getMessage()
			);
		}
		return $content;
	}

	private function getDocumentOrderedByDate($params, $shortcodeData, $atts)
	{
		$content = '';
		return $content;
	}

	public function getDocumentNotOrderedByDate($params, $shortcodeData, $atts)
	{
		$content = '';
		$params = array(
			'pageSize' => '1',
			'resultOrder' => 'desc',
			'startIndex' => '0',
			'documentSortBy' => $atts['order_by'],
		);
		$issuuDocument = $this->getConfig()->getIssuuServiceApi('IssuuDocument');
		$result = $issuuDocument->issuuList($params);

		if ($result['stat'] == 'ok')
		{
			if (!empty($result['document']))
			{
				$docs = $result['document'];
				$doc = array(
					'thumbnail' => 'http://image.issuu.com/' . $docs[0]->documentId . '/jpg/page_1_thumb_large.jpg',
					'title' => $docs[0]->title,
					'url' => 'http://issuu.com/' . $docs[0]->username . '/docs/' . $docs[0]->name
				);
			}
			else
			{
				$doc = array();
			}

			
		}
		else
		{
			$this->getConfig()->getIssuuPanelDebug()->appendMessage(
				"Shortcode [issuu-panel-last-document]: " . $results['message']
			);
			$content = sprintf(
				'<em><strong>Issuu Panel:</strong> E%s %s</em>',
				$results['code'],
				get_issuu_message($documents['message'])
			);
		}
		return $content;
	}
}