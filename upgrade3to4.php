<?php
header("Cache-Control: no-cache");

// db
require_once './my/config.php';
require_once './includes/libs/common.inc.php';
require_once './includes/libs/db.inc.php';
$conn=sql_connect($db_info['host'], $db_info['user'], $db_info['pass'], $db_info['dbname']);

if( mysql_num_rows( mysql_query("SHOW TABLES LIKE '".tb()."pages'")))
{
 echo 'Upgrading finished';
 exit;
}


sql_query("CREATE TABLE IF NOT EXISTS `".tb()."pages` (
  `id` int(11) NOT NULL auto_increment,
  `uri` varchar(30) NOT NULL,
  `uid` int(11) NOT NULL,
  `views` int(11) NOT NULL,
  `logo` varchar(100) NOT NULL,
  `name` varchar(100) NOT NULL,
  `style_ids` text NOT NULL,
  `custom_css` text NOT NULL,
  `background` varchar(100) NOT NULL,
  `type` varchar(25) NOT NULL,
  `description` text NOT NULL,
  `users` int(11) NOT NULL,
  `updated` int(11) NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `uid` (`uid`),
  KEY `uri` (`uri`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8");

sql_query("
CREATE TABLE IF NOT EXISTS `".tb()."page_users` (
  `pid` int(11) NOT NULL,
  `uid` int(11) NOT NULL,
  KEY `pid` (`pid`,`uid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8
");

sql_query("
CREATE TABLE IF NOT EXISTS `jcow_banned` (
  `id` int(11) NOT NULL auto_increment,
  `username` varchar(100) NOT NULL,
  `ip1` varchar(3) NOT NULL,
  `ip2` varchar(3) NOT NULL,
  `ip3` varchar(3) NOT NULL,
  `ip4` varchar(3) NOT NULL,
  `created` int(11) NOT NULL,
  `expired` int(11) NOT NULL,
  `operator` varchar(100) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8
");

sql_query("

CREATE TABLE IF NOT EXISTS `jcow_user_crafts` (
  `uid` int(11) NOT NULL,
  `hash` varchar(5) NOT NULL,
  `created` int(11) NOT NULL,
  KEY `uid` (`uid`,`created`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8
");

$members = 0;
$res = sql_query("select u.username,p.* from ".tb()."profiles as p left join ".tb()."accounts as u on u.id=p.id order by p.id");
while ($profile = sql_fetch_array($res)) {
	$members++;
	sql_query("insert into ".tb()."pages(id,uri,uid,views,custom_css,type) values('{$profile['id']}','{$profile['username']}','{$profile['id']}','{$profile['views']}','{$profile['custom_css']}','u')");
}
echo $members.' profiles updated<br />';

sql_query("ALTER TABLE `".tb()."stories` ADD `page_id` int(11) NOT NULL ");
sql_query("ALTER TABLE `".tb()."stories` ADD `page_type` VARCHAR( 25 ) NOT NULL ");
sql_query("ALTER TABLE `".tb()."stories` ADD INDEX ( `page_id` ) ");
sql_query("UPDATE ".tb()."stories set page_id=uid,page_type='u'");
sql_query("UPDATE ".tb()."streams set wall_id=uid");
sql_query("INSERT INTO ".tb()."gvars (gkey,gvalue) values('jcow_version','4.0')");
echo 'Upgrading finished';

