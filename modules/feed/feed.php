<?php
/* ############################################################ *\
 ----------------------------------------------------------------
Jcow Software (http://www.jcow.net)
IS NOT FREE SOFTWARE
http://www.jcow.net/commercial_license
Copyright (C) 2009 - 2010 jcow.net.  All Rights Reserved.
 ----------------------------------------------------------------
\* ############################################################ */

class feed{
	function feed() {
		global $content, $db, $apps, $client, $settings, $menuon;
		$menuon = 'feed';
		set_title(t('News feed'));
		do_auth( explode('|',get_gvar('permission_feed')) );
	}

	function index($page = 0) {
		global $content, $db, $apps, $client, $settings;
		$offset = $page * 12;
		c(allstream($client['id'],12,$offset) );
	}

	function following($page = 0) {
		global $content, $db, $apps, $client, $settings;
		$offset = $page * 12;
		c(following_stream($client['id'],12,$offset) );
	}

	function friends($page = 0) {
		global $content, $db, $apps, $client, $settings;
		$offset = $page * 12;
		if ($client['id']) {
			c(friends_stream($client['id'],12,$offset) );
		}
		else {
			redirect(uhome());
			exit;
		}
	
	}
	function allstreams($page = 0) {
		global $content, $db, $apps, $client, $settings;
		$offset = $page * 12;
		$current_sub_menu['href'] = 'feed/allstreams';
		c(allstream($client['id'],12,$offset) );
	}

}

function friends_stream($uid,$num = 12, $offset = 0) {
	global $client, $config;
	$page = $offset/$num + 1;
	$num = $num+1;
	$res = sql_query("select f.fid from ".tb()."friends as f left join ".tb()."accounts as u on u.id=f.fid where f.uid='{$client['id']}' order by u.lastlogin desc limit 50");
	while ($row = sql_fetch_array($res)) {
		$uids[] = $row['fid'];
	}
	if (is_array($uids)) {
		$output = activity_get($uids,$num,$offset,0,1);
	}
	else $output = 'you have no friends';
	return $output;
}

function following_stream($uid,$num = 12, $offset = 0) {
	global $client, $config;
	$page = $offset/$num + 1;
	$num = $num+1;
	$res = sql_query("select f.fid from ".tb()."followers as f left join ".tb()."accounts as u on u.id=f.fid where f.uid='{$client['id']}' order by u.lastlogin desc limit 50");
	while ($row = sql_fetch_array($res)) {
		$uids[] = $row['fid'];
	}
	$i = 0;
	if (is_array($uids)) {
		$output = activity_get($uids,$num,$offset,0,1);
	}
	else $output = 'you are not follwing anyone, click '.url('browse',t('Here')).' to browse and follow some users';
	return $output;
}

function allstream($uid,$num = 12, $offset = 0) {
	global $client, $config;
	$output = activity_get(0,$num,$offset,0,1);
	return $output;
}