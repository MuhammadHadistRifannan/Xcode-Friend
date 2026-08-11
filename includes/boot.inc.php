<?php
/* ############################################################ *\
Copyright (C) 2009 - 2011 jcow.net.  All Rights Reserved.
\* ############################################################ */
// db
require_once './my/config.php';
if (!is_array($db_info)) {
	header("location:install.php");
	exit;
}

$php_timezone = getenv('TZ');
if (!$php_timezone && isset($config['date_timezone'])) {
	$php_timezone = $config['date_timezone'];
}
if (!$php_timezone) {
	$php_timezone = 'Asia/Jakarta';
}
date_default_timezone_set($php_timezone);

if (file_exists('./includes/libs/common.pro.inc.php')) {
	include './includes/libs/common.pro.inc.php';
}
else {
	include './includes/libs/common.inc.php';
}
require_once './includes/libs/pages.inc.php';
require_once './includes/libs/story.inc.php';
require_once './includes/libs/db.inc.php';
require_once './includes/captcha/recaptchalib.php';
$captcha['publickey'] = $captcha['publickey']?$captcha['publickey']:'6Ld7BbwSAAAAANqYkhR9oAEsUCuYoWQgoYtNYeiH';
$captcha['privatekey'] = $captcha['privatekey']?$captcha['privatekey']:'6Ld7BbwSAAAAAAdMlleVlFTGqiJqKafjFZsYWDmB';

if (!$config['max_upload']) $config['max_upload'] = 100;

require_once './includes/libs/bbcode.php';
@require_once './my/license.php';
$from_url = getenv(HTTP_REFERER);

$conn=sql_connect($db_info['host'], $db_info['user'], $db_info['pass'], $db_info['dbname']);
mysql_query("SET NAMES UTF8");

$lang_options = array();
// gvars
$res = sql_query("select * from `".tb()."gvars`");
while ($row = sql_fetch_array($res)) {
	$gvars[$row['gkey']] = $row['gvalue'];
}
if (getenv('DISABLE_LEGACY_RECAPTCHA') !== '0') {
	$gvars['disable_recaptcha_reg'] = 1;
	$gvars['disable_recaptcha_login'] = 1;
}
$langs_enabled = array();
if ($le = get_gvar('langs_enabled')) {
	$langs_enabled = explode(',',$le);
}
foreach ($langs_enabled as $key) {
	$lang_options[$key] = $langs[$key];
}
if (!count($lang_options)) $lang_options = array('en'=>'English');
// modules
$current_modules = array();
$res = sql_query("select * from ".tb()."modules");
while ($row = sql_fetch_array($res)) {
	$key = $row['name'];
	$current_modules[$key] = $row;
	if ($row['actived'] && $row['hooking'] && file_exists('modules/'.$row['name'].'/'.$row['name'].'.hook.php')) {
		include_once 'modules/'.$row['name'].'/'.$row['name'].'.hook.php';
	}
}


$_REQUEST['p'] = str_replace('-','',$_REQUEST['p']);
if (!strlen($_REQUEST['p'])) {
	$parr[0] = 'home';
	$parr[1] = 'index';
}
elseif (!preg_match("/^[0-9a-z_\/.\|]+$/i",$_REQUEST['p'])) {
	sys_break('Wrong path:'.htmlspecialchars($_REQUEST['p']));
}
else {
	$parr = explode('/',$_REQUEST['p']);
	if ($parr[1]) {
		$act =  $parr[1];
	}
	else {
		$act = 'index';
	}
}
if (!preg_match("/^[0-9a-z_]+$/i",$parr[0])) {
	sys_break('Wrong app');
}
$application = $parr[0];
if (file_exists('./includes/libs/ss.pro.inc.php')) {
	include './includes/libs/ss.pro.inc.php';
}
else {
	include './includes/libs/ss.inc.php';
}

if ($parr[0] != 'member' && $parr[0] != 'home' && $parr[0] != 'jcow' && $parr[0] != 'paidmember' && $parr[0] != 'upgrade' && $parr[0] != 'rss' && $parr[0] != 'language' && $parr[0] != 'signup' && get_gvar('private_network') && !$client['id'] && !eregi("google",$_SERVER['HTTP_USER_AGENT']) ) {
	$key = 'public_app_'.$parr[0];
	if (get_gvar($key)) {
	}
	else {
		redirect('member/login/1');
	}
}
// page
if (!$_GET['page']) {
	$page = 1;
}
else {
	$page = $_GET['page'];
}

// app cache
if (get_gvar('jcow_cache_enabled') ) {
	$hooks = check_hooks('page_cache');
	if ($hooks) {
		foreach ($hooks as $hook) {
			$hook_func = $hook.'_page_cache';
			if($page_cache = $hook_func($parr,$page,$client)) {
				$enable_page_cache = true;
				if ($page_content = get_cache($page_cache['key'])) {
					if (!$config('disable_execute_info')) {
						$execute_time = microtime_float() - $time_start;
						$execute_info = '<br /><span class="sub">Executed in '.substr($execute_time,0,7).' seconds</span>';
						echo str_replace('<!-- jcow_execute_info -->',$execute_info,$page_content);
					}
					else {
						echo str_replace('<!-- jcow_execute_info -->',$execute_info,$page_content);
					}
					exit();
				}
			}
		}
	}
}


// menu
if (!strlen($parr[1]))
	$current_menu_path = $parr[0];
else
	$current_menu_path = $parr[0].'/'.$parr[1];
$menu_items = array();
$res = sql_query("select * from ".tb()."menu order by weight ASC");
while ($row = sql_fetch_array($res)) {
	$row['allowed_roles'] = explode(',',$row['allowed_roles']);
	$menu_items[$row['path']] = $row;
	if ($row['protected'] && !allow_access($row['allowed_roles'])) {
		continue;
	}
	if ($row['type'] == 'personal' && $row['actived']) {
		$personal_menu[] = $row;
	}
	elseif ($row['type'] == 'community' && $row['actived']) {
		$community_menu[] = $row;
	}
	elseif ($row['type'] == 'admin' && $row['actived']) {
		$admin_menu[] = $row;
	}
	elseif ($row['type'] == 'tab') {
		$all_tab_menu[] = $row;
	}
}
if (strlen($menu_items[$current_menu_path]['parent'])) {
	$top_menu_path = $menu_items[$current_menu_path]['parent'];
}
else {
	$top_menu_path = $current_menu_path;
}
if (strlen($menu_items[$current_menu_path]['name'])) {
	set_title(t($menu_items[$current_menu_path]['name']));
}
if (strlen($menu_items[$top_menu_path]['name'])) {
	$top_title = t($menu_items[$top_menu_path]['name']);
}
if (is_array($all_tab_menu)) {
	foreach ($all_tab_menu as $arr) {
		if ($arr['parent'] == $top_menu_path) {
			$tab_menu[] = $arr;
		}
	}
}




$hide_ad_roles = explode('|',get_gvar('hide_ad_roles'));
if (is_array($hide_ad_roles)) {
	foreach ($hide_ad_roles as $role) {
		if (in_array($role, $client['roles'])) {
			$config['hide_ad'] = 1;
		}
	}
}



$nav[] = url('home','Home');
require_once './includes/libs/apps.inc.php';

