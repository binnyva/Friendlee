<?php
function showHead($title='') {
	require(iframe\App::$config['iframe_folder'] . '/templates/layout/head.php');
}
function showBegin() {
	require(iframe\App::$config['iframe_folder'] . '/templates/layout/begin.php');
}
function showTop($title='') {
	showHead($title);
	showBegin();
}
function showEnd() {
	require(iframe\App::$config['iframe_folder'] . '/templates/layout/end.php');
}

function render($file='', $use_layout=true, $use_exact_path = false, $variable_array=false) {
	//If it is an ajax request, we don't have to render the page.
	if(isset($_REQUEST['ajax'])) {
		print '{"success":"Done","error":false}';
		return;
	}
	
	//Otherwise, render it.
	iframe\App::$template->render($variable_array, [
									'template' 	=> $file, 
									'use_layout'=> $use_layout, 
									'use_exact_path'=> $use_exact_path 
								]);
}

function setupBackwardCompatibility() {
	$config = iframe\App::$config;

	$config['app_title'] = i($config, 'app_name', '');
	$config['app_home'] = i($config, 'app_url', '');

	// Lots of site_ config options were moved to app_. Eg site_title is now app_title
	foreach($config as $key => $value) {
		if(preg_match("/^app_/", $key)) {
			$site_key = str_replace('app_', "site_", $key);
			$config[$site_key] = $value;
		}
	}
	$config['mode'] = $config['env'];

	$GLOBALS['config'] = $config;
	$GLOBALS['rel'] = $config['app_relative_path'];
	$GLOBALS['abs'] = $config['app_absolute_path'];

	$GLOBALS['sql'] = iframe\App::$db;

	iframe\App::$template->css_folder = 'css';
	iframe\App::$template->js_folder = 'js';
	$GLOBALS['template'] = iframe\App::$template;
	$GLOBALS['i_plugin'] = iframe\App::$plugin;
}

