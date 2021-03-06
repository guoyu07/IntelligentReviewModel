<?php
//=============================================================//
$ITS_version = '225d';
$LAST_UPDATE = 'Sep-29-2013';
//=============================================================//
require_once("config.php"); // #1 include
require_once(INCLUDE_DIR . "include.php");
include_once("classes/ITS_timer.php");
require_once("classes/ITS_survey.php");
require_once("classes/ITS_menu.php");
require_once("classes/ITS_message.php");
require_once("classes/ITS_resource.php");
require_once("classes/ITS_feedback.php");

/* -- SCORING module ----------------------------------- */
require_once("classes/ITS_book.php");
require_once("plugins/tagging/ITS_tagInterface.php");

//$timer = new ITS_timer();
//echo $timer;
//include('login.php');
/*
if (!isset($_SESSION['auth'])) {
include('login.php');
exit;
}*/
session_start();
abort_if_unauthenticated();

$id     = $_SESSION['user']->id();
$status = $_SESSION['user']->status();
//------------------------------------------------------//

if (isset($_GET['role'])) {
    $role = $_GET['role'];
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
$updatet = time();
$screen  = new ITS_screen($id, $term, $role, $tset, $updatet);

$S      = $screen->getSchedule($term);
$A      = $screen->getAssignment($S);

$chList = $A[0];
$chArr  = $A[1];
$r      = $A[2];
$view   = $A[3];

//$menu    = new ITS_menu(); //echo $menu->main();
//$message = new ITS_message($screen->lab_number, $screen->lab_active);
$concepts = new ITS_concepts($id, $term);

$_SESSION['screen']   = $screen;
$_SESSION['concepts'] = $concepts;
//var_dump($_SESSION['concepts']);
//------------------------------------------//
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html lang="en">
	
    <head>
        <META HTTP-EQUIV="Expires"         CONTENT="Tue, 01 Jan 1980 1:00:00 GMT">
        <META HTTP-EQUIV="Pragma"          CONTENT="no-cache">
        <meta HTTP-EQUIV="content-type"    CONTENT="text/html; charset=utf-8">
        <meta http-equiv="X-UA-Compatible" CONTENT="IE=edge" />
        <title>ITS</title>
        <meta name="description" content="Signal Processing Tutoring System">
        
	<!-- Toggle Switch -->
	<link rel="stylesheet" href="css/toggle-switch/toggle-switch.css">
	
	<!-- Prism Syntax Highlighter -->
    <link rel="stylesheet" href="css/toggle-switch/lib/prism/prism.css">
    <script src="css/toggle-switch/lib/prism/prism.js"></script>        
<?php
include_once(INCLUDE_DIR . 'stylesheet.php');
include_once('js/ITS_jquery.php');
include_once(INCLUDE_DIR . 'include_fancybox.php');
include_once(INCLUDE_DIR . 'include_mathjax.php');
?>
	<script type="text/javascript" src="js/ITS_concepts.js"></script>
	<script type="text/javascript" src="js/ITS_reinforcement.js"></script>
	<script type="text/javascript" src="js/jquery.tipsy/src/javascripts/jquery.tipsy.js"></script>
    <link rel="stylesheet" type="text/css" href="js/jquery.tipsy/src/stylesheets/tipsy.css" />
    <link rel="stylesheet" type="text/css" href="css/ITS_resource.css" />
    <link rel="stylesheet" type="text/css" href="css/ITS_mode.css" />        
        <link rel="stylesheet" type="text/css" href="css/aaa.css" /> 
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
$("input[name=selectResource]" ).live('click', function(event) {
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
            var field     = $(this).attr("field");
            var rid      = $(this).attr("rid");
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
		<input type="hidden" id="term" term="<?php
echo $term;
?>"> 
        <noscript>
<div style="color:#CC0000; text-align:center">
<b>Warning: <a href="http://quid.gatech.edu/">ITS</a>
requires JavaScript to process the mathematics on this page.<br />
If your browser supports JavaScript, be sure it is enabled.</b>
</div>
<hr>
</noscript>
        <div id="pageContainer">
            <!-- MENU -------------------------------------------------->
            <div id="menuContainer">
                <div id="logout" class="logout" uid="<?php
echo $id;
?>" course="<?php
echo $term;
?>"><a href="logout.php">Logout</a></div>
                <div class="icon" id="instructionIcon" onClick="ITS_MSG(1)"><tt>?</tt></div>
                <div class="icon" id="messageIcon"     onClick="ITS_MSG(1)">&para;</div>
            </div>
<!-- MODE ------------->
<!-- 
<a href="#" id="CONCEPTS" class="wrapper">
<a href="#" id="ASSIGNMENTS" class="wrapper">
-->
<div id="modeContainer">        
				<div class="switch candy quid">
					<input id="CONCEPTS" name="mode" type="radio" class="mode">
					<label for="CONCEPTS" r="<?php
echo intval($r);
?>">CONCEPTS</label>

					<input id="ASSIGNMENTS" name="mode" type="radio" class="mode" checked>	
					<label for="ASSIGNMENTS" r="<?php
echo intval($r);
?>">ASSIGNMENTS</label>
					
					<span class="slide-button"></span>
				</div>
				</div> 
<!-- myScore ---------->
<?php
//var_dump($chArr);die();
$score             = new ITS_score($id, $term, $tset);
$_SESSION['score'] = $score;
$score_str         = $score->renderChapterScores($chArr);       
$MyScores = '<div id="scoreContainer"><span>&raquo;&nbsp;My Scores</span></div><div id="scoreContainerContent">'.$score_str . '</div>';
echo $MyScores;
?>
      
<div id="modeContentContainer">
<?php
echo $chList;
?>
</div>
<!-- NAVIGATION ----------------------------------------------->
<?php
//echo $screen->main();
//--- resources ---//
//$arr = array(2,"",NULL);
//echo array_sum($arr);die('done');
$meta = 'image';
/*
$x = new ITS_book('dspfirst',$ch,$meta,$tex_path);
$o = $x->main();
echo $o.'<p>';
*/
?>
<div id="navContainer">
<?php
$ch = $S[0];
echo $screen->getTab($ch, $r, $view);
?>
</div>
<!-- end div#navContainer -->
<!-- CONTENT -------------------------------------------------->
<?php
$mode           = 'question';
$screen->screen = 4;
echo $screen->main($mode);
?>              
</div>
<?php include(INCLUDE_DIR . 'footer.php'); ?>
           <!-- FOOTER end -->
        </div>
        <!-- end div#page -->
    </body>
</html>
