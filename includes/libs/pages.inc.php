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
function jcow_page_feed($target_id, $args = array(), $uid = 0) {
	global $client, $parr;
	if (!$client['id'] && !$uid) return false;
	if (!$uid) $uid = $client['id'];
	$thumbs = explode(',',$args['picture']);
	if (count($thumbs) > 3) {
		$thumbs = array($thumbs[0],$thumbs[1],$thumbs[2]);
	}
	$attachment = array(
				'uri' => addslashes($args['link']),
				'name' => addslashes($args['name']),
				'thumb' => $thumbs,
				'des' => $args['description']
				);
	if (!ereg("^[0-9a-z_]+$",$args['app'])) $args['app'] = $parr[0];
	$app = array('name'=>$args['app']);

	$stream_id = stream_publish($args['message'],$attachment,$app, $uid,$target_id);
	return $stream_id;
}







class jcow_pages {
	var $profile = array();
	var $extra = array();
	var $type = '';
	function u() {
		global $client, $nav, $ss;
		/*
		if (!$client['id'] && get_gvar('profile_access') == 'member' && !$ss['is_bot']) {
			redirect('member/login/1');
		}
		*/
	}

	
	function status($url = 0, $id = 0) {
		global $client, $apps, $uhome,$ubase, $current_sub_menu, $offset, $num_per_page, $page;
		$profile = $this->settabmenu($url, 1,'u');
		$current_sub_menu['href'] = 'u/'.$url.'/friends';
		

		$res = sql_query("select * from ".tb()."streams where id='$id' and uid='{$profile['id']}'");
		$row = sql_fetch_array($res);
		if ($row['id']) {
			$row['attachment'] = unserialize($row['attachment']);
			$row['username'] = $profile['username'];
			$row['avatar'] = $profile['avatar'];
			section(
			array(
			'content'=>stream_display($row,'simple'))
				);
		}

		section(
				array('title'=>t('Comments'),
				'content'=>
				comment_form($row['id']).
				comment_get($row['id'],100)
				
				)
		);
	}

	
	
	function index($url = 0) {
		global $client, $content, $nav, $apps, $uhome, $blocks,$sections, $ubase, $offset, $num_per_page, $page, $tab_menu,$current_sub_menu, $config, $menuon;
		if (!strlen($url) || $url == 'index') {
			die('wrong url:'.$url);
		}
		$owner = $this->settabmenu($url,0,$this->type);
		$page = $owner['page'];

		enreport();
		if ($owner['id'] == $client['id']) {
			$menuon = 'myprofile';
		}
		$current_sub_menu['href'] = 'u/'.$url;
		// update views
		$key = 'vp'.$owner['page']['id'];
		if (!$_COOKIE[$key]) {
			sql_query("update `".tb()."pages` SET views=views+1 WHERE id='{$owner['page']['id']}' ");
			setcookie($key,1, time()+3600*12,"/");
		}
		
		$config['theme'] = 'themes/default/profile.tpl.php';
		if (!$page['no_comment']) {
			$output = stream_form($profile['id'],$owner['page']);
		}
		
		$output .= stream_get($owner['page']['id'],12,0,$page['id']);
		if (substr_count($output,'user_post_left') > 11) {
			$output .= '
			<div id="morestream_box"></div>
			<div>
			<script>
			$(document).ready(function(){
				$("#morestream_button").click(function() {
					$(this).hide();
					$("#morestream_box").html("<img src=\"'.uhome().'/files/loading.gif\" /> Loading");
					$.post("'.uhome().'/index.php?p=jquery/morestream",
								{offset:$("#stream_offset").val(),page_id:'.$owner['page']['id'].',target_id:'.$page['id'].'},
								  function(data){
									var currentVal = parseInt( $("#stream_offset").val() );
									$("#stream_offset").val(currentVal + 7);
									$("#morestream_box").before(data);
									if (data) {
										$("#morestream_button").show();
									}
									$("#morestream_box").html("");
									},"html"
								);
					return false;
				});
			});
			</script>

			<input type="hidden" id="stream_offset" value="12" />
			<a href="#" id="morestream_button"><strong>'.t('See More').'</strong></a>
			</div>';
		}
		section(array('content'=>$output) );

		$output = '';
		
		$hooks = check_hooks('profile_page');
		if ($hooks) {
			foreach ($hooks as $hook) {
				$hook_func = $hook.'_profile_page';
				$hook_func($owner);
			}
		}

		
	
	}



	function settabmenu($url, $hide_as=0, $page_type='') {
		global $nav, $apps, $uhome, $client, $styles, $custom_css,$profile_css, $optional_apps, $config, $tab_menu;
		$nav = array();
		if (!eregi("[0-9a-z]+",$url)) {
			die('wrong url');
		}
		if ($page_type) {
			$res = sql_query("select * from `".tb()."pages` where uri='$url' and type='{$page_type}'");
		}
		else {
			$res = sql_query("select * from `".tb()."pages` where id='$url'");
		}
		$page = sql_fetch_array($res);
		if (!$page['id']) die('wrong uri');

		$res = sql_query("SELECT * from `".tb()."accounts` where id='{$page['uid']}' ");
		$owner = sql_fetch_array($res);
		if (!$owner['id']) {
			sys_break('can not find the owner');
		}
		
		if ($page['type'] == 'u') {
			if (!$owner['avatar']) {
				$owner['avatar'] = 'undefined.jpg';
			}
			app_header('<table border="0"><tr><td>'.avatar($owner,25).'</td><td valign="middle"><span style="font-size:1.5em">'.h($owner['fullname']).' '.$editbutton.' '.$custombutton.'</span></td></tr></table>');
			set_title($owner['username']."'s profile");
			if ($owner['disabled'] == 2) {
				c('This user('.$owner['username'].') has been suspended');
				stop_here();
			}
		}
		elseif ($page['type'] == 'page') {
			if ($client) {
				$res = sql_query("select * from ".tb()."page_users where uid='{$client['id']}' and pid='{$page['id']}'");
				if (sql_counts($res)) {
					$like_link = '<img src="'.uhome().'/files/icons/thumbs_up.png" /> '.t('Liked').' <span class="sub">('.url('pages/unlike/'.$page['uri'],t('Unlike')).')</span>';
				}
				else {
					$like_link = '<img src="'.uhome().'/files/icons/thumbs_up.png" /> '.url('pages/like/'.$page['uri'],t('Like'));
				}
			}
			else {
				$like_link = '<img src="'.uhome().'/files/icons/thumbs_up.png" />'.url('pages/like/'.$page['uri'],t('Like'));
			}
			app_header('<table border="0"><tr><td>'.page_logo($page,25).'</td><td valign="middle"><span style="font-size:1.5em">'.h($page['name']).'</span> '.$like_link.'</td></tr></table>');
			set_title(h($page['name']));
		}
		elseif ($page['type'] == 'group') {
			app_header('<table border="0"><tr><td>'.page_logo($page,25).'</td><td valign="middle"><span style="font-size:1.5em">'.h($page['name']).'</span> </td></tr></table>');
			set_title(h($page['name']));
		}
		$uid = $owner['id'];
		if ($page['type'] == 'u') {
			$profile_css = unserialize($page['custom_css']);
			$owner['musicplayer'] = $page['musicplayer'];
		}
		$config['is_profile'] = 1;
		if ($client['id']) {
			$res = sql_query("select * from ".tb()."friends where uid='{$client['id']}' and fid='{$owner['id']}' limit 1");
			$row = sql_fetch_array($res);
			if ($row['uid']) {
				$owner['is_friend'] = 1;
			}
		}
		if(!$owner['id']) {
			die('wrong uid');
		}

		if ( (time()-$owner['lastlogin']) > 600) {
			$owner['online'] = 0;
		}
		else {
			$owner['online'] = 1;
		}

		if ($uid == $client['id']) {
			$nav[] = '<strong>'.t('My profile').'</strong>';
		}
		else {
			$nav[] = t("{1}'s profile",'<strong>'.htmlspecialchars($owner['username']).' '.htmlspecialchars($owner['lastname']).'</strong>');
		}
	
		if (!$hide_as) {
			$this->show_sidebar($page,$owner);
		}

		if ($page['type'] == 'u') {
			if ($owner['id'] != $client['id']) {
				if ($owner['profile_permission'] == 2 && !$owner['is_friend']) {
						c(t('Sorry, only friends can view this profile'));
						stop_here();
				}
				elseif ($owner['profile_permission'] == 1 && !$owner['is_friend']) {
					$page['no_comment'] = 1;
				}
				if ($client['id']) {
					$res = sql_query("select * from `".tb()."blacks` where bid='{$client['id']}' and uid='{$owner['id']}' ");
					if (sql_counts($res)) {
						$page['no_comment'] = 1;
					}
				}
			}
		}
		elseif ($page['type'] == 'group') {
			if ($client['id'] && ($owner['id'] != $client['id']) ) {
				$res = sql_query("select pid from ".tb()."page_users where pid='{$page['id']}' and uid='{$client['id']}'");
				if (!sql_counts($res)) {
					$page['no_comment'] = 1;
				}
			}
		}
		$owner['page'] = $page;

		if ($page['type'] == 'u') {
			$tab_menu = u::tab_menu($owner,$page);
		}
		elseif ($page['type'] == 'page') {
			$tab_menu = page::tab_menu($owner,$page);
		}
		elseif ($page['type'] == 'group') {
			$tab_menu = group::tab_menu($owner,$page);
		}
		if ($page['type'] == 'u') {
			$hooks = check_hooks('u_menu');
			if ($hooks) {
				foreach ($hooks as $hook) {
					$hook_func = $hook.'_u_menu';
					$hook_func($tab_menu,$page);
				}
			}
		}
		elseif ($page['type'] == 'page') {
			$hooks = check_hooks('page_menu');
			if ($hooks) {
				foreach ($hooks as $hook) {
					$hook_func = $hook.'_page_menu';
					$hook_func($tab_menu,$page);
				}
			}
		}
		elseif ($page['type'] == 'group') {
			$hooks = check_hooks('group_menu');
			if ($hooks) {
				foreach ($hooks as $hook) {
					$hook_func = $hook.'_group_menu';
					$hook_func($tab_menu,$page);
				}
			}
		}
		


		return $owner;
	}


	function details($profile) {
		global $client;
		if ($client['id'] == $profile['id']) {
			$edit = ' <strong>'.url('account',t('Edit')).'</strong>';
		}
		if (strlen($profile['fullname'])) {
			$output .= '<dt>'.t('Full Name').'</dt>
			<dd>'.htmlspecialchars($profile['fullname']).'</dd>';
		}
		if ($profile['birthmonth']) {
			$birth_info = '<dt>'.t('Birthday').'</dt>
			<dd>'.$profile['birthmonth'].'/'.$profile['birthday'].'</dd>';
		}
		$output .= '
			<dt>'.t('About me').'</dt>
			<dd>'.htmlspecialchars($profile['about_me']).'</dd>
			<dt>'.t('Come from').'</dt>
			<dd>'.($profile['location']).'</dd>
			<dt>'.t('Gender').'</dt>
			<dd>'.gender($profile['gender']).'</dd>
			<dt>'.t('Age').'</dt>
			<dd>'.get_age($profile['birthyear'],$profile['hide_age']).'</dd>
			'.$birth_info.'
			<dt>'.t('Registered').'</dt>
			<dd>'.get_date($profile['created']).'</dd>';
		// custom fields
					for($i=1;$i<=7;$i++) {
						$col = 'var'.$i;
						$key = 'cf_var'.$i;
						$key2 = 'cf_var_value'.$i;
						$key3 = 'cf_var_des'.$i;
						$key4 = 'cf_var_label'.$i;
						$type = get_gvar($key);
						$value = get_gvar($key2);
						$des = get_gvar($key3);
						$label = get_gvar($key4);
						if ($type != 'disabled' && strlen($profile[$col])) {
							$output .= '
								<dt>'.$label.'</dt>
								<dd>'.htmlspecialchars($profile[$col]).'</dd>
								';
						}
					}
		return array('title'=>t('Details').$edit,'content'=>$output);
	}

}
	
