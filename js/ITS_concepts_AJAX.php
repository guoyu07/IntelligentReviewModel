<?php
die('ITS_concepts_AJAX.php');
	
require_once("classes/ITS_concepts.php");

if(isset($_REQUEST['letter'])){
	$letter = $_REQUEST['letter'];
	$obj = new ITS_concepts();	
	$retStr = $obj->getConcepts($letter);
	echo $retStr;
}
if(isset($_REQUEST['choice'])){
	$choice = $_REQUEST['choice'];
	$obj = new ITS_concepts();
	switch ($choice){
	/*	case 'ShowConceptByLetter':
				$letter = $_REQUEST['letter'];
				$retStr = 'yes';// $obj->getConcepts($letter);
				break;
				*/
		case 'submitConcepts':
				$tbvalues = $_REQUEST['tbvalues'];
				$retStr  = $obj->getRelatedQuestions($tbvalues);
				break;
		case 'createModule':
				$moduleName = $_REQUEST['moduleName'];
				$tbvalues = $_REQUEST['tbvaluesQ'];
				$tbvaluesConcp = $_REQUEST['tbvaluesConcp'];
				$retStr = $obj->createModule($moduleName,$tbvalues,$tbvaluesConcp);
				break;
		case 'getConcepts':
				$retStr = $obj->showLetters();
				$retStr .='<div id="content" style="border:3px solid;height: 300px; ">' 
				.$obj->conceptContainer().$obj->SelectedConcContainer(1).$obj->ConcQuesContainer().'</div>';
				break;
		case 'getQuestions':
				$retStr = $obj->getQuestionsStudent();
				echo $retStr;
				break;
		case 'getModuleQuestion':			
				$modulesQuestion = $_REQUEST['modulesQuestion'];
				$retStr = $obj->getModuleQuestion($modulesQuestion);
				break;
		case 'deleteModuleQuestion':
				$deleteQuestion = $_REQUEST['deleteQuestion'];
				$ModuleName = $_REQUEST['ModuleName'];
				$retStr = $obj->deleteModuleQuestion($deleteQuestion,$ModuleName);
				break;
		case 'getModuleDDList':
				$retStr = $obj->moduleList(1);
				break;
		default:	
	}
	echo $retStr;
}
?>
