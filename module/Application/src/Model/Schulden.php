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
		
		$this->glaeubiger = $glaeubiger;
		$this->schuldner = $schuldner;
	
		// Datenbankstatement erzeugen
		$dbStmt = new DB_connection();
		
		//Schuldenbetrag vor Berechnung initialisieren
		$this->betragvonschuldneranglaeubiger = 0;
		
		
		//Holen aller Datensätze aus Tabelle zahlungsteilnehmer, in denen der Glaeubiger mit Status 'ersteller' eingegtragen ist
		$query_glaeubiger = "SELECT * FROM zahlungsteilnehmer WHERE status = 'ersteller' AND u_id = '".$this->glaeubiger->getU_id()."';";
		$result_glaeubiger = $dbStmt->execute($query_glaeubiger);
		
		//Holen aller Datensätze aus Tabelle zahlungsteilnehmer, in denen der Schuldner mit Status 'offen' eingetragen ist
		$query_schuldner = "SELECT * FROM zahlungsteilnehmer WHERE status = 'offen' AND u_id = '".$this->schuldner->getU_id()."';";
		$result_schuldner = $dbStmt->execute($query_schuldner);
		

		
		// Ergebnis der Schuldnerliste Zeile fï¿½r Zeile verarbeiten
		while ($row_schuldner = mysqli_fetch_array($result_schuldner)) {
			echo "schleifenedoft<br>";
			
			
			//Schreiben von $result_glaeubiger in $arbeitsresult_glaeubiger, da sonst nach einem durchlauf das array leer wäre
			$arbeitsresult_schuldner = $result_schuldner;
			
			//Ueberpruefen, ob Schuldner dem ausgewaehlten Glaeubiger etwas schuldet
			while ($row_glaeubiger = mysqli_fetch_array($arbeitsresult_glaeubiger)) {
				echo "chleifeoft<br>";
				
				//Wenn beide Teilnehmer an einer Zahlung sind
				if($row_schuldner['z_id'] == $row_glaeubiger['z_id']){
					
					//Wenn der Schuldner den Status offen hat
					if($row_schuldner['status'] == 'offen'){
						//Berechnen der Schulden des Schuldners an den Glaeubiger
						$this->betragvonschuldneranglaeubiger = $this->betragvonschuldneranglaeubiger + $row_schuldner['restbetrag'];
					}
					
				}
					
			
			}
		
		} 
	}
	
	
	public function getBetragVonSchuldnerAnGlaeubiger(){		
		return $this->betragvonschuldneranglaeubiger;		
	}
	public function getBetragVonGlaeubigerAnSchuldner(){
		return $this->betragvonglaeubigeranschuldner;
	}
	
}
