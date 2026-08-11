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

if (basename($_SERVER["SCRIPT_NAME"]) != 'index.php') die(basename($_SERVER["SCRIPT_NAME"]));


		global $menu_items;

	if ($step == 'post') {
		global $menu_items;
			foreach ($menu_items as $item) {
				$id = $item['id'];
				$path = $_POST['path_'.$id];
				$name = $_POST['name_'.$id];
				$app = $_POST['app_'.$id];
				$weight = $_POST['weight_'.$id];
				$actived = $_POST['active_'.$id] ? 1:0;
				$update_path = strlen($app) ? '':",path='$path'";
				if (strlen($name)) {
					sql_query("update ".tb()."menu set actived=$actived,weight='$weight',name='$name' $update_path where id='$id'");
				}
			}
		redirect('admin/menu',1);
	}
	
	if ($step == 'newitem') {
		sql_query("insert into ".tb()."menu(name,weight,path,actived,type) values('{$_POST['name']}','{$_POST['weight']}','{$_POST['path']}','{$_POST['active']}','{$_POST['type']}') ");
		redirect('admin/menu',1);
	}
	
	if ($step == 'delete') {
		sql_query("delete from ".tb()."menu where id='$id' ");
		redirect('admin/menu',1);
	}


		section_content('
		<p>DO NOT MAKE TRANSLATE THE MENU ITEMS. YOU SHOULD MAKE TRANSLATION '.URL('admin/translate','HERE').'</p><table width="100%" border="0" class="stories">
		<form method="post" action="'.url('admin/menu/post').'">
		<tr class="table_line1"><td width="50">Active</td>
		<td width="80">Weight</td>
		<td>Name</td>
		<td>Path/URL</td>
		</tr>
		<tr class="table_line2"><td colspan="4">Community Menu</td></tr>');
		foreach ($menu_items as $item) {
			if ($item['type'] == 'community') {
				$checked = $item['actived'] ? 'checked':'';
				if (!$item['app']) {
					$path = '<input type="text" name="path_'.$item['id'].'" value="'.$item['path'].'" size="40" />';
					$delete = url('admin/menu/delete/'.$item['id'],t('Delete'));
				}
				else {
					$path = $item['path'];
					$delete = '';
				}
				section_content('<tr class="row1"><td><input type="checkbox" name="active_'.$item['id'].'" value="1" '.$checked.' /></td>
				<td><input type="text" name="weight_'.$item['id'].'" value="'.$item['weight'].'" size="3" /></td>
				<td><input type="text" name="name_'.$item['id'].'" value="'.$item['name'].'" /><input type="hidden" name="app_'.$item['id'].'" value="'.$item['app'].'" /></td>
				<td>'.$path.' '.$delete.'</td>
				</tr>');
			}
		}
		section_content('<tr class="table_line2"><td colspan="4">Personal Menu</td></tr>');
		foreach ($menu_items as $item) {
			if ($item['type'] == 'personal') {
				$checked = $item['actived'] ? 'checked':'';
				if (!$item['app']) {
					$path = '<input type="text" name="path_'.$item['id'].'" value="'.$item['path'].'" size="40" />';
					$delete = url('admin/menu/delete/'.$item['id'],t('Delete'));
				}
				else {
					$path = $item['path'];
					$delete = '';
				}
				section_content('<tr class="row1"><td><input type="checkbox" name="active_'.$item['id'].'" value="1" '.$checked.' /></td>
				<td><input type="text" name="weight_'.$item['id'].'" value="'.$item['weight'].'" size="3" /></td>
				<td><input type="text" name="name_'.$item['id'].'" value="'.$item['name'].'" /><input type="hidden" name="app_'.$item['id'].'" value="'.$item['app'].'" /></td>
				<td>'.$path.' '.$delete.'</td>
				</tr>');
			}
		}
		section_content('
		<tr><td colspan="4">
		<input type="submit" value="Save changes" />
		</td></tr>
		</form></table>');
		
		
		$res = sql_query("select max(weight) as weight from ".tb()."menu");
		$row = sql_fetch_array($res);
		$weight = $row['weight'] + 1;
		section_close('Current menu items');
		section_content('
		<table width="100%" border="0" class="stories">
		<form method="post" action="'.url('admin/menu/newitem').'">
		<tr class="table_line1"><td width="50">Active</td>
		<td width="80">Weight</td>
		<td>Name</td>
		<td>Path/URL</td>
		<td>Type</td>
		</tr>
		<tr class="row1"><td><input type="checkbox" name="active" value="1" checked /></td>
			<td><input type="text" name="weight" size="3" value="'.$weight.'" /></td>
			<td><input type="text" name="name" /></td>
			<td><input type="text" name="path" size="40" value="http://" /></td>
			<td><input type="hidden" name="type" value="community" />Community</td>
			</tr>
		<tr><td colspan="4">
		<input type="submit" value="Create" />
		</td></tr></form></table>');
		section_close('New item');

	
