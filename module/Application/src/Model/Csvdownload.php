<?php
namespace Application\Model;

class Csvdownload{
	
	
	
	
	
	//Funktion, die aus einer übergebenen Liste aus Zahlungen eine CSV erstellt und dieser herunterlädt.
	static function makeCsv($zahlungsliste){
		//Benennen des CSVs
		$table = "Zahlungen";
		
		//Aufbau der ersten CSV-Zeile
		$csv = '"z_id","zahlungsbeschreibung","erstellungsdatum","zahlungsdatum","betrag","k_id","aenderungsdatum","g_id"' . "\n"; 
		
		//Aufbau der CSV-Zeilen		
		foreach ($zahlungsliste as $zahlung){
			$csv .= $zahlung->getZ_id().',';
			$csv .= $zahlung->getZahlungsbeschreibung().',';
			$csv .= $zahlung->getErstellungsdatum().',';
			$csv .= $zahlung->getZahlungsdatum().',';
			$csv .= $zahlung->getBetrag().',';
			//$csv .= $zahlung->getKategorie().',';
			$csv .= $zahlung->getAenderungsdatum().',';
			$csv .= $zahlung->getGruppe()->getG_id();
			$csv .= "\n";
		}
		

		
		//OUPUT HEADERS (nötig für Download)
		header("Pragma: public");
		header("Expires: 0");
		header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
		header("Cache-Control: private",false);
		header("Content-Type: application/octet-stream");
		header("Content-Disposition: attachment; filename=\"$table.csv\";" );
		header("Content-Transfer-Encoding: binary");
		
		//Schreiben des Inhalts in die CSV
		echo($csv);
		exit();
	}
	
	
}