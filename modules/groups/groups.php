<?php
/* ############################################################ *\
 ----------------------------------------------------------------
Jcow Software (http://www.jcow.net)
IS NOT FREE SOFTWARE
http://www.jcow.net/commercial_license
Copyright (C) 2009 - 2010 jcow.net.  All Rights Reserved.
 ----------------------------------------------------------------
\* ############################################################ */

class groups {
	function browse() {
		global $content, $db, $apps, $client, $ubase, $nav, $offset, $page, $num_per_page, $current_sub_menu;
		set_title(t('Groups'));
		if ($client['id']) {
			button('groups/create',t('Create a group'));
		}
		$res = sql_query("select * from ".tb()."pages where type='group' order by id DESC  LIMIT $offset,$num_per_page");
		while ($group = sql_fetch_array($res) ) {
			if (!$group['logo']) {
				$group['logo'] = 'logo.jpg';
			}
			$logo = url('group/'.$group['uri'],'<img src="'.uhome().'/uploads/avatars/s_'.$group['logo'].'" />');
			$i++;
			$res2 = sql_query("select count(*) as num from ".tb()."page_users where pid='{$group['id']}'");
			$row2 = sql_fetch_array($res2);
			$group['users'] = $row2['num'];
			c('<table><tr><td>'.$logo.'</td><td>
				<a href="'.url('group/'.$group['uri']).'" rel="nofollow">'.h($group['name']).'</a>
			<span class="sub"> ('.t('{1} members','<strong>'.$group['users'].'</strong>').')</span>');
			c('<br /><span class="sub">'.h(utf8_substr($group['description'],40)).'</span></td></tr></table>');
		}

		// pager
		$res = sql_query("select count(*) as total from ".tb()."pages where type='group'");
		$row = sql_fetch_array($res);
		$total = $row['total'];
		$pb       = new PageBar($total, $num_per_page, $page);
		$pb->paras = $ubase.'groups/browse';
		$pagebar  = $pb->whole_num_bar();
		c($pagebar);

	}

	function index() {
		global $content, $db, $apps, $client, $ubase, $nav, $offset, $page, $num_per_page, $current_sub_menu;
		need_login();
		button('groups/create',t('Create a group'));
		$res = sql_query("select * from ".tb()."pages where type='group' and uid='{$client['id']}' order by updated DESC limit 100");
		c('<style>
		.page_listings {
			width:230px;
			padding:5px;
			float:left;
	}
	</style>
	<div style="width:100%;clear:both"></div>');
		while ($group = sql_fetch_array($res) ) {
			if (!$group['logo']) {
				$group['logo'] = 'logo.jpg';
			}
			$logo = url('group/'.$group['uri'],'<img src="'.uhome().'/uploads/avatars/s_'.$group['logo'].'" width="25" height="25" />');
			$i++;
			c('<div class="page_listings"><table><tr><td>
			'.url('group/'.$group['uri'],'<img src="'.uhome().'/uploads/avatars/s_'.$group['logo'].'" width="25" height="25" />').'</td><td>'.
				url('group/'.$group['uri'],h($group['name'])).'<div class="sub">'.t('Updated').': '.get_date($group['updated']).'</div></td></tr></table>
			</div>');
		}
		c('<div style="width:100%;clear:both"></div>');
		section_close(t('Groups I created'));
		
		c('<div style="width:100%;clear:both"></div>');
		$res = sql_query("select p.* from ".tb()."page_users as u left join ".tb()."pages as p on p.id=u.pid where u.uid='{$client['id']}' order by p.updated DESC limit 100");
		while ($group = sql_fetch_array($res) ) {
			if (!$group['logo']) {
				$group['logo'] = 'logo.jpg';
			}
			$logo = url('group/'.$group['uri'],'<img src="'.uhome().'/uploads/avatars/s_'.$group['logo'].'" width="25" height="25" />');
			$i++;
			c('<div class="page_listings"><table><tr><td>
			'.url('group/'.$group['uri'],'<img src="'.uhome().'/uploads/avatars/s_'.$group['logo'].'" width="25" height="25" />').'</td><td>'.
				url('group/'.$group['uri'],h($group['name'])).'<div class="sub">'.t('Updated').': '.get_date($group['updated']).'</div></td></tr></table>
			</div>');
		}
		c('<div style="width:100%;clear:both"></div>');
		section_close(t('Groups I joined'));
	}

	function leaveg($uri=0) {
		global $client;
		need_login();
		$res = sql_query("select * from ".tb()."pages where uri='{$uri}' and type='group'");
		$page = sql_fetch_array($res);
		if (!$page['id']) die('wrong page id');
		sql_query("delete from ".tb()."page_users where uid='{$client['id']}' and pid='{$page['id']}'");
		redirect('group/'.$page['uri'],1);
	}

	function create() {
		global $client, $captcha;
		if (!$client['id']) die('need login');
		set_title(t('Create a group'));
		clear_as();

		if ($_POST['step'] == 2) {
			$error = array();
			$_POST['guri'] = strtolower($_POST['guri']);
			if (strlen($_POST['guri']) < 6) {
				$errors[] = 'The Group address must be at least <strong>6</strong> characters long';
			}
			elseif (strlen($_POST['guri']) > 50) {
				$errors[] = 'The Group address cannot be longer than 50';
			}
			elseif (!preg_match("/^[0-9a-z]+$/i",$_POST['guri']) ) {
				$errors[] = 'The Group address can only contain 0-9,a-z';
			}
			else {
				$res = sql_query("select * from ".tb()."pages where uri='{$_POST['guri']}' and type='group'");
				if (sql_counts($res)) {
					$errors[] = 'The Group address is already in use: '.$_POST['guri'];
				}
			}
			if (!strlen($_POST['name']) ) {
				$errors[] = 'Please input a Page Name';
			}
			if (!count($errors)) {
				$resp = recaptcha_check_answer ($captcha['privatekey'],
											$_SERVER["REMOTE_ADDR"],
											$_POST["recaptcha_challenge_field"],
											$_POST["recaptcha_response_field"]);
				if (!$resp->is_valid) {
						c('<script language="javascript" >
				$(document).ready( function(){
									$("#recaptcha_response_field").focus();
				});
									</script>');
						$captchaerror = $resp->error;
						$errors[] = 'Incorrect reCaptcha';
				}
			}
			if (!count($errors)) {
				$page = array(
					'uid'=>$client['id'],
					'uri' => $_POST['guri'],
					'name'=>$_POST['name'],
					'type'=>'group',
					'updated'=>time(),
					'description'=>$_POST['description']
					);
				sql_insert($page, tb().'pages');
				$gid = mysql_insert_id();
				if ($_POST['group_ma']) {
					$group_ma_key = 'group_ma_'.$gid;
					set_text($group_ma_key,1);
				}
				if ($_POST['group_pri']) {
					$group_pri_key = 'group_pri_'.$gid;
					set_text($group_pri_key,1);
				}
				// add member
				sql_query("insert into ".tb()."page_users (uid,pid) value ('{$client['id']}','{$gid}')");
				redirect('group/'.$_POST['guri'] ,1);
				exit;
			}
		}
		if (is_array($errors)) {
			sys_notice('Please fix the Error(s)');
				c('<ul>');
				foreach ($errors as $error) {
					c('<li>'.$error.'</li>');
				}
				c('</ul>');
		}

		c('
			<form action="" method="post">

		<br /><br />'.label(t('Group address')).'
		<span style="font-size:1.5em;color:#3A74AD">'.url('group/','ohno').'</span> <input type="text" name="guri" value="'.$_POST['guri'].'" size="20" class="fpost" /><br />
		<span class="sub">(0-9,a-z),'.t('Example').': http://'.url('group/').'<strong>abcdefg</strong></span><br /><br />
	


		'.label(t('Group Name')).'<input type="text" name="name" value="'.h(stripslashes($_POST['name'])).'" size="20" class="fpost" />
		<br /><br />

		'.label(t('Visible')).'<input type="radio" name="group_pri" value="0" />'.t('Public').' <input type="radio" name="group_pri" value="1" checked />'.t('Private').'
		<br /><br />

		'.label(t('Membership')).'<input type="radio" name="group_ma" value="0" />'.t('Free to join').' <input type="radio" name="group_ma" value="1" checked />'.t('Need approval').'
		<br /><br />

		'.label(t('Group Description').' ('.t('Optional').')').'
		<textarea name="description" rows="5" cols="55">'.h($_POST['description']).'</textarea>
		<br /><br />
		'.recaptcha_get_html($captcha['publickey'],$captchaerror).'<br /><br />
		<input type="submit" value="'.t('Submit').'" class="fbutton" />
		<input type="hidden" value="2" name="step" />
		</form>
		');
		section_close(t('Create a group'));
	}


	function deleteit($page_id) {
		global $client;
		need_login();
		$res = sql_query("select * from ".tb()."pages where id='{$page_id}'");
		$page = sql_fetch_array($res);
		if (!$page['id']) die("wrong page id");
		if ($page['uid'] != $client['id'] && !allow_access(3)) {
			die('access denied');
		}
		if ($_POST['confirm']) {
			sql_query("delete from ".tb()."pages where id='{$page_id}'");
			$res = sql_query("select id from ".tb()."stories where page_id='{$page_id}'");
			while ($story = sql_fetch_array($res)) {
				$res2 = sql_query("select * from ".tb()."story_photos where sid='{$story['id']}'");
				while ($photo = sql_fetch_array($res2)) {
					@unlink($photo['uri']);
					@unlink($photo['thumb']);
					sql_query("delete from ".tb()."story_photos where id='{$photo['id']}'");
				}
				sql_query("delete from ".tb()."stories where id='{$story['id']}'");
				sql_query("delete from ".tb()."tag_ids where sid='{$story['id']}'");
			}
			sql_query("delete from ".tb()."streams where wall_id='{$page_id}'");
			sql_query("delete from ".tb()."page_users where pid='{$page_id}'");
			redirect('groups/mine');
		}
		set_title(h($page['name']));
		c('
			<form action="'.url('groups/deleteit/'.$page['id']).'" method="post">
		'.t('Page').': '.url('group/'.$page['uri'],h($page['name'])).'<br /><br />
		<strong>'.t('Are you sure to delete this Page?').'</strong><br />
		'.t('All posts,blogs,photos,videos under this page will be deleted too.').'
		<br /><br />
		<input type="hidden" name="confirm" value="1" />
		<input type="hidden" name="page_id" value="'.$page['id'].'" />
		<input type="submit" value="'.t('Delete it anyway').'" class="fbutton" />
		</form>
		');
	}


	function manage($page_id) {
		global $client;
		need_login();
		$res = sql_query("select * from ".tb()."pages where id='{$page_id}'");
		$page = sql_fetch_array($res);
		if (!$page['id']) die("wrong page id");
		if ($page['uid'] != $client['id'] && !allow_access(3)) {
			die('access denied');
		}
		set_title(h($page['name']));
		c('
			<form action="'.url('groups/managepost').'" method="post">');

		
		$key = 'group_pri_'.$page['id'];
		if (get_text($key)) {
			$vi_pri_selected = 'selected';
		}
		else {
			$vi_pub_selected = 'selected';
		}

		c(label(t('Visible')).'<select name="group_pri">
		<option value="0" '.$vi_pub_selected.'>'.t('Public').'</option>
		<option value="1" '.$vi_pri_selected.'>'.t('Private').'</option>
		</select>
		<br /><br />');


		$key = 'group_ma_'.$page['id'];
		if (get_text($key)) {
			$ma_selected = 'selected';
		}
		else {
			$free_selected = 'selected';
		}

		c(label(t('Membership')).'<select name="group_ma">
		<option value="0" '.$free_selected.'>'.t('Free to join').'</option>
		<option value="1" '.$ma_selected.'>'.t('Need approval').'</option>
		</select>
		<br /><br />');

		c(label(t('Group Name')).'<input type="text" name="name" value="'.h($page['name']).'" size="20" class="fpost" />
		<br /><br />');
		
		c(label(t('Group Description').' ('.t('Optional').')').'
		<textarea name="description" rows="5" cols="55">'.h($page['description']).'</textarea>
		<br /><br />
		<input type="hidden" name="page_id" value="'.$page['id'].'" />
		<input type="submit" value="'.t('Save changes').'" class="fbutton" />
		</form><br /><br />
		'.url('groups/deleteit/'.$page['id'],t('Delete this group')).'

		');
	}
	function managepost() {
		global $client;
		need_login();
		$res = sql_query("select * from ".tb()."pages where id='{$_POST['page_id']}'");
		$page = sql_fetch_array($res);
		if (!$page['id']) die("wrong page id");
		if ($page['uid'] != $client['id']) {
			die('access denied');
		}
		$group_pri_key = 'group_pri_'.$page['id'];
		if ($_POST['group_pri']) {
			set_text($group_pri_key,1);
		}
		else {
			delete_text($group_pri_key);
		}
		$group_ma_key = 'group_ma_'.$page['id'];
		if ($_POST['group_ma']) {
			set_text($group_ma_key,1);
		}
		else {
			delete_text($group_ma_key);
		}
		$newpage = array(
			'id'=>$page['id'],
			'name'=>$_POST['name'],
			'description'=>$_POST['description']
			);
		sql_update($newpage,tb()."pages");
		redirect('group/'.$page['uri'],1);
	}

	function pending($page_id) {
		global $client;
		need_login();
		$res = sql_query("select * from ".tb()."pages where id='{$page_id}'");
		$page = sql_fetch_array($res);
		if (!$page['id']) die("wrong page id");
		if ($page['uid'] != $client['id'] && !allow_access(3)) {
			die('access denied');
		}
		set_title(h($page['name']));
		c('
		&lt;&lt; '.url('group/'.$page['uri'],'Back').'
			<form action="'.url('groups/pendingpost').'" method="post">');

		$res = sql_query("select u.* from ".tb()."group_members_pending as p left join ".tb()."accounts as u on u.id=p.uid where p.gid='{$page['id']}' and !p.ignored order by p.created limit 50");
		c( '<ul class="small_avatars">');
		while($row = sql_fetch_array($res)) {
			c('
			<li>
		'.avatar($row).'<br />
		<input type="checkbox" name="uids[]" value="'.$row['id'].'" />'.url('u/'.$row['username'],$row['username']).'
		</li>');
		}
		c('</ul>');
		c('<input type="hidden" name="page_id" value="'.$page['id'].'" />
		<div style="width:100%;clear:both"></div>
		Selected:<br />
		<select name="action"><option value="approve">Approve</option><option value="ignore">Ignore</option</select> 
		<input type="submit" value="'.t('Save changes').'"  />
		</form>

		');
	}
	function pendingpost() {
		global $client;
		need_login();
		$res = sql_query("select * from ".tb()."pages where id='{$_POST['page_id']}'");
		$page = sql_fetch_array($res);
		if (!$page['id']) die("wrong page id");
		if ($page['uid'] != $client['id']) {
			die('access denied');
		}
		if (is_array($_POST['uids'])) {
			foreach ($_POST['uids'] as $uid) {
				$res = sql_query("select * from ".tb()."group_members_pending where uid='{$uid}' and gid='{$page['id']}'");
				if (sql_counts($res)) {
					if ($_POST['action'] == 'approve') {
						sql_query("delete from ".tb()."group_members_pending where uid='{$uid}' and gid='{$page['id']}'");
						$res = sql_query("select * from ".tb()."page_users where pid='{$page['id']}' and uid='{$uid}'");
						if (!sql_counts($res)) {
							send_note($uid,t('You are approved in the group').': <strong>'.url('group/'.$page['uri'],h($page['name'])).'</strong>' );
							sql_query("insert into ".tb()."page_users (uid,pid) value ('{$uid}','{$page['id']}')");
						}
					}
					else {
						sql_query("update ".tb()."group_members_pending set ignored=1 where uid='{$uid}' and gid='{$page['id']}'");
					}
				}
			}
		}
		redirect('groups/pending/'.$page['id'],1);
	}


	function logo($page_id) {
		global $client;
		need_login();
		$res = sql_query("select * from ".tb()."pages where id='{$page_id}'");
		$page = sql_fetch_array($res);
		if (!$page['id']) die("wrong page id");
		if ($page['uid'] != $client['id']) {
			die('access denied');
		}
		set_title(h($page['name']));
		c('<br />
		<form method="post" name="form1" action="'.url('groups/logopost').'" enctype="multipart/form-data">
					
					<fieldset>
					<legend>'.t('Group logo').'</legend>
					<p>
					'.page_logo($page,'big').'
					</p>
					<p>
					'.label(t('Upload')).'
					<input name="logo" type="file" id="avatar" />
					</p>
					</fieldset>

					<p>
					<input type="hidden" name="page_id" value="'.$page['id'].'" />
					<input class="button" type="submit" value="'.t('Save').'" />
					</p>
					</form>
		');
	}

	function logopost() {
		global $client;
		need_login();
		$res = sql_query("select * from ".tb()."pages where id='{$_POST['page_id']}'");
		$page = sql_fetch_array($res);
		if (!$page['id']) die("wrong page id");
		if ($page['uid'] != $client['id']) {
			die('access denied');
		}

		// avatar
		$newpage = array('id'=>$page['id']);
		if (strlen($_FILES['logo']['tmp_name'])>0 && $_FILES['logo']['tmp_name'] != "none") {
			include_once('includes/libs/resizeimage.inc.php');
			$dir = date("Ym",time());
			$folder = 'uploads/avatars/'.$dir;
			if (!is_dir($folder))
				mkdir($folder, 0777);
			$s_folder = 'uploads/avatars/s_'.$dir;
			if (!is_dir($s_folder))
				mkdir($s_folder, 0777);
			$name = date("H_i",time()).'_'.get_rand(5);
			//small
			$resizeimage = new resizeimage($_FILES['logo']['tmp_name'], $_FILES['logo']['type'], $s_folder.'/'.$name, 50,50, 0,100,'white');
			//big
			$resizeimage = new resizeimage($_FILES['logo']['tmp_name'], $_FILES['logo']['type'], $folder.'/'.$name, 200,200, 0, 100,'white');
			$newpage['logo'] = $dir.'/'.$name.".".$resizeimage->type;
			sql_update($newpage,tb()."pages");
			if ($page['logo']) {
				@unlink('uploads/avatars/'.$page['logo']);
				@unlink('uploads/avatars/s_'.$page['logo']);
			}
		}
		redirect('group/'.$page['uri'],1);
	}
}

