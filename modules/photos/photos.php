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
/*
text1: picids
*/
class photos extends story{
	function photos() {
		global $nav,$ubase;
		$nav[] = url('photos',t('Photos'));
		set_title(t('Photos'));
		$this->top_stories = 1;
		$this->disable_category = 0;
		$this->photos = 1;
		$this->allow_vote = 0;
		$this->list_type = 'gallery';
		$this->default_thumb = 'uploads/userfiles/undefined.jpg';
		$this->write_story = t('Upload');
		parent::story();
		$this->act_write = t('added a photo album');
		$this->label_title = t('Album name');
		$this->submit = t('Next step');
		$this->label_entry = t('photos');
	}

	function story_content($row) {
		global $client, $nav, $defined_current_tab;
			$story = '<div class="tab_things">'.$this->theme_story_footer($row);
			if ($client['id'] == $row['uid'] || allow_access(3)) {
				if ($this->name == 'photos') {
					$story .= '<div class="tab_thing"><strong>'.url($this->name.'/managephotos/'.$row['id'], t('upload')).'</strong></div>';
				}
				$story .= '<div class="tab_thing">'.url($this->name.'/editstory/'.$row['id'], t('Edit')).'</div>';
				$story .= '<div class="tab_thing">'.url($this->name.'/deletestory/'.$row['id'], t('Delete')).'</div>';
			}
			if (allow_access(3)) {
				if ($row['featured']) {
					$story .= '<div class="tab_thing">'.t('Featured').' ['.url($this->name.'/unfeature_story/'.$row['id'], t('Unfeature')).']</div>';
				}
				else {
					$story .= '<div class="tab_thing">'.url($this->name.'/feature_story/'.$row['id'], t('Feature this')).'</div>';
				}
			}
			$defined_current_tab = 'photos/liststories/page_'.$row['page_id'];

			$story .= '</div>';
			$story .= '<input type="hidden" value="'.$row['id'].'" name="story_id" id="story_id" />';
			$story = '<div class="user_post_2">
			<table>
		<tr><td align="right">
		'.avatar($row).'</td>
		<td align="left">
		<h1>'.url($this->name.'/viewstory/'.$row['id'],$this->title_prefix.h($row['title'])).'</h1>
		View: <a href="'.url('u/'.$row['username']).'">'.t("{1}'s Profile",$row['username']).'</a> | 
		<a href="'.url($this->name.'/liststories/user_'.$row['username']).'">'.t("{1}'s {2}",$row['username'],$this->label_entry).'</a>
		</td></tr>
		</table>
			'.$story.'
			</div>';
			$story .= '<div id="ad_block_content_top">'.show_ad('ad_block_content_top').'</div>';
			if (method_exists($this,'hook_viewstory')) {
				$story .= $this->hook_viewstory($row);
			}
			// photos
			if ($row['photos'] && $this->name == 'photos') {
				$res = sql_query("select * from `".tb()."story_photos` where sid='{$row['id']}' ORDER by id DESC");
				$story .= '<div><ul class="gallery">';
				while ($photo = sql_fetch_array($res)) {
					//$story .= '<li><a title="'.htmlspecialchars($photo['des']).'" href="'.uhome().'/photos/view/'.$photo['id'].'" ><img src="'.uhome().'/'.$photo['thumb'].'" /></a></li>';
					$story .= '<li><a title="'.htmlspecialchars($photo['des']).'" href="'.url('photos/view/'.$photo['id']).'" ><img src="'.uhome().'/'.$photo['thumb'].'" /></a></li>';
				}
				$story .= '</ul></div>';
			}
			$story .= $this->view_content($row);
			if (method_exists($this,'hook_viewstorybottom')) {
				$story .= $this->hook_viewstorybottom($row);
			}
			return $story;
	}

	function view($fid) {
		global $defined_current_tab;
		$res = sql_query("select * from ".tb()."story_photos where id='$fid'");
		$photo = sql_fetch_array($res);
		if (!$photo['id']) die('wrong fid');
		$res = sql_query("select * from ".tb()."stories where id='{$photo['sid']}'");
		$story = sql_fetch_array($res);
		if ($story['app'] != 'photos') die('wrong sid');

		$res = sql_query("select type from ".tb()."pages where id='{$story['page_id']}'");
		$jcow_page = sql_fetch_array($res);
		if (!$jcow_page['type']) die('unknown page id');
		if ($jcow_page['type'] == 'u') {
			include_once('modules/u/u.php');
			u::settabmenu($story['page_id'],1);
		}
		elseif ($jcow_page['type'] == 'page') {
			include_once('modules/page/page.php');
			page::settabmenu($story['page_id'],1);
		}
		elseif ($jcow_page['type'] == 'group') {
			include_once('modules/group/group.php');
			group::settabmenu($story['page_id'],1);
		}

		$defined_current_tab = 'photos/liststories/page_'.$story['page_id'];

		$res = sql_query("select * from ".tb()."accounts where id='{$story['uid']}'");
		$user = sql_fetch_array($res);
		$res = sql_query("select * from ".tb()."story_photos where sid='{$story['id']}' order by id desc");
		$i = 0;
		while ($row = sql_fetch_array($res)) {
			$i++;
			if ($row['id'] == $photo['id']) {
				$porder = $i;
			}
			$photos[$i] = $row;
		}
		if ($i > $porder) {
			$key = $porder+1;
			$nextb = '<strong>'.url('photos/view/'.$photos[$key]['id'],t('Next')).'</strong>';
		}
		if ($porder > 1) {
			$key = $porder-1;
			$prevb = '<strong>'.url('photos/view/'.$photos[$key]['id'],t('Previous')).'</strong>';
		}
		$res = sql_query("select * from ".tb()."streams where app='photo' and aid='{$photo['id']}'");
		$row = sql_fetch_array($res);
		if (!$stream_id = $row['id']) {
			$attachment = array(
				'cwall_id' => 'photo'.$photo['id'],
				'uri' => 'photos/view/'.$photo['id'],
				'thumb' => array($photo['thumb'])
				);
			$app = array('name'=>'photo','id'=>$photo['id']);
			$stream_id = stream_publish(t('Uploaded a photo'),$attachment,$app);
			sql_query("update ".tb()."streams set hide=1,created='{$story['created']}' where id='$stream_id'");
		}

		c('<table width="100%" border="0">
			<tr><td width="50">
			'.avatar($user).'</td>
			<td align="left">
			<h1>'.url('photos/viewstory/'.$story['id'],h($story['title'])).'</h1>
			'.t('Photo').' '.$porder.' of '.$i.' | '.url('photos/viewstory/'.$story['id'],t('Back to album')).'
			</td>
			<td align="right" valign="bottom">'.$prevb.' '.$nextb.'</td></tr>
			</table>');
		if ($story['var5'] == 2) {
			if (!privacy_access(2,$user['id'])) {
				$accessdenied = 1;
			}
			$privacy_flag = t('Friends only');
		}
		elseif ($story['var5'] == 1) {
			if (!privacy_access(1,$user['id'])) {
				$accessdenied = 1;
			}
			$privacy_flag = t('Friends of friends');
		}
		if ($accessdenied) {
			c(t('Sorry, this content is open to {1}','<strong>'.$privacy_flag.'</strong>'));
		}
		else {
			c('<div class="viewphoto">
			<center>
			<img src="'.uhome().'/'.$photo['uri'].'" />
			</center>
			</div>'.h($photo['des']));
			c('
			<style>
			img.viewthumb {
				border: #eeeeee 2px solid;
				margin: 5px;
			}
			img.viewthumb:hover {
				border: #99FF00 2px solid;
			}
			div.viewphoto {
				background: #F7F7F7;
				border-top: #ccc 1px solid;
				border-bottom: #ccc 1px solid;
				padding-top: 10px;
			}
			</style>');


			c('<div class="hr"></div>'.
				comment_form($stream_id).comment_get($stream_id,100)
					);

			c('
			<table width="100%" border="0">
			<tr><td>');
			// pre
			$res = sql_query("select * from ".tb()."story_photos where sid='{$story['id']}' and id>'{$photo['id']}' order by id ASC limit 3");
			while ($row = sql_fetch_array($res)) {
				$prev = '<a href="'.url('photos/view/'.$row['id']).'"><img width="50" height="50" src="'.uhome().'/'.$row['thumb'].'" class="viewthumb" /></a>'.$prev;
			}
			c($prev.'</td><td align="right">');
			// next
			$res = sql_query("select * from ".tb()."story_photos where sid='{$story['id']}' and id<'{$photo['id']}' order by id DESC limit 3");
			while ($row = sql_fetch_array($res)) {
				$next .= '<a href="'.url('photos/view/'.$row['id']).'"><img width="50" height="50" src="'.uhome().'/'.$row['thumb'].'" class="viewthumb" /></a>';
			}
			c($next.'</td></tr></table>');
		}

	}
	
	function story_form_content($row = array()) {
		global $uhome;
		if (file_exists('js/tiny_mce/jquery.tinymce.js')) {
			return '<p>'.label(t('Album Description')).
				$this->tinymce_form().'
	<textarea name="form_content" rows="5" style="width:580px" class="rich" >'.htmlspecialchars($row['content']).'</textarea>
	<input type="hidden" name="photos" value="1" />
	</p>';
		}
		else {
			return '<p>'.label(t('Album Description')).'
	<textarea name="form_content" rows="5" style="width:580px" class="rich" >'.htmlspecialchars($row['content']).'</textarea>
	<input type="hidden" name="photos" value="1" />
	</p>';
		}
	}


		// 文章表单
	function writestory($page_id=0) {
		do_auth($this->story_write);
		clear_as();
		GLOBAL $ubase,$content,$nav, $client, $title, $sub_menu, $ass,$current_app,$cat_id;
		if ($page_id) {
			$page = $this->check_page_access($page_id);
			$page_id = $page['id'];
		}
		else {
			$page_id = $client['page']['id'];
		}

		// choose album
		$res = sql_query("SELECT * FROM `".tb()."stories` WHERE uid='{$client['id']}' and app='photos' and page_id='{$page_id}' order by id DESC");
		if (sql_counts($res)) {
			c('<ul>');
			while ($row = sql_fetch_array($res)) {
				c('<li>'.url('photos/managephotos/'.$row['id'],htmlspecialchars($row['title'])).' ('.get_date($row['created']).')</li>');
			}
			c('</ul>');
		}
		else {
			c('<p>'.t('You have no photo album').'</p>');
		}
		section_close(t('Choose an album to continue'));


		$cat_id = $cid;
		$sub_menu = $ass = '';
		$nav[] = $this->write_story;
		$this->set_current_sub_menu($cid);
		$title = $this->write_story;
		c('<div class="form"><form action="'.$ubase.$this->name.'/'.$this->writepost.'" method="post"  enctype="multipart/form-data">');
		if (!$this->disable_category) {
			c($this->story_form_cat($cid));
		}
		c($this->writestory_form_elements($row));
		if ($this->hook['writestory']) {
			c($this->hook_writestory($row));
		}
		c('<input type="hidden" name="photos" value="1" />');
		c('<p><input type="hidden" name="page_id" value="'.$page_id.'" /><input class="button" type="submit" value="'.$this->submit.'" /></p>');
		c('</form></div>');
		section_close(t('Create a New album'));
	}



	function viewstory($sid) {
		GLOBAL $db,$client,$ubase,$uhome,$nav,$content, $title, $page_title, $page, $client,$cat_id, $num_per_page, $offset, $config;
		clear_as();
		$res = sql_query("select s.*,u.birthyear,u.gender,u.location,u.avatar,u.username from `".tb()."stories` as s left join `".tb()."accounts` as u on u.id=s.uid where s.id='$sid' ");
		$row = sql_fetch_array($res);
		if (!$row['id']) die('wrong sid');

		$res = sql_query("select type from ".tb()."pages where id='{$row['page_id']}'");
		$jcow_page = sql_fetch_array($res);
		if (!$jcow_page['type']) die('unknown page id');
		if ($jcow_page['type'] == 'u') {
			include_once('modules/u/u.php');
			u::settabmenu($row['page_id'],1);
		}
		elseif ($jcow_page['type'] == 'page') {
			include_once('modules/page/page.php');
			page::settabmenu($row['page_id'],1);
		}
		elseif ($jcow_page['type'] == 'group') {
			include_once('modules/group/group.php');
			group::settabmenu($row['page_id'],1);
		}

		$title = $this->title_prefix.$row['title'];
		if (!$row['id']) {
			die(':)');
		}
		if ($row['cid']) {
			$cat = valid_category($row['cid']);
			$cat_id = $row['cid'];
			$closed = $row['closed'];
			$this->set_current_sub_menu($row['id']);
		}
		if ($row['var5'] == 2) {
			if (!privacy_access(2,$row['uid'])) {
				$accessdenied = 1;
			}
			$privacy_flag = t('Friends only');
		}
		elseif ($row['var5'] == 1) {
			if (!privacy_access(1,$row['uid'])) {
				$accessdenied = 1;
			}
			$privacy_flag = t('Friends of friends');
		}
		if ($accessdenied) {
			$accessdenied = 1;
			c('<table>
		<tr><td align="right">
		'.avatar($row).'</td>
		<td align="left">
		<h1>'.url($this->name.'/viewstory/'.$row['id'],$this->title_prefix.h($row['title'])).'</h1>
		View: <a href="'.url('u/'.$row['username']).'">'.t("{1}'s Profile",$row['username']).'</a> | 
		<a href="'.url($this->name.'/liststories/user_'.$row['username']).'">'.t("{1}'s {2}",$row['username'],$this->label_entry).'</a>
		</td></tr>
		</table>');
			c(t('Sorry, this content is open to {1}','<strong>'.$privacy_flag.'</strong>'));
		}
		else {
			// 更新文章阅读数目
			sql_query("update `".tb()."stories` set views=views+1 where id='$sid'");
			/*
			if (!$this->disable_category) {
				$nav[] = url($this->name.'/liststories/'.$cat['id'],$cat['name']);
			}
			*/
			$nav[] = $title = $page_title = htmlspecialchars($row['title']);

			
			// table view
			$story .= $this->story_content($row);
			if ($this->tags && $row['tags']) {
				$story .= '<p><i>'.t('Tags').': </i><br />';
				$tags = explode(',',$row['tags']);
				foreach ($tags as $tag) {
					$res = sql_query("select * from `".tb()."tags` where  name='".addslashes($tag)."' and app='{$this->name}'");
					if ($tag = sql_fetch_array($res)) {
						$tagstr .= $tagstr ? ', '.url($this->name.'/tag/'.$tag['id'],'<img src="'.uhome().'/files/icons/tags.gif" /> '.$tag['name']).'<span class="sub">('.$tag['num'].')</span>' : url($this->name.'/tag/'.$tag['id'],'<img src="'.uhome().'/files/icons/tags.gif" /> '.$tag['name']).'<span class="sub">('.$tag['num'].')</span>';
					}
				}
				$story .= $tagstr;
				$stroy .= '</p>';
			}
			$story .= '<div id="sp_block_content_bottom">'.$config['sp_content_bottom'].show_ad('sp_block_content_bottom').'</div>';

			if ($this->allow_vote && strlen($row['rating']) > 10) {
				if ($client['id']) {
					$res = sql_query("select * from `".tb()."votes` where uid='{$client['id']}' and sid='{$sid}' limit 1");
					if (sql_counts($res)) {
						$vote_button = '';
						$vote_disable = '$("input").rating("readOnly",true);';
					}
					else {
						$vote_button = '<input type="button" style="font-size:10px" value="'.t('Submit').'" id="sendrate" />';
						$vote_disable = '';
					}
				}
				else {
					$vote_button = '';
					$vote_disable = '$("input").rating("readOnly",true);';
				}
				$story .= '<div id="rating_box">
				<script src="'.uhome().'/js/starrating/jquery.MetaData.js" type="text/javascript" language="javascript"></script>
					<script src="'.uhome().'/js/starrating/jquery.rating.js" type="text/javascript" language="javascript"></script>
					 <link href="'.uhome().'/js/starrating/jquery.rating.css" type="text/css" rel="stylesheet"/>
					 <form id="starrate">
					 <table border="0">
					 <td>';
				$ratings = unserialize($row['rating']);
				if (!is_array($ratings)) $ratings = array();
				foreach ($ratings as $key=>$rating) {
					$ratecheck = array();
					if ($rating['users']) {
						$rate = ceil($rating['score']/$rating['users']);
					}
					else {
						$rate = 0;
					}
					$ratecheck[$rate] = 'checked';
					$story .= ' 
					 <div style="width:100%;clear:both">'.$this->vote_options[$key].'</div>
					 <div style="width:100%;clear:both">
					  <input type="radio" class="star {split:2}" name="'.$key.'" value="1" '.$ratecheck['1'].' />
					  <input type="radio" class="star {split:2}" name="'.$key.'" value="2" '.$ratecheck['2'].' />
					  <input type="radio" class="star {split:2}" name="'.$key.'" value="3" '.$ratecheck['3'].' />
					  <input type="radio" class="star {split:2}" name="'.$key.'" value="4" '.$ratecheck['4'].' />
					  <input type="radio" class="star {split:2}" name="'.$key.'" value="5" '.$ratecheck['5'].' />
					  <input type="radio" class="star {split:2}" name="'.$key.'" value="6" '.$ratecheck['6'].' />
					  <input type="radio" class="star {split:2}" name="'.$key.'" value="7"/ '.$ratecheck['7'].' >
					  <input type="radio" class="star {split:2}" name="'.$key.'" value="8"/ '.$ratecheck['8'].' >
					  <input type="radio" class="star {split:2}" name="'.$key.'" value="9"/ '.$ratecheck['9'].' >
					  <input type="radio" class="star {split:2}" name="'.$key.'" value="10"/ '.$ratecheck['10'].' >
					  </div>
					  ';
				}
				$story .= '	  
					  </td>
					  <td>
					  <span id="sendbox">'.$vote_button.'</span>
					  <span id="votesnum" class="sub">'.$row['dugg'].'</span> <span class="sub">vote(s)</span>
					  </td>
					  </table>
					  <script>
					  $(document).ready( function(){
						  '.$vote_disable.'
						  $("#sendrate").click(function() {
							  $("#sendbox").html("<img src=\''.$uhome.'/files/loading.gif\' width=16 height=16 />");
							  $("input").rating("readOnly",true);
								$.post("'.$uhome.'/index.php?p=jquery/ratestory",{
									rate:$("form#starrate").serialize(),
									sid:$("#story_id").val()},
									function(data) {
												$("#sendbox").html("sent");
												$("#votesnum").html(data);
											},\'html\');
								return false;

							});
						});
						</script>
				';
				$story .= '</div>';
			}
			
			if (is_array($this->story_opts)) {
				$story .= '<script>
					$(document).ready( function(){
						$("#add_to_favorite").click(function() {
							$("#add_to_favorite").replaceWith("<img id=\'add_to_favorite\' src=\''.$uhome.'/files/loading.gif\' width=16 height=16 />");
							$.post("'.$uhome.'/index.php?p=jquery/favoriteadd",{sid:$("#story_id").val()},function(data) {
								$("#add_to_favorite").replaceWith(data);
							},\'html\');
						});
				});
				</script>
				<table border="0"><tr>';
				$story .= '</tr></table>';
			}

			if ($this->social_bookmarks) {
				$encoded_url = urlencode(url($this->name.'/viewstory/'.$sid));
				$encoded_title = urlencode($row['title']);
				$story .= '<div style="overflow:hidden;margin:10px 0;"><i>'.t('Bookmark & Share').':</i><div style="font-size:1.5em">';
				$story .= '<a href="http://twitter.com/home?status='.$encoded_url.'" target="_blank"><img src="'.$uhome.'/files/social_bookmarks/twitter.png" />Twitter</a> , ';
				$story .= '<a href="http://digg.com/submit?phase=2&url='.$encoded_url.'&title='.$encoded_title.'"  target="_blank"><img src="'.$uhome.'/files/social_bookmarks/digg.png" /> Digg</a> , ';
				$story .= '<a href="http://www.facebook.com/sharer.php?u='.$encoded_url.'&t='.$encoded_title.'"  target="_blank"><img src="'.$uhome.'/files/social_bookmarks/facebook.png" /> Facebook</a> ';
				$story .= '</div></div>';
			}
			c('
			'
			.$story.
				'<div class="hr"></div>'.
				comment_form($row['stream_id']).comment_get($row['stream_id'],100)
				);
		}
			
		
	}

	function ajax_form($page_type='',$page_id=0) {
		global $client;
		if (!$client) die('login');
		if (!$page_type) $page_type = 'u';
		$res = sql_query("select title,id from ".tb()."stories where uid='{$client['id']}' and app='photos' and page_id='{$page_id}' order by id DESC");
		while ($row = sql_fetch_array($res)) {
			$row['title'] = h(utf8_substr($row['title'],28));
			$my_albums[] = $row;
		}
		if (is_array($my_albums)) {
			echo photos::upload_form($my_albums,$page_type);
		}
		else {
			echo photos::album_form($page_type);
		}
		exit;
	}

	function ajax_post() {
		global $client;
		if ($_POST['act'] == 'create_album') {
			if (!$_POST['album_name']) photos::ajax_error('Please input an album name');
			$vote_options['rating'] = t('Rating');
			foreach ($vote_options as $key=>$vla) {
				$ratings[$key] = array('score'=>0,'users'=>0);
			}
			$page = story::check_page_access($_POST['page_id']);
			$story = array(
				'cid' => 0,
				'page_id' => $_POST['page_id'],
				'page_type'=>$page['type'],
				'title' => $_POST['album_name'],
				'content' => '',
				'uid' => $client['id'],
				'created' => time(),
				'var5' => $_POST['privacy'],
				'app' => 'photos',
				'rating' => serialize($ratings)
				);
			if (sql_insert($story, tb().'stories')) {
				$sid = $album_id = $story['id'] = mysql_insert_id();
				// write act
				$attachment = array(
					'cwall_id' => 'photos'.$sid,
					'uri' => 'photos/viewstory/'.$sid,
					'name' => $_POST['album_name']
					);
				$args = array(
					'message'=>t('added a photo album'),
					'link' => 'photos/viewstory/'.$sid,
					'name' =>  $_POST['album_name'],
					'app' => 'photos',
					);
				$stream_id = jcow_page_feed($_POST['page_id'],$args);
				$set_story['id'] = $sid;
				$set_story['stream_id'] = $stream_id;
				sql_update($set_story,tb()."stories");
				echo t('Album Created!');
			}
			else {
				photos::ajax_error('failed to create album');
			}
		}
		else {
			if (!$_POST['album_id']) photos::ajax_error('no album id');
			if (!$_FILES['photos']['tmp_name'][0]) photos::ajax_error('No photo selected');
			$res = sql_query("select id,photos,title,stream_id from ".tb()."stories where id='{$_POST['album_id']}' and uid='{$client['id']}'");
			$row = sql_fetch_array($res);
			$photos = $row['photos'];
			$album_name = $row['title'];
			$stream_id = $row['stream_id'];
			if (!$album_id = $row['id']) die('wrong album_id');
			foreach ($_FILES['photos']['tmp_name'] as $key=>$file_tmp_name) {
				if ($file_tmp_name) {
					$photo = array('name'=>$_FILES['photos']['name'][$key],
					'tmp_name'=>$file_tmp_name,
					'type'=>$_FILES['photos']['type'][$key],
					'size'=>$_FILES['photos']['size'][$key]);

					list($width, $height) = getimagesize($file_tmp_name);
					if ($width <= 740) {
						$uri = save_file($photo);
					}
					else {
						$height = floor(740*$height/$width);
						$uri = save_thumbnail($photo, 740, 0);
					}
					$photos++;
					$thumb = save_thumbnail($photo, 100, 100);
					$size = $photo['size'];
					sql_query("insert into `".tb()."story_photos` (sid,uri,des,thumb,size) values( {$album_id},'$uri','".$_POST['descriptions'][$key]."','$thumb','$size')");
					
				}
			}
			$set_story['thumbnail'] = $thumb;
			$set_story['id'] = $album_id;
			$set_story['photos'] = $photos ;
			sql_update($set_story, tb().'stories');
			$res = sql_query("select thumb from ".tb()."story_photos where sid='{$album_id}' order by id DESC limit 3");
			while ($photo = sql_fetch_array($res)) {
				$thumbs[] = $photo['thumb'];
				$pics = ' ('.$set_story['photos'].' pics)';
			}
			$attachment = array(
					'cwall_id' => 'photos'.$album_id,
					'uri' => 'photos'.'/viewstory/'.$album_id,
					'name' => addslashes($album_name),
					'thumb' => $thumbs
					);
			$app = array('name'=>'photos','id'=>$album_id);
			$stream_id = stream_update(t('added a photo album').$pics,$attachment,$app, $stream_id);
			echo t('Photo uploaded!'). ' <a href="'.url('photos/viewstory/'.$album_id).'"><strong>'.t('View').'</strong></a>';
		}
		echo photos::ajax_form($_POST['page_type'],$_POST['page_id']);
		exit;
	}

	function ajax_create_album($page_type='') {
		echo photos::album_form($page_type);
		exit;
	}

	function album_form($page_type='') {
		if ($page_type == 'u' || $_REQUEST['page_type'] == 'u') {
			$privacy_form = privacy_form();
		}
		return '<table border="0"><tr><td>'.t('Album name').':</td><td><input type="text" name="album_name" /></td></tr>
		<tr><td>'.t('Description').':</td><td><input type="text" size="55" name="description" />
		<input type="hidden" name="act" value="create_album" /></td></tr>
		</table>
		<div style="padding-right:25px;text-align:right">
		'.$privacy_form.'</div>';
	}

	function upload_form($albums,$page_type='') {
		$photo_album = '
		<script>
				function fresh_apps_box(freshurl) {
					$("#apps_box").html("");
					$("span#spanstatus").html("<img src=\"'.uhome().'/files/loading.gif\" /> Loading");
					$("#apps_box").load(freshurl, function() {
						$("span#spanstatus").html("");
					});
				}
				$("#add_another_photo").click(function() {
					$("#add_another_photo_box").before("<tr><td><input type=\"file\" name=\"photos[]\" /></td><td><input type=\"text\" size=\"35\" name=\"descriptions[]\" /></td></tr>");
				});
	</script><table border="0">
	<tr><td>'.t('Photo').'</td><td>'.t('Description').'</td></tr>
		<tr><td><input type="file" name="photos[]" /></td><td><input type="text" size="35" name="descriptions[]" /></td></tr>
		<tr id="add_another_photo_box"><td colspan="2"><a href="javascript:void();" id="add_another_photo">'.t('Add another photo').'</a></td></tr>
		<tr><td colspan="2">'.t('Album').':<select name="album_id">';
		foreach($albums as $album) {
				$photo_album .= '<option value="'.$album['id'].'">'.$album['title'].'</option>';
			}
		$photo_album .= '</select> '.t('or').' <a href="javascript:void();" onclick="javascript:fresh_apps_box(\''.url('photos/ajax_create_album/'.$page_type.'').'\')">'.t('Create a New album').'</a>
		</td></tr></table>
		';
		return $photo_album;
	}

	function ajax_error($msg) {
		echo '<div style="color:red">'.$msg.'</div>';
		echo photos::ajax_form();
		exit;
	}


}