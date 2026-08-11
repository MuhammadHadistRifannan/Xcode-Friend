<?php
/* ############################################################ *\
 ----------------------------------------------------------------
@package	Jcow Social Networking Script.
@copyright	Copyright (C) 2009 - 2010 jcow.net.  All Rights Reserved.
@license	see http://jcow.net/license
 ----------------------------------------------------------------
\* ############################################################ */

function pages_menu() {
	$items = array();
	$items['pages'] = array(
		'name'=>'Pages',
		'tab_name'=>'Mine',
		'type'=>'personal'
	);

	$items['pages/browse'] = array(
		'name'=>'Browse',
		'type'=>'tab',
		'parent'=>'pages'
	);


	return $items;
}


?>