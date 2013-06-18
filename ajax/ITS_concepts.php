<?php
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT"); // always modified
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); 			   // or IE will pull from cache 100% of time (which is really bad) 
header("Cache-Control: no-cache, must-revalidate"); 		   // Must do cache-control headers 
header("Pragma: no-cache");

require_once("../config.php");
require_once("../classes/ITS_table.php");
require_once("../classes/ITS_configure.php");
require_once("../classes/ITS_question.php");
require_once("../classes/ITS_statistics.php");
require_once("../classes/ITS_concepts.php");
require_once("../classes/ITS_resource.php");

if (isset($_REQUEST['letter'])) {
    $letter = $_REQUEST['letter'];
    $obj    = new ITS_concepts();
    $retStr = $obj->getConcepts($letter);
    echo $retStr;
}
if (isset($_REQUEST['choice'])) {
    $choice = $_REQUEST['choice'];
    $obj    = new ITS_concepts();
    switch ($choice) {
        /*	case 'ShowConceptByLetter':
        $letter = $_REQUEST['letter'];
        $retStr = 'yes';// $obj->getConcepts($letter);
        break;*/
        case 'submitConcepts':
            $tbvalues = $_REQUEST['tbvalues'];
            $retStr   = $obj->getRelatedQuestions($tbvalues);
            break;
        case 'createModule':
            $moduleName    = $_REQUEST['moduleName'];
            $tbvalues      = $_REQUEST['tbvaluesQ'];
            $tbvaluesConcp = $_REQUEST['tbvaluesConcp'];
            $retStr        = $obj->createModule($moduleName, $tbvalues, $tbvaluesConcp);
            break;
         case 'showLetters':
            $retStr = $obj->showLetters();
            break;  
        case 'getConceptNav': 
			$concept = $_REQUEST['ajax_data'];
			$retStr = $obj->getConceptNav($concept);      
			break;     
        case 'getConcepts':
            //die($obj->ConcQuesContainer());
            //$retStr .= '<div id="conceptContainer">'.$obj->conceptListContainer() . $obj->SelectedConcContainer(1) . $obj->ConcQuesContainer() . '</div>';
            //$retStr .= '<div id="conceptContainer">'.$obj->conceptListContainer().'</div>';// $obj->SelectedConcContainer(1) . $obj->ConcQuesContainer().'</div>';
            $retStr = '<div id="navConcept"></div><div id="conceptContainer">'.$obj->conceptListContainer() . '</div>'; //$obj->SelectedConcContainer(1) . $obj->ConcQuesContainer() . '</div>';
            break;
        case 'getQuestions':
            $retStr = $obj->getQuestionsStudent();
            break;
        case 'getModuleQuestion':
            $modulesQuestion = $_REQUEST['modulesQuestion'];
            $retStr          = $obj->getModuleQuestion($modulesQuestion);
            break;
        case 'deleteModuleQuestion':
            $deleteQuestion = $_REQUEST['deleteQuestion'];
            $ModuleName     = $_REQUEST['ModuleName'];
            $retStr         = $obj->deleteModuleQuestion($deleteQuestion, $ModuleName);
            break;
        case 'getModuleDDList':
            $retStr = $obj->moduleList(1);
            break;
        case 'updateScore':
            $retStr = $obj->updateScore();
            break;            
        default:
    }
    echo $retStr;
}
if (isset($_REQUEST['resource'])) {
    $letter = $_REQUEST['resource'];
    $data   = preg_split('[~]',$letter);
    $obj    = new ITS_resource($data[0]);
    $retStr = $obj->renderContainer();
    echo $retStr;
}
?>
