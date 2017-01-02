<?php
namespace Application\Model;


use Application\Model\DB_connection;

class Kategorie {
	protected  $k_id;
	protected  $kategoriebeschreibung;
	
	public function __construct($kategorie_id = null) {
		
		$this->k_id= $kategorie_id;
		echo "$this->k_id";
			
	}
	
	
	public function anlegen () {
	
		$db = new DB_connection();
	
		$query = "INSERT INTO kategorie (kategoriebeschreibung) VALUES (
					
				'".$this->kategoriebeschreibung."'
				)" ;
	
		$result = $db->execute($query);
	
		return $result;
	}
	
	
	public function bearbeiten () {
	
		$db = new DB_connection();
	
		$query = "UPDATE kategorie SET
				kategoriebeschreibung = '".$this->kategoriebeschreibung."'
				 
						";
	
		$result = $db->execute($query);
	
		return $result;
	}
	
	
	public static function listeHolen() {
	
		// Liste initialisieren
		$kategorieListe = array ();
	
		$db = new DB_connection();
	
		$query="SELECT k_id FROM kategorie";
	
		// Wenn die Datenbankabfrage erfolgreich ausgeführt worden ist
		if ($result = $db->execute($query)) {
	
			// Ergebnis Zeile fï¿½r Zeile verarbeiten
			while ($row = mysqli_fetch_array($result)) {
					
				// neues Model erzeugen
				$model = new Kategorie();
	
				// Model anhand der Nummer aus der Datenbankabfrage laden
				$model->laden($row["k_id"]);
	
				// neues Model ans Ende des $userListe-Arrays anfï¿½gen
				$kategorieListe[] = $model;
			}
	
			// fertige Liste von User-Objekten zurï¿½ckgeben
			return $kategorieListe;
		}
	}
	
	
	
	/**
	 * Lï¿½dt eine Kategorie
	 *
	 * @return true, wenn die Kategorie geladen werden konnte, sonst false
	 */
	public function laden ($k_id) {
	
		// Datenbankstatement erzeugen
		$dbStmt = new DB_connection();
	
		// DB-Befehl absetzen: alle Basisinformationen des Teams mit der ï¿½bergebenen $k_id abfragen
	
		$result=$dbStmt->execute("SELECT * FROM kategorie WHERE k_id= '".$k_id."';");
	
		// Variable, die speichert, ob die Kategorie geladen werden konnte oder nicht
		$isLoaded=false;
	
		// Ergebnis verarbeiten, falls vorhanden
		if ($row=mysqli_fetch_array($result)) {
			$this->k_id=$row["k_id"];
			$this->kategoriebeschreibung=$row["kategoriebeschreibung"];
			
	
			// speichern, dass die Kategorien erfolgreich geladen werden konnten
			$isLoaded=true;
		}
	
		// zurï¿½ckgeben, ob beim Laden ein Fehler aufgetreten ist
		return $isLoaded;
	}
	
	
	
	
	
	
	
	
	
	
	
	
	// Getter und Setter
	
	public function getK_id () {
		return $this->k_id;
	}
	
	public function setK_id($k_id) {
		$this->k_id= $k_id;
	}

	public function getKategoriebeschreibung () {
		return $this->kategoriebeschreibung;
	}
	
	public function setKategoriebeschreibung($kategoriebeschreibung) {
		$this->kategoriebeschreibung= $kategoriebeschreibung;
	}
	
	
}