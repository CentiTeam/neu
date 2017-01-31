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
		
		
		//Holen aller Datensätze aus Tabelle zahlungsteilnehmer, in denen der Glaeubiger mit Status 'ersteller' eingegtragen ist
		$query_glaeubiger = "SELECT * FROM zahlungsteilnehmer WHERE status = 'ersteller' AND u_id = '".$glaeubiger->getU_id()."';";
		$result_glaeubiger = $dbStmt->exectue($query_glaeubiger);
		
		//Holen aller Datensätze aus Tabelle zahlungsteilnehmer, in denen der Schuldner mit Status 'offen' eingetragen ist
		$query_schuldner = "SELECT * FROM zahlungsteilnehmer WHERE status = 'offen' AND u_id = '".$schuldner->getU_id()."';";
		$result_schuldner = $dbStmt->exectue($query_schuldner);
		
		//Schuldenbetrag vor Berechnung initialisieren
		$betragvonschuldneranglaeubiger = 0;
		
		// Ergebnis der Schuldnerliste Zeile fï¿½r Zeile verarbeiten
		while ($row_schuldner = mysqli_fetch_array($result_schuldner)) {
			
			//Ueberpruefen, ob Schuldner dem ausgewaehlten Glaeubiger etwas schuldet
			while ($row_glaeubiger = mysqli_fetch_array($result_glaeubiger)) {
				
				//Wenn beide Teilnehmer an einer Zahlung sind
				if($row_schuldner['z_id'] == $row_glaeubiger['z_id']){
					
					//Wenn der Schuldner den Status offen hat
					if($row_schuldner['status'] == 'offen'){						
						//Berechnen der Schulden des Schuldners an den Glaeubiger
						$betragvonschuldneranglaeubiger = $betragvonschuldneranglaeubiger + $row_schuldner['restbetrag'];
					}
					
				}
					
			
			}
		
		} 
	}
	
	
	public function getBetragVonSchuldnerAnGlaeubiger(){
		return $betragvonschuldneranglaeubiger;
		
	}
	
}
