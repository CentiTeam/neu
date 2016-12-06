<?php
namespace Application\Model;

use Zend\Mvc\Controller\AbstractActionController;

class User
{
	public $user_id;
	public $username;
	public $vorname;
	public $nachname;
	public $passwort;
	public $email;
	public $deaktiviert;
	public $systemadmin;
	
	/** Singleton-Instanz der Klasse */
	private static $instance;
	

	// Übernommen aus Tutorial! (Unvollständig) 
	
	public function exchangeArray(array $data)
	{
		$this->id     = !empty($data['id']) ? $data['id'] : null;
		$this->artist = !empty($data['artist']) ? $data['artist'] : null;
		$this->title  = !empty($data['title']) ? $data['title'] : null;
	}
	
	//Übernommen von Webapp!!

	/**
 	* Die Singleton-Instanz der Klasse ist der aktuelle Benutzer der Seite
 	*
 	* @return Singleton-Instanz
 	*/
	public static function getInstance() {

		// Prüft ob die Instanz bereits erstellt wurde
		if (!isset(self::$instance)) {
			// da noch keine Instanz vorhanden ist, wird eine Neue erstellt und gespeichert
			self::$instance = new User();
		}

		return self::$instance;

	}

	public function setAsInstance() {
		self::$instance = $this;
	}

	public function __construct() {
		$this->logout();
	}


	/**
 	* Meldet einen Benutzer an
 	*
 	* @return true, wenn die Anmeldung erfolgreich war, sonst false
	 */
	public function login($username, $passwort) {
		
		// Prüfen, ob Benutzername und Passwort zusammen passen
		$dbStmt = new DBStatement(DBConnection::getInstance());
	 
		$dbStmt->executeQuery("SELECT * FROM user WHERE username=$1 AND passwort=$2;",array($username, $passwort));
	 
		$this->islogedin=false;
		 
		// Falls dies der Fall ist
		$row=$dbStmt->nextRow();
		if ($row)
		{
			//sollen anschlie�end die Daten des Benutzers aus
			// der DB in die entsprechenden Klassenattribute geladen werden
			$this->user_id=$row["user_id"];
			$this->username=$row["username"];
			$this->vorname=$row["vorname"];
			$this->nachname=$row["nachname"];
			$this->passwort=$row["passwort"];
			$this->email=$row["email"];
			$this->deaktiviert=$row["deaktiviert"];
			$this->systemadmin=$row["systemadmin"];
			
			
			// Aktualisieren des Klassenattributs "isLogedin"
			$this->islogedin= true;
		}

	 
		return $this->isLogedIn();
	}


	/**
	 * 	Meldet einen Benutzer ab
 	*
 	* setzt alle Klassenvariablen auf einen Grundzustand zur�ck
 	*/
	public function logout() {
		// alle Klassenattribute zurücksetzen
		$this->islogedin = false;
	 
		$this->user_id = NULL;
		$this->username = "";
		$this->vorname = "";
		$this->nachname = "";
		$this->passwort = "";
		$this->email = "";
		$this->deaktiviert = 0; // oder sollte es NULL heißen??
		$this->systemadmin = 0; 
		

	}


	/**
 	* Lädt eine Liste aller User und gibt diese als Array zurück
 	*/
	public static function listeHolen() {

		// Liste initialisieren
		$userListe = array ();
	
		// Datenbankstatement erzeugen
		$dbStmt = new DBStatement(DBConnection::getInstance());

		// DB-Befehl absetzen: ID's aller User in der DB laden
		$dbStmt->executeQuery("SELECT user_id FROM user;");

		// Ergebnis Zeile für Zeile verarbeiten
		while ($row = $dbStmt->nextRow()) {
			
			// neues Model erzeugen
			$model = new User();
			
			// Model anhand der Nummer aus der Datenbankabfrage laden
			$model->laden($row["user_id"]);
			
			// neues Model ans Ende des $liste-Arrays anfügen
			$userListe[] = $model;
		}

		// fertige Liste von Mitarbeiter-Objekten zurückgeben
		return $userListe;
	}

}
