<?php
namespace Application\Model;

class Csvdownload{
	
	
	
	
	
	static function makeCsv(){
		// create a file pointer connected to the output stream
		$output = fopen('/tmp/test.csv', 'w');
		
		// output the column headings
		fputcsv($output, array('Betrag'));
		//, 'Column 2', 'Column 3'));
		
		//Schliessen der CSV-Datei
		fclose($output);
		
		
		
		
		
		
		
		
		//Starten des Downloads
		Csvdownload::makeDownload("test.csv", $output);
	}
	
	
	
	
	
	
	static function makeDownload($file, $dir) {		

		header("Content-Type: text/csv");

		header("Content-Disposition: attachment; filename=\"$file\"");

		readfile($dir.$file);

	}

}