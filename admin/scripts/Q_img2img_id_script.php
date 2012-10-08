<?php
//Q_img2img_id_script

// UPDATE questions SET image="" WHERE image="0";
// UPDATE questions SET image="" WHERE id=1042;
// ALTER TABLE questions ENGINE=InnoDB;
// show innoDB status;
// ALTER TABLE questions ADD CONSTRAINT questions_ibfk_1 FOREIGN KEY questions_id REFERENCES questions (id)
/*
UPDATE questions SET image = "/on-Line-HW/trig-1-evals.gif" WHERE id=138;
UPDATE questions SET image = "/on-Line-HW/z-evals-1.gif"    WHERE id=471;
UPDATE questions SET image = "/on-Line-HW/trig-2-evals.gif" WHERE id=489;
UPDATE questions SET image = "/on-Line-HW/trig-2-evals.gif" WHERE id=596;
UPDATE questions SET image = "/on-Line-HW/trig-1-evals.gif" WHERE id=611;
UPDATE questions SET image = "/on-Line-HW/ampling-OK-mov.gif" WHERE id=847;
UPDATE questions SET image = "/on-Line-HW/folding-mov.gif"    WHERE id=890;
UPDATE questions SET image = "/on-Line-HW/expon-evals.gif" 	  WHERE id=905;
UPDATE questions SET image = "/on-Line-HW/z-evals-3.gif" 	  WHERE id=957;
UPDATE questions SET image = "/on-Line-HW/z-evals-2.gif" 	  WHERE id=1047;
UPDATE questions SET image = "/on-Line-HW/expon-evals.gif" 	  WHERE id=1061;
*/

$LAST_UPDATE = 'Jun-30-2012';

//--- begin timer -----//
$mtime     = explode(" ",microtime());
$starttime = $mtime[1] + $mtime[0];
//---------------------//

header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT"); // always modified
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");   		   // or IE will pull from cache 100% of time (which is really bad) 
header("Cache-Control: no-cache, must-revalidate"); 		   // Must do cache-control headers 
header("Pragma: no-cache");
	
require_once ("../../config.php");
global $db_dsn, $db_name, $db_table_users, $db_table_user_state;
	 
$dsn = preg_split("/[\/:@()]+/",$db_dsn);
$db_user = $dsn[1];
$db_pass = $dsn[2];
$host    = $dsn[4];
$db_name = $dsn[6];
//------------------------------------------//	
MySQL_connect($host,$db_user,$db_pass);
MySQL_select_db($db_name);

$res_tb = 'questions';
$tb 	= '<table>';
/*----------------------------------------------*/
// Get tags
$query = 'SELECT id,image FROM questions WHERE image!="" AND images_id IS NULL';
$res = MySQL_query($query);
$tag_arr = array();		
while ($row = MySQL_fetch_array($res)) {
	// $q_arr[] = $row;
	$path_parts = pathinfo($row['image']);
	$dir =  $path_parts['dirname'];
	$file = $path_parts['basename'];
	$dir = preg_replace('/^[\/]/','',$dir); // Strip off the starting / in dir 

	$mysql = 'SELECT id,name,dir FROM images WHERE name="'.trim($file).'" AND dir="'.trim($dir).'"';
	echo $mysql.'<br>';
	//$mysql = 'SELECT id,name,dir FROM images WHERE name="FourBands.png " AND dir="PreLabs"';
	$resImg = MySQL_query($mysql);
	while ($imgarr = MySQL_fetch_array($resImg)) {
		$sql = 'UPDATE questions SET images_id='.$imgarr[0].' WHERE id='.$row[0];
		echo $sql.'<br>';
		mysql_query($sql);
	}
}

//SELECT id,name,dir FROM images WHERE name="FourBands.png " AND dir="PreLabs"
//SELECT id,name,dir FROM images WHERE name="FourBands.png " AND dir="PreLabs"
/*
// IMAGE
$iquery = 'SELECT id,name,dir FROM images';
$ires   = MySQL_query($iquery);
while ($irow = MySQL_fetch_array($ires)) {
	$question  = $qrow;
	$dir =  $path_parts['dirname'];
	$file = $path_parts['basename'];
    $str         = preg_replace($pattern,'', $qrow[1]);
	//$str         = preg_replace('/<PRE>/','', $str);
	$keywords = explode(' ', $str);

	foreach ($keywords as $k){
		$k = preg_replace('/[(),.?<>="*]/','',$k);
		$k = trim($k);
		
		//echo $k.'<br>';
		//var_dump($tags);
		if ($k){
		foreach ($q_arr as $q){
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
