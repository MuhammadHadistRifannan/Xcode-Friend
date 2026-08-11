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


class rss{
	
	function notifications($uid,$pass) {
		global $content, $db, $client, $offset, $num_per_page, $page, $ubase, $nav;
		if (!$user = valid_user($uid)) {
			die('wrong uid');
		}
		$rsspass = md5(get_gvar('secure_key').$user['id']);
		if ($pass != $rsspass) {
			die('access denied');
		} 
		header("Content-Type: application/rss+xml");
		echo '<?xml version="1.0" encoding="UTF-8"?>
<rss version="2.0">
<channel>
<title>'.t('Notifications from {1}',get_gvar('site_name')).'</title>
<link>'.url('rss/notifications/'.$user['id'].'/'.$rsspass).'</link>

';
		$res = sql_query("SELECT m.*,u.username FROM `".tb()."messages` as m left join `".tb()."accounts` as u on u.id=m.from_id where m.to_id='{$user['id']}' and m.from_id=0 ORDER by m.id DESC LIMIT 30");
		$rsspass = md5(get_gvar('secure_key').$client['id']);
		c('<tr class="table_line1">
			<td>Notifications</td>
			</tr>');
			while ($row = sql_fetch_array($res)) {
			echo '
<item>
<title>'.get_date($row['created']).'</title>
<description><![CDATA['.$row['message'].']]></description>
</item>
';
		}
		echo '
</channel>
</rss>
';
		exit;
	}

}