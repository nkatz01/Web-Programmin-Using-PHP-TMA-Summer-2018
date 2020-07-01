<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <title>dTT1</title>
    </head>
 <body>
<?php
 require_once 'includes/functions.php';

$title1='dTT1';
$title2='ppGM';
$title3='p1IH';
//test for .xml file
//$title4='test';
$title5='dTT5';
$output= test_dir_and_file_parliminaries($title1);
$output.= test_dir_and_file_parliminaries($title2);
$output.= test_dir_and_file_parliminaries($title3);
//test for .xml file
//$output.= test_dir_and_file_parliminaries($title4);
$output.= test_dir_and_file_parliminaries($title5);
echo $output;
?>
	
   
       
    </body>
</html> 