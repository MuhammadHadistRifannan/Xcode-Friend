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


class search{
	
	function index() {
		c('
		<form action="'.url('search/listing').'" method="post">
		<p>
		<label>title</label> <input type="text" name="title" />
		</p>
		<p>
		<input type="submit" class="button" value="'.t('Submit').'" />
		</p>
		</form>
		');
	}
	
	function listing() {
		global $offset, $num_per_page, $page;
		$title = trim($_POST['title']);
		$display_title = stripslashes(trim($_POST['title']));
		c('Searching <strong>'.h($title).'</strong><br />');
		if (strlen($title) < 3) {
			sys_back(t('Keyword is too short'));
		}
		else {
			$hash = substr(md5($title),0,12);
			if ($c = get_cache($hash)) {
				header("location:".url('search/result/'.$hash));
				exit;
			}
			else {
				$c = '<h1>'.h($display_title).'</h1>';
				$res = sql_query("select * from ".tb()."accounts where username like '%{$title}%' or fullname like '%{$title}%'  order by lastlogin DESC limit 10");
				while ($user = sql_fetch_array($res)) {
					$users .= '<li>'.url('u/'.$user['username'],$user['username']).'<br />'.avatar($user).'<br />'.h($user['fullname']).'</li>';
				}
				if (strlen($users)) {
					$c .= '<h2>'.t('Members').'</h2>';
					$c .= '<ul class="small_avatars">'.$users.'</ul>';
				}
				$c .= '<h2>'.t('Stories').'</h2>
					<p>Searching for <strong>"'.h($title).'"</strong></p>';
				$res = sql_query("select s.*,u.username from `".tb()."stories` as s left join ".tb()."accounts as u on u.id=s.uid where s.title LIKE '%$title%' ORDER BY s.id DESC LIMIT 20");
				if (!sql_counts($res)) {
					$c .= '<p>no story matched</p>';
				}
				else {
					$c .= 'Stories:<br /><ul class="post">';
					while($story = sql_fetch_array($res)) {
						$c .= '<li>
						['.url($story['app'],app_name($story['app'])).'] 
						<a href="'.url($story['app'].'/viewstory/'.$story['id']).'">'.str_replace($title,'<strong>'.h($title).'</strong>',htmlspecialchars($story['title'])).'</a><br />'.get_date($story['created']).', by '.url('u/'.$story['username'],$story['username']).'
						</li>';
					}
					$c .= '</ul>';
				}
				set_cache($hash, $c, 48);
				header("location:".url('search/result/'.$hash));
				exit;
			}
		}
	}

	function result($hash) {
		set_title('Search result');
		c(get_cache($hash).get_gvar('ad_block_search'));
	}
	
}
