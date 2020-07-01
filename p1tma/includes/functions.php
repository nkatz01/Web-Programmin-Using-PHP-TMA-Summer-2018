<?php 
#Web Programming using PHP (P1) - TMA Functions file to be included in TMA web pages

function mmmr($array, $output = 'mean'){ 
    #Provides basic statistical functions - default is mean; other $output parammeters are; 'median', 'mode' and 'range'.
	#Ian Hollender 2016 - adapted from the following, as it was an inacurate solution
	#http://phpsnips.com/45/Mean,-Median,-Mode,-Range-Of-An-Array#tab=snippet
	#Good example of PHP overloading variables with different data types - see the Mode code
	if(!is_array($array)){ 
        echo '<p>Invalid parammeter to mmmr() function: ' . $array . ' is not an array</p>';
		return FALSE; #input parammeter is not an array
    }else{ 
        switch($output){ #determine staistical output required
            case 'mean': #calculate mean or average
                $count = count($array); 
                $sum = array_sum($array); 
                $total = $sum / $count; 
            break; 
            case 'median': #middle value in an ordered list; caters for odd and even lists
                $count = count($array); 
				sort($array); #sort the list of numbers
				if ($count % 2 == 0) { #even list of numbers
					$med1 = $array[$count/2];
					$med2 = $array[($count/2)-1];
					$total = ($med1 + $med2)/2;
				}
				else { #odd list of numbers
					$total = $array[($count-1)/2]; 	
				}				
            break; 
            case 'mode': #most frequent value in a list; N.B. will only find a unique mode or no mode; 
                $v = array_count_values($array); #create associate array; keys are numbers in array, values are counts
                arsort($v); #sort the list of numbers in ascending order				
				
				if (count(array_unique($v)) == 1) { #all frequency counts are the same, as array_unique returns array with all duplicates removed!
					return 'No mode';
				}				
				$i = 0; #used to keep track of count of associative keys processes
                $modes = '';
				foreach($v as $k => $v){ #determine if a unique most frequent number, or return NULL by only looking at first two keys and frequency numbers in the sorted array					
					if ($i == 0) { #first number and frequency in array
						$max1 = $v;	#highest frequency of first number in array
						$modes = $k . ' ';
						$total = $k; #first key is the most frequent number;
					}
					if ($i > 0) { #second number and frequency in array
						$max2 = $v;	#highest frequency of second number in array					
						if ($max1 == $max2) { #two or more numbers with same max frequency; return NULL
							$modes = $modes . $k . ' ';
						}
						else {
							break;  
						}
					}
					$i++; #next item in $v array to be counted
				}
				$total = $modes;				
            break; 
            case 'range': #highest value - lowest value
                sort($array); #find the smallest number
                $sml = $array[0]; 
                rsort($array); #find the largest number
                $lrg = $array[0]; 
                $total = $lrg - $sml; #calculate the range
            break; 
			default :
				echo '<p>Invalid parammeter to mmmr() function: ' . $output . '</p>';
				$total= 0;
				return FALSE;
        } 
        return $total; 
    } 
} 
  
//Nuchem Katz 12/06/2018 University of Birkbeck Tutor Marked assighment 
/* This collection of functions together, given a text file of students and their grades on a particular module, does an analysis on their grade percentages, outputing to the user, their avarage mean, mode and range in addition to the 
relevant information partaining to the course such as course code, title, tutor name, date assessed and number of students. It also reports on the total number of various levels of grades suceh as distinctions, merits etc. 
It has the ability to report on a number of errors, including incorrect student IDs, marks and missing content in fields. */

function test_dir_and_file_parliminaries($title){ //The coming two functions test whether dir and file exists, is in right place; that the file is readable, not empty or of 0 bytes; that it has the right number of fields and students on which to do an analysis
	  
	 $dir=('data');
	  $dir_exists=@is_dir($dir);
	  $file_esists=@is_file('data/'.$title.'.txt');
	  $file_is_readable=@is_readable('data/'.$title.'.txt');
	  $file_is_of_0byte_size= @filesize('data/'.$title.'.txt');
	
	if ($dir_exists AND $file_esists AND $file_is_readable AND ($file_is_of_0byte_size>0)){
		$file_path='data/'.$title.'.txt';
		
		process_fields_and_extract_data($file_path); //call to next function to handle errors inside the file
		
		
	}
	
	
	else
		 
	{	 
		//will pick up wrong file type. eg. .xml file
		$inspect = error_get_last();
		print_r($inspect);
		return "<p>The file you're looking for either doesn't exist, is uploaded in the wrong directory, cannot be processed because of some technical error or is of 0 bytes</p>";
	}
}

function process_fields_and_extract_data($file_path){

	 $handle1_to_directory=opendir('data');
	 $handle1_to_file=fopen($file_path,'r');
	$file_to_string=file_get_contents($file_path);
	if(ctype_space($file_to_string)!=false)
	{
		echo "<p>File contains only whitespaces</p>";
	}
	
	else 
	{
		$firstline_arr=array();
		$firstline_arr=explode(',',fgets($handle1_to_file, 1024));//$firstline_arr is array where header is placed.
		$number_of_fields_1st_line=count($firstline_arr);
		if ($number_of_fields_1st_line<4)
		{
			echo "<p>The header line of your file is missing a field</p>";
			return false;
		}
			$next_line_arr=array();
			$array_of_stu_IDs=array();//array to place list of student IDs.
			$array_of_grades=array();//array to place list of student marks. 
			$number_of_lines=1;//checks if there any students, as first line is header.
			$line_missing_fields_found=false;//assums each row has appropriate number of fields. 
			while (!feof($handle1_to_file))
		{
				$next_line_arr=explode(',',fgets($handle1_to_file, 1024));//parses columns in file.
			if  (count($next_line_arr)<2) //checks number of fields/columns in file.
			{																
				$line_missing_fields_found=true;
			}
				$array_of_stu_IDs[]=trim($next_line_arr[0]);//next two lines trim spaces off student IDs and marks.
				$array_of_grades[]=trim($next_line_arr[1]);	
				$number_of_lines++;
		
		}
			if ($line_missing_fields_found==true)
			{//had there been a row missing a field, this boolean would've been swtichd to true. 
				echo "<p>A line in your file has not got the right number of fields</p>";
				return false;
			}
				
			elseif ($number_of_lines<2)
			{
				echo "<p>No students to report on</p>";
				return false;
			}
			else 
			{
				$file_name= substr($file_path,( strrpos($file_path, '/')+1),strlen($file_path));
				validate_header($file_name, $handle1_to_directory ,$handle1_to_file,$firstline_arr,$array_of_stu_IDs,$array_of_grades);
			}
	}
}

function validate_header($file_name,$handle1_to_directory ,$handle1_to_file,$firstline_arr,$array_of_stu_IDs,$array_of_grades){
	$array_of_validation_references=array();
	$validations_module=array();
	$validate_term=array();
	$validations_date=array();
	$module_abbreviation=strtolower(substr($file_name,0,2));
	$MIN_TERMS = 1; 
	$MAX_TERMS = 3;
	foreach ($firstline_arr as $key => $value){
		 
	$firstline_arr[$key]=trim($value);
	$array_of_validation_references[]="";
	 switch($key) {
		 case 0:
		 if (strlen($firstline_arr[$key])==8){
		 $validations_module[0]=strtolower(substr($firstline_arr[$key],0,2));
		 $validations_module[1]=substr($firstline_arr[$key],2,4);
		 $validations_module[2]=substr($firstline_arr[$key],6,1);
		 $validations_module[3]=substr($firstline_arr[$key],7,1);;
		 if (!(($validations_module[0]==$module_abbreviation) AND  (is_numeric($validations_module[1])) AND (intval($validations_module[1])>=1 OR intval($validations_module[1])<=2018) 
			 AND ($validations_module[2] == 'T') AND ctype_digit($validations_module[3]) AND ((intval($validations_module[3]))<=$MAX_TERMS) AND ((intval($validations_module[3]))>=$MIN_TERMS) ))
			 {
			 $array_of_validation_references[0]=": ERROR";
			 }
		
		 } //end of if strlen
		else{ 
			 $array_of_validation_references[0]=": ERROR";
		 }
		 break;
		case 1: 
		
		if ((empty($firstline_arr[$key])) or (!ctype_print($firstline_arr[$key]))){
		$array_of_validation_references[1]=" ERROR";
		}
		else {
			$firstline_arr[$key]=ucfirst($firstline_arr[$key]);		
		}
		break;
		case 2:
		if  ((empty($firstline_arr[$key])) or (!ctype_print($firstline_arr[$key]))){
		$array_of_validation_references[2]=" ERROR";}
		else {
			$firstline_arr[$key]=ucfirst($firstline_arr[$key]);		
		
		}
		break;
		case 3:
		 
		$validations_date=explode('/' , $firstline_arr[$key]);
		foreach   ($firstline_arr as $innerkey => $value2){
			switch($innerkey){				
			case 0: 
				if (!(checkdate($validations_date[1],$validations_date[0],$validations_date[2]) ))
				{
				$array_of_validation_references[3]=": ERROR";
			}
			break;
			}//end of inner swtich
		}//end of inner foreach
	 }//end of swtich
	}//end of foreach
	

display_header_data($array_of_validation_references, $file_name, $handle1_to_directory ,$handle1_to_file, $firstline_arr, $array_of_stu_IDs, $array_of_grades);
 
	}
	
function display_header_data($array_of_validation_references, $file_name, $handle1_to_directory ,$handle1_to_file, $firstline_arr,$array_of_stu_IDs,$array_of_grades){
	
	$cell_content_ref=array('Module Code :','Module Title :', 'Tutor :', 'Marked Date :');
	echo "<p>Module Header Data...</p>".PHP_EOL; // first section of dsplay starts
	echo "<p>File name : $file_name</p>"; 
	foreach ($firstline_arr as $key => $value){

	
		
	echo "<p>$cell_content_ref[$key] $firstline_arr[$key] $array_of_validation_references[$key]</p>";	//run through both arrays in paralel as their element positions correspond to each other. 
}
	
	display_and_aggregate_student_data($handle1_to_directory ,$handle1_to_file, $array_of_stu_IDs,$array_of_grades); //finished displaying student header, now let's process student marks and IDs. 
}

	
function display_and_aggregate_student_data($handle1_to_directory ,$handle1_to_file, $array_of_stu_IDs,$array_of_grades){
	 $stu_not_inc=0;//will be passed by refernce to function who validates student ID and mark so that it can increment it and retain previous value. 
	 $array =array();
	 $entries_ofStu_to_not_inc=array_pad(array(), count($array_of_stu_IDs),1); // Haved 3 arrays, student IDs, studnet marks and whether they're a student included in our analysis, where elements are linked between each other by element position in array. See first IF condition in second Foreach in this function. 
//at first assume all sutdents will be included, so place a 1 in each cell. 	 
	  
	  $grades_total=0.0;//decalre variable to calculate mean as well as types of passess. 
	  $distinction=0;
	  $merit=0;
	  $pass=0;
	  $fail=0;
	  
	echo "<p>Student ID and Mark data read from file...</p>"; //second section of display begins. 
	foreach ($array_of_stu_IDs as $key => $value)
	{
	echo '<p>'.$array_of_stu_IDs[$key].' : '.$array_of_grades[$key]. validate_student_data($array_of_stu_IDs[$key],  $array_of_grades[$key] ,$stu_not_inc, $key ,$entries_ofStu_to_not_inc).'</p>';//display Student ID, Mark and sends their values over to next function which will check valid formatting, produce 1 or two 
	//error messages and make a note in a third array of to reference of it, as well as bumping up a counter. (could really just later count all the 0s in $entries_ofStu_to_not_inc).  
	
	 
	}
	 $students_included=count($array_of_stu_IDs)-$stu_not_inc;
	 echo "<p>ID's and module marks to be included...</p>";//3rd section of display begins
	
	foreach ($array_of_stu_IDs as $key => $value)
	{
		if  ( ($entries_ofStu_to_not_inc[$key])==0)
		{ 
		continue; // skips an entry in all 3 arrays 
		}
			echo "<p>$array_of_stu_IDs[$key] : $array_of_grades[$key]</p>";
			$grades_total+=intval($array_of_grades[$key]);
			 $array[]=intval($array_of_grades[$key]);
			
			
			
			if (intval($array_of_grades[$key])>=70){ //This task as well as calculating could really have been given to a separate function. 
				$distinction++;
			 }
			elseif (intval($array_of_grades[$key])>=60)
			 {
				$merit++;
			 }
			elseif (intval($array_of_grades[$key])>=40){
				$pass++;}
			else{
				$fail++;}
				 
			
	}
	
	echo "<p>Statistical Analysis of module marks...</p>";
	echo '<p>'.'Mean: '.round($grades_total/$students_included,2).'</p>';//mmmr is not used to calculate mean. 
	echo '<p>'.'Mode: '.mmmr($array,'mode').'</p>';
	echo '<p>'.'Range: '.mmmr($array,'range').'</p>';
	echo "<p># of students $students_included</p>";
	echo "<p>Grade Distribution of module marks...</p>";
	echo "<p>Dist : $distinction</p>";
	echo "<p>Merit : $merit</p>";
	echo "<p>Pass : $pass</p>";
	echo "<p>Fail : $fail</p>";
	
	closedir($handle1_to_directory);//finishes programm
	fclose($handle1_to_file); //finishes programm
	}
  
  
function validate_student_data($studentID, $student_grade, &$stu_not_inc ,$key,  &$entries_ofStu_to_not_inc ){ //receives the key of where in the array of Studnets, we currently are, as well as array of inclusion references but which is passed by reference. 
		$empty="";
		$error_display=' - incorrect student ID : not included';
		$student_id_errored=(((strlen($studentID))<8) or (!ctype_digit($studentID)) or ctype_space($studentID));
		$student_grade_errored=(($student_grade<0) or ($student_grade>100) or (!ctype_digit($student_grade))  or (ctype_space($student_grade)));
		if ($student_grade_errored AND $student_id_errored)
		{
			$stu_not_inc++;
			$entries_ofStu_to_not_inc[$key]=0; //make a pointer in 3rd array so that the previous function knows, the student referenced by this position in their array, is not to be included and so in the next to IFs. 
			return add_grade_error_display($error_display);
		}
		elseif ($student_id_errored )
		{
			$stu_not_inc++;
			$entries_ofStu_to_not_inc[$key]=0;
			return  $error_display;
		}
		elseif ($student_grade_errored) 
		{
		$stu_not_inc++;
		 $entries_ofStu_to_not_inc[$key]=0;
		return add_grade_error_display($empty);
		}
		else {
		return $empty;
		}
}
	
function add_grade_error_display(&$error_display){//concatenates error message about mark to error message about ID, in case there are two errors for one studetns in both, their mark and ID. 
	$error_display.=' - Incorrect mark : not included';
	return $error_display;
}


 

?>
