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
class dashboard{
	function dashboard() {
		global $content, $db, $apps, $client, $settings, $tab_menu, $current_sub_menu, $menuon;
		do_auth(2);
		$menuon = 'dashboard';
		set_menu_path('dashboard');

	}

	function index() {
		global $content, $db, $apps, $client, $settings, $config;
		if ($client['id']) {
			if ($slogan = get_gvar('site_slogan')) {
				set_title($slogan);
			}
			else {
				set_title(t('Home'));
			}
			if (allow_access(3)) {
				ass(array(
					'title'=>'Administration',
					'content'=>'Go to <strong>'.url('admin',t('Administration Panel') ).'</strong>'));
			}
			block(
				my_account()
			);
			block(
				friends_birthday()
			);


			$lblocks[] = array('title' => t('Quick share'),'content'=>stream_form($client['id']).'<div style="height:7px"></div>');
			$lblocks[] = $this->newsfeed(10);

			if (is_array($lblocks)) {
				foreach ($lblocks as $lblock) {
					section($lblock);
				}
			}
			$hooks = check_hooks('dashboard');
			if ($hooks) {
				foreach ($hooks as $hook) {
					$hook_func = $hook.'_dashboard';
					$hook_func($sections,$parr);
				}
			}
		}
		
	}

	private function newsfeed($num=5) {
		global $client;
		$uids[] = $client['id'];
		$res = sql_query("select f.fid from ".tb()."friends as f left join ".tb()."accounts as u on u.id=f.fid where f.uid='{$client['id']}' order by u.lastlogin desc limit 5");
		while ($row = sql_fetch_array($res)) {
			$uids[] = $row['fid'];
		}
		$res = sql_query("select f.fid from ".tb()."followers as f left join ".tb()."accounts as u on u.id=f.fid where f.uid='{$client['id']}' order by u.lastlogin desc limit 5");
		while ($row = sql_fetch_array($res)) {
			$uids[] = $row['fid'];
		}
		if (is_array($uids)) {
			$output .= activity_get($uids,$num,0,0,1);
		}
		else $output = t('No people');
		return array('title'=>t('News feed'),'content'=>$output);
	}
	
}



function my_account() {
		global $client, $apps;
		if (!$client['id']) return false;
		$res = sql_query("select * from `".tb()."pages` where uid='{$client['id']}' and type='u'");
		$row = sql_fetch_array($res);
		if ($client['avatar'] == 'undefined.jpg') {
			$uf[] = url('account/avatar', t('Avatar picture'));
		}
		if (is_array($uf)) {
			sys_notice(t("You haven't finished editing your profile").' : '.implode(', ',$uf));
		}
		$profile_views = $row['views'];
		$res = sql_query("select count(*) as num from ".tb()."friends where uid='{$client['id']}'");
		$row = sql_fetch_array($res);
		$friends = $row['num'];
		$res = sql_query("select count(*) as num from ".tb()."followers where fid='{$client['id']}'");
		$row = sql_fetch_array($res);
		$followers = $row['num'];
		$content = 
			t('Your profile was viewed {1} times.','<strong>'.$profile_views.'</strong>').'
		<div class="hr"></div>'.
			t('You have {1} friends and {2} followers.','<strong>'.$friends.'</strong>','<strong>'.$followers.'</strong>');

		
		$content .= '<div class="hr"></div>';
		$content .= '
		<ul>
		<li>'.url('u/'.$client['username'],t('My Profile')).'</li>
		<li>'.url('follow/myfollowers',t('My Followers').'('.$followers.')' ).'</li>
		<li>'.url('follow/imfollowing',t('My Following') ).'</li>
		<li>'.url('preference',t('Preference')).'</li>
		</ul>';
		
		return array('title'=>t('Account'), 'content' => $content);
	}

function friends_birthday() {
	global $client;
	$m = date('n');
	$d = date('j');
	$next = $m+1;
	if ($m<10) $m = '0'.$m;
	if ($next > 12) $next = '01';
	if ($d > 20) {
		$nextm = " or (f.uid='{$client['id']}' and birthmonth='$next' and birthday<$d) ";
	}
	$res = sql_query("select u.* from ".tb()."friends as f left join ".tb()."accounts as u on u.id=f.fid where (f.uid='{$client['id']}' and u.birthmonth='$m' and u.birthday>$d) $nextm  order by u.lastlogin desc limit 15");
	$content = '<ul>';
	while ($user = sql_fetch_array($res)) {
		$total++;
		if ($user['birthmonth'] < 10) $user['birthmonth'] = '0'.$user['birthmonth'];
		if ($user['birthday'] < 10) $user['birthday'] = '0'.$user['birthday'];
		$content .= '<li>'.url('u/'.$user['username'],$user['username']).' - <strong>'.$user['birthmonth'].'/'.$user['birthday'].'</strong></li>';
	}
	$content .= '</ul>';
	if (!$total) $content = 'none';
	return array('title'=>t('Friends birthday coming up'), 'content' => $content);
}