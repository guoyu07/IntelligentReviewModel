<?php
//=============================================================//
$ITS_version = '210d';
$LAST_UPDATE = 'Dec-1-2012';
//=============================================================//
require_once("config.php"); // #1 include
require_once(INCLUDE_DIR . "include.php");

include_once("classes/ITS_timer.php");
require_once("classes/ITS_survey.php");
require_once("classes/ITS_menu.php");
require_once("classes/ITS_message.php");
require_once("classes/ITS_query2.php");
require_once("classes/ITS_footer.php");
require_once("classes/ITS_tag.php");
require_once("classes/ITS_concepts.php");
require_once("classes/ITS_resource.php");
/* -- SCORING module ----------------------------------- */
require_once("classes/ITS_book.php");
require_once("plugins/tagging/ITS_tagInterface.php");

//$timer = new ITS_timer();
//echo $timer;
session_start();
abort_if_unauthenticated();

$id     = $_SESSION['user']->id();
$status = $_SESSION['user']->status();
$view   = TRUE; // VIEW: TRUE | FALSE => "Question" tab closed

//----- SCHEDULE -----//
$open  = array(array(8,20),array(8,27),array(9,3),array(9,24),array(10,22),array(10,29),array(11,19),array(11,19));
$close = array(array(9,24),array(10,1),array(11,5),array(11,19),array(11,26),array(12,3),array(12,10),array(12,10));

$term_arr = explode('_',$term);
$tset = mktime(4, 0, 0, $open[0][0], $open[0][1], $term_arr[1]);
$index_max = 0;
foreach ($open as $Odate){
	$open_time = mktime(4, 0, 0, $Odate[0], $Odate[1], $term_arr[1]);
	if ($open_time < time())
		$index_max++;
}
$index_hide = 0;
$schedule = array();
foreach ($close as $Cdate){
    $close_time = mktime(23, 59, 59, $Cdate[0], $Cdate[1], $term_arr[1]);
    array_push($schedule,date("M - j", $close_time).' @ midnight');  
    //echo '<p>'.date("M-j", $close_time).'</p>';
	if ($close_time < time())
		$index_hide++;
}
//echo '<p>'.$index_max.'--'.$index_hide.'</p>';
//##########################################//
if (isset($_POST['role'])) {
    $role = $_POST['role'];
} else {
    switch ($status) {
        case 'admin':
            $role = 'admin';
            break;
        default:
            $role = 'student';
            break;
    }
}

$screen    = new ITS_screen2($id, $role, $status, $index_hide + 1, $tset);
//$menu    = new ITS_menu(); //echo $menu->main();
//$message = new ITS_message($screen->lab_number, $screen->lab_active);
$_SESSION['screen'] = $screen;
//------------------------------------------//
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html lang="en">
    <head>
        <META HTTP-EQUIV="Expires" 	    CONTENT="Tue, 01 Jan 1980 1:00:00 GMT">
        <META HTTP-EQUIV="Pragma"       CONTENT="no-cache">
        <meta HTTP-EQUIV="content-type" CONTENT="text/html; charset=utf-8">
        <title>ITS</title>
<?php
include(INCLUDE_DIR.'stylesheet.php');
include('js/ITS_jquery.php');
include(INCLUDE_DIR.'include_fancybox.php');
?>	
<script type="text/javascript" src="js/ITS_concepts.js"></script>
	<script type="text/javascript" src="js/jquery.tipsy/src/javascripts/jquery.tipsy.js"></script>
	<link rel="stylesheet" type="text/css" href="js/jquery.tipsy/src/stylesheets/tipsy.css" />
	<link rel="stylesheet" type="text/css" href="css/ITS_resource.css" />
<script type="text/javascript">
$(document).ready(function() {
$(".fancybox").fancybox({
			  type: 'inline',
		  closeClick: true,
		  padding: 5,
          helpers: {
	overlay : {
		closeClick : true,
		speedOut   : 300,
		showEarly  : false,
		css        : { 'background' : 'rgba(155, 155, 155, 0.5)'}
	},			  
              title : {
                  type : 'inside'
              }
          }
      });    
$(".ITS_schedule").fancybox({
		  type: 'ajax',
		  closeClick: true,
		  padding: 5,
          helpers: {
	overlay : {
		closeClick : true,
		speedOut   : 300,
		showEarly  : false,
		css        : { 'background' : 'rgba(155, 155, 155, 0.5)'}
	},			  
              title : {
                  type : 'inside'
              }
          }
      });
$( "input[name=selectResource]" ).live('click', function(event) {
	        var field = $(this).val();
	        var concept = $(this).attr("concept");
	        //alert(field+'='+concept);
            $.get("ajax/ITS_resource.php", {
                ajax_args: "test",
                ajax_data: concept+'~'+field.toLowerCase()
            }, function(data) {
                $('#resourceList').html(data);
            });
});     
$( "input[name=resourceSelect]" ).live('click', function(event) {
	        var field 	= $(this).attr("field");
	        var rid  	= $(this).attr("rid");
	        var concept = $(this).attr("concept");
	        //alert(field+'~'+rid+'~'+concept);
	        alert(field.toLowerCase()+'~'+rid);
			//$('#ITS_resource_'+field.toLowerCase()).html(rid);
            $.get("ajax/ITS_resource.php", {
                ajax_args: "select",
                ajax_data: field.toLowerCase()+'~'+rid
            }, function(data) {
                $('#ITS_resource_'+field.toLowerCase()+'_'+concept).html(data);
                $('#ITS_resource_'+field.toLowerCase()+'_'+concept).attr("rid",rid);
            });
});
});
</script>
<script type='text/javascript'>
  $(function() {
    $('#chapter_index1').tipsy({opacity: 0.5});
  });
</script>
    </head>
    <body>
        <div id="pageContainer">
            <!-- MENU -------------------------------------------------->
            <div id="menuContainer">
                <div id="logout" class="logout" uid="<?php echo $id;?>"><a href="logout.php">Logout</a></div>
                <!--
	  <div class="icon" id="Minst_icon">I</div>
               <p class="ITS_instruction"><img src="images/matching_example1.png" style="position:relative;max-width:100%"></p>
		<div class="icon" id="Tag_icon">Tag</div>
		-->
                <div class="icon" id="instructionIcon" onClick="ITS_MSG(1)"><tt>?</tt></div>
                <div class="icon" id="messageIcon"     onClick="ITS_MSG(1)">&para;</div>
            </div>
            <!-- myScore ---------->
            <?php //die($status);
          
switch ($status) {
    case 'BMED6787':
        $chUser   = 1;
        $MyScores = '';
        break;
    default:
        /* -- */
        $MyScores = '<div id="scoreContainer"><span>&raquo;&nbsp;My Scores</span></div><div id="scoreContainerContent">';
        
        switch ($role) {
            case 'admin':
            case 'instructor':
                $chUser = 1;
                $chMax  = 11;
                break;
            default:
                $chUser = 1;
                $chMax  = $index_max;
        }
        $chArr             = range(1, $chMax);
        $score             = new ITS_score($id, $role, $chArr, $term, $tset); //,$ch);
        $_SESSION['score'] = $score;
        $str               = $score->renderChapterScores(); //($chMax)         
           
        $MyScores .= $str . '</div>';
}
echo $MyScores;
?>
            <!-- NAVIGATION ----------------------------------------------->
            <div id="modeContainer">
                    <?php
/* -------------------- */
switch ($status) {
    /* ----------------- */
    case 'BMED6787':
        /* ----------------- */
        $chUser       = 1;
        $mode         = 'survey';
        $screen->mode = $mode;
        $chList       = '<ul id="chList">';
        $chList .= '<li><a href="#" class="chapter_index" id="Survey02" name="chapter" value="1">Survey</a></li>';
        break;
    /* ----------------- */
    default:
        /* ----------------- */
        $mode   = 'question'; // index | practice | question
        //$chList = '<span id="chText">MODULE</span><ul id="chList">';
        
        ///*     
        $chList = '<div class="QuestionMode"><select id="QuestionMode">'
        .'<option value="MODULE">Modules</option>'
        .'<option value="CONCEPT" id="showConcepts">Concepts</option></select>'
        .'<input type="button" style="display:none" name="changeConcept" id="changeConcept" value="change Concept"/></div>'
        .'<div class="module_index" id="ModuleListingDiv"></div><div id="chapterListingDiv"><ul id="chList">';
        //*/
        //++$chList = '<div id="modeSelContainer"><ul id="nav1" class="ITS_nav"><li><a href="faq/ITS_schedule_tb.html" id="current" data-fancybox-type="iframe" class="ITS_schedule" name="selectMode" title="ECE 2026 &ndash; Fall 2012<br>ITS Schedule | <a href=faq target=_blank>ITS - FAQ</a>">ASSIGNMENT</a></li></ul></div>';
        //
        $chList = '<div id="modeSelContainer"><ul id="nav1" class="ITS_nav"><li><a href="#" id="current" name="selectMode">ASSIGNMENT</a><ul id="nav2"><li><a href="#" name="selectMode">CONCEPT</a></li></ul></li></ul></div>';
        //++
        $chList .= '<div id="modeContentContainer"><div id="chContainer"><ul id="chList">';
        //$chList = '<span id="chText">MODULE</span><ul id="chList" class="ITS_nav">';
        
        //$chList .= '<li><a href="#" class="chapter_index" name="chapter" value="0">Introduction</a></li>';
        switch ($role) {
            case 'admin':
            case 'instructor':
                for ($i = 1; $i <= $chMax; $i++) {
                    //echo $i.' -- '.($index_hide+1).'<br>';
                    if ($i == ($index_hide+1)) {
                        $idx_id = 'id="current"';
                    } else {
                        $idx_id = '';
                    }
                    $chList .= '<li><a href="#" class="chapter_index" name="chapter" ' . $idx_id . ' value="' . $i . '" title="'.$schedule[$i-1].'">' . $i . '</a></li>';
                }
                $view = TRUE;
                $r    = TRUE; // role
                break;
            default:
                //var_dump($chArr); die();
                for ($i = 0; $i < count($chArr); $i++) {
                    if ($i == ($index_hide + 1)) {
                        $idx_id = 'id="current"'; // PRACTICE: = ''
                    } else {
                        $idx_id = '';
                    }
                    $chList .= '<li><a href="#" class="chapter_index" name="chapter" ' . $idx_id . ' value="' . $chArr[$i] . '" title="'.$schedule[$i].'">' . $chArr[$i] . '</a></li>';
                }
                $r = FALSE;
                break;
        }
        //$chList .= '<li><a href="#" class="survey_index" id="current" name="chapter" value="1">Survey</a></li>';
        $chList .= '</ul></div><div id="coContainer"></div></div>';
        /* -------------------- */
}
//echo $chList;die('====');
//chapter_index'.$i.'
//id="chapter_index'.$chArr[$i].'"
//$strConcept = '<div id="showConcepts">Concepts</div>';
//$alph
echo $chList;
/* FANCYBOX TEST
<a class="fancybox" href="#inline1" title="ITS &ndash; FAQ">Inline</a>
<a class="fancybox" href="http://its.vip.gatech.edu/ITS_FILES/SPFIRST/SPFirstPages/SP1_Page_015.png">Ajax</a>
<div id="inline1" style="width:400px;display: none;">
<h3>Etiam quis mi eu elit</h3>
<p><?php include 'faq/index.html';?>
</p>
</div>
*/
?>
   </div><div id="QuesConcept"></div>
   <input type="hidden" id="index_hide" value="<?php
echo $index_hide;
?>">
                </div>
                <?php
//-- TEST -------------------------------------------------->
//$s = new ITS_score($id);
//$str = $s->renderLabScores();
//echo $str;
?>
            </div>
            <div id="page">
                <!-- CONTENT ----------------------------------------------->
                <?php
//echo $screen->main();
//--- resources ---//
//$arr = array(2,"",NULL);
//echo array_sum($arr);die('done');
//--------$screen->chapter_number = $chUser;
$meta = 'image';
/*
$x = new ITS_book('dspfirst',$ch,$meta,$tex_path);
$o = $x->main();
echo $o.'<p>';
<li id="Practice" name="header" choice_mode ="<?php echo $mode; ?>" view="<?php echo intval($view); ?>" r="<?php echo intval($r); ?>" ch="<?php echo ($index_hide+1); ?>" style="margin-left: 50px;"><a href="#">Practice</a></li>
*/
//----------------//
?>
<div id="navContainer">
<ul id="navListQC">                     
<li id="Question" name="header" view="<?php echo intval($view);
?>" r="<?php
echo intval($r);
?>" ch="<?php
echo ($index_hide + 1);
?>"><a href="#" id="current">Questions</a></li>
                        <li id="Review"   name="header" view="<?php
echo intval($view);
?>" r="<?php
echo intval($r);
?>" ch="<?php
echo ($index_hide + 1);
?>" style="margin-left: 50px;"><a href="#">Review</a></li>                      
                    </ul>
                </div>
                <!-- end div#navContainer -->
                <?php
//echo '<ul><li>ITS Modules 1 - 4 have closed.</li><li>Your answers for Modules 1-4 are available for review.</li></ul>';

$screen->screen       = 4;
$screen->term_current = $term;
echo $screen->main($mode);
/*
echo '<a class="group" href="excel_icon.png"><img src="excel_icon.png" alt=""/></a>'
.'<a id="inline" href="#data">This shows content of element who has id="data"</a>'
.'<div style="display:none"><div name="data">Some title</div></div>';
*/
//echo $screen->reviewMode(1,0);
?>              
</div>
            <!-- FOOTER -->
          <?php
include(INCLUDE_DIR.'footer.php');
?>
            <!-- end FOOTER -->
        </div>
        <!-- end div#page -->
    </body>
</html>
