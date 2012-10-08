<?php
/*
test_MIMETEX.php - test "MIMETEX" installation 

Author(s): Greg Krudysz
Last Update: Jun-22-2011
=============================================*/

$tex_path = '/cgi-bin/mimetex.cgi?';
$img_src = $tex_path.'\pi';

echo 'MIMETEX image shown below:<p>';
echo '<img src="'.$img_src.'">';
?> 
