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

class report{
	
	function report() {
		global $client, $menuon;
		clear_report();
		if (!$client['id']) {
			redirect('member/login/1');
		}
	}
	
	function index() {
		global $db, $nav, $client;
		set_title('Report a page to Admin');
		clear_as();
		$nav[] = t('Report');
		$report_url = getenv(HTTP_REFERER);
		if (!eregi(uhome(),$report_url) || !$report_url) die('unable to find the url');
		c('<h1>'.t('Report spam, advertising, and problematic.').'</h1>
		<form method="post" action="'.url('report/post').'" >
					<p>
					URL:<br />
					'.$report_url.'
					</p>
					<p>
					'.label(t('Message')).'
					<textarea name="message" style="width:500px" rows="3"></textarea>
					</p>
					<p>
					<input type="hidden" name="report_url" value="'.h($report_url).'" />
					<input class="button" type="submit" value="'.t('Send to Admin').'" />
					</p>
					</form>');
	}

	function post() {
		global $db, $client, $config;
		sql_query("insert into ".tb()."reports (uid,message,url,created) values('{$client['id']}','{$_POST['message']}','{$_POST['report_url']}',".time().")");
		set_title(t('Message sent, thank you!'));
		c(t('Message sent, thank you!'));
	}

	function delete($mid) {
		global $db, $client;
		// ids
		if (is_array($_REQUEST['ids'])) {
			foreach ($_REQUEST['ids'] as $id) {
				sql_query("delete from `".tb()."messages` where id='{$id}' and to_id='{$client['id']}' ");
			}
		}
		else {
			sql_query("delete from `".tb()."messages` where id='{$mid}' and to_id='{$client['id']}' ");
		}
		redirect(url('message/inbox'),1);
	}
}