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

$res_tb = 'questions_m';
//$res_fd = 'id,Limage1,Limage2,Limage3';
$res_fd = 'id,Rimage1,Rimage2,Rimage3,Rimage4';
$tb 	= '<table>';
/*----------------------------------------------*/

$cquery = 'SELECT '.$res_fd.' FROM '.$res_tb.' WHERE Rimage1>0';
$cres   = MySQL_query($cquery);
while ($qrow = MySQL_fetch_array($cres)) {
		//$mysql = 'UPDATE '.$res_tb.' SET Limage1='.$qrow[1].',Limage2='.$qrow[2].',Limage3='.$qrow[3].' WHERE questions_id='.$qrow[0].';';
		$mysql = 'UPDATE '.$res_tb.' SET Rimage1='.$qrow[1].',Rimage2='.$qrow[2].',Rimage3='.$qrow[3].',Rimage4='.$qrow[4].' WHERE questions_id='.$qrow[0].';';		
		echo $mysql.'<br>';
		// mysql_query($mysql);
}
// var_dump($keywords);
// die('done');
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
