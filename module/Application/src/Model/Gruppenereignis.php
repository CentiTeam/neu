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
	
	public function gruppenmitgliedhinzufuegenEreignis($user, $gruppe){
		
		$ereignisbeschreibung = "Der Benutzer ".$user->getUsername()." wurde zur Gruppe hinzugef�gt";
		// Datenbankstatement erzeugen
		$dbStmt = new DB_connection();
		
		// DB-Befehl absetzen: alle Basisinformationen des Ereignisses anhand der uebergebenen e_id abrufen
		$result=$dbStmt->execute("INSERT INTO ereignis (g_id, beschreibung, zeitpunkt) VALUES ('".$gruppe->getG_id()."', '".$ereignisbeschreibung."', NOW());");
		
	}
	
	public function gruppenmitgliedentfernenEreignis($user, $gruppe){
	
		$ereignisbeschreibung = "Der Benutzer ".$user->getUsername()." wurde aus der Gruppe von Admin entfernt";
		// Datenbankstatement erzeugen
		$dbStmt = new DB_connection();
	
		// DB-Befehl absetzen: alle Basisinformationen des Ereignisses anhand der uebergebenen e_id abrufen
		$result=$dbStmt->execute("INSERT INTO ereignis (g_id, beschreibung, zeitpunkt) VALUES ('".$gruppe->getG_id()."', '".$ereignisbeschreibung."', NOW());");
	
	}
	
	public function gruppenmitgliedaustretenEreignis($user, $gruppe){
	
		$ereignisbeschreibung = "Der Benutzer ".$user->getUsername()." ist aus der Gruppe ausgetreten";
		// Datenbankstatement erzeugen
		$dbStmt = new DB_connection();
	
		// DB-Befehl absetzen: alle Basisinformationen des Ereignisses anhand der uebergebenen e_id abrufen
		$result=$dbStmt->execute("INSERT INTO ereignis (g_id, beschreibung, zeitpunkt) VALUES ('".$gruppe->getG_id()."', '".$ereignisbeschreibung."', NOW());");
	
	}
	
	public function gruppenmitgliedloeschenEreignis($user, $gruppe){
	
		$ereignisbeschreibung = "Der Benutzer ".$user->getUsername()." wurde aus dem System geloescht";
		// Datenbankstatement erzeugen
		$dbStmt = new DB_connection();
	
		// DB-Befehl absetzen: alle Basisinformationen des Ereignisses anhand der uebergebenen e_id abrufen
		$result=$dbStmt->execute("INSERT INTO ereignis (g_id, beschreibung, zeitpunkt) VALUES ('".$gruppe->getG_id()."', '".$ereignisbeschreibung."', NOW());");
	
	}
	
	public static function zahlunganlegenEreignis($zahlung, $gruppe, $user){
	
		$ereignisbeschreibung = "Die Zahlung ".$zahlung->getZahlungsbeschreibung()." mit der ID ".$zahlung->getZ_id()." in Hoehe von ".$zahlung->getBetrag()." wurde in der Kategorie ".$zahlung->getKategorie()->getKategoriebeschreibung()." von ".$user->getUsername()." angelegt";
		// Datenbankstatement erzeugen
		$dbStmt = new DB_connection();  
	
		// DB-Befehl absetzen: alle Basisinformationen des Ereignisses anhand der uebergebenen e_id abrufen
		$result=$dbStmt->execute("INSERT INTO ereignis (g_id, beschreibung, zeitpunkt) VALUES ('".$gruppe->getG_id()."', '".$ereignisbeschreibung."', NOW());");
	
	}
	
	
	public function zahlungbegleichenEreignis($zahlung, $gruppe, $user){
	
		$ereignisbeschreibung = "Die Zahlung ".$zahlung->getZahlungsbeschreibung()." mit der ID ".$zahlung->getZ_id()." in Hoehe von ".$zahlung->getBetrag()." wurde von ".$user->getUsername()." beglichen";
		// Datenbankstatement erzeugen
		$dbStmt = new DB_connection();
	
		// DB-Befehl absetzen: alle Basisinformationen des Ereignisses anhand der uebergebenen e_id abrufen
		$result=$dbStmt->execute("INSERT INTO ereignis (g_id, beschreibung, zeitpunkt) VALUES ('".$gruppe->getG_id()."', '".$ereignisbeschreibung."', NOW());");
	
	}
	
	
	public function zahlungeditierenEreignis($zahlung, $gruppe, $user){
	
		$ereignisbeschreibung = "Die Zahlung ".$zahlung->getZahlungsbeschreibung()." mit der ID ".$zahlung->getZ_id()." in Hoehe von ".$zahlung->getBetrag()." wurde von ".$user->getUsername()." editiert";
		// Datenbankstatement erzeugen
		$dbStmt = new DB_connection();
	
		// DB-Befehl absetzen: alle Basisinformationen des Ereignisses anhand der uebergebenen e_id abrufen
		$result=$dbStmt->execute("INSERT INTO ereignis (g_id, beschreibung, zeitpunkt) VALUES ('".$gruppe->getG_id()."', '".$ereignisbeschreibung."', NOW());");
	
	}
	
	public function zahlungstatusaenderungEreignis($zahlung, $gruppe, $user){
	
		$ereignisbeschreibung = "Der Status der Zahlung ".$zahlung->getZahlungsbeschreibung()." mit der ID ".$zahlung->getZ_id()." in Hoehe von ".$zahlung->getBetrag()." wurde von ".$user->getUsername()." zu ".$zahlung->getStatus()."geaendert";
		// Datenbankstatement erzeugen
		$dbStmt = new DB_connection();
	
		// DB-Befehl absetzen: alle Basisinformationen des Ereignisses anhand der uebergebenen e_id abrufen
		$result=$dbStmt->execute("INSERT INTO ereignis (g_id, beschreibung, zeitpunkt) VALUES ('".$gruppe->getG_id()."', '".$ereignisbeschreibung."', NOW());");
	
	}
	
	
	public function zahlungloeschenEreignis($zahlung, $gruppe, $user){
	
		$ereignisbeschreibung = "Die Zahlung ".$zahlung->getZahlungsbeschreibung()." mit der ID ".$zahlung->getZ_id()." in Hoehe von ".$zahlung->getBetrag()." wurde von ".$user->getUsername()." geloescht";
		// Datenbankstatement erzeugen
		$dbStmt = new DB_connection();
	
		// DB-Befehl absetzen: alle Basisinformationen des Ereignisses anhand der uebergebenen e_id abrufen
		$result=$dbStmt->execute("INSERT INTO ereignis (g_id, beschreibung, zeitpunkt) VALUES ('".$gruppe->getG_id()."', '".$ereignisbeschreibung."', NOW());");
	
	}
	
	
	public static function gruppenadminrechteweitergebenEreignis($geber, $nehmer, $gruppe){
	
		$ereignisbeschreibung = $geber->getUsername()." hat Gruppenadminrechte an ".$nehmer->getUsername()." weitergegeben";
		// Datenbankstatement erzeugen
		$dbStmt = new DB_connection();
	
		// DB-Befehl absetzen: alle Basisinformationen des Ereignisses anhand der uebergebenen e_id abrufen
		$result=$dbStmt->execute("INSERT INTO ereignis (g_id, beschreibung, zeitpunkt) VALUES ('".$gruppe->getG_id()."', '".$ereignisbeschreibung."', NOW());");
	
	}
	
	public static function gruppenadminrechtenehmenEreignis($nehmer, $genommener, $gruppe){
	
		$ereignisbeschreibung = $nehmer->getUsername()." hat Gruppenadminrechte von ".$genommener->getUsername()." wieder genommen";
		// Datenbankstatement erzeugen
		$dbStmt = new DB_connection();
	
		// DB-Befehl absetzen: alle Basisinformationen des Ereignisses anhand der uebergebenen e_id abrufen
		$result=$dbStmt->execute("INSERT INTO ereignis (g_id, beschreibung, zeitpunkt) VALUES ('".$gruppe->getG_id()."', '".$ereignisbeschreibung."', NOW());");
	
	}
	
	public static function gruppeanlegenEreignis($gruppe){
	
		$ereignisbeschreibung = "Die Gruppe wurde erstellt";
		// Datenbankstatement erzeugen
		$dbStmt = new DB_connection();
	
		// DB-Befehl absetzen: alle Basisinformationen des Ereignisses anhand der uebergebenen e_id abrufen
		$result=$dbStmt->execute("INSERT INTO ereignis (g_id, beschreibung, zeitpunkt) VALUES ('".$gruppe->getG_id()."', '".$ereignisbeschreibung."', NOW());");
	
	}
	
	public static function gruppeloeschenEreignis($gruppe){
	
		$ereignisbeschreibung = "Die Gruppe wurde gel�scht";
		// Datenbankstatement erzeugen
		$dbStmt = new DB_connection();
	
		// DB-Befehl absetzen: alle Basisinformationen des Ereignisses anhand der uebergebenen e_id abrufen
		$result=$dbStmt->execute("INSERT INTO ereignis (g_id, beschreibung, zeitpunkt) VALUES ('".$gruppe->getG_id()."', '".$ereignisbeschreibung."', NOW());");
	
	}
	
	public static function akutelleereignisse($user_id) {
		
		$dbStmt = new DB_connection();
		
		$query = "SELECT * FROM ereignis
				  NATURAL JOIN gruppe
				  NATURAL JOIN gruppenmitglied
				  WHERE gruppenmitglied.u_id='".$user_id."'
				  AND (date(zeitpunkt) BETWEEN curdate()-INTERVAL 5 DAY AND curdate())
				  ORDER BY g_id, zeitpunkt DESC;";
		
		// Wenn die Datenbankabfrage erfolgreich ausgef�hrt worden ist
		if ($result = $dbStmt->execute($query)) {
		
			// Ergebnis Zeile f�r Zeile verarbeiten
			while ($row = mysqli_fetch_array($result)) {
					
				// neues Model erzeugen
				$model = new Gruppenereignis();
		
				// Model anhand der Nummer aus der Datenbankabfrage laden
				$model->laden($row["e_id"]);
		
				// neues Model ans Ende des Arrays anf�gen
				$aktuelleListe[] = $model;
			}
		
			// fertige Liste von Ereignis-Objekten zur�ckgeben
			return $aktuelleListe;
		}
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