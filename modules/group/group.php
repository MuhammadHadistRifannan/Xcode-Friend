<?php
class group extends jcow_pages{
	function group() {
		$this->type = 'group';
	}

	function tab_menu($owner, $page) {
		return array(
			array('path'=>'group/'.$page['uri'], 'name'=>t('Wall'))
			);
	}

	function show_sidebar($page, $owner) {
		global $client;
		if (!$owner['location']) $owner['location'] = ' - ';
		$output = 
		'<center>'.
			page_logo($page,'big').'
		</center>';
		if ($client['id'] && $client['id'] != $owner['id']) {
			$res = sql_query("select * from ".tb()."page_users where uid='{$client['id']}' and pid='{$page['id']}'");
			if (sql_counts($res)) {
				$join_link = '<li>'.url('group/'.$page['uri'].'/leave',t('Leave this group')).'</li>';
			}
			else {
				$join_link = '<li>'.url('group/'.$page['uri'].'/joining',t('Join this group')).'</li>';
			}
		}
		$output .= '<ul class="sidebar_buttons">'.$join_link;
		if ($page['uid'] == $client['id']) {
			$output .= '
			<li>'.url('groups/manage/'.$page['id'],t('Settings')).'</li>';
			$output .= '<li>'.url('groups/logo/'.$page['id'],t('Edit group Logo')).'</li>';
			$key = 'group_ma_'.$page['id'];
			if (get_text($key)) {
				$res = sql_query("select count(*)as num from ".tb()."group_members_pending where gid='{$page['id']}' and !ignored");
				$row = sql_fetch_array($res);
				$output .= '<li>'.url('groups/pending/'.$page['id'],t('Pending members').' ('.$row['num'].')').'</li>';
			}
			$output .= '<li>'.url('group/'.$page['uri'].'/managemembers',t('Manage members')).'</li>';
		}
		$output .= '</ul>';
		ass(array('content'=>$output));
		$output = '';

		ass(
			array(
			'title'=>t('Description'),
			'content'=>nl2br(h($page['description']))
			)
			);

		$res = sql_query("select count(*) as num from ".tb()."page_users where pid='{$page['id']}'");
		$row = sql_fetch_array($res);
		$num = $row['num'];
		$res = sql_query("select u.username,u.fullname,u.avatar from ".tb()."page_users as p left join ".tb()."accounts as u on u.id=p.uid where p.pid='{$page['id']}' limit 6");
		while ($user = sql_fetch_array($res)) {
			$output .= avatar($user);
		}
		ass(
			array(
			'title'=>t('{1} members',$num),
			'content'=>$output,
			'box'=>url('group/'.$page['uri'].'/members',t('See all'))
			)
		);
	}

	function members($url) {
		global $client, $content, $nav, $apps, $uhome,  $ubase, $offset, $num_per_page, $page,$config, $menuon;
		$owner = $this->settabmenu($url, 1,'group');

		$res = sql_query("select u.username,u.fullname,u.avatar from ".tb()."page_users as p left join ".tb()."accounts as u on u.id=p.uid where p.pid='{$owner['page']['id']}' order by u.lastlogin DESC LIMIT $offset,$num_per_page");
		c('<ul class="gallery">');
		while ($row = sql_fetch_array($res)) {
			c('<li>');
			c('<span>'.url('u/'.$row['username'], $row['fullname']).'</span> '.avatar($row));
			c('</li>');
		}
		c('</ul>');

		// pager
		$res = sql_query("select count(*) as num from ".tb()."page_users where pid='{$owner['page']['id']}'");
		$row = sql_fetch_array($res);
		$total = $row['num'];
		$pb       = new PageBar($total, $num_per_page, $page);
		$pb->paras = url('group/'.$owner['page']['uri'].'/members');
		$pagebar  = $pb->whole_num_bar();
		c($pagebar);
		
	}
	
	function leave($url) {
		global $client;
		$owner = $this->settabmenu($url, 1,'group');
		$res = sql_query("select * from ".tb()."page_users where pid='{$owner['page']['id']}' and uid='{$client['id']}'");
		if (!sql_counts($res)) die('you are not a member');
		if ($owner['id'] == $client['id']) {
			c('You can not leave group created by yourself');
			stop_here();
		}
		if ($_POST['act'] == 'confirm') {
			// delete posts
			sql_query("delete c.* from ".tb()."comments as c left join ".tb()."streams as s on s.id=c.stream_id where c.uid='{$client['id']}' and s.wall_id='{$owner['page']['id']}'");
			sql_query("delete from ".tb()."streams where uid='{$client['id']}' and wall_id='{$owner['page']['id']}'");
			$res = sql_query("select id from ".tb()."stories where uid='{$client['id']}' and page_id='{$owner['page']['id']}'");
			while ($story = sql_fetch_array($res)) {
				$res2 = sql_query("select uri from ".tb()."story_photos where sid='{$story['id']}'");
				while($photo = sql_fetch_array($res2)) {
					@unlink($photo['uri']);
					@unlink($photo['thumb']);
				}
			}
			sql_query("delete from ".tb()."stories where uid='{$client['id']}' and page_id='{$owner['page']['id']}'");
			sql_query("delete from ".tb()."page_users where pid='{$owner['page']['id']}' and uid='{$client['id']}'");
			redirect('group/'.$url,1);
		}
		else {
			c('<form action="'.uhome().'/index.php?p=group/'.$url.'/leave" method="post">
			<input type="hidden" name="act" value="confirm" />
			'.t('Are you sure to leave this group? (All your posts in this group will be deleted!').'
			 <input type="submit" value=" '.t('Yes').' " />
			 </form>');
		}
	}

	function joining($url) {
		global $client, $captcha;
		$owner = $this->settabmenu($url, 1,'group');
		need_login();
		$res = sql_query("select * from ".tb()."page_users where pid='{$owner['page']['id']}' and uid='{$client['id']}'");
		if (sql_counts($res)) {
			redirect('group/'.$owner['page']['uri'],1);
			exit;
		}

		$key = 'group_ma_'.$owner['page']['id'];
		$group_ma = get_text($key);
		$res = sql_query("select * from ".tb()."group_members_pending where gid='{$owner['page']['id']}' and uid='{$client['id']}'");
		$row = sql_fetch_array($res);
		if ($row['uid']) {
			if ($row['ignored']) {
				c(t('You were ignored from this group'));
				stop_here();
			}
			else {
				c('<strong>'.t('Request sent.').'</strong><br />Status: '. t('Pending approval'));
				stop_here();
			}
		}
		else {
			if ($_POST['step2']) {
				$error = 0;
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
						$error = 'Incorrect reCaptcha';
				}
				if (!$error) {
					if ($group_ma && ($owner['id'] != $client['id'])) {
						sql_query("insert into ".tb()."group_members_pending (uid,gid,created) value ('{$client['id']}','{$owner['page']['id']}',".time().")");
						c('<strong>'.t('Request sent.').'</strong><br />Status: '. t('Pending approval'));
						send_note($owner['id'],t('{1} wants to join your group',url('u/'.$client['username'],h($client['fullname']))).': <strong>'.url('group/'.$owner['page']['uri'],h($owner['page']['name'])).'</strong>' );
						stop_here();
					}
					else {
						sql_query("insert into ".tb()."page_users (uid,pid) value ('{$client['id']}','{$owner['page']['id']}')");
						redirect('group/'.$owner['page']['uri'],1);
					}
				}
				else {
					sys_notice($error);
				}
			}
			c('
			<form method="post" action="'.url('group/'.$url.'/joining').'" >
			<p>'.recaptcha_get_html($captcha['publickey'],$captchaerror).'</p>
			<p><input type="submit" value="'.t('Join Now').'" />
			<input type="hidden" name="step2" value="1" />
			</p>
			</form>');
		}

	}

	function managemembers($url) {
		global $client, $content, $nav, $apps, $uhome,  $ubase, $offset, $num_per_page, $page,$config, $menuon;
		$owner = $this->settabmenu($url, 1,'group');
		if ($owner['id'] != $client['id']) die("access denied");
		if ($_POST['act'] == 'delete' && is_numeric($_POST['uid'])) {
			$user = valid_user($_POST['uid']);
			c('<p>'.
				t('Are you sure to delete {1} from your group? All his/her posts will be deleted from this group! And he/she can not join again!','<strong>'.h($user['fullname']).'</strong>').'<br />
			<form action="'.uhome().'/index.php?p=group/'.$url.'/managemembers" method="post">
			<input type="hidden" name="act" value="delete_confirm" />
			<input type="hidden" name="uid" value="'.$_POST['uid'].'" />
			<input type="submit" value="'.t('Yes').'" /> 
			'.url('group/'.$url.'/managemembers','Back').'
			</form>
			</p>');
			stop_here();
		}
		if ($_POST['act'] == 'delete_confirm' && is_numeric($_POST['uid'])) {
			$user = valid_user($_POST['uid']);
			if (!$user['id']) die('wrong uid');
			$res = sql_query("select * from ".tb()."page_users where pid='{$owner['page']['id']}' and uid='{$user['id']}'");
			$row = sql_fetch_array($res);
			if (!$row['uid']) die('not a member');
			// delete posts
			sql_query("delete c.* from ".tb()."comments as c left join ".tb()."streams as s on s.id=c.stream_id where c.uid='{$user['id']}' and s.wall_id='{$owner['page']['id']}'");
			sql_query("delete from ".tb()."streams where uid='{$user['id']}' and wall_id='{$owner['page']['id']}'");
			$res = sql_query("select id from ".tb()."stories where uid='{$user['id']}' and page_id='{$owner['page']['id']}'");
			while ($story = sql_fetch_array($res)) {
				$res2 = sql_query("select uri from ".tb()."story_photos where sid='{$story['id']}'");
				while($photo = sql_fetch_array($res2)) {
					@unlink($photo['uri']);
					@unlink($photo['thumb']);
				}
			}
			sql_query("delete from ".tb()."stories where uid='{$user['id']}' and page_id='{$owner['page']['id']}'");
			sql_query("delete from ".tb()."page_users where pid='{$owner['page']['id']}' and uid='{$user['id']}'");
			sql_query("insert into ".tb()."group_members_pending(uid,gid,ignored) values('{$user['id']}','{$owner['page']['id']}',2)");
			redirect(url('group/'.$url.'/managemembers'),1);
		}
		$res = sql_query("select u.id,u.username,u.fullname,u.avatar from ".tb()."page_users as p left join ".tb()."accounts as u on u.id=p.uid where p.pid='{$owner['page']['id']}' order by u.lastlogin DESC LIMIT $offset,$num_per_page");
		c('<ul class="gallery">');
		while ($row = sql_fetch_array($res)) {
			c('<li>');
			c('<span>'.url('u/'.$row['username'], $row['fullname']).'</span> '.avatar($row));
			c('<form action="'.url('group/'.$url.'/managemembers').'" method="post">
			<input type="hidden" name="act" value="delete" />
			<input type="hidden" name="uid" value="'.$row['id'].'" />
			<input type="submit" value="'.t('Delete').'" />
			</form>
			</li>');
		}
		c('</ul>');

		// pager
		$res = sql_query("select count(*) as num from ".tb()."page_users where pid='{$owner['page']['id']}'");
		$row = sql_fetch_array($res);
		$total = $row['num'];
		$pb       = new PageBar($total, $num_per_page, $page);
		$pb->paras = url('group/'.$owner['page']['uri'].'/members');
		$pagebar  = $pb->whole_num_bar();
		c($pagebar);
		
	}
}
