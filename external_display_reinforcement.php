<?php
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
$count = $_POST["count"];
if(!($connection1 = @ mysql_connect(localhost, root, csip)))
						echo "connect failed.";
	if(!(mysql_select_db("its", $connection1)))
						echo "database selection failed";					
	if(!($tempgame = @ mysql_query("Select q_ID from reinforcement_team_Fall_2012_1 where user_ID =1341", $connection1)))
						echo "connection failed";					
	if(!($tempgame2 = @ mysql_query("Select q_ID from reinforcement_team_Fall_2012_1 where user_ID =-1", $connection1)))
						echo "connection failed";	
	$updatet = time();
	$screen  = new ITS_screen("1341", $term, 'student', $tset, $updatet);
	$question = new ITS_question(“1341”, “its”, “questions”);
	echo "<br>";
	//echo $tempgame;
	echo "<br>";
	// while loop : since one has to get all the userIDs for a particular user.
	
		 $element_count = 0;
		while($temp1 = mysql_fetch_array($tempgame)){
			$final[$element_count] = $temp1[0];
			$element_count++;
		}
		
		while($temp2 = mysql_fetch_array($tempgame2)){
			$final[$element_count] = $temp2[0];
			$element_count++;
		}
		$display = count($final);
		//echo "$display";

		$loopcount =0;
		for($loopcount = 0; $loopcount < $display; $loopcount++){
				if(!($tempgame1 = @ mysql_query("Select question from questions where id =$final[$loopcount]", $connection1)))
			echo "connection failed";
		//echo $final[$loopcount];
		if(!($tempgame10 = @ mysql_query("Select title from questions where id =$final[$loopcount]", $connection1)))
			echo "connection failed";



//added

		

		$jerry = mysql_fetch_array($tempgame1);
		$pujun = mysql_fetch_array($tempgame10);
		if($count > 5){
			$count = 5;
			echo "<p><b>There are no more questions to be display.<br> Please come back later.</b></p>";
			return;
			//continue;
		}
		if($count > 0){
			$count--;
			continue;
		}		
		//echo $screen->screen;
		$mode           = 'question';
		$screen->screen = 4;
		//echo $screen->main($mode);
		$name = 'ITS_' . $final[loopcount];		
		$updatedQuestions = $screen->getQuestion($final[$loopcount], '');
		//$updatedQuestions = $questions->render_ANSWERS($final[$loopcount], 1);
		$gamer = 5;
		//$updatedQuestions ="\n Concept : ".$pujun[0]. " " .$updatedQuestions;
		echo "<p><b>Concept Name: $pujun[0]</b></p>";		
		echo " $updatedQuestions";
		
		//ob_clean();
	//	echo $pujun[0];


//echo 6;
//echo "$pujun[0]";		
//echo  $jerry[0];
		echo "<br>";
		echo "<br>";				
		echo "<br>";
		//ob_clean();
		break;

		
		}	
	
?>
