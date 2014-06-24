<?php
error_reporting(E_ALL);
define('ABS', dirname(__FILE__));
require_once ABS.'/config.php';
require_once ABS.'/functions.php';


$path_info = '/';
if (!empty($_SERVER['PATH_INFO'])) {
	$path_info = $_SERVER['PATH_INFO'];
} else if (!empty($_SERVER['ORIG_PATH_INFO']) && $_SERVER['ORIG_PATH_INFO'] !== '/index.php') {
	$path_info = $_SERVER['ORIG_PATH_INFO'];
} else {
	if (!empty($_SERVER['REQUEST_URI'])) {
		$path_info = (strpos($_SERVER['REQUEST_URI'], '?') > 0) ? strstr($_SERVER['REQUEST_URI'], '?', true) : $_SERVER['REQUEST_URI'];
	}
}
$parts = preg_split('#/#', $path_info, -1);
foreach ($parts as $i => $p){
	if ( empty($p) ){
		unset($parts[$i]);
	}
}
$parts = array_values($parts);

$query_vars = array();
$home = true;

switch ( count($parts) ){
	case 3:
		$query_vars['country'] = urldecode($parts[0]);
		if ( $parts[1] != '+' ){
			$query_vars['loc'] = dash2space(urldecode($parts[1]));
		}
		$query_vars['job'] = dash2space(urldecode($parts[2]));
		$home = false;
		break;
	case 2:
		$query_vars['country'] = urldecode($parts[0]);
		$query_vars['loc'] = dash2space(urldecode($parts[1]));
		$home = false;
		break;
	case 1:
		$query_vars['country'] = urldecode($parts[0]);
		$home = false;
		break;
}

if ( isset($query_vars['country']) ){
	$old_local = isset($_COOKIE["locale"]) ? $_COOKIE["locale"] : '';
	$new_local = isset($_locales[$query_vars['country']]) ? $_locales[$query_vars['country']] : '';
	
	if ( $new_local == '' ){
		$new_local = $_locales['US'];
		$query_vars['country'] = 'US';
		$redirect = true;
	} else if ( $new_local != $old_local ){
		$locale = $new_local;
		setcookie("locale", $locale, time()+2592000, '/');
		unset($query_vars['job']);
		unset($query_vars['loc']);
		header('Location: ' . get_site_url() );
		exit;
	} else {
		$locale = $new_local;
	}
} else {
	if ( isset($_COOKIE["locale"]) ){
		$locale = $_COOKIE["locale"];
	}
	$locale = get_locale();
	@list($tmp, $_country) = explode('-', $locale);
	$query_vars['country'] = $_country;
}

if ( isset($_REQUEST['keyword']) && !empty($_REQUEST['keyword']) && $_REQUEST['keyword'] != @$query_vars['job'] ){
	$query_vars['job'] = dash2space($_REQUEST['keyword']);
	$redirect = true;
}
if ( isset($_REQUEST['job_location']) && !empty($_REQUEST['job_location']) && $_REQUEST['job_location'] != @$query_vars['loc'] ){
	$query_vars['loc'] = dash2space($_REQUEST['job_location']);
	$redirect = true;
}

if ( isset($redirect) && $redirect ){
	header('Location: ' . get_site_url() );
	exit;
}

require_once get_language_file();

$q = isset($query_vars['job']) ? $query_vars['job'] : '';
if( empty($q) ){
	if( isset($_REQUEST['keyword']) && !empty($_REQUEST['keyword']) ){
		$q = dash2space($_REQUEST['keyword']);
	}else{
		$q = $default_keyword;
	}
}

require_once get_feedlist_file();

$document_title = !empty($q) ? sprintf( _text('SUFFIX_JOBS'), $q ) : _text('JOBS');
if ( !empty($query_vars['loc'])){
	$document_title .= sprintf( _text('PREFIX_IN'), $query_vars['loc'] );
}

$_themes = array(
		'amelia'   => 'Amelia',
		'cerulean' => 'Cerulean',
		'cosmo'    => 'Cosmo',
		'cyborg'   => 'Cyborg',
		'default'  => 'Default',
		'flatly'   => 'Flatly',
		'journal'  => 'Journal',
		'readable' => 'Readable',
		'simplex'  => 'Simplex',
		'slate'    => 'Slate',
		'spacelab' => 'Spacelab',
		'united'   => 'United'
);



echo '<!-- ';
var_dump($query_vars);
var_dump($locale);
echo ' -->';
