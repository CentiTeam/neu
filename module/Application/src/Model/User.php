<?php
namespace Application\Model;

use Zend\Mvc\Controller\AbstractActionController;

class User
{
	public $u_id;
	public $username;
	public $vorname;
	public $nachname;
	public $passwort;
	public $email;
	public $deaktiviert;
	public $systemadmin;
	
/**
	public function logout() {
		$this->u_id=NULL;
		$this->username="";
		$this->vorname="";
		$this->nachname="";
		$this->passwort="";
		$this->email="";
		$this->deaktiviert="";
		$this->systemadmin="";
	}
*/


	//Übernommen von Webapp!!

	/**
 	* Lädt eine Liste aller User und gibt diese als Array zurück
 	*/
	
	/**
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
	
	//Setter für vorname
	public function setVorname($value) {
		$this->vorname = $value;
	}
	//Getter für vorname
	public function getVorname() {
		return $this->vorname;
	}
	
	*/

}
