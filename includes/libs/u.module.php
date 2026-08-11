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

class u extends jcow_pages {
	function u() {
		$this->type = 'u';
	}

	function tab_menu($owner,$page=array()) {
		return array(
			array('path'=>'u/'.$owner['username'], 'name'=>t('Wall')),
			array('path'=>'u/'.$owner['username'].'/liked', 'name'=>t('Liked'))
			);
	}

	function show_sidebar($page,$owner) {
		global $client;
		if ($client['id']) {
			$res = sql_query("select * from `".tb()."blacks` where uid={$client['id']} and bid={$owner['id']} ");
			if (sql_counts($res)) {
				$ublock = '<tr><td align="right"><img src="'.uhome().'/files/icons/unblock.gif" /></td><td align="left"><a href="'.url('blacklist/remove/'.$owner['id']).'">'.t('Unblock').'</a></td>';
			}
			else {
				$ublock = '<tr><td align="right"><img src="'.uhome().'/files/icons/block.gif" /></td><td align="left"><a href="'.url('blacklist/add/'.$owner['id']).'">'.t('Block').'</a></td>';
			}

			$res = sql_query("select * from ".tb()."followers where uid='{$client['id']}' and fid='{$owner['id']}' limit 1");
			if (!sql_counts($res)) {
				$follow_url = url('follow/add/'.$owner['id'],t('Follow'));
			}
			else {
				$follow_url = url('follow/remove/'.$owner['id'],t('Unfollow'));
			}
		}
		else {
			$follow_url = url('member/login/1',t('Follow') );
		}
		if ($owner['online']) {
			$onoff = '<img src="'.uhome().'/files/icons/online.gif" /><br />';
		}
		else {
			$onoff = '<img src="'.uhome().'/files/icons/offline.gif" /><br />';
		}
		if (!$owner['birthyear']) {
			$age = ' - ';
		}
		else {
			$age = (date("Y",time()) - $owner['birthyear']);
		}
		if (!$owner['location']) $owner['location'] = ' - ';
		$output = 
		'<div>'.
			avatar($owner,'normal').'<br />'.t('Last login').':'.get_date($owner['lastlogin']).'
		</div>
		<img src="'.uhome().'/files/icons/favorite.png" /> <strong>'.$owner['followers'].'</strong> Followers 

		<table>
		<tr><td align="right"><img src="'.uhome().'/files/icons/profile.gif" /></td><td align="left">'.$follow_url.'</td>
		<td align="right"><img src="'.uhome().'/files/icons/message.gif" /></td><td align="left"><a href="'.url('message/compose/u'.$owner['id']).'">'.t('Message').'</a></td></tr>
		'.$ublock.'
		<td align="right"><img src="'.uhome().'/files/icons/add_friend.gif" /></td><td align="left"><a href="'.url('friends/add/'.$owner['id']).'">'.t('Add friend').'</a></td></tr>
		</table>';
		if ($client['id'] == $owner['id']) {
			$output .= '['.url('account',t('Edit profile')).']';
		}
		if (allow_access(3)) {
			$output .= '<br />['.url('admin/useredit/'.$owner['id'],'Manage this User').']';
		}
		ass(array('content'=>'<center>'.$output.'</center>'));

		
		// following
		$res = sql_query("select u.id,u.username,u.avatar from ".tb()."followers as f left join ".tb()."accounts as u on u.id=f.fid where f.uid='{$owner['id']}' order by u.lastlogin DESC limit 10");
		$output = '';
		while ($row = sql_fetch_array($res)) {
			$f = 1;
			$output .= avatar($row);
		}
		ass(array('title'=>t('Following'), 'content' => '<div class="toolbar">'.url('u/'.$owner['username'] .'/following',t('See all')).'</div>'.$output));

		// friends
		$res = sql_query("SELECT u.* FROM `".tb()."friends` as f left join `".tb()."accounts` as u on u.id=f.fid where f.uid={$owner['id']} ".dbhold('f')." ORDER BY f.created DESC LIMIT 9");
		$output = '';
		while ($row = sql_fetch_array($res)) {
			$f = 1;
			$output .= avatar($row);
		}
		ass(array('title'=>t('Friends'), 'content' => '<div class="toolbar">'.url('u/'.$owner['username'] .'/friends',t('See all')).'</div>'.$output));

		// groups
		$res = sql_query("SELECT p.* FROM `".tb()."page_users` as m left join `".tb()."pages` as p on p.id=m.pid where m.uid={$owner['id']} and p.type='group' LIMIT 9");
		$output = '';
		while ($group = sql_fetch_array($res)) {
			if (!$group['logo']) {
				$group['logo'] = 'logo.jpg';
			}
			$output .= url('group/'.$group['uri'],'<img src="'.uhome().'/uploads/avatars/s_'.$group['logo'].'" />').' ';
		}
		ass(array('title'=>t('Groups'), 'content' => $output));

		// pages
		$res = sql_query("SELECT p.* FROM `".tb()."page_users` as m left join `".tb()."pages` as p on p.id=m.pid where m.uid={$owner['id']} and p.type='page' LIMIT 9");
		$output = '';
		while ($mypage = sql_fetch_array($res)) {
			if (!$mypage['logo']) {
				$mypage['logo'] = 'logo.jpg';
			}
			$output .= url('group/'.$mypage['uri'],'<img src="'.uhome().'/uploads/avatars/s_'.$mypage['logo'].'" />').' ';
		}
		ass(array('title'=>t('Pages'), 'content' => $output));

		ass($this->details($owner));
	}



	function friends($url = 0) {
		global $client, $apps, $uhome,$ubase, $current_sub_menu, $offset, $num_per_page, $page;
		$profile = $this->settabmenu($url, 1,'u');
		$current_sub_menu['href'] = 'u/'.$url.'/friends';
		
		// friends
		$output = '<ul class="small_avatars">';
		$res = sql_query("SELECT u.* FROM `".tb()."friends` as f left join `".tb()."accounts` as u on u.id=f.fid where f.uid={$profile['id']}  ORDER BY f.created DESC LIMIT $offset, $num_per_page");
		while ($row = sql_fetch_array($res)) {
			$output .= '<li>';
			$output .= '<span>'.url('u/'.$row['username'], $row['username']).'</span> '.avatar($row);
			$output .= '</li>';
		}
		$output .= '</ul>';

		// pager
		$res = sql_query("select count(*) as total from `".tb()."friends` where uid='{$profile['id']}' ".dbhold() );
		$row = sql_fetch_array($res);
		$total = $row['total'];
		$pb       = new PageBar($total, $num_per_page, $page);
		$pb->paras = $ubase.'u/'.$profile['username'].'/friends';
		$pagebar  = $pb->whole_num_bar();
		$output .= $pagebar;

		section(
			array('title'=>'Friends',
			'content'=>$output)
			);
	}


	function liked($url = 0) {
		global $client, $content, $nav, $apps, $uhome,  $ubase, $offset, $num_per_page, $page,$config, $menuon;
		$profile = $this->settabmenu($url, 1,'u');
		$res = sql_query("select stream_id from ".tb()."liked where uid='{$profile['id']}' order by id DESC limit 10");
		while ($row = sql_fetch_array($res)) {
			$res2 = sql_query("select s.*,u.username,u.avatar from ".tb()."streams as s left join ".tb()."accounts as u on u.id=s.uid where s.id='{$row['stream_id']}'");
			$stream = sql_fetch_array($res2);
			$stream['attachment'] = unserialize($stream['attachment']);
			$output .= stream_display($stream);
		}
		if (substr_count($output,'user_post_left') > 9) {
			$output .= '
			<div id="morestream_box"></div>
			<div>
			<script>
			$(document).ready(function(){
				$("#morestream_button").click(function() {
					$(this).hide();
					$("#morestream_box").html("<img src=\"'.uhome().'/files/loading.gif\" /> Loading");
					$.post("'.uhome().'/index.php?p=jquery/morelikestream",
								{offset:$("#stream_offset").val(),uid:'.$profile['id'].'},
								  function(data){
									var currentVal = parseInt( $("#stream_offset").val() );
									$("#stream_offset").val(currentVal + 10);
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

			<input type="hidden" id="stream_offset" value="10" />
			<a href="#" id="morestream_button"><strong>'.t('See More').'</strong></a>
			</div>';
		}

		$current_sub_menu['href'] = 'u/'.$profile['username'].'/liked';
		section(array('title'=>t('Liked'),'content'=>$output)
			);

	}





	function following($url) {
		global $client, $apps, $uhome,$ubase, $offset, $num_per_page, $page;
		if (!eregi("[0-9a-z]+",$url)) {
			die('wrong uid');
		}
		$profile = $this->settabmenu($url, 1,'u');
		if (!$profile['id']) die('bad uid');
		$res = sql_query("select u.id,u.username,u.avatar from ".tb()."followers as f left join ".tb()."accounts as u on u.id=f.fid where f.uid='{$profile['id']}' order by u.lastlogin DESC limit $offset, $num_per_page");
		$output = '';
		while ($row = sql_fetch_array($res)) {
			$f = 1;
			$output .= avatar($row);
		}
		// pager
		$res = sql_query("select count(*) as total from `".tb()."followers` where uid='{$profile['id']}' ".dbhold() );
		$row = sql_fetch_array($res);
		$total = $row['total'];
		$pb       = new PageBar($total, $num_per_page, $page);
		$pb->paras = $ubase.'u/'.$profile['username'].'/following';
		$pagebar  = $pb->whole_num_bar();
		$output .= $pagebar;
		section(array('title'=>'Following','content'=>$output));
	}

}
