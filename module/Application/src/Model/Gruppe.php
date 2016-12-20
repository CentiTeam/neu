<?php
namespace Application\Model;


use Application\Model\DB_connection;
use Application\Model\Zend_Db_Statement_Mysqli;

class Gruppe {
	protected  $g_id;
	protected  $gruppenname;
	protected  $gruppenbeschreibung;
	protected  $gruppenbildpfad;
	
	public function __construct($gruppen_id = null) {
		
		$this->g_id= $gruppen_id;
		echo "$this->g_id";
		
		
	}
	
	public function anlegen () {
		
		$db = new DB_connection();
		
		$query = "INSERT INTO gruppe (gruppenname, gruppenbeschreibung, gruppenbildpfad) VALUES (
				'".$this->gruppenname."', 
				'".$this->gruppenbeschreibung."',
				'".$this->gruppenbildpfad."')" ;
		
		$result = $db->execute($query);
		
		
		// GENAUE SYNTAX FEHLT!!
		// $isOK = mysqli_affected_rows ($result) > 0;
		
		
		return $result;
	}
	
	public function loeschen () {
		
	}
	/**
	public static function listeHolen() {
	
		// Liste initialisieren
		$gruppeListe = array ();
	
		// Datenbankstatement erzeugen
		$db = new DB_connection();
	
		// DB-Befehl absetzen: ID's aller Gruppen in der DB laden
		//$dbStmt->execute("SELECT g_id FROM gruppe;");
		
		$abfrage="SELECT g_id FROM gruppe";
		
		if ($result=mysqli_query($db, $abfrage)) {
	
		// Ergebnis Zeile f�r Zeile verarbeiten
		while ($row = mysqli_fetch_row()) {
				
			// neues Model erzeugen
			$model = new Gruppe();
				
			// Model anhand der Nummer aus der Datenbankabfrage laden
			$model->laden($row["g_id"]);
				
			// neues Model ans Ende des $gruppeListe-Arrays anf�gen
			$gruppeListe[] = $model;
			
			var_dump($gruppeListe->getGruppenname());
		}
	
		// fertige Liste von Gruppe-Objekten zur�ckgeben
		return $gruppeListe;
		}
	}
	*/
	
	/**
	 * L�dt eine Gruppe
	 *
	 * @return true, wenn die Gruppe geladen werden konnte, sonst false
	 */
	public function laden ($g_id) {
	
		// Datenbankstatement erzeugen
		$dbStmt = new DB_connection();
	
		// DB-Befehl absetzen: alle Basisinformationen des Teams mit der �bergebenen $t_id abfragen
		$dbStmt->execute("SELECT * FROM gruppe WHERE g_id=$1;", array($g_id));
	
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