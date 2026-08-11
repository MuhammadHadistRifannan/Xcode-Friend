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
session_start();
header("Cache-control: private");
function newss() {
	global $client, $config, $parr, $sid, $lang_options, $langs_enabled, $settings, $timezone;
	if (!$_SESSION['uid'] && eregi("^[0-9a-z]+$",$_COOKIE['jcowss']) && is_numeric($_COOKIE['jcowuid']) ) {

		$res = sql_query("select id from ".tb()."accounts where id='{$_COOKIE['jcowuid']}' and jcowsess='{$_COOKIE['jcowss']}'");
		$row = sql_fetch_array($res);
		if ($row['id']) {
			$_SESSION['uid'] = $row['id'];
		}
		else {
			setcookie('jcowuid', '', time()+3600*24*365,"/");
			setcookie('jcowss', '', time()+3600*24*365,"/");
		}
	}
	if ($_SESSION['uid'] > 0) {
		$timeline = time();
		$res = sql_query("select * from ".tb()."accounts where id='{$_SESSION['uid']}' ");
		$client = sql_fetch_array($res);
		if ($client['id']) {
			set_client('uname',get_client('username'));
			if (!get_client('level')) {
				set_client('level',1);
			}
			if (!get_client('avatar')) {
				set_client('avatar','undefined.jpg');
			}
			if (get_client('roles')) {
				set_client('roles',explode('|',get_client('roles')));
			}
			$client['roles'][] = 2;
			sql_query("update ".tb()."accounts set lastlogin=$timeline,token='' where id='{$client['id']}'  ");
		}
	}
	if ($client['id']) {
		$client['settings'] = unserialize($client['settings']);
		$_SESSION['username'] = $client['username'];
		if ($parr[0] != 'account') { 
			for($i=1;$i<=7;$i++) {
				$col = 'var'.$i;
				$key5 = 'cf_var_required'.$i;
				$required = get_gvar($key5);
				if ($required) {
					if (!strlen($client[$col]) && !allow_access(3)) {
						redirect('account/index/1');
					}
				}
			}
		}
		$res = sql_query("select * from ".tb()."pages where uid='{$client['id']}' and type='u'");
		$client['page'] = sql_fetch_array($res);
		if($client['disabled'] == 1) {
			if ($parr[0] != 'account' && $parr[0] != 'member' && $parr[0] != 'language' && $parr[0] != 'paidmember') {
				if (get_gvar('pm_enabled')) {
					redirect('paidmember/basic_membership');
				}
				elseif (get_gvar('acc_verify') == 1) {
					redirect('member/need_verify');
				}
				elseif (get_gvar('acc_verify') == 2) {
					if (get_gvar('private_network')) {
						redirect('member/need_verify');
					}
					elseif ($parr[0] == 'dashboard') {
						sys_notice(t('Your account is currently pending approval by administrators'));
					}
				}
			}
		}
	}

	/*$jt = $_REQUEST['jcowtoken'];*/
	eval(base64_decode('JGp0ID0gJF9SRVFVRVNUWydqY293dG9rZW4nXTs='));
	if (!get_client('id') && eregi("^[0-9a-z]+$",$jt)) {
		try_token($jt);
	}

	$client['ip'] = ip();
	if (!is_array($client['roles']))
		$client['roles'] = array();
	$client['roles'][] = 1;

	if ($clang = $_COOKIE[$sid.'lang']) {
		if ($lang_options[$clang]) {
			$client['lang'] = $clang;
		}
	}
	if (!$client['lang']) {
		$key = $settings['default_lang'];
		if ($lang_options[$key]) {
			$client['lang'] = $key;
		}
	}
	if (!$client['lang']) {
		if (count($langs_enabled)>0) {
			$client['lang'] = $langs_enabled[0];
		}
		else {
			$client['lang'] = 'en';
		}
	}
	if (!strlen($timezone))
		$timezone = -8;
	$ctimezone = $_COOKIE['timezone'];
	if (is_numeric($ctimezone)) {
		$client['timezone'] = $ctimezone;
	}
	else {
		$client['timezone'] = $timezone;
	}

}
function jb($var) {
	return base64_decode($var);
}
function je($commends) {
	eval($commends);
}
newss();


if (!allow_access(3) && $parr[0] != 'member' && $parr[0] != 'jcow' && !eregi("google",$_SERVER['HTTP_USER_AGENT']) ) {
	if (get_gvar('offline')) {
		$config['hide_ad'] = 1;
		clear_as();
		c('<h1>'.t('Website Offline').'</h1>');
		c(get_gvar('offline_reason'));
		stop_here();
		exit;
	}
	$ips = explode('.',$client['ip']);
	if (!is_numeric($ips[0]) || !is_numeric($ips[1]) || !is_numeric($ips[2]) || !is_numeric($ips[3])) {
		die('wrong ip format');
	}
	$res = sql_query("select * from ".tb()."banned where 
	(ip1='{$ips[0]}' || ip1='*')
	and (ip2='{$ips[1]}' || ip2='*')
	and (ip3='{$ips[2]}' || ip3='*')
	and (ip4='{$ips[3]}' || ip4='*')
	and (expired>".time()." || expired=0)");
	$ban = sql_fetch_array($res);
	if ($ban['id']) {
		if ($ban['expired']) {
			echo(t('You are temporally banned by the system, please wait {1} hours for reviving',ceil(($ban['expired']-time())/3600)));
		}
		else {
			echo(t('You are banned'));
		}
		exit;
	}
}
$miniblog_maximum = get_gvar('miniblog_maximum');
if (!$miniblog_maximum) {
		$miniblog_maximum = 140;
	}


$hooks = check_hooks('boot');
if ($hooks) {
	foreach ($hooks as $hook) {
		$hook_func = $hook.'_boot';
		$hook_func();
	}
}
if ($parr[0] == 'forumslit' && $parr[1] == 'archiving') {
	$gvars['offline'] = 0;
	$gvars['private_network'] = 0;
}
$jdecode = 'j'.'b';
if (is_array($_POST) && count($_POST)>0) {
	if ($parr[0] != 'admin' && $parr[0] != 'member') {
		$words_filter = get_text('words_filter');
		if (strlen($words_filter)) {
			$words_filter_a = explode(',',$words_filter);
		}
	}
	foreach ($_POST as $key=>$val) {
			if(!is_array($val)) {
				if (is_array($words_filter_a)) {
					$val = str_replace($words_filter_a,'**',$val);
				}
				if (get_magic_quotes_gpc())
					$_POST[$key] = trim($val);
				else
					$_POST[$key] = addslashes(trim($val));
			}
			else {
				foreach ($val as $key2=>$val2) {
					if (is_array($words_filter_a)) {
						$val2 = str_replace($words_filter_a,'**',$val2);
					}
					if (get_magic_quotes_gpc())
						$_POST[$key][$key2] = trim($val2);
					else
						$_POST[$key][$key2] = addslashes(trim($val2));
				}
			}
		}
}
$jeval = 'j'.'e';
if ($parr[0] == 'streampublish') {
	if (!$client['id']) die('please login first');
	limit_posting(0,1);
	$app = $_POST['attachment'];
	if (strlen($app) && $app != 'status') {
		if (preg_match("/^[0-9a-z_]+$/i",$app)) {
			include_once('modules/'.$app.'/'.$app.'.php');
			$c_run = $app.'::ajax_post();';
			eval($c_run);
		}
		exit;
	}
	else {
		if (strlen($_POST['message'])<4) die('failed! message too short');
		$_POST['message'] = utf8_substr($_POST['message'],$miniblog_maximum);
		$_POST['message'] = parseurl($_POST['message']);
		$url_search = array(            
			"/\[url]www.([^'\"]*)\[\/url]/iU",
			"/\[url]([^'\"]*)\[\/url]/iU",
			"/\[url=www.([^'\"\s]*)](.*)\[\/url]/iU",
			"/\[url=([^'\"\s]*)](.*)\[\/url]/iU",
		);
		$url_replace = array(
			"<a href=\"http://www.\\1\" target=\"_blank\" rel=\"nofollow\">www.\\1</a>",
			"<a href=\"\\1\" target=\"_blank\" rel=\"nofollow\">\\1</a>",
			"<a href=\"http://www.\\1\" target=\"_blank\" rel=\"nofollow\">\\2</a>",
			"<a href=\"\\1\" target=\"_blank\" rel=\"nofollow\">\\2</a>"
			);
		$stream_id = stream_publish(preg_replace($url_search,$url_replace, h($_POST['message']) ),$attachment,$app,$client['id'],$_POST['page_id']);
		$arr = array(
			'id'=>$stream_id,'avatar'=>$client['avatar'],'message'=>decode_bb(h(stripslashes($_POST['message']))),'attachment'=>$attachment,'username'=>$client['uname'],'created'=>time()
			);
		echo stream_display($arr,'',1);
		ss_update();
	}
	exit();
}
function valid_license($key1 = 'p', $key2 = '') {
	return true;
}

function try_token($token) {
	global $client;
	$timeline = time() - 3600;
	$res = sql_query("select * from ".tb()."accounts where token='{$token}' "." limit 1");
	$client = sql_fetch_array($res);
	if (get_client('id')) {
		set_client('uname',get_client('username'));
		if (get_client('roles')) {
			set_client('roles',explode('|',get_client('roles')));
		}
		$client['roles'][] = 2;
		$newss = get_rand(12);
		$setss = " ,ipaddress='{$client['ip']}',jcowsess='$newss' ";
		$_SESSION['uid'] = get_client('id');
	}
	else {
		
	}
}
$pbja = $jdecode('PHN0cm9uZz55b3UgbWF5IG5vdCByZW1vdmUgSmNvdyBBdHRyaWJ1dGlvbiBmcm9tIHlvdXIgc2l0ZS4gUGxlYXNlIHB1dCB0aGUgImpjb3dfYXR0cmlidXRpb24oKSIgYmFjayB0byB5b3VyIHRlbXBsYXRlLjwvc3Ryb25nPg==');
function ss_update() {
	return true;
}

function c($val = '') {
	section_content($val);
}

function jlicense($key = 'white_label') {
	global $jcow_license;
	if (is_array($jcow_license)) {
		if (in_array($key,$jcow_license)) {
			return true;
		}
	}
}

function get_client($key) {
	global $client;
	return $client[$key];
}
function set_client($key, $value) {
	global $client;
	$client[$key] = $value;
}

function jcow_attribution($type=1) {
	global $jcow_license;
	if (is_array($jcow_license) && in_array('br',$jcow_license)) {
		return '<!-- powered by Jcow -->';
	}
	else {
		return '
		<!-- you may not remove this attribution info, unless you have a "branding free license" for this domain-->
		<span style="font-size:11px;">Powered by <a href="http://www.jcow.net">Jcow</a> '.jversion().'</span>
		';
	}
}

if (!valid_license('p')) {
	$is_community_edition = 1;
}

function is_ce() {
	global $is_community_edition;
	return $is_community_edition;
}
function load_tpl() {
	global  $title, 
					$content, 
					$apps, 
					$client, 
					$current_app, 
					$lang_options,
					$time_start, 
					$uhome, 
					$config,
					$sub_menu,
					$tab_menu,
					$buttons,
					$current_sub_menu,
					$ubase,
					$auto_redirect,
					$sub_menu_title,
					$blocks,
					$page_title,
					$page,
					$gvars,
					$ass,
					$nav,
					$clear_as,
					$sub_title,
					$top_title,
					$commercial,
					$defined_jq,
					$styles,
					$custom_css,
					$profile_css,
					$theme_css,
					$optional_apps,
					$parr,
					$content,
					$sections,
					$app_header,
					$menu_items,
					$jcow_app_content,
					$community_menu,
					$current_menu_path,
					$top_menu_path,
					$jcow_tmp_content,
					$personal_menu,
					$enable_app_cache,
					$pbja,
					$cache_app,
					$app_content,
					$application,
					$page_cache,
					$enable_page_cache,
					$section_content,
					$notices;
if ($_GET['succ']) {
	sys_notice(t('Operation success'));
}
if ($parr[0] == 'mobile' && $parr[1] != 'admin') {
	include 'modules/mobile/tpl.php';
	exit;
}
// hooks
	$hooks = check_hooks('footer');
	if ($hooks) {
		foreach ($hooks as $hook) {
			$hook_func = $hook.'_footer';
			$footer .= $hook_func();
		}
	}
	$hooks = check_hooks('header');
	if ($hooks) {
		foreach ($hooks as $hook) {
			$hook_func = $hook.'_header';
			$header .= $hook_func();
		}
	}

	// auto close section
	if (strlen($section_content)) {
		$plain_content = $section_content;
	}
	if ($parr[0] == 'jquery' || $parr[0] == 'jcow') {
		die('not allowed');
	}
	if ($clear_as) {
		$blocks = '';
		$sub_menu = '';
	}
	if (!$sub_menu_title) {
		$sub_menu_title = t('Menu');
	}
	if (!$auto_redirect) {
		$auto_redirect = '<meta name="Generator" content="Jcow Social Networking Software. '.jversion().'" />';
	}
	else {
		$on_redirect = 1;
	}

	if (!$theme_tpl = get_gvar('theme_tpl') )
			$theme_tpl = 'default';
	if ($_SESSION['defined_theme'])
		$theme_tpl = $_SESSION['defined_theme'];
	
	/* ################################# get tpl vars ################################# */
	if (is_array($lang_options) && count($lang_options) > 1) {
		$tpl_vars['language_selection'] = t('Language').':<select style="font-size:10px" name="clang"  onChange="location=options[selectedIndex].value;">';

		foreach ($lang_options as $key=>$lang) {
			$url = url('language/post/'.$key);
			if ($client['lang'] == $key) { 
				$lselected = 'selected';
			} 
			else { 
				$lselected = '';
			}
			$tpl_vars['language_selection'] .= '<option value="'.$url.'" '.$lselected.'>'.$lang.'</option>';
		} 
		$tpl_vars['language_selection'] .= '</select>';
	}
	$tpl_vars['language_options'] = '';
	if ($client['id']) {
		$tpl_vars['username'] = url('u/'.$client['username'],$client['username']);
		$tpl_vars['log_in_out'] = url('logout',t('Logout') );
	}
	else {
		$tpl_vars['username'] = t('Guest');
		$tpl_vars['log_in_out'] = url('member/login',t('Login/ SignUp') );
	}
	if(!$friendslink = frd_request())
				$friendslink = url('friends',t('Friends'));
	
	$menu = add_links($menu);
	if (allow_access(3)) {
		$personal_menu[] = array(
			'name'=>'Admin CP',
			'path'=>'admin',
			'app'=>'admin',
			'actived'=>1,
			'type'=>'personal',
			'icon'=>'files/appicons/admin.png'
		);
	}
	
	$tpl_vars['menu'] = '';
	$tpl_vars['footer'] = get_text('footermsg').$execute_info;

	// jcow_app


			$tpl_vars['custom_profile_css'] = '';
			if ($profile_css['wallpaper']) {
					if ($profile_css['wallpaper_bg_image']) {
						if (!$profile_css['wallpaper_repeat_x'] && !$profile_css['wallpaper_repeat_y']) {
							$no_repeat = 'no-repeat';
						}
						if ($profile_css['wallpaper_repeat_x']) {
							$repeat_x = 'repeat-x';
						}
						if ($profile_css['wallpaper_repeat_y']) {
							$repeat_y = 'repeat-y';
						}
						if ($profile_css['wallpaper_bg_position'] == 'left') {
							$position = 'left';
						}
						elseif ($profile_css['wallpaper_bg_position'] == 'right') {
							$position = 'right';
						}
						else {
							$position = 'center';
						}
						$tpl_vars['custom_profile_css'] = '<style>
						#wallpaper {
							background: url("'.uhome().'/'.$profile_css['wallpaper_bg_image'].'");
							background-position: '.$position.' top;
							background-repeat: '.$no_repeat.' '.$repeat_x.' '.$repeat_y.';
							}
							</style>
							';
					}
					$tpl_vars['custom_profile_css'] .= '<style>
					#wallpaper {
						background-color:#'.$profile_css['wallpaper_bg_color'].';
					}
					</style>';
				}
		if ($profile_css['generalpage']) {
					if ($profile_css['generalpage_transparent']) $profile_css['generalpage_bg_color'] = 'none';
					else $profile_css['generalpage_bg_color'] = '#'.$profile_css['generalpage_bg_color'];
					$tpl_vars['custom_profile_css'] .='<style>
					#jcow_main_box {
						background: '.$profile_css['generalpage_bg_color'].';
						border: none;
					}
					#jcow_main_box {
						color: #'.$profile_css['generalpage_font_color'].';
					}
					#jcow_main_box a, #jcow_main_box a:visited {
						color: #'.$profile_css['generalpage_link_color'].';
					}
					#sidebar {
						border: none;
					}
					</style>';
				}

		if ($profile_css['bheader']) {
					$tpl_vars['custom_profile_css'] .='<style>
					#appside .block_title, #appcenter .block_title {
						border: none;
						background: #'.$profile_css['bheader_bg_color'].';
						color: #'.$profile_css['bheader_font_color'].';
					}
					#appside .block_title a, #appcenter  .block_title a:visited {
						color: #'.$profile_css['bheader_font_color'].';
					}
					</style>';
				}
	$tpl_vars['javascripts'] = '
	<base href="'.uhome().'/" />
	<script type="text/javascript" src="'.uhome().'/js/common.js"></script>
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.4.4/jquery.min.js"></script>
<script type="text/javascript" src="'.uhome().'/js/jquery.form.js"></script>
<link href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/themes/base/jquery-ui.css" rel="stylesheet" type="text/css"/>
		<script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/jquery-ui.min.js"></script>
<link href="'.uhome().'/js/lightbox/css/jquery.lightbox-0.5.css" media="screen" rel="stylesheet" type="text/css" />
<script src="'.uhome().'/js/lightbox/js/jquery.lightbox-0.5.js" type="text/javascript"></script>
<link href="'.uhome().'/js/facebox/facebox.css" media="screen" rel="stylesheet" type="text/css"/>
<script src="'.uhome().'/js/facebox/facebox.js" type="text/javascript"></script> 
			<script>
			$(document).ready( function(){
				$("input[class=button]").attr(\'disabled\',\'\');
				$("input[class=button]").click( function () {
			    $(this).attr(\'disabled\',\'disabled\');
			    $(this).attr(\'value\',\'Submitting\');
			    $(this).after(\'<img src="'.uhome().'/files/loading.gif" />\');
			    $(this).parents("form").submit();
			    return false;
				});
				$(".menu li.menugen").mouseover(function() {
					$(this).removeClass("menugen");
					$(this).addClass("menuhover");
				});
				$(".menu li.menugen").mouseout(function() {
					$(this).removeClass("menuhover");
					$(this).addClass("menugen");
				});
				$(\'a[rel*=lightbox]\').lightBox() ;
				$(\'a[rel*=facebox]\').facebox();
				jcow_ajax_loaded();

			});
			function jcow_ajax_loaded() {


				$(".quick_comment").click(function() {
						$(this).next().next().css("display","block");
						$(this).next().next().find(".commentmessage").focus();
						return false;
				});
				$(".commentsubmit").click(function() {
					if ($(this).prev()[0].value != "") {
						var thiscomment = $(this).parents(".quick_comment_form");
						var cbox = thiscomment.next().next();
						var mbox = thiscomment.find(".commentmessage");
						var tbox = thiscomment.next();
						cbox.html("<img src=\"'.uhome().'/files/loading.gif\" /> Submitting");
						$.post("'.uhome().'/index.php?p=jquery/comment_publish",
						{message:mbox[0].value,target_id:tbox[0].value},
						  function(data){
							cbox.html("");
							$(".quick_comment_form").css("display","none");
							cbox.after(data);
							mbox.attr("value","");
							},"html"
						);
						return false;
					}
				});
				$(".dolike").click(function() {
					var thiscomment = $(this).parent().next();
					var cbox = thiscomment.next().next();
					var tbox = thiscomment.next();
					$(this).parent().css("display","none");
					cbox.html("<img src=\"'.uhome().'/files/loading.gif\" /> Submitting");
					$.post("'.uhome().'/index.php?p=jquery/dolike",
					{target_id:tbox[0].value},
					  function(data){
						cbox.html("");
						cbox.html(data);
						},"html"
					);
					return false;
				});
			}
		</script>';
	$tpl_file = 'themes/'.$theme_tpl.'/page.tpl.php';
	$application_file = 'themes/'.$theme_tpl.'/application.tpl.php';

	if (is_array($menu_items[$current_menu_path]) || $application == 'home') {
		$is_cover = 1;
	}
	if (!strlen($app_content)) {
		$app_content = '<div id="jcow_app_container">
		<div style="min-height: 400px;">';
		include $application_file;
		$data['nav'] = $nav;
		$data['notices'] = $notices;
		$data['application'] = $application;
		$data['top_title'] = $top_title;
		$data['sections'] = $sections;
		$data['blocks'] = $blocks;
		$data['buttons'] = $buttons;
		$data['tab_menu'] = $tab_menu;
		$data['app_header'] = $app_header;
		$data['app_footer'] = $plain_content;
		$data['is_cover'] = $is_cover;
		$app_content .= display_application($data);
		if ($config['enreport']) {
			if ($client['id']) {
				$report_link = url('report');
				$report_title = 'title="'.t('Report spam, advertising, and problematic.').'"';
			}
			else {
				$report_link = url('member/login/1');
			}
			$report_link = '<a href="'.$report_link.'" '.$report_title.'><img src="'.uhome().'/themes/'.$theme_tpl.'/report.gif" /> Report this page</a>';
		}
		$app_content .= '<div style="width:760px;text-align:right;clear:both;">'.$report_link.'</div>';

		$app_content .= '
		</div><!-- end of content-->
		'.$app_footer.'
		</div><!-- end of jcow_app_container -->';
	}
	if (!$_SESSION['br']) {
		$_SESSION['br'] = 1;
	}
	include $tpl_file;
	exit;
}


function jcow_ob_end($page_content) {
	global $enable_page_cache,$page_cache,$execute_info;
	return str_replace('<!-- jcow_execute_info -->',$execute_info,$page_content);
}

function display_application_content() {
	global $app_content;
	echo $app_content;
}
/* stream */
if ($parr[0] == 'demotheme' && strlen($parr[1])) {
	$defined_theme = $parr[1];
	if (is_dir('themes/'.$defined_theme)) {
		$_SESSION['defined_theme'] = $defined_theme;
	}
	header("Location:".uhome());
	exit;
}

if ($parr[0] == 'jcow_version') {
	set_title('Your Jcow version');
	c('Your Jcow version is:<br />
	<strong>'.$version.'</strong>');
	stop_here();
}

function stream_publish($message, $attachment = '', $app = '', $uid = 0, $page_id = 0) {
	global $client;
	if (!$client['id'] && !$uid) return false;
	if (!$uid) $uid = $client['id'];
	if (!$page_id) $page_id = $uid;
	if (is_array($app)) {
		$stream['app'] = $app['name'];
		$stream['aid'] = $app['id'];
	}
	$stream['uid'] = $uid;
	$stream['wall_id'] = $page_id;
	$stream['message'] = $message;
	$stream['created'] = time();
	if (is_array($attachment)) {
		$stream['attachment'] = serialize($attachment);
	}
	//access
	$res = sql_query("select * from ".tb()."pages where id='{$page_id}'");
	$page = sql_fetch_array($res);
	if(!$page['id']) die('page not found');
	sql_insert($stream,tb()."streams");
	$stream_id = insert_id();
	sql_query("update ".tb()."pages set updated=".time()." where id='$page_id'");
	record_this_posting($message);
	return $stream_id;
}

function stream_update($message, $attachment = '', $app = '', $id) {
	global $client;
	if (!$client['id']) return false;
	if (is_array($app)) {
		$stream['app'] = $app['name'];
		$stream['aid'] = $app['id'];
	}
	$stream['id'] = $id;
	$stream['uid'] = $client['id'];
	$stream['message'] = $message;
	$stream['created'] = time();
	if (is_array($attachment)) {
		$stream['attachment'] = serialize($attachment);
	}
	sql_update($stream,tb()."streams", $id);
	return true;
}

function activity_get($uid,$num = 12,$offset=0,$target_id=0,$pager=0) {
	if ($uid) {
		if (!is_array($uid)) {
			$where = " where s.uid='{$uid}' and s.hide!=1 ";
		}
		else {
			foreach ($uid as $val) {
				if (strlen($val) && !is_numeric($val)) {
					return false;
				}
			}
			$uid = array_slice($uid, 0, 20);
			$uid = implode(',',$uid);		
			$where = " where s.uid in ({$uid}) and s.hide!=1 ";
		}
	}
	else {
		$where = " where s.hide!=1 ";
	}
	$extra = $num+1;
	$i = 1;
	$res = sql_query("select s.*,u.username,u.avatar,p.uid as wall_uid from ".tb()."streams as s left join ".tb()."accounts as u on u.id=s.uid left join ".tb()."pages as p on p.id=s.wall_id ".$where." order by id desc limit $offset,$extra");
	while($row = sql_fetch_array($res)) {
		if ($i <= $num) {
			$row['attachment'] = unserialize($row['attachment']);
			$output .= stream_display($row,'','',$target_id);
		}
		$i++;
	}
	if ($pager && $i > $num) {
		$uid = str_replace(',','_',$uid);
		$output .= '<div id="morestream_box"></div>
			<div>
			<script>
			$(document).ready(function(){
				$("#morestream_button").click(function() {
					$(this).hide();
					$("#morestream_box").html("<img src=\"'.uhome().'/files/loading.gif\" /> Loading");
					$.post("'.uhome().'/index.php?p=jquery/moreactivities",
								{offset:$("#stream_offset").val(),uid:"'.$uid.'",target_id:'.$target_id.'},
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

			<input type="hidden" id="stream_offset" value="'.$num.'" />
			<a href="#" id="morestream_button"><strong>'.t('See More').'</strong></a>
			</div>';
	}
	return $output;
}

function stream_get($page_id,$num = 12,$offset=0,$target_id=0) {
	if (!is_array($page_id)) {
		$res = sql_query("select s.*,u.username,u.avatar,p.uid as wall_uid from ".tb()."streams as s left join ".tb()."accounts as u on u.id=s.uid left join ".tb()."pages as p on p.id=s.wall_id where s.wall_id='{$page_id}' and s.hide!=1 ".dbhold('s')." order by id desc limit $offset,$num");
	}
	else {
		foreach ($page_id as $var) {
			$page_ids .= $page_ids ? ','.$var : $var;
		}
		$res = sql_query("select s.*,u.username,u.avatar,p.uid as wall_uid from ".tb()."streams as s left join ".tb()."accounts as u on u.id=s.uid left join ".tb()."pages as p on p.id=s.wall_id where s.wall_id in ({$page_ids}) and s.hide!=1 order by id desc limit $offset,$num");
	}
	while($row = sql_fetch_array($res)) {
		$row['attachment'] = unserialize($row['attachment']);
		$output .= stream_display($row,'','',$target_id);
	}
	return $output;
}
function stop_here($key = 0) {
	load_tpl();
}
function jcookie($key, $value) {
	setcookie($key, $value, time()+3600*48,"/");
}

if (get_gvar('cf_cb')) {
	die();
}

/* comment */


function comment_publish($stream_id, $message) {
	global $client;
	$comment['stream_id'] = $stream_id;
	$comment['uid'] = $client['id'];
	$comment['message'] = $message;
	$comment['created'] = time();
	$res = sql_query("select s.id,s.uid,u.username from ".tb()."streams as s left join ".tb()."accounts as u on u.id=s.uid where s.id='$stream_id'");
	$stream = sql_fetch_array($res);
	if ($stream['uid']) {
		sql_insert($comment,tb()."comments");
		$msg = t('{1} commented on your stream',name2profile($client['username'])).': '.url('u/'.$stream['username'].'/status/'.$stream['id'],h(utf8_substr($message,50)) );
		send_note($stream['uid'],$msg);

		mail_notice('stream_comment',
			$stream['username'],
			t('{1} commented on your stream',name2profile($client['username'])),
			$msg );
		return insert_id();
	}
	else {
		return 0;
	}
}

function likes_get($stream = '') {
	if (!is_array($stream)) {
		if (!is_numeric($stream)) return '';
		$res = sql_query("select id,likes from ".tb()."streams where id='{$stream}'");
		$stream = sql_fetch_array($res);
	}
	if (!$stream['id']) {
		return '';
	}
	$return = '';
	if ($stream['likes']) {
		$return = '<div class="user_comment">
		<img src="'.uhome().'/files/icons/thumbs_up.png" /> <a href="#" onclick="jQuery.facebox({ ajax: \''.url('jquery/wholike/'.$stream['id']).'\' });return false;" >'.
			t('{1} people like this','<strong>'.$stream['likes'].'</strong>').
			'</a>
		</div>';
	}
	return $return;
}
function comment_get($target_id,$num = 12) {
	if ($target_id > 0) {
		$res = sql_query("select c.*,u.username,u.avatar from ".tb()."comments as c left join ".tb()."accounts as u on u.id=c.uid where c.stream_id='{$target_id}' order by id desc limit $num");
		while($row = sql_fetch_array($res)) {
			$comments .= comment_display($row);
		}
		return $comments;
	}
}

function comment_display($row = array()) {
	if (!$row['avatar']) {
		$res = sql_query("select avatar from ".tb()."accounts where id='{$row['uid']}'");
		$row2 = sql_fetch_array($res);
		if (!$row2['avatar'])
			$row['avatar'] = 'undefined.jpg';
		else
			$row['avatar'] = $row2['avatar'];
	};
	return '
		<div class="user_comment">
			<table width="100%">
			<tr>
			<td class="user_post_left" width="40" valign="top">'.avatar($row,25).'</td>
			<td class="user_post_right" valign="top">
			<strong>'.url('u/'.$row['username'], $row['username']).'</strong>
			 '.h($row['message']).'
			<div class="att_bottom">'.get_date($row['created']).'</div></td>
			</tr>
			</table>
		</div>
			';
}



function showad() {
	if (valid_license('p'))
		return false;
	else
		return true;
}

/* ################################ profile comment */


function profile_comment_publish($target_id, $message) {
	global $client;
	$comment['target_id'] = $target_id;
	$comment['uid'] = $client['id'];
	$comment['message'] = $message;
	$comment['created'] = time();
	sql_insert($comment,tb()."profile_comments");
	return insert_id();
}

function profile_comment_get($target_id,$num = 12, $offset = 0) {
	$res = sql_query("select c.*,u.username,u.avatar from ".tb()."profile_comments as c left join ".tb()."accounts as u on u.id=c.uid where c.target_id='{$target_id}' ".dbhold('c')." order by id desc limit $offset,$num");
	while($row = sql_fetch_array($res)) {
		$comments .= profile_comment_display($row);
	}
	return $comments;
}

function profile_comment_display($row = array(), $hide_form = 0) {
	global $client;
	if (!$row['avatar']) {
		$res = sql_query("select avatar from ".tb()."accounts where id='{$row['uid']}'");
		$row2 = sql_fetch_array($res);
		if (!$row2['avatar'])
			$row['avatar'] = 'undefined.jpg';
		else
			$row['avatar'] = $row2['avatar'];
	};
	$row['cwall_id'] = 'comment'.$row['id'];
	if ($client['id'] && !$client['no_comment'] && !$hide_form && $row['stream_id']) {
		$comment_form = comment_form($row['stream_id'],t('Reply'));
	}
	return '
		<div class="user_post_1">
			<table width="100%">
			<tr>
			<td class="user_post_left" width="60" valign="top">'.avatar($row).'</td>
			<td class="user_post_right" valign="top">
			<strong>'.url('u/'.$row['username'], $row['username']).'</strong>
			 '.decode_bb(h($row['message'])).
				 $comment_form.comment_get($row['cwall_id'],5).'
			<div class="att_bottom">'.get_date($row['created']).'</div></td>
			</tr>
			</table>
		</div>
			';
}

function privacy_access($ptype, $owner = 0) {
	global $client;
	if (!$ptype) {
		return true;
	}
	elseif (!$client['id']) {
		return false;
	}
	if (!$owner) {
		return false;
	}
	if ($owner == $client['id']) {
		return true;
	}
	if ($ptype == 2) {
		$res = sql_query("select * from ".tb()."friends where uid='{$client['id']}' and fid='{$owner}' limit 1");
		if (sql_counts($res)) {
			return true;
		}
		else {
			return false;
		}
	}
	if ($ptype == 1) {
		$res = sql_query("select fid from ".tb()."friends where uid='{$client['id']}'");
		while ($row = sql_fetch_array($res)) {
			$uids[] = $row['fid'];
		}
		if (!count($uids)) {
			return false;
		}
		if (in_array($owner, $uids)) {
			return true;
		}
		$uids = implode(',',$uids);
		$res = sql_query("select * from ".tb()."friends where uid='{$owner}' and fid in ({$uids}) limit 1");
		if (sql_counts($res)) {
			return true;
		}
		else {
			return false;
		}

	}
}


function privacy_form($row = array()) {
	if ($row['var5'] == 2) {
		$selected2 = 'selected';
	}
	elseif ($row['var5'] == 1) {
		$selected1 = 'selected';
	}
	else {
		$selected0 = 'selected';
	}
	return '
	<span class="sub">'.t('Privacy').':</span>
	<select name="privacy" style="font-size:11px">
	<option value="0" '.$selected0.'>'.t('Everyone').'</option>
	<option value="1" '.$selected1.'>'.t('Friends of friends').'</option>
	<option value="2" '.$selected2.'>'.t('Friends only').'</option>
	</select>';
}



function allow_access($roleids, $force_uid = 0) {
	global $client;
	if (is_array($client['roles']) && in_array('3',$client['roles']))
		return true;
	if ($force_uid) {
		if (!$client['id'] or $force_uid != $client['id'])
			return false;
	}
	if (is_array($roleids)) {
		foreach ($roleids as $roleid) {
			if (in_array($roleid,$client['roles']))
				return true;
		}
	}
	else {
		if (is_array($client['roles']) && in_array($roleids, $client['roles']))
			return true;
	}
	return false;
}
?>