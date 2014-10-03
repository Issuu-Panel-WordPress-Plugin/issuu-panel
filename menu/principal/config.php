<?php

add_action(ISSUU_PAINEL_PREFIX . 'menu_page', 'issuu_painel_menu_admin');

function issuu_painel_menu_admin()
{
	add_menu_page(
		'Issuu Painel',
		'Issuu Painel',
		'manage_options',
		'issuu-painel-admin',
		'issuu_painel_menu_admin_init',
		ISSUU_PAINEL_URL . 'images/icon2.png'
	);
}

function issuu_painel_menu_admin_init()
{
	global $api_key, $api_secret;

	echo '<div class="wrap">';

	$link_api_service = '<a target="_blank" href="https://issuu.com/home/settings/apikey">clique aqui</a> para gerar.';

	if ($_SERVER['REQUEST_METHOD'] == 'POST')
	{
		update_option(ISSUU_PAINEL_PREFIX . 'api_key', trim($_POST['api_key']));
		update_option(ISSUU_PAINEL_PREFIX . 'api_secret',trim($_POST['api_secret']));

		$api_key = get_option(ISSUU_PAINEL_PREFIX . 'api_key');
		$api_secret = get_option(ISSUU_PAINEL_PREFIX . 'api_secret');
	}

	if (strlen($api_key) <= 0)
	{
		echo "<div class=\"error\"><p>Insira uma chave de API. Caso não possua uma $link_api_service</p></div>";
	}

	if (strlen($api_secret) <= 0)
	{
		echo "<div class=\"error\"><p>Insira uma chave de API secreta. Caso não possua uma $link_api_service</p></div>";
	}

	echo '<h1>Issuu Painel Admin</h1>';

	echo "<form action=\"\" method=\"post\" accept-charset=\"utf-8\">";
	echo '<p><label for="api_key"><strong>Chave de API</strong></label><br>';
	echo "<input type=\"text\" name=\"api_key\" id=\"api_key\" placeholder=\"Insira a chave de API aqui\" value=\"$api_key\" style=\"width: 300px;\"><p>";
	echo '<p><label for="api_secret"><strong>Chave de API secreta</strong></label><br>';
	echo "<input type=\"text\" name=\"api_secret\" id=\"api_secret\" placeholder=\"Insira a chave de API secreta aqui\" value=\"$api_secret\" style=\"width: 300px;\"><p>";
	echo "<p><input type=\"submit\" class=\"button-primary\" value=\"Cadastrar\"></p>";
	echo "</form>";

	echo '</div>';
}