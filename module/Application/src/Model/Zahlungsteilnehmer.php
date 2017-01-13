<?php
namespace Application\Model;


use Application\Model\DB_connection;
use Application\Model\User;
use Application\Model\Zahlung;

class Zahlungsteilnehmer {
	protected  $user;
	protected  $zahlung;
	protected  $status;
	protected  $anteil;
	protected  $zahlungsempfaenger; 
	
	// HIER Kann evtl. ein Fehler liegen!!! (Objekt muss beachtet werden?)
	public function __construct($zahlung_id = null, $user_id = null) {
		$this->zahlung= $zahlung_id;
		$this->user=$user_id;
		echo "$this->zahlung";
		echo "$this->user";
	}
	
	public function anlegen () {
	
		$db = new DB_connection();
	
		$query = "INSERT INTO zahlungsteilnehmer (u_id, z_id, status, anteil, zahlungsempfaenger_id) VALUES (
				'".$this->getUser()->getU_id()."',
				'".$this->getZahlung()->getZ_id()."',
				'".$this->status."',
				'".$this->anteil."',
				'".$this->getZahlungsempfaenger()->getU_id()."'
				)" ;
	
		$result = $db->execute($query);
	
		return $result;
	}
	
	
	public static function teilnehmerzahlungenholen($user_id) {
	
		// Liste initialisieren
		$zahlungenListe = array ();
	
		$db = new DB_connection();
	
		$query="SELECT * FROM `zahlungsteilnehmer`
				WHERE u_id= '".$user_id."' ";
	
		// Wenn die Datenbankabfrage erfolgreich ausgef�hrt worden ist
		if ($result = $db->execute($query)) {
	
			// Ergebnis Zeile f�r Zeile verarbeiten
			while ($row = mysqli_fetch_array($result)) {
					
				// neues Model erzeugen
				$model = new Zahlungsteilnehmer();
	
				// Model anhand der Nummer aus der Datenbankabfrage laden
				$model->laden($row["z_id"], $row["u_id"]);
	
				// neues Model ans Ende des $gruppeListe-Arrays anf�gen
				$zahlungListe[] = $model;
			}
	
			// fertige Liste von Gruppe-Objekten zur�ckgeben
			return $zahlungListe;
		}
	}
	
	
	
	
	
	
	public function laden ($z_id, $u_id) {
	
		// Datenbankstatement erzeugen
		$dbStmt = new DB_connection();
	
		// DB-Befehl absetzen: alle Basisinformationen des Teams mit der �bergebenen $t_id abfragen
	
		$result=$dbStmt->execute("SELECT * FROM zahlungsteilnehmer 
				WHERE z_id= '".$z_id."'
				AND u_id='".$u_id."';");
		
		// Variable, die speichert, ob das Team geladen werden konnte oder nicht
		$isLoaded=false;
	
		// Ergebnis verarbeiten, falls vorhanden
		if ($row=mysqli_fetch_array($result)) {
			$zahlung_id=$row["z_id"];
			$this->zahlung=new Zahlung(); 
			$this->zahlung->laden($zahlung_id);
			
			$user_id=$row["u_id"];
			$this->user=new User();
			$this->user->laden($user_id);
			
			
			$this->status=$row["status"];
			$this->anteil=$row["anteil"];
			
			$zahlungsempfaenger_id=$row["zahlungsempfaenger_id"];
			$this->zahlungsempfaenger=new User();
			$this->zahlungsempfaenger->laden($zahlungsempfaenger_id);
				
				
			// speichern, dass die Basisinformationen des Teams erfolgreich geladen werden konnten
			$isLoaded=true;
		}
	
		// zur�ckgeben, ob beim Laden ein Fehler aufgetreten ist
		return $isLoaded;
	}
	
	
	
	public static function loeschen($z_id){
		$db = new DB_connection();
		
		$query = "DELETE FROM zahlungsteilnehmer
					WHERE zahlung_id = '".$z_id."'
					;" ;
		
	}
	
	// Getter und Setter
	
	public function getUser () {
		return $this->user;
	}
	
	public function setUser($user) {
		$this->user= $user;
	}
	
	public function getZahlung () {
		return $this->zahlung;
	}
		
	public function setZahlung($zahlung) {
		$this->zahlung= $zahlung;
	}
	
	public function getStatus() {
		return $this->status;
	}
	
	public function setStatus($status) {
		$this->status= $status;
	}
	
	public function getAnteil() {
		return $this->anteil;
	}
	
	public function setAnteil($anteil) {
		$this->anteil= $anteil;
	}
	
	public function getZahlungsempfaenger() {
		return $this->zahlungsempfaenger;
	}
	
	public function setZahlungsempfaenger($zahlungsempfaenger) {
		$this->zahlungsempfaenger= $zahlungsempfaenger;
	}
}