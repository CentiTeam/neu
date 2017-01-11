<?php
namespace Application\Model;

class Csvdownload{
	
	
	
	
	
	static function makeCsv($zahlungsliste){
		//NAME THE FILE
		$table = "test";
		
		//BUILD CSV CONTENT
		$csv = '"z_id","zahlungsbeschreibung","erstellungsdatum","zahlungsdatum","betrag","k_id","aenderungsdatum","g_id"' . "\n"; 
		
		//BUILD CSV ROWS
		
		foreach ($zahlungsliste as $zahlung){
			$csv .= $zahlung->getZ_id().',';
			$csv .= $zahlung->getZahlungsbeschreibung().',';
			$csv .= $zahlung->getErstellungsdatum().',';
			$csv .= $zahlung->getZahlungsdatum().',';
			$csv .= $zahlung->getBetrag().',';
			$csv .= $zahlung->getK_id().',';
			$csv .= $zahlung->getAenderungsdatum().',';
			$csv .= $zahlung->getG_id();

			
			
			
			$csv .= "\n";
		}
		
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
		exit();
	}
	
	
	//Behalten als Vorlage, weil es funktioniert!!!!!
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
		exit();
		
	}
	
	
	static function makeDownload($file, $dir) {		

		header("Content-Type: text/csv");

		header("Content-Disposition: attachment; filename=\"$file\"");

		readfile($dir.$file);

	}

}