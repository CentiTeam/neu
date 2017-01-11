<?php
namespace Application\Model;

class Csvdownload{
	
	function makeCsv($file, $dir) {
		
		
		
		
		 
		
		
		
		

		header("Content-Type: text/csv");

		header("Content-Disposition: attachment; filename=\"$file\"");

		readfile($dir.$file);

	}

}