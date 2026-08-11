<?php
/* ############################################################ *\
 ----------------------------------------------------------------
Jcow Software (http://www.jcow.net)
IS NOT FREE SOFTWARE
http://www.jcow.net/commercial_license
Copyright (C) 2009 - 2010 jcow.net.  All Rights Reserved.
 ----------------------------------------------------------------
\* ############################################################ */

class pages {
	function browse() {
		global $content, $db, $apps, $client, $ubase, $nav, $offset, $page, $num_per_page, $current_sub_menu;
		if ($client['id']) {
			button('pages/create',t('Create a page'));
		}
		$res = sql_query("select * from ".tb()."pages where type='page' order by id DESC  LIMIT $offset,$num_per_page");
		while ($jcow_page = sql_fetch_array($res) ) {
			if (!$jcow_page['logo']) {
				$jcow_page['logo'] = 'logo.jpg';
			}
			$logo = url('page/'.$jcow_page['uri'],'<img src="'.uhome().'/uploads/avatars/s_'.$jcow_page['logo'].'" />');
			$i++;
			$res2 = sql_query("select count(*) as num from ".tb()."page_users where pid='{$jcow_page['id']}'");
			$row2 = sql_fetch_array($res2);
			$jcow_page['users'] = $row2['num'];
			c('<table><tr><td>'.$logo.'</td><td>'.
				url('page/'.$jcow_page['uri'],h($jcow_page['name']) ).'
			<span class="sub"> ('.t('{1} people like this','<strong>'.$jcow_page['users'].'</strong>').')</span>');
			c('<br /><span class="sub">'.h(utf8_substr($jcow_page['description'],40)).'</span></td></tr></table>');
		}

		// pager
		$res = sql_query("select count(*) as total from ".tb()."pages where type='page'");
		$row = sql_fetch_array($res);
		$total = $row['total'];
		$pb       = new PageBar($total, $num_per_page, $page);
		$pb->paras = $ubase.'pages/browse';
		$pagebar  = $pb->whole_num_bar();
		c($pagebar);

	}

	function index() {
		global $content, $db, $apps, $client, $ubase, $nav, $offset, $page, $num_per_page, $current_sub_menu;
		need_login();
		button('pages/create',t('Create a page'));
		$res = sql_query("select * from ".tb()."pages where type='page' and uid='{$client['id']}' order by updated DESC limit 100");
		c('<style>
		.page_listings {
			width:230px;
			padding:5px;
			float:left;
	}
	</style>
	<div style="width:100%;clear:both"></div>');
		while ($jcow_page = sql_fetch_array($res) ) {
			if (!$jcow_page['logo']) {
				$jcow_page['logo'] = 'logo.jpg';
			}
			$logo = url('page/'.$jcow_page['uri'],'<img src="'.uhome().'/uploads/avatars/s_'.$jcow_page['logo'].'" width="25" height="25" />');
			$i++;
			c('<div class="page_listings"><table><tr><td>
			'.url('page/'.$jcow_page['uri'],'<img src="'.uhome().'/uploads/avatars/s_'.$jcow_page['logo'].'" width="25" height="25" />').'</td><td>'.
				url('page/'.$jcow_page['uri'],h($jcow_page['name'])).'<div class="sub">'.t('Updated').': '.get_date($jcow_page['updated']).'</div></td></tr></table>
			</div>');
		}
		c('<div style="width:100%;clear:both"></div>');
		section_close(t('Pages I created'));
		
		c('<div style="width:100%;clear:both"></div>');
		$res = sql_query("select p.* from ".tb()."page_users as u left join ".tb()."pages as p on p.id=u.pid where u.uid='{$client['id']}' and p.type='page' order by p.updated DESC limit 100");
		while ($jcow_page = sql_fetch_array($res) ) {
			if (!$jcow_page['logo']) {
				$jcow_page['logo'] = 'logo.jpg';
			}
			$logo = url('page/'.$jcow_page['uri'],'<img src="'.uhome().'/uploads/avatars/s_'.$jcow_page['logo'].'" width="25" height="25" />');
			$i++;
			c('<div class="page_listings"><table><tr><td>
			'.url('page/'.$jcow_page['uri'],'<img src="'.uhome().'/uploads/avatars/s_'.$jcow_page['logo'].'" width="25" height="25" />').'</td><td>'.
				url('page/'.$jcow_page['uri'],h($jcow_page['name'])).'<div class="sub">'.t('Updated').': '.get_date($jcow_page['updated']).'</div></td></tr></table>
			</div>');
		}
		c('<div style="width:100%;clear:both"></div>');
		section_close(t('Pages I liked'));
	}

	function like($uri=0) {
		global $client;
		need_login();
		$res = sql_query("select * from ".tb()."pages where uri='{$uri}' and type='page'");
		$page = sql_fetch_array($res);
		if (!$page['id']) die('wrong page id');
		$res = sql_query("select * from ".tb()."page_users where pid='{$page['id']}' and uid='{$client['id']}'");
		if (!sql_counts($res)) {
			sql_query("insert into ".tb()."page_users (uid,pid) value ('{$client['id']}','{$page['id']}')");
		}
		redirect('page/'.$page['uri'],1);
	}

	function unlike($uri=0) {
		global $client;
		need_login();
		$res = sql_query("select * from ".tb()."pages where uri='{$uri}' and type='page'");
		$page = sql_fetch_array($res);
		if (!$page['id']) die('wrong page id');
		sql_query("delete from ".tb()."page_users where uid='{$client['id']}' and pid='{$page['id']}'");
		redirect('page/'.$page['uri'],1);
	}

	function create() {
		global $client, $captcha;
		if (!$client['id']) die('need login');
		set_title(t('Create a page'));
		clear_as();

		if ($_POST['step'] == 2) {
			$errors = array();
			$_POST['guri'] = strtolower($_POST['guri']);
			if (strlen($_POST['guri']) < 6) {
				$errors[] = 'The Page Address must be at least <strong>6</strong> characters long';
			}
			elseif (strlen($_POST['guri']) > 50) {
				$errors[] = 'The Page Address cannot be longer than 50';
			}
			elseif (!preg_match("/^[0-9a-z]+$/i",$_POST['guri']) ) {
				$errors[] = 'The Page Address can only contain 0-9,a-z';
			}
			else {
				$res = sql_query("select * from ".tb()."pages where uri='{$_POST['guri']}' and type='page'");
				if (sql_counts($res)) {
					$errors[] = 'The page address is already in use: '.$_POST['guri'];
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
					'type'=>'page',
					'updated'=>time(),
					'description'=>$_POST['description']
					);
				sql_insert($page, tb().'pages');
				redirect('page/'.$_POST['guri'] ,1);
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

		<br /><br />'.label(t('Page Address')).'
		<span style="font-size:1.5em;color:#3A74AD">'.url('page/','ohno').'</span> <input type="text" name="guri" value="'.$_POST['guri'].'" size="20" class="fpost" /><br />
		<span class="sub">(0-9,a-z),'.t('Example').': http://'.url('page/').'<strong>abcdefg</strong></span><br /><br />
	


		'.label(t('Page Name')).'<input type="text" name="name" value="'.h(stripslashes($_POST['name'])).'" size="20" class="fpost" />
		<br /><br />

		'.label(t('Page Description').' ('.t('Optional').')').'
		<textarea name="description" rows="5" cols="55">'.h($_POST['description']).'</textarea>
		<br /><br />
		'.recaptcha_get_html($captcha['publickey'],$captchaerror).'
		<br /><br />
		<input type="submit" value="'.t('Submit').'" class="fbutton" />
		<input type="hidden" value="2" name="step" />
		</form>
		');
		section_close(t('Create a page'));
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
			<form action="'.url('pages/managepost').'" method="post">

		'.label(t('Page Name')).'<input type="text" name="name" value="'.h($page['name']).'" size="20" class="fpost" />
		<br /><br />

		'.label(t('Page Description').' ('.t('Optional').')').'
		<textarea name="description" rows="5" cols="55">'.h($page['description']).'</textarea>
		<br /><br />
		<input type="hidden" name="page_id" value="'.$page['id'].'" />
		<input type="submit" value="'.t('Save changes').'" class="fbutton" />
		'.url('pages/deleteit/'.$page['id'],t('Delete')).'
		</form>

		');
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
			redirect('pages');
		}
		set_title(h($page['name']));
		c('
			<form action="'.url('pages/deleteit/'.$page['id']).'" method="post">
		'.t('Page').': '.url('page/'.$page['uri'],h($page['name'])).'<br /><br />
		<strong>'.t('Are you sure to delete this Page?').'</strong><br />
		'.t('All posts,blogs,photos,videos under this page will be deleted too.').'
		<br /><br />
		<input type="hidden" name="confirm" value="1" />
		<input type="hidden" name="page_id" value="'.$page['id'].'" />
		<input type="submit" value="'.t('Delete it anyway').'" class="fbutton" />
		</form>
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
		$newpage = array(
			'id'=>$page['id'],
			'name'=>$_POST['name'],
			'description'=>$_POST['description']
			);
		sql_update($newpage,tb()."pages");
		redirect('page/'.$page['uri'],1);
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
		<form method="post" name="form1" action="'.url('pages/logopost').'" enctype="multipart/form-data">
					
					<fieldset>
					<legend>'.t('Page logo').'</legend>
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
		redirect('page/'.$page['uri'],1);
	}
}

