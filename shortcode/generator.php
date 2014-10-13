<?php

$i = 1;
$max = count($docs);
$content = '<div id="issuu-iframe"><div data-url="' . $docs[0]['url'] . '" style="width: 100%; height: 323px;"
	class="issuuembed"></div><script type="text/javascript" src="//e.issuu.com/embed.js" async="true"></script></div>';
$content .= '<div id="issuu-painel-list">';

foreach ($docs as $doc) {
	if ($i % 3 == 1)
	{
		$content .= '<div class="issuu-document-row">';
	}

	$content .= '<div class="document-cell">';
	$content .= '<a href="' . $doc['url'] . '" class="link-issuu-document">';
	$content .= '<img src="' . $doc['thumbnail'] . '">';
	$content .= '</a><br>';
	$content .= '<span>' . $doc['title'] . '</span>';
	$content .= '</div>';

	if ($i % 3 == 0 || $i == $max)
	{
		$content .= '</div>';
	}

	$i++;
}

$content .= '</div>';