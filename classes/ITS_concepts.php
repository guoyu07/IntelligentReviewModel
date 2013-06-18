<?php
/*=====================================================================//
ITS_concepts- query DB for concept browser.

Constructor: ITS_concepts(ch)

ex. $query = new ITS_concepts('tableA',2,2,array(1,2,3,4),array(20,30));

Author(s): Khyati Shrivastava | May-10-2012
Last Revision: Greg Krudysz, Nov-27-2012

//=====================================================================*/
/*
if(isset($_REQUEST['tbvalues'])){
$tbvalues = $_REQUEST['tbvalues'];
$obj = new ITS_concepts();
$val = $obj->getRelatedQuestions($tbvalues);
echo $val;
}
if(isset($_REQUEST['moduleName'])){
$moduleName = $_REQUEST['moduleName'];
$tbvalues = $_REQUEST['tbvaluesQ'];
$tbvaluesConcp = $_REQUEST['tbvaluesConcp'];
$obj = new ITS_concepts();
$val = $obj->createModule($moduleName,$tbvalues,$tbvaluesConcp);
echo $val;
}
*/
//echo getcwd() . "\n";
//var_dump($_REQUEST['choice']);

class ITS_concepts
{
    public $db_user;
    public $db_pass;
    public $db_host;
    public $db_name;
    public $debug;
    public $mode;
    
    //=====================================================================//
    function __construct(){
        //=====================================================================//
        //echo 'NOW'; die();
        $this->debug = FALSE; //TRUE;
        
        if ($this->debug) {
            echo '<br>' . get_called_class();
        }
        global $db_dsn, $db_name, $tb_name, $db_table_user_state, $tex_path;
        
        $dsn = preg_split("/[\/:@()]+/", $db_dsn);
        //foreach ($dsn as $value) {echo $value.'<br>';}
        
        $this->db_user = $dsn[1];
        $this->db_pass = $dsn[2];
        $this->db_host = $dsn[4];
        $this->db_name = $dsn[6];
        //   echo "Values: ".$this->db_host.$this->db_user.$this->db_pass;
    }
    //=====================================================================//
    function getConceptNav($concept){
        //=====================================================================//    
    
        $str = '<div class="navConcept">'.$concept.'</div><span class="navConceptInfo"><span class="todo">concept info here</span></span><br><hr>';
        
        return $str;
	}
    //=====================================================================//	
    // Returns all concepts at the highest level
    function getConcepts($letter){
    //=====================================================================//	
        $con = mysql_connect($this->db_host, $this->db_user, $this->db_pass) or die('Could not Connect!');
        mysql_select_db($this->db_name, $con) or die('Could not select DB');
        //$query = "SELECT name FROM SPFindex WHERE name LIKE '" . $letter . "%' ORDER BY name";
        //$query = "SELECT name FROM index_1 WHERE name LIKE '" . $letter . "%' AND chapter_id=3 ORDER BY name";
        //$query = "SELECT name FROM tags WHERE name LIKE '" . $letter . "%' AND synonym=0 ORDER BY name";
        if ($letter=='ALL') { $where = ''; }
        else { $where = ' WHERE t.name LIKE "'. $letter .'%"'; }
        
        $query = 'SELECT t.name, COUNT(qt.tags_id) AS count
  FROM
    tags t
  LEFT JOIN
    questions_tags qt
    ON
       qt.tags_id = t.id
    '. $where .'     
  GROUP BY
     t.id
   ORDER BY
	 count DESC';

        //ALTER TABLE its.tags DROP question_id
        //ALTER TABLE its.tags DROP concept_id
        //ALTER TABLE its.tags ADD COLUMN synonym INT, ADD FOREIGN KEY tags_id(synonym) REFERENCES tags(id) ON DELETE CASCADE;
  
        //die($query);
        $res   = mysql_query($query, $con);  
        if (!$res) {die('Query execution problem in '.get_class($this).': ' . msql_error());}
        //$concepts_result = mysql_fetch_assoc($res);
        $N = 10; // list items per column
        
        $str = '<div id="conceptColumnContainer">';
        for ($x = 0; $x < mysql_num_rows($res); $x++) {
			$mod = $x % $N;
			if ($mod==0) { $str .= '<div class="conceptColumn"><ul class="conceptList">'; }
			//echo $mod.'<br>';
            $row = mysql_fetch_assoc($res);
            if (empty($row['count'])) { $row['count'] = '&ndash;'; }
            $str .= '<li  id="con_' . $row['name'] . '" cid="' . $row['id'] . '" class="selcon">' . $row['name'] . '<span class="conceptCount">'.$row['count'].'</span></li>';
            //$str .= ''.$x.'</div>';
            
            if ($mod==($N-1) || ($x == (mysql_num_rows($res)-1))) { $str .= '</ul></div>'; }
        }
        $str .= '</div>';
        //echo htmlspecialchars($str);
        
        if ($str != '')
            //return "<center><ul class='conceptLIST'>" . $str . "</ul></center>";
            return $str;
        else
            return $str;
    } // End of getConcepts()
    //=====================================================================//
    // returns all questions when given a set of concepts to be matched with tags associated with the questions
    function getRelatedQuestions($tbvalues){
    //=====================================================================//
        $con = mysql_connect($this->db_host, $this->db_user, $this->db_pass) or die('Could not Connect!');
        mysql_select_db($this->db_name, $con) or die('Could not select DB');
        //die($tbvalues);
        $arr_val  = split(',', $tbvalues);
        $str_vals = "'" . $arr_val[0] . "'";
        for ($i = 1; $i < sizeof($arr_val); $i++) {
            $str_vals .= ",'" . $arr_val[$i] . "'";
        }
        $query = "SELECT id,question FROM questions w where w.id in (select questions_id from questions_tags q where q.tags_id in (SELECT tags_id FROM SPFindex i where i.name in (" . $str_vals . ")))";
        //SELECT id,question FROM questions w where w.id IN (select questions_id from questions_tags q where q.tags_id in (SELECT tags_id FROM SPFindex i where i.name IN ('Matlab')));
        
        //die($query);
        $res = mysql_query($query, $con);
        if (!$res) {
            die('Query execution problem in '.get_class($this).': ' . msql_error());
        }
        //$concepts_result = mysql_fetch_assoc($res);
        $str = '<table id="ques" class="PROFILE"><tbody><tr><th style="width:5%;"><input type="checkbox" id="chckHead"/></th><th style="width:15%;">No.</th><th style="width:80%;">Question</th></tr>';
        for ($x = 0; $x < mysql_num_rows($res); $x++) {
            $row = mysql_fetch_assoc($res);
            $str .= "<tr class='PROFILE'><td class='PROFILE'><input type='checkbox' name='chcktbl' class='chcktbl' id='chcktbl' value=" . $row['id'] . ">" . "</td><td class='PROFILE'>" . "<a href='Question.php?qNum=" . $row['id'] . "' target=”_blank” class='ITS_ADMIN'>" . $row['id'] . "</a>" . "</td><td class='PROFILE'>" . $row['question'] . "</td></tr>";
        }
        $str .= '</tbody></table>';
        mysql_free_result($res);
        return $str;
        
    } // End of getRelatedQuestions()  
    /*
     * This function creates entries in the Db for a module
     * If module name already exist, it simply adds question to that module
     * It also adds the tags associated with the module in module_tag table
     */
    //=====================================================================//
    function createModule($moduleName, $tbvalues, $tbvaluesConcp){
    //=====================================================================//
        $returnStr = 'Server returned error initial';
        
        $con = mysql_connect($this->db_host, $this->db_user, $this->db_pass) or die('Could not Connect!');
        mysql_select_db($this->db_name, $con) or die('Could not select DB in '.get_class($this));
        $ques_ids  = split(',', $tbvalues);
        $tag_names = split(',', $tbvaluesConcp);
        $query     = "SELECT mid FROM module WHERE title = '$moduleName'";
        $res       = mysql_query($query, $con);
        $row       = mysql_fetch_assoc($res);
        // Module name does not exist
        if (empty($row)) {
            //die('ji');
            $query = "INSERT INTO module(title) VALUES ('$moduleName')";
            $res   = mysql_query($query, $con);
            if (!$res)
                return $returnStr . '1' . $query;
            $module_id = mysql_insert_id();
        } else {
            //$row = mysql_fetch_assoc($res);
            $module_id = $row['mid'];
        }
        
        // Adding question to the module created.
        $query = "INSERT IGNORE INTO module_question(mid,qid) VALUES ";
        $query .= "($module_id," . $ques_ids[0] . ")";
        for ($i = 1; $i < count($ques_ids); $i++) {
            $query .= ",($module_id," . $ques_ids[$i] . ")";
        }
        $res = mysql_query($query, $con);
        if (!$res)
            return $returnStr . '2' . $query;
        else
            $returnStr = "ok! $moduleName Saved!!";
        
        // Add the relation between module ids and tags selected.
        $str_vals = "'" . $tag_names[0] . "'";
        for ($i = 1; $i < sizeof($tag_names); $i++) {
            $str_vals .= ",'" . $tag_names[$i] . "'";
        }
        
        $query = "SELECT tag_id FROM index_1 WHERE name in (" . $str_vals . ")";
        $res   = mysql_query($query, $con);
        //die($query);
        
        if (!$res) {
            die('here?' . $query);
            die('Query execution problem in '.get_class($this).': ' . msql_error());
        }
        
        for ($x = 0; $x < mysql_num_rows($res); $x++) {
            $row         = mysql_fetch_assoc($res);
            $tag_ids[$x] = $row['tag_id'];
            //die($row['tag_id']);
        }
        $tags_ids_imploded = implode(',', $tag_ids);
        $tag_ids           = explode(',', $tags_ids_imploded);
        $query             = "INSERT IGNORE INTO module_tag(mid,tag_id) VALUES ";
        $query .= "($module_id, " . $tag_ids[0] . ")";
        for ($i = 1; $i < count($tag_ids); $i++) {
            $query .= ",($module_id, " . $tag_ids[$i] . ")";
        }
        //die($query);
        $res = mysql_query($query, $con);
        if (!$res) {
            return $returnStr . $query;
        } else
            $returnStr = "ok! $moduleName Saved!!";
        
        return $returnStr;
    } // End of createModule function
    //=====================================================================//
    //<a href="Question.php?qNum=990" class="ITS_ADMIN">990</a>
    function ConcQuesContainer(){
    //=====================================================================//
        $str = '<div id="ConcQuesContainer"><form id="qform" name="qform"></form></div>';
        return $str;
    }
    //=====================================================================//
    function SelectedConcContainer($mode){
        //=====================================================================//
         $box =  '<div id="resourceContainer"><span>&raquo;&nbsp;Resources</span></div><div id="resourceContainerContent">';    			

        $str = '<div id="SelectedConcContainer"><table id="seldcon" class="conceptTable"></table>';
        if ($mode == 0) // 0 is for Instructor mode
            $str .= '<input type="button" id="submitConcepts" name="submit" value="Submit Concepts"></div>';
        else if ($mode == 1) // 1 is for Student mode
            $str .= '<div id="resourceList" class="ITS_meta"></div><input type="button" id="getQuesForConcepts" name="getQuesForConcepts" class="ITS_submit" value="Get Questions"></div><br><br><div id="resourceList" class="ITS_meta"></div></div>';
        
        $box .= $str . '</div>';
        
        return $box;
    }
    //=====================================================================//
    function conceptListContainer(){
    //=====================================================================//
        $str = '<div id="conceptListContainer">'.$this->getConcepts('S').'</div><div id="errorConceptContainer"></div>';
        return $str;
    }
    //=====================================================================//
    function showLetters(){
    //=====================================================================//
        $con = mysql_connect($this->db_host, $this->db_user, $this->db_pass) or die('Could not Connect!');
        mysql_select_db($this->db_name, $con) or die('Could not select DB');
        $query = 'SELECT DISTINCT LEFT(name,1) FROM tags';
        $res   = mysql_query($query, $con);    
        
		$str = '<ul class="nav"><li><a href="#" name="ITS_alph_index" value="ALL">ALL</a></li>';          
        for ($x = 1; $x <= mysql_num_rows($res); $x++) {
            $row = mysql_fetch_row($res);
            $val = strtoupper($row[0]);
            
            if (!fmod($x,15)) { $str .= '<br><hr class="concept">'; }
            if ($val == 'S') { $idx_id = 'id="current"'; }
            else 		  	 { $idx_id = ''; }		
            $str .= '<li><a href="#" name="ITS_alph_index" ' . $idx_id . ' value="' . $val . '">' . $val . '</a></li>';
        }       
        $str .= '</ul>';
        return $str;
    }
    /*	
    //=====================================================================// 
    function getQuestionsStudent(){
    //=====================================================================//
    $status = 'active';
    $id = 1;
    $index_hide = 4;
    $role = 'student';
    $mode = 'concQuestion';
    $screen = new ITS_screen($id, $role, $status,$index_hide+1);
    //return 'yey';   
    $screen->screen = 5;
    $screen->term_current = 'Spring_2012';
    return $screen->main($mode);     
    }*/
    //=====================================================================//
    function moduleList($choice){ // switch case ->? 0 for 1st page, 1 for drop down
        //=====================================================================//
        $con = mysql_connect($this->db_host, $this->db_user, $this->db_pass) or die('Could not Connect!>');
        mysql_select_db($this->db_name, $con) or die('Could not select DB');
        $query = "SELECT mid,title FROM module";
        $res   = mysql_query($query, $con);
        if (!$res) {
            die('Query execution problem in '.get_class($this).': ' . msql_error());
        }
        $str = '';
        switch ($choice) {
            case 0:
                for ($x = 0; $x < mysql_num_rows($res); $x++) {
                    $row = mysql_fetch_assoc($res);
                    $str .= "<li id='" . $row['title'] . "'><div  align='left' id='" . $row['title'] . "' class='modules'>" . $row['title'] . "</div></li>";
                }
                if ($str != '')
                    $str = "<ul>" . $str . "</ul>";
                break;  
            case 1:
                for ($x = 0; $x < mysql_num_rows($res); $x++) {
                    $row = mysql_fetch_assoc($res);
                    $str .= "<option value='" . $row['title'] . "' >" . $row['title'] . "</option>";
                }
                //if($str!='') 
                $str = "<div id='moduleListDialogDiv'><select id='moduleListDD' class='moduleListDD'><option value='0'>Create a new module..</option>" . $str . "</select></div>";
                break;
            default:
                $str = 'server error';
        }
        mysql_free_result($res);
        return $str;
    }
    //=====================================================================//
    function getModuleQuestion($modulesQuestion){
        //=====================================================================//
        $con = mysql_connect($this->db_host, $this->db_user, $this->db_pass) or die('Could not Connect!');
        mysql_select_db($this->db_name, $con) or die('Could not select DB');
        $query = "SELECT qid FROM module_question where mid in (select mid from module where title='$modulesQuestion')";
        $res   = mysql_query($query, $con);
        if (!$res) {
            die('Query execution problem in '.get_class($this).': ' . msql_error());
        }
        
        //	return $query ;
        if ($row = mysql_fetch_assoc($res))
            $str = $row['qid'];
        else
            $str = '';
        for ($x = 1; $x < mysql_num_rows($res); $x++) {
            $row = mysql_fetch_assoc($res);
            $str .= ',' . $row['qid'];
        }
        
        // Fetch Tags for the Modules
        $query = "SELECT name FROM SPFindex WHERE tags_id IN (SELECT tag_id FROM module_tag WHERE mid = (SELECT mid FROM module WHERE title='$modulesQuestion'))";
        //return $query;
        $res   = mysql_query($query, $con);
        if (!$res) {
            die('Query execution problem in '.get_class($this).': ' . msql_error());
        }
        $count = 0;
        
        for ($x = 0; $x < mysql_num_rows($res); $x++) {
            $row                     = mysql_fetch_assoc($res);
            $associatedTagsArray[$x] = $row['name'];
        }
        if (count($associatedTagsArray) > 0)
            $associatedTags = implode(',', $associatedTagsArray);
        else
            $associatedTags = "No Tags Associated";
        $query = "select id,question from questions w where w.id in ($str)";
        //return $query.$str;
        $res   = mysql_query($query, $con);
        if (!$res) {
            return "No questions for this module";
            //die('Query execution problem in '.get_class($this).': ' . msql_error());
        }
        //$concepts_result = mysql_fetch_assoc($res);
        $str2 = '<table id="ques" class="PROFILE"><tbody><tr><th style="width:5%;"><input type="checkbox" id="chckHead"/>' . '</th><th style="width:80%">Question' //</th><th style="width:15%;">Tags Associated'
            . '</th></tr>';
        
        for ($x = 0; $x < mysql_num_rows($res); $x++) {
            $row = mysql_fetch_assoc($res);
             /* to fetch tags associated with each question in the module
             $query = "SELECT tag_id FROM webct WHERE id=$row['id']";
             $res = mysql_query($query,$con);
             if (!$res) {
             die('Query execution problem in '.get_class($this).': ' . msql_error());
             }
             $row_tags = mysql_fetch_assoc($res);
             $associatedTagsArray = $row_tags['tag_id'];
             if(count($associatedTagsArray)>0)
             $associatedTags = implode(',',$associatedTagsArray);
             else
             $associatedTags = "No Tags Associated";
             */
            
            $str2 .= "<tr class='PROFILE'><td class='PROFILE'>" . "<input type='checkbox' name='chcktbl' class='chcktbl' id='chcktbl' value=" . $row['id'] . "><br><br>" . "<a href='Question.php?qNum=" . $row['id'] . "' target=”_blank” class='ITS_ADMIN'>" . $row['id'] . "</a>" . "</td><td class='PROFILE'>" . $row['question'] . "</td>"
            //."<td class='PROFILE'>$associatedTags</td>"
                . "</tr>";
        }
        $str2 .= '</tbody></table>';
        //return $str;
        return $str2;
    }
    //=====================================================================//
    // delete from module_question where mid=(select mid from module where title=$ModuleName) AND qid IN (string of tbvalues!)
    function deleteModuleQuestion($deleteQuestion, $ModuleName){
        //=====================================================================//
        $con = mysql_connect($this->db_host, $this->db_user, $this->db_pass) or die('Could not Connect!');
        mysql_select_db($this->db_name, $con) or die('Could not select DB');
        $query = "delete from module_question where mid=(select mid from module where title='$ModuleName') AND qid IN ($deleteQuestion)";
        
        //die($query);
        $res = mysql_query($query, $con);
        if (!$res) {
            die('Query execution problem in '.get_class($this).': ' . msql_error());
        }
        return "Successful deletion";
    }
    //=====================================================================//
    // function to return array of tag names when tag ids are provided 
    /*
    function getTags($tag_ids){
    //=====================================================================//
    $DBHOST = 'localhost';
    $DBUSER = 'root';
    $DBPASS = 'root';
    $DBNAME = 'its';
    $con = mysql_connect($DBHOST, $DBUSER, $DBPASS) or die('Could not Connect');
    mysql_select_db($DBNAME, $con) or die('Could not select DB');
    for($x=0;$x<count($tag_ids);$x++){
    $query = 'SELECT name from index_1 WHERE id='.$tag_ids[$x];
    $res = mysql_query($query,$con);
    }
    }
    */
    //=====================================================================//
    public function updateScore(){
    //=====================================================================//
        $str = '<span class="todo">concept scoreboard here</span>';
        return $str;
    }	
    
} // eof class
?>
