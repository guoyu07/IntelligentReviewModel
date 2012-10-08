<?php
/*
ALTER TABLE tags MODIFY COLUMN id INT AUTO_INCREMENT
ALTER TABLE tags MODIFY COLUMN name VARCHAR(64) UNIQUE KEY
ALTER TABLE index_1 CHANGE oldname newname varchar (10) ;             */

$LAST_UPDATE = 'May-6-2012';
//--- begin timer ---//
$mtime     = explode(" ",microtime());
$starttime = $mtime[1] + $mtime[0];
//------------------//

header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT"); // always modified
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");   		   // or IE will pull from cache 100% of time (which is really bad) 
header("Cache-Control: no-cache, must-revalidate"); 		   // Must do cache-control headers 
header("Pragma: no-cache");
	
require_once ("../config.php");
global $db_dsn, $db_name, $db_table_users, $db_table_user_state;

/*	 
$dsn = preg_split("/[\/:@()]+/",$db_dsn);
$db_user = $dsn[1];
$db_pass = $dsn[2];
$host    = $dsn[4];
$db_name = $dsn[6];*/

$host 	 = 'localhost';
$db_user = 'root';
$db_pass = 'csip';
$db_name = 'its';
//------------------------------------------//

//=== STOPWORDS ============================//	
$myfile = 'stopwords2.txt';
$lines = file($myfile);   
$stopwords = array();
for($i=count($lines);$i>0;$i--){
	$str = trim($lines[$i]);
	if ($str){
		/*$str = preg_replace('/([A-Z])/','',$str); // Strip off the starting hyphens
		$str = preg_replace('/\s[\s]+/','-',$str);     // Strip off multiple spaces
		$str = preg_replace('/[\s\W]+/','-',$str);     // Strip off spaces and non-alpha-numeric
		$str = preg_replace('/^[\-]+/','',$str); 	   // Strip off the starting hyphens
		$str = preg_replace('/[\-]+$/','',$str); 	   // Strip off the ending hyphens 
		* */
		$stopwords[] = $str;
	}
}

//echo '<pre>'; var_dump($stopwords); '</pre>';die();
	
//=== STOPWORDS ============================//	
MySQL_connect($host,$db_user,$db_pass);
MySQL_select_db($db_name);

/*------------ SPFindex --------------------*/
//SQL: ALTER TABLE concept3 ADD tag_id VARCHAR(128), ADD FOREIGN KEY (tag_id) REFERENCES tags (id);
$res_tb = 'SPFindex';
$tag_tb = 'tags';
$res_fd = 'id,name';

// Get tags
$query = 'SELECT id,name FROM '.$tag_tb;
echo $cquery;
$res = MySQL_query($query);
$tag_arr = array();		
while ($row = MySQL_fetch_array($res)) {
	$tag_arr[] = $row;
}

// CONCEPTS
$cquery = 'SELECT '.$res_fd.' FROM '.$res_tb;
echo $cquery;
$cres = MySQL_query($cquery);
//$tags = array();		
$tb = '<table>';

/*-----------------------------------------------/
STEP 1:
/ ----------------------------------------------*/
while ($row = MySQL_fetch_array($cres)) {
	
	$concept  = $row;
	$keywords = explode(' ', $concept[1]);

	foreach ($keywords as $k){
		$k = preg_replace('/[()]/','',$k);
		$k = trim($k);
		//echo $k.'<br>';
		//var_dump($tags);
		if ($k){
		$tag_found = false;
		foreach ($tag_arr as $t){
		//echo $t[0].' '.$t[1].'<br>';
			 if (!strcasecmp($k,$t[1])) { 
				 $tag_found = true;
		     }
		}
		if (!$tag_found) { 
			// TAG not found: STOPWORD? ELSE ADD as tag
			$stopword = false;
			foreach ($stopwords as $sw){
				if (!strcmp($sw,$k)) {
					//var_dump($sw);var_dump($keywords[1]);
				    echo '<span style="color:blue">'.$sw.' = '.$k.'</span><br>';
				    $stopword = true;
				}
			}
			if (!$stopword) {
				echo 'new tag: <span style="color:red">'.$k.'</span><br>';
				$query = 'INSERT IGNORE INTO '.$tag_tb.' (name) VALUES ("'.$k.'")';
				echo $query.'<br>';				
				mysql_query($query);
		    }
		}
	}
	}
	//var_dump($keywords); die('done');
}
echo '<hr>';
/*-----------------------------------------------/
STEP 2: LINKER
/ ----------------------------------------------*/
// Get tags
$query = 'SELECT id,name FROM '.$tag_tb;
$res = MySQL_query($query);
$tag_arr = array();		
while ($row = MySQL_fetch_array($res)) {
	$tag_arr[] = $row;
}

// CONCEPTS
$cres = MySQL_query($cquery);
while ($crow = MySQL_fetch_array($cres)) {
	$concept  = $crow;
	$keywords = explode(' ', $concept[1]);

	foreach ($keywords as $k){
		$k = preg_replace('/[()]/','',$k);
		$k = trim($k);
		//echo $k.'<br>';
		//var_dump($tags);
		if ($k){
		foreach ($tag_arr as $t){
		//echo $t[0].' '.$t[1].'<br>';
			 if (!strcasecmp($k,$t[1])) { 
				 //$tb .= '<tr><td>'.$k.'</td><td>'.$concept[0].'</td><td>'.$t[1].'</td><td>'.$t[0].'</td></tr>';
				 //echo 'INSERT IGNORE INTO '.$res_tb.'_'.$tag_tb.' ('.$res_tb.'_id,'.$tag_tb.'_id) VALUES ('.$concept[0].','.$t[0].')<br>';
				 $mysql = 'INSERT IGNORE INTO '.$res_tb.'_'.$tag_tb.' ('.$res_tb.'_id,'.$tag_tb.'_id) VALUES ('.$concept[0].','.$t[0].')';
				 echo $mysql.'<br>';
				 mysql_query($mysql);
		     }
		}
	}
	}
	//var_dump($keywords); //die('done');
}
/*-----------------------------------------*/
/*  echo '<pre>';print_r($tags);echo '</pre>';die();  */
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
