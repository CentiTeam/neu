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
		
		
//Berechnen der Schulden des Schuldners an den Glaeubiger
		
		//Holen aller Datensätze aus Tabelle zahlungsteilnehmer, in denen der Schuldner mit Status 'offen' eingetragen ist
		$query_schuldner = "SELECT * FROM zahlungsteilnehmer WHERE status = 'offen' AND u_id = '".$this->schuldner->getU_id()."';";
		$result_schuldner = $dbStmt->execute($query_schuldner);		

		
		// Ergebnis der Schuldnerliste Zeile fï¿½r Zeile verarbeiten
		while ($row_schuldner = mysqli_fetch_array($result_schuldner)) {
			
			
			//Holen aller Datensätze aus Tabelle zahlungsteilnehmer, in denen der Glaeubiger mit Status 'ersteller' eingegtragen ist
			$query_glaeubiger = "SELECT * FROM zahlungsteilnehmer WHERE status = 'ersteller' AND u_id = '".$this->glaeubiger->getU_id()."';";
			$result_glaeubiger = $dbStmt->execute($query_glaeubiger);
			
			//Ueberpruefen, ob Schuldner dem ausgewaehlten Glaeubiger etwas schuldet
			while ($row_glaeubiger = mysqli_fetch_array($result_glaeubiger)) {
				
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
		
		
		
		
		
		
//Berechnen der Schulden des Glaeubigers an den Schuldner

		//Holen aller Datensätze aus Tabelle zahlungsteilnehmer, in denen der Glaeubiger mit Status 'offen' eingetragen ist
		$query_glaeubiger = "SELECT * FROM zahlungsteilnehmer WHERE status = 'offen' AND u_id = '".$this->glaeubiger->getU_id()."';";
		$result_glaeubiger = $dbStmt->execute($query_glaeubiger);
		
		
		// Ergebnis der Glaeubigerliste Zeile fï¿½r Zeile verarbeiten
		while ($row_glaeubiger = mysqli_fetch_array($result_glaeubiger)) {
				
				
			//Holen aller Datensätze aus Tabelle zahlungsteilnehmer, in denen der Schuldner mit Status 'ersteller' eingegtragen ist
			$query_schuldner = "SELECT * FROM zahlungsteilnehmer WHERE status = 'ersteller' AND u_id = '".$this->schuldner->getU_id()."';";
			$result_schuldner = $dbStmt->execute($query_schuldner);
				
			//Ueberpruefen, ob Schuldner dem ausgewaehlten Glaeubiger etwas schuldet
			while ($row_schuldner = mysqli_fetch_array($result_schuldner)) {
		
				//Wenn beide Teilnehmer an einer Zahlung sind
				if($row_glaeubiger['z_id'] == $row_schuldner['z_id']){
						
					//Wenn der Schuldner den Status offen hat
					if($row_glaeubiger['status'] == 'offen'){
						//Berechnen der Schulden des Schuldners an den Glaeubiger
						$this->betragvonglaeubigeranschuldner = $this->betragvonglaeubigeranschuldner + $row_glaeubiger['restbetrag'];
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
	
	public function schuldenVonGlaeubigerAnSchuldnerBegleichen($wert){
		
		// Datenbankstatement erzeugen
		$dbStmt = new DB_connection();
		
		//Query, um die Zahlungen, sortiert nach Zahlungsdatum, aus der Datenbank auszulesen
		$glaeubiger_u_id = $this->glaeubiger->getU_id();
		$schuldner_u_id = $this->schuldner->getU_id();
		$query = "SELECT zahlungsteilnehmer.u_id, zahlungsteilnehmer.z_id, zahlungsteilnehmer.status, zahlungsteilnehmer.restbetrag, zahlung.zahlungsdatum FROM zahlungsteilnehmer JOIN zahlung USING (z_id) WHERE u_id = '".$glaeubiger_u_id."' AND zahlungsempfaenger_id ='".$schuldner_u_id."' AND status='offen' ORDER BY zahlungsdatum ASC;";
		$result = $dbStmt->execute($query);
		
		
		//Schleife, um die Zahlungen zu begleichen (Zahlungen mit aeltestem Zahlungsdatum werden zuerst beglichen)
		$restwert = $wert;
		while($row = mysqli_fetch_array($result)){
			if($row['restbetrag']>$restwert){
				//Schreiben des neuen Restbetrages in die Datenbank
				$neuerwert = $row['restbetrag'] - $restwert;
				$query_speichern = "UPDATE zahlungsteilnehmer SET 'restbetrag' = '".$neuerwert."' WHERE u_id = '".row['u_id']."' AND z_id ='".$row['z_id']."';";
				$dbStmt->execute($query_speichern);
			
			}
		}
		
		
		//Holen der neuen Werte aus der Datenbank
		$this->laden();
		
	}
	
	
}
