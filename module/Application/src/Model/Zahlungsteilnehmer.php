<?php
namespace Application\Model;


use Application\Model\DB_connection;

class Zahlungsteilnehmer {
	protected  $user;
	protected  $zahlung;
	protected  $status;
	protected  $anteil;
	protected  $zahlungsempfaenger; 
	
	// HIER Kann evtl. ein Fehler liegen!!! (Objekt muss beachtet werden?)
	public function __construct($zahlung_id, $user_id) {
		$this->zahlung= $zahlung_id;
		$this->user=$user_id;
		echo "$this->zahlung";
		echo "$this->user";
	}
	
	public function anlegen () {
	
		$db = new DB_connection();
	
		$query = "INSERT INTO zahlungsteilnehmer (user_id, zahlung_id, status, anteil, zahlungsempfaenger_id) VALUES (
				'".$this->getUser()->getU_id()."',
				'".$this->getZahlung()->getZ_id()."',
				'".$this->status."',
				'".$this->anteil."',
				'".$this->getZahlungsempfaenger()->getU_id()."'
				)" ;
	
		$result = $db->execute($query);
	
		return $result;
	}
	
	public function laden ($z_id, $u_id) {
	
		// Datenbankstatement erzeugen
		$dbStmt = new DB_connection();
	
		// DB-Befehl absetzen: alle Basisinformationen des Teams mit der �bergebenen $t_id abfragen
	
		$result=$dbStmt->execute("SELECT * FROM zahlungsteilnehmer 
				WHERE zahlung_id= '".$z_id."'
				AND user_id='".$u_id."';");
		
		// Variable, die speichert, ob das Team geladen werden konnte oder nicht
		$isLoaded=false;
	
		// Ergebnis verarbeiten, falls vorhanden
		if ($row=mysqli_fetch_array($result)) {
			$zahlung_id=$row["zahlung_id"];
			$this->zahlung=new Zahlung();
			$this->zahlung->laden($zahlung_id);
			
			$user_id=$row["user_id"];
			$this->user=new User();
			$this->user->laden($user_id);
			
			
			$this->status=$row["status"];
			$this->anteil=$row["anteil"];
			
			$zahlungsempfänger_id=$row["zahlungsempfänger_id"];
			$this->zahlungsempfänger=new User();
			$this->zahlungsempfänger->laden($zahlungsempfänger_id);
				
				
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