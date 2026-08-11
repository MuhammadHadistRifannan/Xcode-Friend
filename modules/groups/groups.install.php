<?php
/* ############################################################ *\
 ----------------------------------------------------------------
@package	Jcow Social Networking Script.
@copyright	Copyright (C) 2009 - 2010 jcow.net.  All Rights Reserved.
@license	see http://jcow.net/license
 ----------------------------------------------------------------
\* ############################################################ */

function groups_menu() {
	$items = array();
	$items['groups'] = array(
		'name'=>'Groups',
		'tab_name'=>'Mine',
		'type'=>'personal'
	);

	$items['groups/browse'] = array(
		'name'=>'Browse',
		'type'=>'tab',
		'parent'=>'groups'
	);
	return $items;
}

?>