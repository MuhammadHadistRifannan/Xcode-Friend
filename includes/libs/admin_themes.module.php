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


if ($step == 'post') {
		set_gvar('theme_tpl',$_POST['theme_tpl']);
		if (file_exists('themes/'.$_POST['theme_tpl'].'/settings.php')) {
			include('themes/'.$_POST['theme_tpl'].'/settings.php');
		}
		if (is_array($theme_blocks)) {
			foreach ($theme_blocks as $key=>$block) {
				$key = 'theme_block_'.$key;
				if (!get_text($key)) {
					set_text($key,addslashes($block['default_value']));
				}
			}
		}
		redirect('admin/themes',1);
}
		if ($handle = opendir('themes')) {
			while (false !== ($file = readdir($handle))) {
				if (is_dir('themes/' .$file) && $file != '.' && $file != '..' && $file != '.svn' ) {
					$themes[] = $file;
				}
			}
			closedir($handle);
		}
		section_content('
		<style>
		.theme_preview {
			height:160px;
			width:150px;
			float:left;
			border: #eee 1px solid;
			margin: 5px;
			text-align:center;
		}
		.theme_preview img {
			border: #eee 1px solid;
		}
		</style>
		<form method="post" action="'.url('admin/themes/post').'">');
		if (is_array($themes)) {
			foreach ($themes as $theme) {
				$selected = '';
				if ($theme == get_gvar('theme_tpl')) {
					$selected = 'checked';
					$actived = '<strong>Actived</strong><br />'.url('admin/blocks','Manage Blocks');
				}
				else {
					$actived = 'Active';
				}
				section_content('<div class="theme_preview">
				<label for="theme'.$theme.'">
				<img src="'.uhome().'/themes/'.$theme.'/preview.gif" /><br />
				'.$theme.'<br />
				<input type="radio" name="theme_tpl" value="'.$theme.'" id="theme'.$theme.'" '.$selected.' />'.$actived.'
				</label>
				</div>');
			}
		}
		section_content('
		<div class="br"></div>
		<input type="submit" value="Save Change" />
		</form>');
		section_close('Themes');


