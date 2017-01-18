<?php

namespace Application\Model;

use Application\Model\DB_connection;
use Application\Model\Gruppe;
use Application\Model\User;

class Gruppenereignis{
	
	protected $e_id;
	protected $gruppe;
	protected $beschreibung;
	protected $zeitpunkt;
	
	public function __construct($gruppe = null) {	
		$this->gruppe= $gruppe;
	}
	
	
	
	
	
	public static function listeHolen($gruppe) {
	
		// Liste initialisieren
		$ereignisListe = array ();
	
		$db = new DB_connection();
	
		$query="SELECT e_id FROM ereignis WHERE g_id = ".$gruppe->getG_id().";";
	
		// Wenn die Datenbankabfrage erfolgreich ausgef�hrt worden ist
		if ($result = $db->execute($query)) {
	
			// Ergebnis Zeile f�r Zeile verarbeiten
			while ($row = mysqli_fetch_array($result)) {
					
				// neues Model erzeugen
				$model = new Gruppenereignis();
	
				// Model anhand der Nummer aus der Datenbankabfrage laden
				$model->laden($row["e_id"]);
	
				// neues Model ans Ende des $gruppeListe-Arrays anf�gen
				$ereignisListe[] = $model;
			}
	
			// fertige Liste von Gruppe-Objekten zur�ckgeben
			return $ereignisListe;
		}
	}
	
	
	
	

	public function laden ($e_id) {
	
		// Datenbankstatement erzeugen
		$dbStmt = new DB_connection();
	
		// DB-Befehl absetzen: alle Basisinformationen des Ereignisses anhand der uebergebenen e_id abrufen
		$result=$dbStmt->execute("SELECT * FROM ereignis WHERE e_id= '".$e_id."';");
	
		// Variable, die speichert, ob das Ereignis geladen werden konnte oder nicht
		$isLoaded=false;
	
		// Ergebnis verarbeiten, falls vorhanden
		if ($row=mysqli_fetch_array($result)) {
	
			$gruppen_id=$row["g_id"];
			$this->gruppe=new Gruppe();
			$this->gruppe->laden($gruppen_id);
			
			$this->e_id = $row['e_id'];
			$this->beschreibung = $row['beschreibung'];
			$this->zeitpunkt = $row['zeitpunkt'];
				
	
			// speichern, dass die Basisinformationen des Ereignisses erfolgreich geladen werden konnten
			$isLoaded=true;
		}
	
		// zur�ckgeben, ob beim Laden ein Fehler aufgetreten ist
		return $isLoaded;
	}
	
	
	
	
	// Getter und Setter
	
	public function getGruppe () {
		return $this->gruppe;
	}
	
	public function setGruppe($gruppe) {
		$this->Gruppe= $gruppe;
	}
	
	
	public function getE_id () {
		return $this->e_id;
	}
	
	public function setE_id($e_id) {
		$this->e_id= $e_id;
	}
	
	public function getZeitpunkt () {
		return $this->zeitpunkt;
	}
	
	public function setZeitpunkt($zeitpunkt) {
		$this->zeitpunkt= $zeitpunkt;
	}
	
	public function getBeschreibung () {
		return $this->beschreibung;
	}
	
	public function setBeschreibung($beschreibung) {
		$this->beschreibung= $beschreibung;
	}
}