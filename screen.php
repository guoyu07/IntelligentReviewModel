<?php
//=============================================================//
$ITS_version = '216g';
$LAST_UPDATE = 'Apr-29-2013';
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

// SCHEDULE
// echo '<p>'.$index_max.'--'.$index_hide.'</p>';die();
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

$screen             = new ITS_screen2($id, $role, $status, $index_hide + 1, $tset);
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
include(INCLUDE_DIR . 'stylesheet.php');
include('js/ITS_jquery.php');
include(INCLUDE_DIR . 'include_fancybox.php');
?>
<script type="text/javascript" src="js/ITS_concepts.js"></script>
	<script type="text/javascript" src="js/jquery.tipsy/src/javascripts/jquery.tipsy.js"></script>
	<link rel="stylesheet" type="text/css" href="js/jquery.tipsy/src/stylesheets/tipsy.css" />
	<link rel="stylesheet" type="text/css" href="css/ITS_resource.css" />
	<link rel="stylesheet" type="text/css" href="css/ITS_mode.css" />	
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
                <div id="logout" class="logout" uid="<?php
echo $id;
?>"><a href="logout.php">Logout</a></div>
                <!--
	  <div class="icon" id="Minst_icon">I</div>
               <p class="ITS_instruction"><img src="images/matching_example1.png" style="position:relative;max-width:100%"></p>
		<div class="icon" id="Tag_icon">Tag</div>
		-->
                <div class="icon" id="instructionIcon" onClick="ITS_MSG(1)"><tt>?</tt></div>
                <div class="icon" id="messageIcon"     onClick="ITS_MSG(1)">&para;</div>            
            </div>
<!-- MODE ------------->
<div id="modeContainer">
<input id="ASSIGNMENTS" class="toggle" name="toggle" value="false" type="radio" checked r="<?php $role;?>">
<label for="ASSIGNMENTS" class="btn">ASSIGNMENTS</label>
<input id="CONCEPTS" class="toggle" name="toggle" value="true" type="radio">
<label for="CONCEPTS" class="btn">CONCEPTS</label>
</div>          
<!-- myScore ---------->
<?php
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
//var_dump($chArr);die();
$score             = new ITS_score($id, $role, $chArr, $term, $tset); //,$ch);
$_SESSION['score'] = $score;
$str               = $score->renderChapterScores(); //($chMax)         

$MyScores .= $str . '</div>';

echo $MyScores;

//-- TEST -------------------------------------------------->
//$s = new ITS_score($id);
//$str = $s->renderLabScores();
//echo $str;
?>
<!-- NAVIGATION ----------------------------------------------->
<div id="navXXContainer" style="border:1px solid red">
<?php
/* -------------------- */

$view     = TRUE; // VIEW: TRUE | FALSE => "Question" tab closed
$schedule = $screen->getSchedule();
$chList   = $screen->renderAssignment($status, $view, $role, $chArr, $chMax, $index_max, $index_hide, $schedule);

$modeDiv = '<div id="modeContentContainer">' . $chList . '</div>';
//$modeDiv = '<div id="navModeContainer"><ul id="navlist"><li id="active" style="color:#999">ASSIGNMENTS</li></div><div id="modeContentContainer">'.$chList.'</div>';
//.'<li id="CON" style="color:#999"><a name="selectMode">PRACTICE</a></li></ul></div><div style="border:1px solid #fff" id="modeContentContainer">'.$chList.'</div>';
/*$modeDiv = '<div id="navModeContainer"><ul id="navlist"><li id="active"><a href="#" id="current">ASSIGNMENTS</a></li>'
.'<li><a href="#" name="selectMode">PRACTICE</a></li></ul></div><div id="modeContentContainer">'.$chList.'</div>';
*/
echo $modeDiv;
?>  
<input type="hidden" id="index_hide" value="<?php
echo $index_hide;
?>">  
            </div>
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
?>
<div id="navContainer">
<ul id="navListQC">                    
<li id="Question" name="header" view="<?php
echo intval($view);
?>" r="<?php
echo intval($r);
?>" ch="<?php
echo ($index_max);
?>"><a href="#" id="current">Questions</a></li>
                        <li id="Review"   name="header" view="<?php
echo intval($view);
?>" r="<?php
echo intval($r);
?>" ch="<?php
echo ($index_max);
?>" style="margin-left: 50px;"><a href="#">Review</a></li>                      
                    </ul><div id="navListPC"></div>
                </div>
                <!-- end div#navContainer -->
                <?php
//echo '<ul><li>ITS Modules 1 - 4 have closed.</li><li>Your answers for Modules 1-4 are available for review.</li></ul>';

$screen->screen       = 4;
$screen->term_current = $term;
echo $screen->main($mode);
//echo $screen->reviewMode(1,0);
?>              
</div>
            <!-- FOOTER -->
          <?php
include(INCLUDE_DIR . 'footer.php');
?>
            <!-- end FOOTER -->
        </div>
        <!-- end div#page -->
    </body>
</html>
