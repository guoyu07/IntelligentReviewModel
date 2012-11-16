<?php
/*=====================================================================//
ITS_resource - search box class.
ajax/ITS_resource.php

Constructor: ITS_resource( ... )	

ex. $r = new ITS_resource( ... );

Author(s): Greg Krudysz
Last Update: Nov-21-2012	 
//=====================================================================*/
class ITS_resource
{
    public function __construct($tag)
    {
        global $db_dsn, $tb_name;
        
        $this->db_dsn  = $db_dsn;
        $this->tb_name = $tb_name;
        $this->concept = $tag;     
    }
    //=====================================================================//
    public function renderBox($rtb, $rid)
    {
        //=====================================================================//
        //if (empty($rating)) { $rating = 0; }
        //$box = '<hr class="ITS_search"><input id="ITS_search_box" type="text" name="keyword" rtb="'.$rtb.'" rid="'.$rid.'">'.
        //       '<div class="ITS_search"></div></p>';		
        $box = '<input id="ITS_search_box" type="text" name="keyword" rtb="' . $rtb . '" rid="' . $rid . '">';
        
        return $box;
    }
    //=====================================================================//
    public function renderResources()
    {
        //=====================================================================//	
        $box = '<hr class="ITS"><div class="ITS_search"></div>';
        
        return $box;
    }
    //=====================================================================//
    public function renderContainer()
    {
        //=====================================================================//	
        $tharr = array(
            'Text',
            'Equation',
            'Image',
            'Example'
        );
        $th    = ''; //'<th></th>';
        $td    = ''; //'<td>' . $this->concept . '</td>';
        for ($n = 0; $n < count($tharr); $n++) {
            $td .= '<th><input type="button" name="selectResource" concept="'.$this->concept.'" value="' . $tharr[$n] . '" class="ITS_res"></th>';
            $th .= '<td id="ITS_resource_'.strtolower($tharr[$n]).'" rid=""></td>';
        }
        $tb .= '<table class="ITS_resource"><tr>' . $th . '</tr><tr>' . $td . '</tr></table>';
        
        return $tb;
    }
    //=====================================================================//
    public function getResource($field)
    {
        //=====================================================================//	
        //$queryQid = 'SELECT dspfirst_ids FROM dspfirst_map WHERE tag_id='.$this->concept;        
        //$query    = 'SELECT * FROM dspfirst WHERE id IN ('.$queryQid.')';
        //die($this->concept);
        switch ($field) {
            //+++++++++++++++++++++++++++++++++++++++++++//
            case 'text':
                $query = 'SELECT id,content FROM dSPFirst WHERE content REGEXP "'.$this->concept.'" AND meta="paragraph"';
                break;
            case 'equation':
                //SELECT content FROM dSPFirst WHERE  name REGEXP "sampling" AND meta="equation";
                $query = 'SELECT id,code FROM concepts WHERE  name REGEXP "'.$this->concept.'"';
                break;
            case 'image':
                //$query = 'SELECT content FROM dSPFirst WHERE name REGEXP "sampling" AND meta="image"';
                $query = 'SELECT id,dir,name FROM images WHERE name REGEXP "'.$this->concept.'"';
                break;
            case 'example':
                $query = 'SELECT id,statement,solutions,term,year FROM SPF WHERE title REGEXP "'.$this->concept.'" OR keywords REGEXP "'.$this->concept.'" LIMIT 5';
                break;
        }
        
        //echo $query.'<br>';
        $id_arr = array();
        $li_arr = array();
        $res    = mysql_query($query);
        if (!$res) {
            die('Query execution problem in ITS_resource: ' . msql_error());
        }
        
        if (!empty($res)) {
            switch ($field) {
                //---//
                case 'equation':
                    //---//
                    $path = '../cgi-bin/mathtex.cgi?\large ';
                    for ($i = 0; $i < mysql_num_rows($res); $i++) {
						$id   = mysql_result($res, $i);
                        $code = mysql_result($res, $i);
                        if (!empty($code)) {
							array_push($id_arr,$id);
                            array_push($li_arr, '<img class="ITS_LaTeX" src="' . $path . $code . ' "/>');
                        }
                    }
                    break;
                //---//
                case 'image':
                    //---//
                    $path = 'ITS_FILES/';
                    for ($i = 0; $i < mysql_num_rows($res); $i++) {
						$id   = mysql_result($res, $i);
                        $code = trim(mysql_result($res, $i, 'dir')) . '/' . trim(mysql_result($res, $i, 'name'));
                        if (!empty($code)) {
                            //echo $path.$code.'<br>';
                            $img = '<a id="single_image" href="' . $path . $code . '" class="ITS_question_img" title="' . $path . $code . '"><img src="' . $path . $code . '" class="ITS_question_img ITS_resource_img" alt="' . $path . $code . '"></a>';
                            array_push($id_arr,$id);
                            array_push($li_arr,$img);
                        }
                    }
                    break;
                //---//
                case 'example':
                    //---//
                    $path  = 'ITS_FILES/';
                    $path1 = 'ITS_FILES/SPFIRST/PNGs/';
                    $path2 = 'ITS_FILES/SPFIRST/solutions/';
                    //+++++++++
                    $idx   = 1;
                    while ($row = MySQL_fetch_array($res)) {
                        //echo '<pre>';var_dump($row);echo '</pre>';die();
                        $id   	   = $row['id'];
                        $fname     = $row['statement'];
                        $solutions = $row['solutions'];
                        $term      = $row['term'];
                        switch ($term) {
                            case 'Spring':
                            case 'Summer':
                                $t = $term[0] . $term[1];
                                break;
                            case 'Fall':
                            case 'Winter':
                                $t = $term[0];
                                break;
                        }
                        $year    = $row['year'];
                        $fname   = preg_replace('/.pdf/', '.png', $fname);
                        $f       = $path1 . strtolower($t) . '_' . $year[2] . $year[3] . '/' . $fname;
                        //echo $path;die();
                        $sol_arr = explode(',', $solutions);
                        
                        $sol_list = '';
                        foreach ($sol_arr as $s) {
                            if (empty($s)) {
                                $sol_list .= '';
                            } else {
                                $pathS = $path2 . strtolower($t) . '_' . $year[2] . $year[3] . '/' . $s;
                                $sol_list .= '<a id="single_image" href="' . $pathS . '" class="ITS_question_img" title="' . $pathS . '"><img src="' . $pathS . '" class="ITS_question_img ITS_resource_img" alt="' . $s . '"></a>';
                            }
                        }
                        //echo '<pre>';var_dump($sol_list);echo '</pre>';die();
                        //$sol  = '<div class="file"><a href="'.$solutions.'" target="_blank"><img alt="'.$solutions.'" src="'.$solutions.'" /></a></div>';
                        
                        $sol  = '<div class="file">' . $sol_list . '</div>';
                        $file = '<a id="single_image" href="' . $f . '" class="ITS_question_img" title="' . $f . '"><img src="' . $f . '" class="ITS_question_img ITS_resource_img" alt="' . $fname . '"></a>';
                        //echo "<tr><td>{$row['id']}</td><td>{$row['title']}</td><td>{$row['score']}</td></tr>";   
                        array_push($id_arr,$id);
                        array_push($li_arr,$file.'<br>'.$sol_list);
                    } //while
                    break;
                default:
                    for ($i = 0; $i < mysql_num_rows($res); $i++) {
						array_push($id_arr,mysql_result($res, $i,0));
                        array_push($li_arr, mysql_result($res, $i,1));
                    }
                    //var_dump($li_arr);die();
                    //+++//
            }
            switch ($field) {
                //---//
                case 'text':
                    //---//
                    $tList = '<table class="CPROFILE">';
                    for ($i = 0; $i < count($li_arr); $i++) {
                        $tList .= '<tr><td><span class="ITS_List">' . $li_arr[$i] . '</span></td><td><input id="aa" type="button" name="resourceSelect" value="select" field="'.$field.'" rid="'.$id_arr[$i].'"></td></tr>';
                    }
                    $tList .= '</table>';
                    break;
                default:
                    $tList = ''; //'<ul class="ITS_list">';
                    for ($i = 0; $i < count($li_arr); $i++) {
                        //$tList .= '<li><table><tr><td>' . $li_arr[$i] . '</td></tr><tr><td><input type="button" value="SELECT"></td></tr></li>';
                        $tList .= '<div class="fl"><table class="tt"><tr><td><div class="ITS_latex">' . $li_arr[$i] . '</div></td></tr><tr><td><input type="button" name="resourceSelect" value="select" field="'.$field.'" rid="'.$id_arr[$i].'"></td></tr></table></div>';
                    }
                    //$tList .= '</ul>';
            }
        }
        return '<center>' . $tList . '</center>';
    }
        //=====================================================================//
    public function setResource($field,$rid)
    {
        //=====================================================================//
        $callback = 'resourceDelete';
        $action = 'delete';
                switch ($field) {
            //+++++++++++++++++++++++++++++++++++++++++++//
            case 'text':
                $query = 'SELECT content FROM dSPFirst WHERE id='.$rid;
                break;
            case 'equation':
                //SELECT content FROM dSPFirst WHERE  name REGEXP "sampling" AND meta="equation";
                $query = 'SELECT code FROM concepts WHERE id='.$rid;
                break;
            case 'image':
                //$query = 'SELECT content FROM dSPFirst WHERE name REGEXP "sampling" AND meta="image"';
                $query = 'SELECT dir,name FROM images WHERE id='.$rid;
                break;
            case 'example':
                $query = 'SELECT statement,solutions,term,year FROM SPF WHERE id='.$rid;
                break;
        }
        
        //echo $query.'<br>';
        $li_arr = array();
        $res    = mysql_query($query);
        if (!$res) {
            die('Query execution problem in ITS_resource: ' . msql_error());
        }
        
        if (!empty($res)) {
            switch ($field) {
                //---//
                case 'equation':
                    //---//
                    $path = '../cgi-bin/mathtex.cgi?\large ';
                    for ($i = 0; $i < mysql_num_rows($res); $i++) {
                        $code = mysql_result($res, $i);
                        if (!empty($code)) {
							array_push($id_arr,$id);
                            array_push($li_arr, '<img class="ITS_LaTeX" src="' . $path . $code . ' "/>');
                        }
                    }
                    break;
                //---//
                case 'image':
                    //---//
                    $path = 'ITS_FILES/';
                    for ($i = 0; $i < mysql_num_rows($res); $i++) {
                        $code = trim(mysql_result($res, $i, 'dir')) . '/' . trim(mysql_result($res, $i, 'name'));
                        if (!empty($code)) {
                            //echo $path.$code.'<br>';
                            $img = '<a id="single_image" href="' . $path . $code . '" class="ITS_question_img" title="' . $path . $code . '"><img src="' . $path . $code . '" class="ITS_question_img ITS_resource_img" alt="' . $path . $code . '"></a>';
                            array_push($li_arr,$img);
                        }
                    }
                    break;
                //---//
                case 'example':
                    //---//
                    $path  = 'ITS_FILES/';
                    $path1 = 'ITS_FILES/SPFIRST/PNGs/';
                    $path2 = 'ITS_FILES/SPFIRST/solutions/';
                    //+++++++++
                    $idx   = 1;
                    while ($row = MySQL_fetch_array($res)) {
                        //echo '<pre>';var_dump($row);echo '</pre>';die();
                        $fname     = $row['statement'];
                        $solutions = $row['solutions'];
                        $term      = $row['term'];
                        switch ($term) {
                            case 'Spring':
                            case 'Summer':
                                $t = $term[0] . $term[1];
                                break;
                            case 'Fall':
                            case 'Winter':
                                $t = $term[0];
                                break;
                        }
                        $year    = $row['year'];
                        $fname   = preg_replace('/.pdf/', '.png', $fname);
                        $f       = $path1 . strtolower($t) . '_' . $year[2] . $year[3] . '/' . $fname;
                        //echo $path;die();
                        $sol_arr = explode(',', $solutions);
                        
                        $sol_list = '';
                        foreach ($sol_arr as $s) {
                            if (empty($s)) {
                                $sol_list .= '';
                            } else {
                                $pathS = $path2 . strtolower($t) . '_' . $year[2] . $year[3] . '/' . $s;
                                $sol_list .= '<a id="single_image" href="' . $pathS . '" class="ITS_question_img" title="' . $pathS . '"><img src="' . $pathS . '" class="ITS_question_img ITS_resource_img" alt="' . $s . '"></a>';
                            }
                        }
                        //echo '<pre>';var_dump($sol_list);echo '</pre>';die();
                        //$sol  = '<div class="file"><a href="'.$solutions.'" target="_blank"><img alt="'.$solutions.'" src="'.$solutions.'" /></a></div>';
                        
                        $sol  = '<div class="file">' . $sol_list . '</div>';
                        $file = '<a id="single_image" href="' . $f . '" class="ITS_question_img" title="' . $f . '"><img src="' . $f . '" class="ITS_question_img ITS_resource_img" alt="' . $fname . '"></a>';
                        //echo "<tr><td>{$row['id']}</td><td>{$row['title']}</td><td>{$row['score']}</td></tr>";   
                        array_push($li_arr,$file.'<br>'.$sol_list);
                    } //while
                    break;
                default:
                    for ($i = 0; $i < mysql_num_rows($res); $i++) {
                        array_push($li_arr, mysql_result($res, $i));
                    }
                    //var_dump($li_arr);die();
                    //+++//
            }
            switch ($field) {
                //---//
                case 'text':
                    //---//
                    $tList = '<table class="CPROFILE">';
                    for ($i = 0; $i < count($li_arr); $i++) {
                        $tList .= '<tr><td><span class="ITS_List">' . $li_arr[$i] . '</span></td><td><input id="aa" type="button" name="'.$callback.'" value="'.$action.'" field="'.$field.'"></td></tr>';
                    }
                    $tList .= '</table>';
                    break;
                default:
                    $tList = ''; //'<ul class="ITS_list">';
                    for ($i = 0; $i < count($li_arr); $i++) {
                        //$tList .= '<li><table><tr><td>' . $li_arr[$i] . '</td></tr><tr><td><input type="button" value="SELECT"></td></tr></li>';
                        $tList .= '<div class="fl"><table class="tt"><tr><td><div class="ITS_latex">' . $li_arr[$i] . '</div></td></tr><tr><td><input type="button" name="'.$callback.'" value="'.$action.'" field="'.$field.'" rid="'.$rid.'"></td></tr></table></div>';
                    }
                    //$tList .= '</ul>';
            }
        }
        return '<center>' . $tList . '</center>';
        
	}
    //=====================================================================//
} //eo:class
//=====================================================================//
?>
