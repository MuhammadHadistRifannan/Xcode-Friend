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

function theme_home() {
	global $client;
	if (!$client['id']) {
		$output .= '<div><a href="'.url('member/signup').'"><img src="'.uhome().'/themes/default/welcome.jpg" alt="welcome" /></a></div>';
	}
	$output .= '
	<script type="text/javascript" src="'.uhome().'/js/jquery.vtricker.js"></script>
	<script>
	jQuery(document).ready(function($) {
		$(\'#recent_activites\').vTicker({
		   speed: 800,
		   pause: 5000,
		   showItems: 4,
		   animation: \'fade\',
		   mousePause: false,
		   height: 350,
		   direction: \'down\'
		});
				});
	</script>
	<style>
	#recent_activites li{
		margin:0;
		padding:0;
		}
	</style>
	<strong>'.t('Community activities').'</strong>
	<div id="recent_activites">
	<ul>
	';
	$res = sql_query("SELECT s.*,u.username,u.avatar from `".tb()."streams` as s left join ".tb()."accounts as u on u.id=s.uid where s.hide!=1  order by s.id desc limit 20");
	while ($stream = sql_fetch_array($res)) {
		$stream['attachment'] = unserialize($stream['attachment']);
		$output .= '<li>'.stream_display($stream,'simple').'</li>';
	}
	$output .= '</ul>
	<div style="position:absolute;left:0;bottom:0px;height:20px;width:100%;background:url('.uhome().'/files/common_css/fade.png) repeat-x"> </div></div>';

	return $output;

}