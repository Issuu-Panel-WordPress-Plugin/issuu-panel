<?php

function retornaDataHora()
{
	$current_offset = get_option('gmt_offset');
	$tzstring = get_option('timezone_string');
	$check_zone_info = true;

	if (false !== strpos($tzstring, 'Etc/GMT'))
		$tzstring = '';

	if (empty($tzstring))
	{
		$check_zone_info = false;
		if ( 0 == $current_offset )
			$tzstring = 'UTC+0';
		elseif ($current_offset < 0)
			$tzstring = 'UTC' . $current_offset;
		else
			$tzstring = 'UTC+' . $current_offset;
	}

	$pub = $_POST['pub'];

	$timezone_format = _x('c', 'timezone date format');
	if ($check_zone_info && $tzstring)
	{
		date_default_timezone_set($tzstring);

		print_r(localtime(strtotime('21/08/2014'), true));
	}
	else
	{
		date_i18n($timezone_format);
	}
}

function montarSelect()
{
	$current_offset = get_option('gmt_offset');
	$tzstring = get_option('timezone_string');
	$check_zone_info = true;

	if (false !== strpos($tzstring, 'Etc/GMT'))
		$tzstring = '';

	if (empty($tzstring))
	{
		$check_zone_info = false;
		if ( 0 == $current_offset )
			$tzstring = 'UTC+0';
		elseif ($current_offset < 0)
			$tzstring = 'UTC' . $current_offset;
		else
			$tzstring = 'UTC+' . $current_offset;
	}

	echo '<select name="timezone">';
	echo wp_timezone_choice($tzstring);
	echo '</select>';
}