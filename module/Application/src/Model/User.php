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
	protected $teilnehmerbildpfad;
	protected $pwcode;
	
	private $isloggedin;
	

	public function __construct($user_id = null) {
		$this->username = $user_id;
	}
		
		// Registrieren eines neuen Users
		public function registrieren ($username, $passwort, $email, $vorname, $nachname, $pwcode) 
		{
			//Aufbau einer Datenbankverbindung
			$db = new DB_connection;
			
			//Erstellen und Ausführen einer Query zum Überprüfen, ob die eingegebene E-Mailadresse bereits verwendet wird
			$query_emailueberpruefung = "SELECT * FROM User WHERE email ='".$this->email."';";
			$result_emailueberpruefung = $db->execute($query_emailueberpruefung);
			
			//Erstellen und Ausführen einer Query zum Überprüfen, ob der eingegebene Benutzername bereits verwendet wird
			$query_benutzernamenueberpruefung = "SELECT * FROM User WHERE username ='".$this->username."';";
			$result_benutzernamenueberpruefung = $db->execute($query_benutzernamenueberpruefung);
			
		
			
			//Falls E-Mailadresse noch nicht verwendet wird, dann Schreiben der Daten in die Datenbank.
			if(mysqli_num_rows($result_emailueberpruefung) == 0 && mysqli_num_rows($result_benutzernamenueberpruefung) == 0){ 
			
			$query = "INSERT INTO User (username, vorname, nachname, passwort, email, deaktiviert, systemadmin, teilnehmerbildpfad, pwcode) VALUES (
			
				'".$this->username."',
				'".$this->vorname."',
				'".$this->nachname."',
				'".$this->passwort."',
				'".$this->email."',
					1,
					0,
				'/img/anonymeruser.png',
				'".$this->pwcode."'		
				)";
		
			$result = $db->execute($query);
			$isOK = mysqli_affected_rows ($result) > 0;
			return $isOK;
		}
		else{

			echo "<center><h4>Benutzername oder E-Mailadresse bereits verwendet! Bitte erneut registrieren</h4></center>";
			
		}
		}
		
		
		// Methode für das Zurücksetzen des Passwortes
		public function passwordreset ($passwort, $u_id) {
			
			$db = new DB_connection;
			$query = "UPDATE User SET passwort = '".$this->passwort."'
					WHERE u_id = '".$this->u_id."';";
	
			$result = $db->execute($query);
	
			return $result;
				
		}
		
		
		// Methode beim Vergessen des Passwortes
		public function passwortvergessen ($email, $passwort) {
			
			$db = new DB_connection;
			$query = "UPDATE User SET passwort = '".$this->passwort."'
					WHERE email = '".$this->email."'
					";
			$result = $db->execute($query);
			return $result;
		}
		
		// Methode zur Email-Bestätigung bei der Registrierung
		public function confirm ($email) {
			
			$db = new DB_connection;
			$query = "UPDATE User SET deaktiviert = 0
					WHERE email = '".$this->email."'
							";
			
			$result = $db->execute($query);
			return $result;
		}
		
	
		// Login eines Users
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
				$this->teilnehmerbildpfad = $row['teilnehmerbildpfad'];


				//Wenn es sich um einen Systemadmin handelt, dann wird die Sessionvariable "angemeldet", welche für angemeldeter Benutzer steht, mit nein überschrieben und die Sessionvariable "systemadmin" erhält den Wert ja
				if($row['systemadmin'] == 1){
					$_SESSION['systemadmin'] = "ja";
					$_SESSION['angemeldet'] = "nein";
					//echo "test";
				}
				
				
				$this->isloggedin = true;
			}
		
		}
		
		return $this->isloggedin;
		
	}
	
	// Liste aller User holen
	public static function listeHolen() {
	
		// Liste initialisieren
		$userListe = array ();
	
		$db = new DB_connection();
	
		$query="SELECT u_id FROM User WHERE systemadmin = 0;";
		
		// Wenn die Datenbankabfrage erfolgreich ausgeführt worden ist
		if ($result = $db->execute($query)) {
	
			// Ergebnis Zeile fï¿½r Zeile verarbeiten
			while ($row = mysqli_fetch_array($result)) {
					
				// neues Model erzeugen
				$model = new User();
	
				// Model anhand der Nummer aus der Datenbankabfrage laden
				$model->laden($row["u_id"]);
	
				// neues Model ans Ende des $userListe-Arrays anfï¿½gen
				$userListe[] = $model;
			}
	
			// fertige Liste von User-Objekten zurï¿½ckgeben
			return $userListe;
		}
	}
	
	
	// Nur die nicht deaktivierten Gruppenmitglieder als Liste zurueckgeben
	public static function gruppenmitgliederlisteHolen($gruppen_id) {
	
		// Liste initialisieren
		$userListe = array ();
	
		$db = new DB_connection();
	
		$query="SELECT * FROM `User`
				LEFT JOIN gruppenmitglied ON (User.u_id=gruppenmitglied.u_id)
				WHERE gruppenmitglied.g_id= '".$gruppen_id."' 
				AND deaktiviert = 0
				ORDER BY vorname ASC
				";
	
		// Wenn die Datenbankabfrage erfolgreich ausgeführt worden ist
		if ($result = $db->execute($query)) {
	
			// Ergebnis Zeile fï¿½r Zeile verarbeiten
			while ($row = mysqli_fetch_array($result)) {
					
				// neues Model erzeugen
				$model = new User();
	
				// Model anhand der Nummer aus der Datenbankabfrage laden
				$model->laden($row["u_id"]);
	
				// neues Model ans Ende des $gruppeListe-Arrays anfï¿½gen
				$userListe[] = $model;
			}
	
			// fertige Liste von Gruppe-Objekten zurï¿½ckgeben
			return $userListe; 
		}
	}
	
	
	// Liste aller User holen, die den Suchkriterien entsprechen (like Username, Vorname, Nachname, E-Mail)
	public static function suchlisteHolen($suche) {
	
		// Liste initialisieren
		$suchuserListe = array ();
	
		$db = new DB_connection();
		 
	
		$query="SELECT u_id FROM User WHERE systemadmin = 0 
				AND username LIKE '%$suche%' 
				OR vorname LIKE '%$suche%'
				OR nachname LIKE '%$suche%'
				OR email LIKE '%$suche%'
				;"; 
		
		// Wenn die Datenbankabfrage erfolgreich ausgeführt worden ist
		if ($result = $db->execute($query)) {
	
			// Ergebnis Zeile fï¿½r Zeile verarbeiten
			while ($row = mysqli_fetch_array($result)) {
					
				// neues Model erzeugen
				$model = new User();
	
				// Model anhand der Nummer aus der Datenbankabfrage laden
				$model->laden($row["u_id"]);
	
				// neues Model ans Ende des $userListe-Arrays anfï¿½gen
				$suchuserListe[] = $model;
			}
	
			// fertige Liste von User-Objekten zurï¿½ckgeben
			return $suchuserListe;
		}
	}
	
	// Liste aller User holen, die den Suchkriterien entsprechen (like Username, Vorname, Nachname, E-Mail)
	// Nur diejenigen User, die noch nicht in der Gruppe $gruppen_id vorhanden sind
	public static function gruppensuchlisteHolen($suche, $gruppen_id) {
	
		// Liste initialisieren
		$gruppensuchuserListe = array ();
	
		$db = new DB_connection();
		
		// Alle User, die nicth Systemadmin sind, die nicht bereits Mitglied in der Gruppe sind und den Kriterien der Suche entsprechen
		$query ="SELECT * FROM User WHERE systemadmin = 0 
					AND deaktiviert = 0
					AND (username LIKE '%$suche%'
						OR vorname LIKE '%$suche%'
						OR nachname LIKE '%$suche%'
						OR email LIKE '%$suche%')
    	  			AND u_id NOT IN 
						(SELECT u_id FROM User 
							LEFT JOIN gruppenmitglied USING (u_id)
							LEFT JOIN gruppe USING (g_id)
								WHERE gruppenmitglied.g_id = '".$gruppen_id."'
							GROUP BY u_id)
				;";

		// Wenn die Datenbankabfrage erfolgreich ausgeführt worden ist
		if ($result = $db->execute($query)) {
	
			// Ergebnis Zeile fï¿½r Zeile verarbeiten
			while ($row = mysqli_fetch_array($result)) {
					
				// neues Model erzeugen
				$model = new User();
	
				// Model anhand der Nummer aus der Datenbankabfrage laden
				$model->laden($row["u_id"]);
	
				// neues Model ans Ende des $userListe-Arrays anfï¿½gen
				$gruppensuchuserListe[] = $model;
			}
	
			// fertige Liste von User-Objekten zurï¿½ckgeben
			return $gruppensuchuserListe;
		}
	}
	
	
	/**
	 * Lï¿½dt einen User
	 *
	 * @return true, wenn der User geladen werden konnte, sonst false
	 */
	public function laden ($u_id) {
	
		// Datenbankstatement erzeugen
		$dbStmt = new DB_connection();
	
		// DB-Befehl absetzen: alle Basisinformationen des Teams mit der ï¿½bergebenen $t_id abfragen
	
		$result=$dbStmt->execute("SELECT * FROM User WHERE u_id= '".$u_id."';");
	
		// Variable, die speichert, ob das Team geladen werden konnte oder nicht
		$isLoaded=false;
	
		// Ergebnis verarbeiten, falls vorhanden
		if ($row=mysqli_fetch_array($result)) {
			$this->u_id=$row["u_id"];
			$this->username=$row["username"];
			$this->vorname=$row["vorname"];
			$this->nachname=$row["nachname"];
			$this->passwort=$row["passwort"];
			$this->email=$row["email"];
			$this->nachname=$row["nachname"];
			$this->deaktiviert=$row["deaktiviert"];
			$this->systemadmin=$row["systemadmin"];
			$this->teilnehmerbildpfad=$row["teilnehmerbildpfad"];
			$this->pwcode=$row["pwcode"];
	
			// speichern, dass die Basisinformationen der User erfolgreich geladen werden konnten
			$isLoaded=true;
		}
	
		// zurï¿½ckgeben, ob beim Laden ein Fehler aufgetreten ist
		return $isLoaded;
	}
	
	
	//Methode um Benutzer in Datenbank und im Objekt selbst zu deaktivieren
	public function deaktivieren(){
		
		$this->deaktiviert = 1;
		
		$db = new DB_connection();
		
		$query = "UPDATE User SET
				deaktiviert = '".$this->deaktiviert."'
				WHERE u_id = '".$this->u_id."'
			
						";
		
		$result = $db->execute($query);
		
		return $result;
	
	}
	
	//Methode um Benutzer in Datenbank und im Objekt selbst zu reaktivieren
	public function reaktivieren(){
	
		$this->deaktiviert = 0;
	
		$db = new DB_connection();
	
		$query = "UPDATE User SET
				deaktiviert = '".$this->deaktiviert."'
				WHERE u_id = '".$this->u_id."'
		
						";
	
		$result = $db->execute($query);
	
		return $result;
	
	}
	
	// Profilbild für einen User setzen
	public static function bild($path, $u_id) {
	
		// Datenbankstatement erzeugen
		$db = new DB_connection();
	
		$query = "UPDATE User SET
				teilnehmerbildpfad = '".$path."'
				WHERE u_id = '".$u_id."'
				";
	
		$result = $db->execute($query);
	
		return $result;
	}
	
	

	//Setter für u_id
	public function setU_id($value) {
		$this->u_id = $value;
	}
	//Getter für u_id
	public function getU_id() {
		return $this->u_id;
	}
	
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
	
	public function getAltespasswort() {
		return $this->altespasswort;
	}
	
	public function setAltespasswort($value) {
		$this->altespasswort = $value;
	}
	public function setEmail($value) {
		$this->email = $value;
	}
	
	public function getEmail() {
		return $this->email;
	}
	
	public function setEmailwdh($value) {
		$this->emailwdh = $value;
	}
	
	public function getEmailwdh() {
		return $this->emailwdh;
	}
	
	
	public function setPwcode($value) {
		$this->pwcode = $value;
	}
	//Getter für pw code
	public function getPwcode() {
		return $this->pwcode;
	}
	// Getter für die EIgenschaft isloggedin
	public function isloggedin() {
		return $this->isloggedin;
	}


	public function setDeaktiviert($value) {
		$this->deaktiviert = $value;
	}
	public function getDeaktiviert() {
		return $this->deaktiviert;
	}	

	public function setSystemadmin($value) {
		$this->systemadmin = $value;
	}
	public function getSystemadmin() {
		return $this->systemadmin;
	}
	
	public function getTeilnehmerbildpfad () {
		return $this->teilnehmerbildpfad;
	}
	
	public function setTeilnehmerbildpfad($teilnehmerbildpfad) {
		$this->teilnehmerbildpfad= $teilnehmerbildpfad;
	}
}


