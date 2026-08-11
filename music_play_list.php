<?php

header("Cache-Control: no-cache");
// db
require_once './my/config.php';
require_once './includes/libs/common.inc.php';
require_once './includes/libs/db.inc.php';
$conn=sql_connect($db_info['host'], $db_info['user'], $db_info['pass'], $db_info['dbname']);
mysql_query("SET NAMES UTF8");
$uid = $_GET['uid'];
$mid = $_GET['mid'];
$mids = $_GET['mids'];
if (!$uid && !$mid && !$mids) {
	die('lb');
}

if ($uid) {
	if (!is_numeric($uid)) {
		die('lb');
	}
	$q = "SELECT * FROM `".tb()."stories` WHERE  uid='$uid' and app='music'";
	$res =sql_query($q, $conn);
	WHILE ($row = sql_fetch_array($res)) {
		if ($row['var1']) {
			$row['uri'] = $uhome.'/'.$row['var1'];
			$row['title'] = htmlspecialchars($row['title']);
			$tracks[] = $row;
		}
	}
	if (!count($tracks)) {
		die('wb');
	}
}
elseif ($mid) {
	if (!is_numeric($mid)) {
		die('lb');
	}
	$q = "SELECT s.*,u.username FROM `".tb()."stories` AS s left join `".tb()."accounts` as u on u.id=s.uid WHERE s.id='$mid'";
	$res = sql_query($q);
	$row = sql_fetch_array($res);
	if (!$row['id'])
		die('lm~');
	$row['uri'] = $uhome.'/'.$row['var1'];
	$row['title'] = htmlspecialchars($row['title']);
	if (!$row['var2']) {
		$row['var2'] = $row['username'];
	}
	$row['var2'] = htmlspecialchars($row['var2']);
	$tracks[] = $row;
}
elseif ($mids) {
	$mids = explode('_',$mids);
	foreach ($mids as $mid) {
		if (is_numeric($mid)) {
			$nmids[] = $mid;
		}
	}
	if (!is_array($nmids)) die('bmids');
	$mids = implode(',',$nmids);
	$q = "SELECT s.*,u.username FROM `".tb()."stories` AS s left join `".tb()."accounts` as u on u.id=s.uid WHERE s.id in ($mids)";
	$res = sql_query($q);
	while ($row = sql_fetch_array($res)) {
		$row['uri'] = $uhome.'/'.$row['var1'];
		$row['title'] = htmlspecialchars($row['title']);
		if (!$row['var2']) {
			$row['var2'] = $row['username'];
		}
		$row['var2'] = htmlspecialchars($row['var2']);
		$tracks[] = $row;
	}
}

echo '<?xml version="1.0" encoding="UTF-8"?>';
	?>

<playlist version="0" xmlns = "http://xspf.org/ns/0/">
  <trackList>

	<?php
	$i = 1;
	foreach ($tracks as $val) {
		if (eregi(".mp3$",$val['uri'])) {
			echo "<track>";
			echo "<location>{$val['uri']}</location>
    <annotation>:{$val['title']}</annotation>
		";
	echo "</track>";
		$i++;
		}
	}
	?>

  </trackList>
</playlist>
