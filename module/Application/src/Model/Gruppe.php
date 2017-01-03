<?php
namespace Application\Model;


use Application\Model\DB_connection;

class Gruppe {
	protected  $g_id;
	protected  $gruppenname;
	protected  $gruppenbeschreibung;
	protected  $gruppenbildpfad;
	
	
	public function __construct($gruppen_id = null) {
		
		$this->g_id= $gruppen_id;
	}
	
	
	public function anlegen () {
		
		$db = new DB_connection();
		
		$query = "INSERT INTO gruppe (gruppenname, gruppenbeschreibung, gruppenbildpfad) VALUES (
				'".$this->gruppenname."', 
				'".$this->gruppenbeschreibung."',
				'".$this->gruppenbildpfad."'
				)" ;
		
		$result = $db->execute($query);
		
		return $result;
	}
	
		
	public function bearbeiten () {
	
		$db = new DB_connection();
		
		$query = "UPDATE gruppe SET
				gruppenname = '".$this->gruppenname."',
				gruppenbeschreibung = '".$this->gruppenbeschreibung."',
				gruppenbildpfad = '".$this->gruppenbildpfad."'
				WHERE g_id = '".$this->g_id."'
				";
		
		$result = $db->execute($query);
	
		return $result;
	}
	
	
	public function loeschen ($gruppe_id) {
		
		// Datenbankstatement erzeugen
		$db = new DB_connection();
		
		// !!!
		// !!!!! Loeschen der verbundenen Daten fehlt noch!!!!!
		// !!!!
		
		// L�schen der Gruppenmitglied-Eintr�ge, die die zu l�schende Gruppe betreffen
		$query_verbundeneDaten1="DELETE FROM gruppenmitglied WHERE g_id='".$gruppe_id."' ";
		$db->execute($query_verbundeneDaten1);
		
		// Abfrage bauen
		$query = "DELETE FROM gruppe WHERE g_id= '".$gruppe_id."' ";
		
		// Loeschen des Gruppe-Datensatzes
		$result = $db->execute($query);
		
		
		// speichert, ob mindestens eine Zeile gel�scht wurde
		// dies ist gleichbedeutet mit der Information, ob eine Gruppe gel�scht wurde
		//$deleted = mysqli_affected_rows() > 0;
		//return $deleted;
		
		// R�ckgabe, ob die Gruppe gel�scht wurde oder nicht
		return $result;
	}
	
	public static function bild($g_id) {
		
		// Datenbankstatement erzeugen
		$db = new DB_connection();
		
		$query = "UPDATE gruppe SET
				gruppenbildpfad = '".$this->gruppenbildpfad."'
				WHERE g_id = '".$g_id."'
				";
		
		$result = $db->execute($query);
		
		return $result;
	}
	
	public static function listeHolen() {
	
		// Liste initialisieren
		$gruppeListe = array ();
	
		$db = new DB_connection();
		
		$query="SELECT g_id FROM gruppe";
		
		// Wenn die Datenbankabfrage erfolgreich ausgef�hrt worden ist
		if ($result = $db->execute($query)) {
		
		// Ergebnis Zeile f�r Zeile verarbeiten
		while ($row = mysqli_fetch_array($result)) {
			
			// neues Model erzeugen
			$model = new Gruppe();
				
			// Model anhand der Nummer aus der Datenbankabfrage laden
			$model->laden($row["g_id"]);
				
			// neues Model ans Ende des $gruppeListe-Arrays anf�gen
			$gruppeListe[] = $model;
		}
		
		// fertige Liste von Gruppe-Objekten zur�ckgeben
		return $gruppeListe;
		}
	}
	
	
	/**
	 * L�dt eine Gruppe
	 *
	 * @return true, wenn die Gruppe geladen werden konnte, sonst false
	 */
	public function laden ($g_id = null) {
	
		// Datenbankstatement erzeugen
		$dbStmt = new DB_connection();
	
		// DB-Befehl absetzen: alle Basisinformationen des Teams mit der �bergebenen $t_id abfragen
		
		
		if ($g_id) {
		$result=$dbStmt->execute("SELECT * FROM gruppe WHERE g_id= '".$g_id."';");
		}
		else {
			$result=$dbStmt->execute("SELECT * FROM gruppe WHERE g_id =(SELECT MAX(g_id) FROM gruppe)");
		}
		// Variable, die speichert, ob das Team geladen werden konnte oder nicht
		$isLoaded=false;
		
		// Ergebnis verarbeiten, falls vorhanden
		if ($row=mysqli_fetch_array($result)) {
			$this->g_id=$row["g_id"];
			$this->gruppenname=$row["gruppenname"];
			$this->gruppenbeschreibung=$row["gruppenbeschreibung"];
			$this->gruppenbildpfad=$row["gruppenbildpfad"];
				
			// speichern, dass die Basisinformationen des Teams erfolgreich geladen werden konnten
			$isLoaded=true;
		}
	
		// zur�ckgeben, ob beim Laden ein Fehler aufgetreten ist
		return $isLoaded;
	}
	
	
	
	// Getter und Setter
	
	public function getG_id () {
		return $this->g_id;
	}
	
	public function setG_Id($g_id) {
		$this->g_id= $g_id;
	}
	
	public function getGruppenname () {
		return $this->gruppenname;
	}
	
	public function setGruppenname($gruppenname) {
		$this->gruppenname= $gruppenname;
	}
	
	public function getGruppenbeschreibung () {
		return $this->gruppenbeschreibung;
	}
	
	public function setGruppenbeschreibung($gruppenbeschreibung) {
		$this->gruppenbeschreibung= $gruppenbeschreibung;
	}
	
	public function getGruppenbildpfad () {
		return $this->gruppenbildpfad;
	}
	
	public function setGruppenbildpfad($gruppenbildpfad) {
		$this->gruppenbildpfad= $gruppenbildpfad;
	}
	
	
}