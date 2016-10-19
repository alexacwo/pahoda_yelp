<?php
 
	$get_files = scandir(dirname(__FILE__)); 
	
	$files = array_slice($get_files, 2);

	foreach ($files as $file) {
		/*$filename = str_replace("_", "-", $file);
		$filename = str_replace(",", "", $filename);
		$filename = str_replace("\\", "", $filename);
		$filename = str_replace(" ", "-", $filename);
		$filename = str_replace("'", "", $filename);
		
		$filename = str_replace("---", "-", $filename);
		$filename = str_replace("--", "-", $filename);
		rename($file, $filename);*/
		
		echo $file."<br>";
	}
	
?>