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
class blogs extends story{
	public $list_type = 'ul';
	function blogs() {
		global $nav,$ubase;
		set_title(t('Blogs'));
		$nav[] = url('blogs','Blogs');
		//$this->stories_from_cat = t('Lasted blogs in this category');
		$this->top_stories = 1;
		$this->about_the_author = 0;
		$this->stories_from_cat = t('Related posts');
		$this->tags = 1;
		$this->allow_vote = 1;
		$this->story_opts = array('tofavorite'=>1);
		parent::story();
		$this->act_write = t('added a blog post');
		$this->label_entry = t('blog entries');
	}

	function convert_content_before_insert($content) {
		if (!file_exists('js/tiny_mce/jquery.tinymce.js')) {
			return nl2br(
				preg_replace('/<a /i','<a rel="nofollow external" ',$content)
			);
		}
		else {
			return preg_replace('/<a /i','<a rel="nofollow external" ',$content);
		}
	}


	function ajax_form($page_type='') {
		global $client;
		if (!$client) die('login');
		if ($page_type == 'u' || $_REQUEST['page_type'] == 'u') {
			$privacy_form = privacy_form();
		}
		echo '
		<div>
		<strong>'.t('Blog Title').'</strong>: <input type="text" size="55" name="blog_title" /><br />
		<textarea rows="9" cols="55" name="blog_content"></textarea><br />
		'.t('Tags').': <input type="text" size="25" name="tags" /> <span class="sub">('.t('Separated with commas').')</span>
		</div>
		<div style="padding-right:25px;text-align:right">
		'.$privacy_form.'</div>';
		exit;
	}

	function ajax_post() {
		global $client,$page_type;
		if (!$client) die('login');
		if (!$_POST['blog_title']) blogs::ajax_error(t('Please input a Title'));
		$vote_options['rating'] = t('Rating');
		foreach ($vote_options as $key=>$vla) {
			$ratings[$key] = array('score'=>0,'users'=>0);
		}
		$page = story::check_page_access($_POST['page_id']);
		$story = array(
			'cid' => 0,
			'page_id' => $_POST['page_id'],
			'page_type'=>$page['type'],
			'title' => $_POST['blog_title'],
			'content' =>  nl2br(preg_replace('/<a /i','<a rel="nofollow external" ',$_POST['blog_content'])),
			'uid' => $client['id'],
			'created' => time(),
			'app' => 'blogs',
			'var5' => $_POST['privacy'],
			'rating' => serialize($ratings)
			);
		$stags = array();
		$tags = explode(',',$_POST['tags']);
		if (is_array($tags)) {
			foreach ($tags as $tag) {
				if (count($stags) > 5) {
					continue;
				}
				$tag = strtolower(trim($tag));
				if (strlen($tag) > 0 && strlen($tag) < 50) {
					$stags[] = $tag;
				}
			}
		}
		if ($num = count($stags)) {
			$story['tags'] = implode(',',$stags);
		}
		if (sql_insert($story, tb().'stories')) {
			$sid = $story['id'] = mysql_insert_id();
			save_tags($stags,$sid,'blogs');
			// write act
			$attachment = array(
				'cwall_id' => 'blogs'.$sid,
				'uri' => 'blogs/viewstory/'.$sid,
				'name' => $_POST['blog_title']
				);
			$app = array('name'=>'blogs','id'=>$sid);
			$stream_id = stream_publish(t('added a blog'),$attachment,$app,$client['id'],$_POST['page_id']);
			$set_story['id'] = $sid;
			$set_story['stream_id'] = $stream_id;
			sql_update($set_story,tb()."stories");
			echo t('Blog Added!').' <a href="'.url('blogs/viewstory/'.$sid).'"><strong>'.t('View').'</strong></a>';
		}
		else {
			blogs::ajax_error('failed to add blog');
		}
		echo blogs::ajax_form();
		exit;
	}
	function ajax_error($msg) {
		echo '<div style="color:red">'.$msg.'</div>';
		echo blogs::ajax_form();
		exit;
	}


	
}