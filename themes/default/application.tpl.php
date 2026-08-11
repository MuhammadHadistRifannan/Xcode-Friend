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



/* 
content in this container may not exceed 760px;
*/

function display_application($data) {

	if (is_array($data['blocks'])) {
		if ($data['is_cover']) {
			$output .= '
				<style>
				#appmain {
					width: 540px;
					float: left;
				}
				#appside {
					width: 210px;
					float: right;
				}
				</style>';
		}
		else {
			$output .= '
				<style>
				#appmain {
					width: 540px;
					float: right;
				}
				#appside {
					width: 210px;
					float: left;
				}
				</style>';
		}
	}
	$output .= '
		<div id="appmain">';

	if (is_array($data['sections'])) {
		foreach ($data['sections'] as $section) {
			$output .= '<div class="block">';
			if (strlen($section['title'])) {
				$output .= '<div class="block_title">'.$section['title'].'</div>';
			}
			$output .= '<div class="block_content">'.$section['content'].'</div>
			</div>';
		}
	}
	$output .= '</div>'; // end of app_main

	if (is_array($data['blocks'])) {
		$output .= '<div id="appside">';
		foreach($data['blocks'] as $block) {
			if (is_array($block)) {
				$output .= '
				<div class="block">';
				if ($block['title']) {
					$output .= '<div class="block_title">'.$block['title'].'</div>';
				}
				$output .= '<div class="block_content">
				'.$block['content'].
				'</div>
				</div>
				';
			}
		}
		$output .= get_gvar('theme_block_sidebar').'
		</div>';// end of app_sidebar
	}
	$output .= $data['app_footer'];
	return $output;

}