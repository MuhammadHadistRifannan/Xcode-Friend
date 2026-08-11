<?php
class page extends jcow_pages{
	function page() {
		$this->type = 'page';
	}

	function tab_menu($owner, $page) {
		return array(
			array('path'=>'page/'.$page['uri'], 'name'=>t('Wall'))
			);
	}

	function show_sidebar($page, $owner) {
		global $client;
		if (!$owner['location']) $owner['location'] = ' - ';
		$output = 
		'<center>'.
			page_logo($page,'big').'
		</center>';
		if ($page['uid'] == $client['id']) {
			$output .= '<ul class="sidebar_buttons">
			<li>'.url('pages/manage/'.$page['id'],t('Edit page')).'</li>';
			$output .= '<li>'.url('pages/logo/'.$page['id'],t('Edit page Logo')).'</li>';
			$output .= '</ul>';
		}
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
			'title'=>t('{1} people like this',$num),
			'content'=>$output,
			'box'=>url('page/'.$page['uri'].'/fans',t('See all'))
			)
		);
	}

	function fans($url) {
		global $client, $content, $nav, $apps, $uhome,  $ubase, $offset, $num_per_page, $page,$config, $menuon;
		$owner = $this->settabmenu($url, 1,'page');

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
		$pb->paras = url('page/'.$owner['page']['uri'].'/fans');
		$pagebar  = $pb->whole_num_bar();
		c($pagebar);
		
	}
}
