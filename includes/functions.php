<?php

function issuu_painel_quick_sort($array, $order = 'asc')
{
	$length = count($array);
	
	if ($length <= 1)
	{
		return $array;
	}
	else
	{
		$pivot = $array[0];
		$left = $right = array();
		$c = count($array);
		for ($i = 1; $i < $c; $i++) {
			if ($order == 'asc')
			{
				if ($array[$i]['pubTime'] < $pivot['pubTime'])
				{
					$left[] = $array[$i];
				}
				else
				{
					$right[] = $array[$i];
				}
			}
			else
			{
				if ($array[$i]['pubTime'] > $pivot['pubTime'])
				{
					$left[] = $array[$i];
				}
				else
				{
					$right[] = $array[$i];
				}
			}
		}
		
		return array_merge(issuu_painel_quick_sort($left), array($pivot), issuu_painel_quick_sort($right));
	}
}

function get_issuu_message($text)
{
	return __($text, ISSUU_PAINEL_DOMAIN_LANG);
}

function the_issuu_message($text)
{
	_e($text, ISSUU_PAINEL_DOMAIN_LANG);
}

function add_youtube_button()
{
	if (!current_user_can('edit_posts') && !current_user_can('edit_pages'))
		return;
	if (get_user_option('rich_editing') == 'true')
	{
		add_filter('mce_external_plugins', 'add_issuu_painel_tinymce_plugin');
		add_filter('mce_buttons', 'register_issuu_painel_button');
	}
}

add_action('init', 'add_youtube_button');

function register_issuu_painel_button($buttons)
{
	array_push($buttons, "|", "issuupainel");
	return $buttons;
}

function add_issuu_painel_tinymce_plugin($plugin_array)
{
	$plugin_array['issuupainel'] = ISSUU_PAINEL_URL . 'js/tinymce-button.js';
	return $plugin_array;
}

function issuu_painel_refresh_mce($ver)
{
	$ver += 3;
	return $ver;
}

add_filter('tiny_mce_version', 'issuu_painel_refresh_mce');

function issuu_painel_tinymce_ajax()
{
	global $api_key, $api_secret;
	?>

	<!DOCTYPE html>
	<html>
	<head>
		<title>Issuu Painel Shortcode</title>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<link rel="stylesheet" href="<?= ISSUU_PAINEL_URL; ?>css/issuu-painel-tinymce-popup.css">
		<?php
			wp_enqueue_script('tiny_mce_popup.js', includes_url('js/tinymce/tiny_mce_popup.js'));
			wp_print_scripts('tiny_mce_popup.js');
		?>
	</head>
	<body>
		<div id="issuu-painel-table">
			<div class="issuu-painel-table-row">
				<div class="issuu-painel-table-cell label"><?php the_issuu_message('Folder'); ?></div>
				<div class="issuu-painel-table-cell">
					<select name="folderId">
						<option value="none"><?php the_issuu_message('Select...'); ?></option>
					</select>
				</div>
			</div>
		</div>
	</body>
	</html>

	<?php
	die();
}

add_action('wp_ajax_issuu_painel_tinymce_ajax', 'issuu_painel_tinymce_ajax');
