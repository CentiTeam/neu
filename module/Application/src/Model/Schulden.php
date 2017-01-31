<?php

namespace Application\Model;


use Application\Model\DB_connection;
use Application\Model\User;


class Schulden{
	protected $betragvonglaeubigeranschuldner;
	protected $betragvonschuldneranglaeubiger;
	protected $glaeubiger;
	protected $schuldner;
	
	
	public function __construct() {
			
	}
	
	
	public function laden ($glaeubiger = null, $schuldner = null) {
	
		// Datenbankstatement erzeugen
		$dbStmt = new DB_connection();
		echo "Datenbankstatement erzeugen";
		
		//Schuldenbetrag vor Berechnung initialisieren
		$betragvonschuldneranglaeubiger = 0;
		echo "Schuldenbetrag vor Berechnung initialisieren";
		
		
		//Holen aller Datensätze aus Tabelle zahlungsteilnehmer, in denen der Glaeubiger mit Status 'ersteller' eingegtragen ist
		$query_glaeubiger = "SELECT * FROM zahlungsteilnehmer WHERE status = 'ersteller' AND u_id = '".$glaeubiger->getU_id()."';";
		$result_glaeubiger = $dbStmt->execute($query_glaeubiger);
		echo "Holen aller Datensätze aus Tabelle zahlungsteilnehmer, in denen der Glaeubiger mit Status 'ersteller' eingegtragen ist";
		
		//Holen aller Datensätze aus Tabelle zahlungsteilnehmer, in denen der Schuldner mit Status 'offen' eingetragen ist
		$query_schuldner = "SELECT * FROM zahlungsteilnehmer WHERE status = 'offen' AND u_id = '".$schuldner->getU_id()."';";
		$result_schuldner = $dbStmt->execute($query_schuldner);
		echo "Holen aller Datensätze aus Tabelle zahlungsteilnehmer, in denen der Schuldner mit Status 'offen' eingetragen ist";
		

		
		// Ergebnis der Schuldnerliste Zeile fï¿½r Zeile verarbeiten
		while ($row_schuldner = mysqli_fetch_array($result_schuldner)) {
			echo "in erster schleife";
			
			//Ueberpruefen, ob Schuldner dem ausgewaehlten Glaeubiger etwas schuldet
			while ($row_glaeubiger = mysqli_fetch_array($result_glaeubiger)) {
				echo "in zweiter schleife";
				
				//Wenn beide Teilnehmer an einer Zahlung sind
				if($row_schuldner['z_id'] == $row_glaeubiger['z_id']){
					echo "bedingung beide";
					
					//Wenn der Schuldner den Status offen hat
					if($row_schuldner['status'] == 'offen'){
						echo "berechnung";
						//Berechnen der Schulden des Schuldners an den Glaeubiger
						$betragvonschuldneranglaeubiger = $betragvonschuldneranglaeubiger + $row_schuldner['restbetrag'];
						echo $betragvonschuldneranglaeubiger;
					}
					
				}
					
			
			}
		
		} 
	}
	
	
	public function getBetragVonSchuldnerAnGlaeubiger(){
		return $betragvonschuldneranglaeubiger;
		
	}
	
}
