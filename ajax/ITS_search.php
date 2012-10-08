<?php
/*  ITS_search_AJAX - script for AJAX search
Author(s): Greg Krudysz
Date: Jun-4-2012
---------------------------------------------------------------------*/
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT"); // always modified
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); // or IE will pull from cache 100% of time (which is really bad) 
header("Cache-Control: no-cache, must-revalidate"); // Must do cache-control headers 
header("Pragma: no-cache");

require_once("../config.php");
require_once("../FILES/PEAR/MDB2.php");
require_once("../classes/ITS_table.php");
require_once("../classes/ITS_configure.php");
require_once("../classes/ITS_question.php");
require_once("../classes/ITS_tag.php");
require_once("../classes/ITS_search.php");

$style = '<head>' . '<link type="text/css" href="jquery-ui-1.8.23.custom/css/ui-lightness/jquery-ui-1.8.23.custom.css" rel="stylesheet" />' . '<link type="text/css" href="css/ITS_question.css" rel="stylesheet" />' . '<link type="text/css" href="css/ITS_tag.css" rel="stylesheet" />' . '</head>';
session_start();
//===================================================================//
global $db_dsn, $db_name, $tb_name, $db_table_user_state;

//-- Get AJAX arguments
$args   = preg_split('[,]', $_GET['ajax_args']);
$action = $args[0];

//-- Get AJAX user data
//$Data = rawurldecode($_GET['ajax_data']);
$Data = $_GET['ajax_data'];
// preprocess before SQL
$Data = str_replace("'", "&#39;", $Data);
//$Data = nl2br($Data);

/*
echo 'action = '.$action.'<p>';
echo 'data   = '.$Data.'<p>';    die();
*/
$mdb2 =& MDB2::connect($db_dsn);
if (PEAR::isError($mdb2)) {
    throw new Question_Control_Exception($mdb2->getMessage());
}

//-----------------------------------------------//
switch ($action) {
    //-------------------------------------------//
    case 'search2':
        //-------------------------------------------//
        $data  = preg_split('[~]', $Data);
        $query = 'SELECT * FROM stats_' . $id; //die($query);
        $res =& $mdb2->query($query);
        if (PEAR::isError($res)) {
            throw new Question_Control_Exception($res->getMessage());
        }
        $list = $usr->add_user($data[0], $data[1], $data[2], $data[3]);
        $str  = $list;
        break;
    //-------------------------------------------//
    case 'search':
        //-------------------------------------------//		  
        $data          = preg_split('[~]', $Data);
        $tname         = 'tags';
        $t             = new ITS_tag($tname);
        $Ques_tag_arr  = $t->getByResource($data[1], $data[2]);
        $Keyw_tag_arr  = $t->getByKeyword($data[0], $Ques_tag_arr);
        $Keyw_tag_list = $t->render2($Keyw_tag_arr, $data[2], $data[1], $tname);
        $str           = $Keyw_tag_list;
        break;
    //-------------------------------------------//
    case 'submit':
        //-------------------------------------------//
        $data = preg_split('[~]', $Data);
        $t    = new ITS_tag('tags');
        
        $Ques_tag_arr  = $t->getByResource($data[1], $data[2]);
        //var_dump($Ques_tag_arr);		  die('stop');
        $Keyw_tag_list = '';
        $Keyw_tag_arr  = $t->query($data[0], $Ques_tag_arr);
        if (empty($Keyw_tag_arr[0])) {
            $Keyw_tag_list = $t->add($data[0], $data[2], $data[1]);
        }
        
        //die($list);
        $str = $Keyw_tag_list;
        break;
        //-------------------------------------------//
}
//-----------------------------------------------//
$mdb2->disconnect();
echo $style . $str;
?>
