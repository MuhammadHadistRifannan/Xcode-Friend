<?php
/* ############################################################ *\
Copyright (C) 2009 - 2010 jcow.net.  All Rights Reserved.
------------------------------------------------------------------------
The contents of this file are subject to the Common Public Attribution
License Version 1.0. (the "License"); you may not use this file except in
compliance with the License. You may obtain a copy of the License at
http://www.jcow.net/celicense. The License is based on the Mozilla Public
License Version 1.1, but Sections 14 and 15 have been added to cover use of
software over a computer network and provide for limited attribution for the
Original Developer. In addition, Exhibit A has been modified to be consistent
with Exhibit B.

Software distributed under the License is distributed on an "AS IS" basis,
 WITHOUT WARRANTY OF ANY KIND, either express or implied. See the License for
the specific language governing rights and limitations under the License.
------------------------------------------------------------------------
The Original Code is Jcow.

The Original Developer is the Initial Developer.  The Initial Developer of the
Original Code is jcow.net.

\* ############################################################ */

if ($parr[3]) {
	$real_path = $parr[0].'/'.$parr[1].'/'.$parr[2].'/'.$parr[3];
}
elseif ($parr[2]) {
	$real_path = $parr[0].'/'.$parr[1].'/'.$parr[2];
}
elseif ($parr[1]) {
	$real_path = $parr[0].'/'.$parr[1];
}
else {
	$real_path = $parr[0];
}
if ($parr[0] == 'u' || $parr[0] == 'page') {

	if ($parr[2]) {
		$tmp = $parr[1];
		$parr[1] = $parr[2];
		$parr[2] = $tmp;
	}
	else {
		$parr[3] = $parr[2];
		$parr[2] = $parr[1];
		$parr[1] = 'index';
	}

}

elseif ($parr[0] == 'group') {
	if (!file_exists('modules/groups/group3.php')) {
		$guri = $parr[1];
		$parr[1] = $parr[2];
		$parr[2] = $parr[3];
		$parr[3] = $parr[4];
		if (!$parr[1]) $parr[1] = 'index';
	}
	else {
		if ($parr[2]) {
			$tmp = $parr[1];
			$parr[1] = $parr[2];
			$parr[2] = $tmp;
		}
		else {
			$parr[3] = $parr[2];
			$parr[2] = $parr[1];
			$parr[1] = 'index';
		}
	}
}

$path = 'modules/'.$parr[0].'/'.$parr[0].'.php';
$my_app = 'my/';
if (!file_exists($path)) {
	$my_app = '';
	$path = 'modules/home/home.php';
	$parr[0] = 'home';
	$parr[1] = 'index';
}


//
if ($client['disabled'] == 2 && $parr[0] != 'member') {
	redirecting('member/logout',t('Sorry, your account has been suspended'));
}

$offset = $num_per_page*($page-1);

if (!$current_app) {
	$current_app = $all_apps[$parr[0]];
}

// do app


if ($current_app['force'] == 'guest' && $client['id']) {
	header("Location:".uhome());
}

if ($menu_items[$current_menu_path]['type'] == 'private' || $menu_items[$top_menu_path]['type'] == 'private') {
	if (!$client['id']) {
		redirect('member/login/1');
	}
}

//
$key = $parr[0];


// access

if ($menu_items[$current_menu_path]['protected']) {
	do_auth($menu_items[$current_menu_path]['allowed_roles']);
}

// app cache
if (get_gvar('jcow_cache_enabled') ) {
	$hooks = check_hooks('app_cache');
	if ($hooks) {
		foreach ($hooks as $hook) {
			$hook_func = $hook.'_app_cache';
			if($cache_app = $hook_func($parr,$page,$client)) {
				$enable_app_cache = true;
			}
		}
	}
}


if ($enable_app_cache) {
	$app_content = get_cache($cache_app['key']);
}

if (!strlen($app_content)) {
	include_once($path);
	$farr = array($parr[2],$parr[3],$parr[4]);
}
else {
	load_tpl();
}

// functions

function app_name($id) {
	global $apps;
	return $apps[$id]['flag'];
}