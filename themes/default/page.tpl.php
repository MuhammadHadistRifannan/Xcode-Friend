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
if ($client['id'] && $parr[0] == 'home') {
	header("location:".url('dashboard'));
	exit;
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">	

<head>
<base href="<?php echo uhome()?>/" />
<meta http-equiv="content-type" content="text/html; charset=UTF-8">
<meta name="Generator" content="Powered by Jcow" />
<?php echo $auto_redirect?>
<style type="text/css" media="all">@import "<?php echo $uhome;?>/files/common_css/style.css";</style>
<style type="text/css" media="all">@import "<?php echo $uhome;?>/themes/<?php echo get_gvar('theme_tpl')?>/page.css";</style>
<link rel="shortcut icon" href="<?php echo $uhome;?>/themes/default/ico.gif" type="image/x-icon" />
<?php echo $tpl_vars['javascripts'];?>
<title><?php echo $title?> - <?php echo get_gvar('site_name')?></title>
<?php echo $tpl_vars['custom_profile_css']?>
<?php
if (strlen(get_gvar('site_keywords'))) {
	echo '<meta name="keywords" content="'.get_gvar('site_keywords').'" />';
}
?>
<?php echo $header?>
<script type="text/javascript">
 var RecaptchaOptions = {
    theme : 'white'
 };
</script>
</head>

<body>

<div id="topbar_box">
<div id="topbar">
<table width="100%" height="50" cellpadding="0" cellspacing="0"><tr>
<td width="150">
<?php
if ($client['id']) {
	$home = url('dashboard');
}
else {
	$home = uhome();
}
echo '<a href="'.$home.'"><img src="'.uhome().'/themes/default/logo.png" /></a>';
?>
</td>
<td valign="middle">
	<table  cellpadding="0" cellspacing="0">
	<form action="<?php echo url('search/listing')?>" method="post" name="search_form">
	<tr>
	<td valign="top">
	<input type="text" id="search_box" name="title" value="" name="search_box"  />
	</td>
	<td>
	<input type="submit" value="find" id="search_button" style="display:none" />
	</td>
	</tr>
	</form>
	</table>
</td>
<?php
if (!$client['id']) {
	echo '
	<td align="right">
	'.url('member/login',t('Login')).' | '.url('member/signup',t('Sign up')).'
	</td>';
}
else {
	echo '
	<td align="right">'.url('u/'.$client['uname'],$client['uname']).' | '.$friendslink.' | '.url('message',t('Inbox').msg_unread() ).' | '.url('notifications',t('Notifications').note_unread()).' | <span class="sub">'.url('member/logout',t('Logout') ).'</span>
	</td>';
}
?>
</tr>
</table>

<div id="jcow_community_menu">
<ul class="menu">
<?php
echo '<li '.check_menu_on('home/index').'>'.url(uhome(),t('Home')).'</li>';
if (is_array($community_menu)) {
	foreach ($community_menu as $item) {
		echo '<li '.check_menu_on($item['path']).'>'.url($item['path'],t($item['name'])).'</li>';
	}
}
?>
</ul>
</div>

</div>
</div>


<!-- #################### structure ################## -->

<div id="wallpaper" style="width:100%;height:100%;overflow:hidden">
<div id="jcow_main_box">
<div id="jcow_main">
<table id="appframe" cellspacing="0"><tr>
<?php
if ($is_cover) {
	echo '<td valign="top" id="menubar_td">
	<div id="menubar">';
	if($client['id']) {
		if ($client['avatar'] == 'undefined.jpg') {
			$addavatar = '<br />'.url('account/avatar',t('Upload'));
		}
		echo '<table border="0"><tr><td width="60"><a href="'.url('u/'.$client['username']).'"><img width="50" height="50" src="'.uhome().'/uploads/avatars/s_'.$client['avatar'].'" /></a>
		'.$addavatar.'</td>
		<td valign="top"><strong>'.h($client['fullname']).'</strong>
		<br />'.url('u/'.$client['username'],t('Profile')).'
		<br />'.url('account',t('Account')).'
		</td></tr></table>
		<ul id="sidebarmenu">
		';
		if (is_array($personal_menu)) {
			foreach ($personal_menu as $item) {
				if ($item['path'] != 'account') {
					echo '<li '.check_menu_on($item['path']).' >'.url($item['path'],
					'<div style="padding:3px 0 3px 23px;background:url('.uhome().'/modules/'.$item['app'].'/icon.png) 0 1px no-repeat">'.t($item['name']).'</div>'
					).'</li>';
				}
			}
		}
		echo '</ul>';

	}
	else {
		if ($application != 'member') {
			echo '
			<form method="post" name="loginform" id="form1" action="'.url('member/loginpost').'" >
			'.t('Username or Email').':<br />
			<input type="text" size="10" name="username" style="width:120px" /><br />
							'.t('Password').':<br />
			<input type="password" size="10" name="password" style="width:120px" /><br />
			<div class="sub">( '.url('member/chpass',t('Forgot password?')).' )</div>
			<input type="checkbox" name="remember_me" value="1" /> '.t('Remember me').'<br />
			<input type="submit" value=" Login " />
			</form>';
			if (get_gvar('fb_id')) {
				echo '<div>'.url('fblogin','<img src="'.uhome().'/modules/fblogin/button.png" />').'</div>';
			}
			echo '
			<script language="javascript">document.loginform.username.focus();</script>
			<div class="hr"></div>
			'.t('New to our Network?').'<br />
			<a href="'.url('member/signup').'" style="display:block;font-size:2em;">
			'.t('Join Now!').'
			</a>
			<div class="hr"></div>
			';
		}
		//## display recent logged members
		$res = sql_query("SELECT * from `".tb()."accounts` order by lastlogin desc limit 20");
		/*
		//use this line if you want to display members who have avatars.
		$res = sql_query("SELECT * from `".tb()."accounts` where avatar!='' order by lastlogin desc limit 20");
		*/
		while($row = sql_fetch_array($res)) {
			$recentlogin .= avatar($row,25);
		}
		$theme_default_cache_left =  '
		<div>
		<strong>'.t('Recent Logins').':</strong><br />
		<div>'.$recentlogin.'</div>
		'.url('browse',t('Browse more people')).'
		</div>
		<div class="hr"></div>';

		//## display network statistics.
		$res = sql_query("SELECT count(*) as num from `".tb()."accounts`");
		$row = sql_fetch_array($res);
		$stats['members'] = $row['num'];
		$res = sql_query("SELECT count(*) as num from `".tb()."friends`");
		$row = sql_fetch_array($res);
		$stats['friendships'] = $row['num']/2;
		$res = sql_query("SELECT count(*) as num from `".tb()."comments`");
		$row = sql_fetch_array($res);
		$stats['comments'] = $row['num'];
		$res = sql_query("SELECT count(*) as num from `".tb()."streams`");
		$row = sql_fetch_array($res);
		$stats['activities'] = $row['num'];
		$theme_default_cache_left .= '
		<div>
		<strong>'.t('Network Statistics').':</strong><br />
		<strong>'.$stats['activities'].'</strong> '.t('Activities').'<br />
		<strong>'.$stats['members'].'</strong> '.t('Members').'<br />
		<strong>'.$stats['friendships'].'</strong> '.t('Friendships').'<br />
		<strong>'.$stats['comments'].'</strong> '.t('Comments').'
		</div>
		';

		echo $theme_default_cache_left;

	}
	echo get_gvar('theme_block_lsidebar');



	echo '</div>';
	echo '</td>';
}

echo '<td valign="top">';
if (count($nav) > 2) {
	echo '<div id="nav">'.gen_nav($nav).'</div>';
}
if (is_array($notices)) {
	foreach ($notices as $notice) {
		echo '<div class="notice">'.$notice.'</div>';
	}
}
if ($top_title) {
	echo '<div style="padding:3px 0 3px 30px;background:url('.uhome().'/modules/'.$application.'/icon.png) 9px 5px no-repeat;font-size:1.5em">'.$top_title.'</div>';
}

echo $app_header;
if (is_array($tab_menu)) {
	echo '<div id="tabmenu">';
	echo '<ul>';
	echo '<li class="tm_begin"></li>';
	echo tabmenu_begin();
	foreach ($tab_menu as $item) {
		echo '<li '.check_tabmenu_on($item['path']).'>'.url($item['path'],t($item['name'])).'</li>';
	}
	echo '<li class="tm_end"> </li>';
	echo '</ul>
	</div>';
}

if (is_array($buttons)) {
		echo '<div style="padding-left:10px;"><ul class="buttons">';
		foreach ($buttons as $val) {
			echo '<li>'.$val.'</li>';
		}
		echo '</ul></div>';
	}

/* 
The "display_application_content" is the output of applications. 
The default Width is 780px. 
You may not change the Width, otherwise some applications can not be displayed correctly 
*/
echo '<table border="0" width="100%">
<tr><td valign="top">';
if ($application != 'home') {
	display_application_content();
}
else {
	if (!$client['id']) {
		echo '<div><a href="'.url('member/signup').'"><img src="'.uhome().'/themes/default/welcome.jpg" alt="welcome" /></a></div>';
	}
	echo '
	<script type="text/javascript" src="'.uhome().'/js/jquery.vtricker.js"></script>
	<script>
	jQuery(document).ready(function($) {
		$(\'#recent_activites\').vTicker({
		   speed: 800,
		   pause: 5000,
		   showItems: 4,
		   animation: \'fade\',
		   mousePause: false,
		   height: 350,
		   direction: \'down\'
		});
				});
	</script>
	<style>
	#recent_activites li{
		margin:0;
		padding:0;
		}
	</style>
	<strong>'.t('Community activities').'</strong>
	<div id="recent_activites">
	<ul>
	';
	$res = sql_query("SELECT s.*,u.username,u.avatar from `".tb()."streams` as s left join ".tb()."accounts as u on u.id=s.uid where s.hide!=1  order by s.id desc limit 20");
	while ($stream = sql_fetch_array($res)) {
		$stream['attachment'] = unserialize($stream['attachment']);
		echo '<li>'.stream_display($stream,'simple').'</li>';
	}
	echo '</ul>
	<div style="position:absolute;left:0;bottom:0px;height:20px;width:100%;background:url('.uhome().'/files/common_css/fade.png) repeat-x"> </div></div>';
}
echo '</td>';
if (!$is_cover) {
	echo '<td valign="top"><div style="width:170px;float:right;">
	'.get_gvar('theme_block_adsbar').'</div></td>';
}

echo '
</tr></table>

</td>';


?>


</table>


</div><!-- end jcow_application -->
</div><!-- end jcow_application_box -->
</div><!-- end wallpaper -->

<div id="footer">
<div>
<?php echo $tpl_vars['language_selection']?>
</div>
<?php echo $tpl_vars['footer']?>
<br /><br />
<!-- do NOT remove the Jcow Attribution Information -->
<?php echo jcow_attribution(1);?>




</div>

<?php echo $footer;?>
</body>
</html>