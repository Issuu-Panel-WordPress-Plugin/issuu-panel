<?php

$i = 1;
$max = count($docs);
$content = '<div id="issuu-iframe">';
$content .= '<div data-doc-id="' . $docs[0]['id'] . '" style="width: 100%; height: 323px;" class="issuuembed issuu-isrendered">';
$content .= '<div style="width:100%; height:100%;">';
$content .= '<div style="height:-moz-calc(100% - 18px); height:-webkit-calc(100% - 18px); height:-o-calc(100% - 18px); height:calc(100% - 18px);">';
$content .= '<object id="issuu_18089929316192865" style="width:100%;height:100%" type="application/x-shockwave-flash" data="http://static.issuu.com/webembed/viewers/style1/v2/IssuuReader.swf">';
$content .= '<param name="movie" value="http://static.issuu.com/webembed/viewers/style1/v2/IssuuReader.swf">';
$content .= '<param name="flashvars" value="mode=mini&amp;pageNumber=issuu.com&amp;documentId=' . $docs[0]['id'] . '&amp;embedId=1">';
$content .= '<param name="allowfullscreen" value="true">';
$content .= '<param name="allowscriptaccess" value="always">';
$content .= '<param name="menu" value="false">';
$content .= '<param name="wmode" value="transparent">';
$content .= '</object>';
$content .= '</div>';
$content .= '</div>';
$content .= '</div>';
$content .= '</div><!-- /#issuu-iframe -->';

$content .= '<div id="issuu-painel-list">';

foreach ($docs as $doc) {
	if ($i % 3 == 1)
	{
		$content .= '<div class="issuu-document-row">';
	}

	$content .= '<div class="document-cell">';
	$content .= '<a href="' . $doc['id'] . '" class="link-issuu-document">';
	$content .= '<img src="' . $doc['thumbnail'] . '">';
	$content .= '</a><br>';
	$content .= '<span>' . $doc['title'] . '</span>';
	$content .= '</div>';

	if ($i % 3 == 0 || $i == $max)
	{
		$content .= '</div><!-- /.issuu-document-row -->';
	}

	$i++;
}

$content .= '</div><!-- /#issuu-painel-list -->';