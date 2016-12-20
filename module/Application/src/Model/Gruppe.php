<?php
namespace Application\Model;


use Application\Model\DB_connection;
use Application\Model\Zend_Db_Statement_Mysqli;

class Gruppe {
	protected  $g_id;
	protected  $gruppenname;
	protected  $gruppenbeschreibung;
	protected  $gruppenbildpfad;
	
	public function __construct() {
		$this->g_id= 50;
		
	}
	
	public function anlegen () {
		die ("In gruppenklasse angekommen!!!");
		
		
		$db = new DB_connection();
		
		var_dump($this->gruppenname);
		
		
		$query = "INSERT INTO gruppe (gruppenname, gruppenbeschreibung, gruppenbildpfad) VALUES (
				'".$this->gruppenname."', 
				'".$this->gruppenbeschreibung."',
				'".$this->gruppenbildpfad."')" ;

		// $DBstmt = new Zend_Db_Statement_Mysqli($db, $query);
		
		$result = $db->execute($query);
		
		var_dump($this->gruppenname);
		
		
		// GENAUE SYNTAX FEHLT!!
		$isOK = mysqli_affected_rows ($result) > 0;
		
		return $isOK;
		
	}
	
	public function loeschen () {
		
	}
	
	public static function listeHolen() {
	
		// Liste initialisieren
		$gruppeListe = array ();
	
		// Datenbankstatement erzeugen
		$dbStmt = new DB_connection();
	
		// DB-Befehl absetzen: ID's aller Gruppen in der DB laden
		$dbStmt->execute("SELECT g_id FROM gruppe;");
	
		// Ergebnis Zeile f�r Zeile verarbeiten
		while ($row = $dbStmt->fetchAll()) {
				
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
	
	/**
	 * L�dt eine Gruppe
	 *
	 * @return true, wenn die Gruppe geladen werden konnte, sonst false
	 */
	public function laden ($g_id) {
	
		// Datenbankstatement erzeugen
		$dbStmt = new DB_connection();
	
		// DB-Befehl absetzen: alle Basisinformationen des Teams mit der �bergebenen $t_id abfragen
		$dbStmt->execute("SELECT * FROM team WHERE g_id=$1;", array($g_id));
	
		// Variable, die speichert, ob das Team geladen werden konnte oder nicht
		$isLoaded=false;
		
		// Ergebnis verarbeiten, falls vorhanden
		if ($row=$dbStmt->nextRowset()) {
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
	
	public function setG_Id() {
		$this->g_id= $g_id;
	}
	
	public function getGruppenname () {
		return $this->gruppenname;
	}
	
	public function setGruppenname() {
		$this->gruppenname= $gruppenname;
	}
	
	public function getGruppenbeschreibung () {
		return $this->gruppenbeschreibung;
	}
	
	public function setGruppenbeschreibung() {
		$this->gruppenbeschreibung= $gruppenbeschreibung;
	}
	
	public function getGruppenbildpfad () {
		return $this->gruppenbildpfad;
	}
	
	public function setGruppenbildpfad() {
		$this->gruppenbildpfad= $gruppenbildpfad;
	}
	
	
}