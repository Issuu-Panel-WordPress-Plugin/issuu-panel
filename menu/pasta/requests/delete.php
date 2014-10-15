<?php


$params['folderIds'] = '';
$count = count($_POST['folderId']);

if ($count > 0)
{
	for ($i = 0; $i < $count; $i++) {
		if ($i == ($count - 1))
		{
			$params['folderIds'] .= $_POST['folderId'][$i];
		}
		else
		{
			$params['folderIds'] .= $_POST['folderId'][$i] . ',';
		}
	}

	$result = $issuu_folder->delete($params);

	if ($result['stat'] == 'ok')
	{
		if ($count > 1)
		{
			echo '<div class="updated"><p>' . __('Folders deleted successfully', ISSUU_PAINEL_DOMAIN_LANG) . '</p></div>';
		}
		else
		{
			echo '<div class="updated"><p>' . __('Folder deleted successfully', ISSUU_PAINEL_DOMAIN_LANG) . '</p></div>';
		}
	}
	else if ($result['stat'] == 'fail')
	{
		echo '<div class="error"><p>' . $result['message'] . '</p></div>';
	}
}
else
{
	echo '<div class="update-nag">' . __('Nothing was excluded', ISSUU_PAINEL_DOMAIN_LANG) . '</div>';
}