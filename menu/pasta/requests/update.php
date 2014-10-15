<?php

foreach ($_POST as $key => $value) {
	$_POST[$key] = trim($value);
}

$response = $issuu_folder->update($_POST);

if ($response['stat'] == 'ok')
{
	echo '<div class="updated"><p>' . __('Folder updated successfully', ISSUU_PAINEL_DOMAIN_LANG) . '</p></div>';
}
else
{
	echo '<div class="error"><p>' . __('Error while updating the folder', ISSUU_PAINEL_DOMAIN_LANG) . ' - ' .
		$response['message'] . '</p></div>';
}