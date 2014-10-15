<?php

foreach ($_POST as $key => $value) {
	if (($_POST[$key] = trim($value)) == "")
	{
		unset($_POST[$key]);
	}
}

$response = $issuu_folder->add($_POST);

if ($response['stat'] == 'ok')
{
	echo '<div class="updated"><p>' . __('Folder created successfully', ISSUU_PAINEL_DOMAIN_LANG) . '</p></div>';
}
else
{
	echo '<div class="error"><p>' . __('Error while creating the folder', ISSUU_PAINEL_DOMAIN_LANG) . ' - ' .
		$response['message'] . (($response['field'] != '')? ' :' . $response['field'] : '') . '</p></div>';
}