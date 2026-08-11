<?php
/* ############################################################ *\
 ----------------------------------------------------------------
@package	Jcow Social Networking Script.
@copyright	Copyright (C) 2009 - 2010 jcow.net.  All Rights Reserved.
@license	see http://jcow.net/license
 ----------------------------------------------------------------
\* ############################################################ */

function music_menu() {
	$items = array();
	$items['music'] = array(
		'name'=>'Music',
		'type'=>'community'
	);
	$items['music/mine'] = array(
		'name'=>'Music',
		'tab_name'=>'My music',
		'type'=>'personal'
	);
	$items['music/following'] = array(
		'name'=>'Following',
		'type'=>'tab',
		'parent'=>'music/mine'
	);
	$items['music/friends'] = array(
		'name'=>'Friends',
		'type'=>'tab',
		'parent'=>'music/mine'
	);
	return $items;
}

?>