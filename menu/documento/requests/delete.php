<?php

$params['names'] = '';
$count = count($_POST['name']);

if ($count > 0)
{
	for ($i = 0; $i < $count; $i++) {
		if ($i == ($count - 1))
		{
			$params['names'] .= $_POST['name'][$i];
		}
		else
		{
			$params['names'] .= $_POST['name'][$i] . ',';
		}
	}

	$result = $issuu_document->delete($params);

	if ($result['stat'] == 'ok')
	{
		if ($count > 1)
		{
			echo '<div class="updated"><p>Documentos excluídos com sucesso</p></div>';
		}
		else
		{
			echo '<div class="updated"><p>Documento excluído com sucesso</p></div>';
		}
	}
	else if ($result['stat'] == 'fail')
	{
		echo '<div class="error"><p>' . $result['message'] . '</p></div>';
	}
}
else
{
	echo '<div class="update-nag">Nada foi excluído</div>';
}