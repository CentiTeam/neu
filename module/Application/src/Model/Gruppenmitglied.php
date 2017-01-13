<?php
namespace Application\Model;


use Application\Model\DB_connection;
use Application\Model\User;
use Application\Model\Gruppe;

class Gruppenmitglied {
	protected  $user;
	protected  $gruppe;
	protected  $gruppenadmin;
	
	public function __construct($user_id = null, $gruppen_id = null) {
		
		$this->user = $user_id;
		$this->gruppe= $gruppen_id;
	}
	
	
	public function anlegen () { 
		
		$db = new DB_connection();
		
		echo "Versuch 1";
		var_dump($this->user);
		
		echo "Versuch 2";
		var_dump($this->getUser()->getU_id());
		
		$query = "INSERT INTO gruppenmitglied (u_id, g_id, gruppenadmin) VALUES (
				'".$this->getUser()->getU_id()."',
				'".$this->getGruppe()->getG_id()."',
				'".$this->gruppenadmin."'
				)";
		
		$result = $db->execute($query);

		return $result;
	}
	
	public function bearbeiten ($admin) {
		
		$db = new DB_connection();
		
		$query = "UPDATE gruppenmitglied SET
				gruppenadmin = '".$admin."',
				WHERE g_id = '".$this->getGruppe->getG_id()."' AND u_id='".$this->getUser->getU_id()."'
				";
		
		$result = $db->execute($query);
		
		return $result;
	}
		
	public function laden ($g_id, $u_id) {
	
		// Datenbankstatement erzeugen
		$dbStmt = new DB_connection();
	
		// DB-Befehl absetzen: alle Basisinformationen des Teams mit der �bergebenen $t_id abfragen
		$result=$dbStmt->execute("SELECT * FROM gruppenmitglied WHERE g_id= '".$g_id."' AND u_id= '".$u_id."';");

		// Variable, die speichert, ob das Team geladen werden konnte oder nicht
		$isLoaded=false;
	
		// Ergebnis verarbeiten, falls vorhanden
		if ($row=mysqli_fetch_array($result)) {

			$gruppen_id=$row["g_id"];
			$this->gruppe=new Gruppe();
			$this->gruppe->laden($gruppen_id);
			
			$user_id=$row["u_id"];
			$this->user=new User();
			$this->user->laden($user_id);
			
			$this->gruppenadmin=$row["gruppenadmin"];
		
			// speichern, dass die Basisinformationen des Teams erfolgreich geladen werden konnten
			$isLoaded=true;
		}
	
		// zur�ckgeben, ob beim Laden ein Fehler aufgetreten ist
		return $isLoaded;
	}
	
	
	public static function gruppenmitgliederlisteHolen($gruppen_id) {
	
		// Liste initialisieren
		$gruppenmitgliedListe = array ();
	
		$db = new DB_connection();
	
		$query="SELECT g_id, u_id FROM gruppenmitglied WHERE g_id='".$gruppen_id."'";
	
		// Wenn die Datenbankabfrage erfolgreich ausgef�hrt worden ist
		if ($result = $db->execute($query)) {
	
			// Ergebnis Zeile f�r Zeile verarbeiten
			while ($row = mysqli_fetch_array($result)) {
					
				// neues Model erzeugen
				$model = new Gruppenmitglied();
	
				// Model anhand der Nummer aus der Datenbankabfrage laden
				$model->laden($row["g_id"], $row["u_id"]);
	
				// neues Model ans Ende des $gruppeListe-Arrays anf�gen
				$gruppenmitgliedListe[] = $model;
			}
	
			// fertige Liste von Gruppe-Objekten zur�ckgeben
			return $gruppenmitgliedListe;
		}
	}
	
	
	
	
	public static function eigenelisteHolen($user_id) {
	
		// Liste initialisieren
		$gruppenmitgliedListe = array ();
	
		$db = new DB_connection();
	
		$query="SELECT g_id, u_id FROM gruppenmitglied WHERE u_id='".$user_id."'";
	
		// Wenn die Datenbankabfrage erfolgreich ausgef�hrt worden ist
		if ($result = $db->execute($query)) {
	
			// Ergebnis Zeile f�r Zeile verarbeiten
			while ($row = mysqli_fetch_array($result)) {
					
				// neues Model erzeugen
				$model = new Gruppenmitglied();
	
				// Model anhand der Nummer aus der Datenbankabfrage laden
				$model->laden($row["g_id"], $row["u_id"]);
	
				// neues Model ans Ende des $gruppeListe-Arrays anf�gen
				$gruppenmitgliedListe[] = $model;
			}
	
			// fertige Liste von Gruppe-Objekten zur�ckgeben
			return $gruppenmitgliedListe;
		}
	}
	
	
	
	public static function listeHolen() {
	
		// Liste initialisieren
		$gruppenmitgliedListe = array ();
	
		$db = new DB_connection();
	
		$query="SELECT g_id, u_id FROM gruppenmitglied";
	
		// Wenn die Datenbankabfrage erfolgreich ausgef�hrt worden ist
		if ($result = $db->execute($query)) {
	
			// Ergebnis Zeile f�r Zeile verarbeiten
			while ($row = mysqli_fetch_array($result)) {
					
				// neues Model erzeugen
				$model = new Gruppenmitglied();
	
				// Model anhand der Nummer aus der Datenbankabfrage laden
				$model->laden($row["g_id"], $row["u_id"]);
	
				// neues Model ans Ende des $gruppeListe-Arrays anf�gen
				$gruppenmitgliedListe[] = $model;
			}
	
			// fertige Liste von Gruppe-Objekten zur�ckgeben
			return $gruppenmitgliedListe;
		}
	}
	
	
	// Getter und Setter
	
	public function getUser () {
		return $this->user;
	}
	
	public function setUser($user) {
		$this->user= $user;
	}
	
	public function getGruppe () {
		return $this->gruppe;
	}
	
	public function setGruppe($gruppe) {
		$this->gruppe= $gruppe;
	}
	
	public function getGruppenadmin () {
		return $this->gruppenadmin;
	}
	
	public function setGruppenadmin($gruppenadmin) {
		$this->gruppenadmin= $gruppenadmin;
	}
	
}