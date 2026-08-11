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

// var1: song location
// var2: musician name
// var3: picture
class music extends story{
	
	function music() {
		$this->hooks(array('writestory','writestorypost','viewstory'));
		$this->write_story = t('Upload song');
		set_title(t('Music'));
		$this->stories_from_author = 1;
		$this->stories_from_cat = 1;
		$this->top_stories = 1;
		$this->tags = 1;
		$this->allow_vote = 1;
		$this->label_content = t('Description');
		$this->act_write = t('added a song');
		$this->label_title = t('Name of the song');
		$this->default_thumb = 'uploads/userfiles/undefined_song.gif';
		parent::story();
		
	}
	
	function hook_writestory($row) {
		return '
			<p><label>'.t('Upload').'</label><input type="file" name="song" />
			<span>'.t('Only mp3 is accepted').'</span></p>
			<p><label>'.t('Music source').'</label>
			<input type="radio" name="musicsource" value="own" checked="1" />'.t('My own').' 
			<input type="radio" name="musicsource" value="others" />'.t('From another musician').'
			</p>
			<div id="ms_others" style="display:none">
			<p><label>'.t('Musician name').'</label><input name="musician" /></p>
			</div>
			<div id="ms_own">
			<p></p>
			</div>
			<p><label>'.t('Picture').' ('.t('Optional').')</label><input type="file" name="picture" />
			<span>'.t('A picture about the song or the musician').'</span></p>
			<script type="text/javascript">
	    jQuery(document).ready(function($) {
	    	$("input[value=\'own\']").click(
	    		function() {
	    		$("#ms_others").hide();
	    		$("#ms_own").fadeIn();
	    		}
	    		);
	      $("input[value=\'others\']").click(
	    		function() {
	    		$("#ms_own").hide();
	    		$("#ms_others").fadeIn();
	    		}
	    		);
	    })
	  </script>';
	}

	function hook_writestorypost(&$story) {
		if ($_POST['musicsource'] == 'others') {
			if (!strlen($_POST['musician'])) {
				sys_back('If the song is not created by your own, you must enter the musician name');
			}
			$story['var2'] = $_POST['musician'];
		}
		if (!eregi(".mp3$",$_FILES['song']['name'])) {
				sys_back('Unavaible mp3 format');
		}
		if ($_FILES['picture']['tmp_name']) {
			$thumb = save_thumbnail($_FILES['picture']);
			list($width, $height) = getimagesize($_FILES['picture']['tmp_name']);
			if ($width <= 580) {
				$picture = save_file($_FILES['picture']);
			}
			else {
				$height = floor(580*$height/$width);
				$picture = save_thumbnail($_FILES['picture'], 580, $height);
			}
			$story['thumbnail'] = $thumb;
			$story['var3'] = $picture;
		}
		
		// upload song
		$uri = save_file($_FILES['song'],array('mp3'));
		$story['var1'] = $uri;
	}
	
	function hook_viewstory($row) {
		global $uhome;
		if (strlen($row['var1'])) {
			if (!$row['var2']) {
				$row['var2'] = $row['username'];
			}
			if ($row['var3']) {
				$image = '<img src="'.$uhome.'/'.$row['var3'].'" />';
			}
			$encoded_uhome = urlencode($uhome.'/');
			$output = '
			'.$image.'<br />'.t('Musician').': <strong>'.htmlspecialchars($row['var2']).'</strong><br />
			<object type="application/x-shockwave-flash" width="400" height="15" data="'.uhome().'/music_player.swf?repeat_playlist=false&autoplay=true&playlist_url='.$encoded_uhome.'music_play_list.php%3Fmid%3D'.$row['id'].'">
			<param name="movie" 
			value="'.uhome().'/music_player.swf?repeat_playlist=false&autoplay=true&playlist_url='.$encoded_uhome.'music_play_list.php%3Fmid%3D'.$row['id'].'"
			/>
			</object>';
			return $output;
		}
		else {
			return '<p>'.$row['text1'].'</p>';
		}
	}
	
	function list_views($row) {
		return $row['views'].' '.t('Listens');
	}
}