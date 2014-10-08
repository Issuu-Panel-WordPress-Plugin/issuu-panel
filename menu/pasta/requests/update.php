<?php

foreach ($_POST as $key => $value) {
	$_POST[$key] = trim($value);
}

$response = $issuu_folder->update($_POST);

if ($response['stat'] == 'ok')
{
	echo '<div class="updated"><p>Pasta atualizada com sucesso</p></div>';
}
else
{
	echo '<div class="error"><p>Erro ao atualizar página:' . $response['message'] . '</p></div>';
}