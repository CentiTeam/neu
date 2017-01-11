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
	
	
	
	static function sepp(){
		//NAME THE FILE
		$table = "test";
		
		//BUILD CSV CONTENT
		$csv = '"Column 1","Column 2"' . "\n";
		
		//BUILD CSV ROWS
		$csv .= '"Column 1 Content","Column 2 Content"' . "\n";
		$csv .= '"Column 1 Content","Column 2 Content"' . "\n";
		$csv .= '"Column 1 Content","Column 2 Content"' . "\n";
		$csv .= '"Column 1 Content","Column 2 Content"' . "\n";
		$csv .= '"Column 1 Content","Column 2 Content"' . "\n";
		
		//OUPUT HEADERS
		header("Pragma: public");
		header("Expires: 0");
		header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
		header("Cache-Control: private",false);
		header("Content-Type: application/octet-stream");
		header("Content-Disposition: attachment; filename=\"$table.csv\";" );
		header("Content-Transfer-Encoding: binary");
		
		//OUTPUT CSV CONTENT
		echo($csv);
		
	}
	
	
	static function makeDownload($file, $dir) {		

		header("Content-Type: text/csv");

		header("Content-Disposition: attachment; filename=\"$file\"");

		readfile($dir.$file);

	}

}