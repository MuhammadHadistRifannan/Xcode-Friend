<?php

function events_menu() {
	$items = array();
	$items['events'] = array(
		'name'=>'Events',
		'type'=>'community'
	);
	$items['events/mine'] = array(
		'name'=>'Events',
		'tab_name'=>'My events',
		'type'=>'personal'
	);
	$items['events/following'] = array(
		'name'=>'Following',
		'type'=>'tab',
		'parent'=>'events/mine'
	);
	$items['events/friends'] = array(
		'name'=>'Friends',
		'type'=>'tab',
		'parent'=>'events/mine'
	);
	return $items;
}
?>