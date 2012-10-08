<?php
/*
ALTER TABLE tags MODIFY COLUMN id INT AUTO_INCREMENT
ALTER TABLE tags MODIFY COLUMN name VARCHAR(64) UNIQUE KEY
ALTER TABLE index_1 CHANGE oldname newname varchar (10) ;
*/
$LAST_UPDATE = 'May-6-2012';
//--- begin timer -----//
$mtime     = explode(" ",microtime());
$starttime = $mtime[1] + $mtime[0];
//---------------------//

header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT"); // always modified
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");   		   // or IE will pull from cache 100% of time (which is really bad) 
header("Cache-Control: no-cache, must-revalidate"); 		   // Must do cache-control headers 
header("Pragma: no-cache");
	
require_once ("../config.php");
global $db_dsn, $db_name, $db_table_users, $db_table_user_state;
	 
$dsn = preg_split("/[\/:@()]+/",$db_dsn);
$db_user = $dsn[1];
$db_pass = $dsn[2];
$host    = $dsn[4];
$db_name = $dsn[6];
//------------------------------------------//
	
mySQL_connect($host,$db_user,$db_pass);
mySQL_select_db($db_name);

$res_tb = 'questions';
$tag_tb = 'tags';
$res_fd = 'id,question,tag_id';
$tb 	= '<table>';

/*----------------------------------------------*/

// Get tags
$query = 'SELECT id,name FROM '.$tag_tb;
$res = MySQL_query($query);
$tag_arr = array();		
while ($row = MySQL_fetch_array($res)) {
	$tag_arr[] = $row;
}

// CONCEPTS
$cquery = 'SELECT '.$res_fd.' FROM '.$res_tb;
$cres   = MySQL_query($cquery);
while ($qrow = MySQL_fetch_array($cres)) {
	$question  = $qrow;
	// Pre-process
	$pattern     = "/<latex[^>]*>(.*?)<\/latex>/im";
    $str         = preg_replace($pattern,'', $qrow[1]);
	//$str         = preg_replace('/<PRE>/','', $str);
	$keywords = explode(' ', $str);

	foreach ($keywords as $k){
		$k = preg_replace('/[(),.?<>="*]/','',$k);
		$k = trim($k);
		
		//echo $k.'<br>';
		//var_dump($tags);
		if ($k){
		foreach ($tag_arr as $t){
		//echo $t[0].' '.$t[1].'<br>';
			 if (!strcasecmp($k,$t[1])) { 
				 //$tb .= '<tr><td>'.$k.'</td><td>'.$concept[0].'</td><td>'.$t[1].'</td><td>'.$t[0].'</td></tr>';
				 //echo 'INSERT IGNORE INTO '.$res_tb.'_'.$tag_tb.' ('.$res_tb.'_id,'.$tag_tb.'_id) VALUES ('.$concept[0].','.$t[0].')<br>';
				 $mysql = 'INSERT IGNORE INTO '.$res_tb.'_'.$tag_tb.' ('.$res_tb.'_id,'.$tag_tb.'_id) VALUES ('.$question[0].','.$t[0].')';
				 echo $mysql.'<br>';
				 mysql_query($mysql);
		     }
		}
	}
	}
	//var_dump($keywords);
	//die('done');
}

/*-----------------------------------------*/
/*
echo '<pre>';print_r($tags);echo '</pre>';die();
*/
$str.= $tb.'</table>';
$str = 'done';
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html lang="en">
<head>
	<meta http-equiv="content-type" content="text/html; charset=utf-8">
	<title>DATABASE</title>
	<?php include '../_include/stylesheet.php'; ?>	
	<script src="tagging/ITS_tagging.js"></script>
	<script src="rating/forms/star_rating.js"></script>
	<script type="text/javascript">
	$(function() {
      $(".ITS_select").change(function() { document.profile.submit(); });
			$("#select_class").buttonset();
  });
	/*-------------------------------------------------------------------------*/
  $(document).ready(function() { 
     //$("#scoreContainer").click(function(){$("#scoreContainerContent").slideToggle("slow");});
  });
  </script>
  <style>
	  #select_class { margin-top: 2em; }
		.ui-widget-header   { background: #aaa; border: 2px solid #666; }
		.ui-dialog-titlebar { background: #aaa; border: 2px solid #666; }
		.ui-dialog-content  { text-align: left; color: #666; padding: 0.5em; }
		.ui-button-text { color: #00a; }
	</style>	
</head>
<body>
<?php
echo $str;
//--- TIMER -------------------------------------------------//
$mtime     = explode(" ",microtime());
$endtime   = $mtime[1] + $mtime[0];
$totaltime = ($endtime - $starttime);
//--- FOOTER ------------------------------------------------//
$ftr = new ITS_footer($status,$LAST_UPDATE,$totaltime);
echo $ftr->main();
//-----------------------------------------------------------//
?>
</body>
</html>
