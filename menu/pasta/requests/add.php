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
	echo '<div class="updated"><p>Pasta criada com sucesso</p></div>';
}
else
{
	echo '<div class="error"><p>Erro ao criar pasta - ' . $response['message'] .
		(($response['field'] != '')? ' :' . $response['field'] : '') . '</p></div>';
}