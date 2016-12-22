<?php
namespace Application\Model;


use Application\Model\DB_connection;

class User
{
	protected $u_id;
	protected $username;
	protected $vorname;
	protected $nachname;
	protected $passwort;
	protected $email;
	protected $deaktiviert;
	protected $systemadmin;
	
	private $isloggedin;
	

	public function __construct($user_id = null) {
		$this->username = $user_id;
	}

		public function registrieren ($username, $passwort, $email, $vorname, $nachname) 
		{
			//Aufbau einer Datenbankverbindung
			$db = new DB_connection;
			
			//Erstellen und Ausführen einer Query zum Überprüfen, ob die eingegebene E-Mailadresse bereits verwendet wird
			$query_emailueberpruefung = "SELECT email FROM User WHERE email ='".$email."';";
			$result_emailueberpruefung = $db->execute($query_emailueberpruefung);
			echo $result_emailueberpruefung;
			echo mysqli_num_rows($result_emailueberpruefung);
			
			//Falls E-Mailadresse noch nicht verwendet wird, dann Schreiben der Daten in die Datenbank.
			if(mysqli_num_rows($result_emailueberpruefung) == 0){ 
			
			$query = "INSERT INTO User (username, vorname, nachname, passwort, email, deaktiviert, systemadmin) VALUES (
			
				'".$this->username."',
				'".$this->vorname."',
				'".$this->nachname."',
				'".$this->passwort."',
				'".$this->email."',
					0,
					0)";
		
			$result = $db->execute($query);
			$isOK = mysqli_affected_rows ($result) > 0;
			return $isOK;
		}
		else{
			echo "<center><h4>E-Mailadresse bereits verwendet! Bitte erneut registrieren</h4></center>";
			
		}
		}
	
	
	
		public function login($email, $passwort) {
		
		$db = new DB_connection;
		
		//Query, um alle Daten des Benutzers, dessen Benutzername eingegeben wurde aus der Datenbank zu holen
		$query_benutzerdaten = "SELECT * FROM User WHERE email = '".$email."' AND passwort = '".$passwort."';";
		
		$result= $db->execute($query_benutzerdaten);
		
		$this->isloggedin=false;
		
		//Ausgeben einer Fehlermeldung, falls ein Fehler beim Ausfï¿½hren der Query auftritt und somit $result leer bleibt
		if(!isset($result)){
			echo "Fehler beim Holen der Daten aus der Datenbank";
		}
		//Wenn Werte aus der Datenbank in $result geschrieben wurden, dann wird weitergemacht
		else{
			//Holen der ersten (und hier einzigen, da nur ein Benutzername) Zeile des Ergebnisses
			$row=mysqli_fetch_array($result);
		
			//Prï¿½fen, ob das eingegebene Passwort korrekt ist und der Benutzer aktiviert ist
			if($row['passwort'] == $passwort && $row['deaktiviert']==0){
				echo "Erfolgreich angemeldet über die UserKlasse!";
				//Wenn man angemeldet ist, so wird dies in der Sessionvariable "angemeldet" gespeichert.
				$_SESSION['angemeldet'] = "ja";
				
				$this->u_id = $row['u_id'];
				$this->username = $row['username'];
				$this->vorname = $row['vorname'];
				$this->nachname = $row['nachname'];
				$this->passwort = $row['passwort'];
				$this->email = $row['email'];
				$this->deaktiviert = $row['deaktiviert'];
				$this->systemadmin = $row['systemadmin'];
				
				
				$this->isloggedin = true;
			}
		
		}
		
		return $this->isloggedin;
		
	}
	

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
	
	*/
	
	//Setter für vorname
	public function setVorname($value) {
		$this->vorname = $value;
	}
	//Getter für vorname
	public function getVorname() {
		return $this->vorname;
	}
	
	
	//Setter für nachname
	public function setNachname($value) {
		$this->nachname = $value;
	}
	//Getter für nachname
	public function getNachname() {
		return $this->nachname;
	}
	
	public function setUsername($value) {
		$this->username = $value;
	}
	
	public function getUsername() {
		return $this->username;
	}
	
	
	public function setPasswort($value) {
		$this->passwort = $value;
	}
	public function getPasswortwdh() {
		return $this->passwortwdh;
	}
	
	public function setPasswortwdh($value) {
		$this->passwortwdh = $value;
	}
	public function getPasswort() {
		return $this->passwort;
	}
	
	public function setEmail($value) {
		$this->email = $value;
	}
	
	public function getEmail() {
		return $this->email;
	}
	
	// Getter für die EIgenschaft isloggedin
	public function isloggedin() {
		return $this->isloggedin;
	}

}
