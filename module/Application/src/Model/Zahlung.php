<?php
namespace Application\Model;


use Application\Model\DB_connection;

class Zahlung {
	protected  $z_id;
	protected  $zahlungsbeschreibung;
	protected  $erstellungsdatum;
	protected  $zahlungsdatum;
	protected  $betrag;
	protected  $k_id;
	protected  $aenderungsdatum;
	protected  $g_id;
	
	public function __construct($zahlung_id = null) {
		
		$this->z_id= $zahlung_id;
		echo "$this->z_id";
			
	}
	
	
	public function anlegen () {
	
		$db = new DB_connection();
	
		$query = "INSERT INTO zahlung (zahlungsbeschreibung, erstellungsdatum, zahlungsdatum, betrag, k_id, aenderungsdatum, g_id) VALUES (
				'".$this->zahlungsbeschreibung."',
				CURDATE(),
				'".$this->zahlungsdatum."',
				'".$this->betrag."',
				'".$this->k_id."',
				'".$this->aenderungsdatum."',
				'".$this->g_id."'
				)" ;
	
		$result = $db->execute($query);
	
		return $result;
	}
	
	
	public function laden ($z_id = null) {
	
		// Datenbankstatement erzeugen
		$dbStmt = new DB_connection();
	
		// DB-Befehl absetzen: alle Basisinformationen des Teams mit der �bergebenen $t_id abfragen
	
	
		if ($g_id) {
			$result=$dbStmt->execute("SELECT * FROM zahlung WHERE z_id= '".$z_id."';");
		}
		else {
			$result=$dbStmt->execute("SELECT * FROM zahlung WHERE z_id =(SELECT MAX(z_id) FROM zahlung)");
		}
		// Variable, die speichert, ob das Team geladen werden konnte oder nicht
		$isLoaded=false;
	
		// Ergebnis verarbeiten, falls vorhanden
		if ($row=mysqli_fetch_array($result)) {
			$this->z_id=$row["z_id"];
			$this->zahlungsbeschreibung=$row["zahlungsbeschreibung"];
			$this->erstellungsdatum=$row["erstellungsdatum"];
			$this->zahlungsdatum=$row["zahlungsdatum"];
			$this->betrag=$row["betrag"];
			$this->k_id=$row["k_id"];
			$this->aenderungsdatum=$row["aenderungsdatum"]; 
			$this->g_id=$row["g_id"];
			
			// speichern, dass die Basisinformationen des Teams erfolgreich geladen werden konnten
			$isLoaded=true;
		}
	
		// zur�ckgeben, ob beim Laden ein Fehler aufgetreten ist
		return $isLoaded;
	}
	
	
	
	
	// Getter und Setter
	
	public function getZ_id () {
		return $this->z_id;
	}
	
	public function setZ_Id($z_id) {
		$this->z_id= $z_id;
	}

	public function getZzahlungsbeschreibung () {
		return $this->zahlungsbeschreibung;
	}
	
	public function setZahlungsbeschreibung($zahlungsbeschreibung) {
		$this->zahlungsbeschreibung= $zahlungsbeschreibung;
	}
	
	public function getErstellungsdatum () {
		return $this->erstellungsdatum;
	}
	
	public function setErstellungsdatum($erstellungsdatum) {
		$this->erstellungsdatum= $erstellungsdatum;
	}
	
	public function getZahlungsdatum () {
		return $this->zahlungsdatum;
	}
	
	public function setZahlungsdatum($zahlungsdatum) {
		$this->zahlungsdatum= $zahlungsdatum;
	}
	
	public function getBetrag () {
		return $this->betrag;
	}
	
	public function setBetrag($betrag) {
		$this->betrag= $betrag;
	}
	
	public function getK_id () {
		return $this->k_id;
	}
	
	public function setK_Id($k_id) {
		$this->k_id= $k_id;
	}
	
	public function getAenderungsdatum () {
		return $this->aenderungsdatum;
	}
	
	public function setAenderungsdatum($aenderungsdatum) {
		$this->aenderungsdatum= $aenderungsdatum;
	}
	
	public function getG_id () {
		return $this->g_id;
	}
	
	public function setG_Id($g_id) {
		$this->g_id= $g_id;
	}
	
}