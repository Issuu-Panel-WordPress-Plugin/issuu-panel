<?php

function issuu_painel_embed_documents_shortcode($atts)
{
	global $api_key, $api_secret;

	$atts = shortcode_atts(
		array(
			'order_by' => 'publishDate',
			'result_order' => 'desc',
			'per_page' => '12'
		),
		$atts
	);

	$page = (($page = get_query_var('page')) == 0)? 1 : $page;
	$params = array(
		'pageSize' => $atts['per_page'],
		'startIndex' => ($atts['per_page'] * ($page - 1)),
		'resultOrder' => $atts['result_order'],
		'documentSortBy' => $atts['order_by']
	);

	$issuu_document = new IssuuDocument($api_key, $api_secret);
	$documents = $issuu_document->issuuList($params);

	if ($documents['stat'] == 'ok')
	{
		if (isset($documents['document']) && !empty($documents['document']))
		{
			$docs = array();

			foreach ($documents as $doc) {
				$docs[] = array(
					'thumbnail' => 'http://image.issuu.com/' . $doc->documentId . '/jpg/page_1_thumb_large.jpg',
					'url' => 'http://issuu.com/' . $doc->username . '/docs/' . $doc->name,
					'title' => $doc->title,
					'date' => date_i18n('d/F/Y', strtotime($doc->publishDate)) 
				);
			}
			
			include(ISSUU_PAINEL_DIR . 'shortcode/generator.php');

			return $content;
		}
		else
		{
			return '<h3>No documents in list</h3>';
		}
	}
	else
	{
		return '<h3>' . $documents['message'] . '</h3>';
	}

}

add_shortcode('issuu-painel-document-list', 'issuu_painel_embed_documents_shortcode');