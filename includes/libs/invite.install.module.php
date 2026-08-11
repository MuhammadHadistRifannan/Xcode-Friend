<?php
/* ############################################################ *\
 ----------------------------------------------------------------
@package	Jcow Social Networking Script.
@copyright	Copyright (C) 2009 - 2010 jcow.net.  All Rights Reserved.
@license	see http://jcow.net/license
 ----------------------------------------------------------------
\* ############################################################ */

function invite_menu() {
	$items = array();
	$items['invite'] = array(
		'name'=>'Invite',
		'tab_name'=>'Invite',
		'type'=>'personal'
	);

	$items['invite/histories'] = array(
		'name'=>'Histories',
		'tab_name'=>"Following",
		'parent'=>'invite',
		'type'=>'tab'
	);
	return $items;
}

?>