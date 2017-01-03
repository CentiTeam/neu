<?php
namespace Application\Model;


use Application\Model\DB_connection;

class Gruppenmitglied {
	protected  $u_id;
	protected  $g_id;
	protected  $gruppenadmin;
	
	public function __construct($user_id = null, $gruppen_id = null) {
		
		$this->u_id = $user_id;
		$this->g_id= $gruppen_id;
	}
	
	
	public function anlegen () {
		
		$db = new DB_connection();
		
		$query = "INSERT INTO gruppenmitglied (u_id, g_id, gruppenadmin) VALUES (
				'".$this->u_id."',
				'".$this->g_id."',
				'".$this->gruppenadmin."'
				)";
		
		$result = $db->execute($query);
		
		return $result;
	}
	
	public function bearbeiten ($admin) {
		
	}
		
	
	public static function listeHolen() {
	
		// Liste initialisieren
		$gruppenmitgliedListe = array ();
	
		$db = new DB_connection();
	
		$query="SELECT g_id FROM gruppenmitglied";
	
		// Wenn die Datenbankabfrage erfolgreich ausgeführt worden ist
		if ($result = $db->execute($query)) {
	
			// Ergebnis Zeile fï¿½r Zeile verarbeiten
			while ($row = mysqli_fetch_array($result)) {
					
				// neues Model erzeugen
				$model = new Gruppenmitglied();
	
				// Model anhand der Nummer aus der Datenbankabfrage laden
				$model->laden($row["g_id"]);
	
				// neues Model ans Ende des $gruppeListe-Arrays anfï¿½gen
				$gruppenmitgliedListe[] = $model;
			}
	
			// fertige Liste von Gruppe-Objekten zurï¿½ckgeben
			return $gruppenmitgliedListe;
		}
	}
	
	
	// Getter und Setter
	
	public function getU_id () {
		return $this->u_id;
	}
	
	public function setU_Id($u_id) {
		$this->u_id= $u_id;
	}
	
	public function getG_id () {
		return $this->g_id;
	}
	
	public function setG_Id($g_id) {
		$this->g_id= $g_id;
	}
	
	public function getGruppenadmin () {
		return $this->gruppenadmin;
	}
	
	public function setGruppenadmin($gruppenadmin) {
		$this->gruppenadmin= $gruppenadmin;
	}
	
}