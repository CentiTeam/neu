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
			$query_emailueberpruefung = "SELECT * FROM User WHERE email ='".$this->email."';";
			$result_emailueberpruefung = $db->execute($query_emailueberpruefung);
			
			//Erstellen und Ausführen einer Query zum Überprüfen, ob der eingegebene Benutzername bereits verwendet wird
			$query_benutzernamenueberpruefung = "SELECT * FROM User WHERE username ='".$this->username."';";
			$result_benutzernamenueberpruefung = $db->execute($query_benutzernamenueberpruefung);
			
			
			
			//Falls E-Mailadresse noch nicht verwendet wird, dann Schreiben der Daten in die Datenbank.
			if(mysqli_num_rows($result_emailueberpruefung) == 0 && mysqli_num_rows($result_benutzernamenueberpruefung) == 0){ 
			
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

			echo "<center><h4>Benutzername oder E-Mailadresse bereits verwendet! Bitte erneut registrieren</h4></center>";
			
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
	


	// TODO: Userliste holen ! funktioniert noch nicht
	
	
	public static function listeHolen() {
		
		$db = new DB_connection;
		$userliste = array ();
		$zaehler = 0;
		
		$query_userliste = "SELECT * FROM User ORDER BY u_id;";
		$result= $db->execute($query_userliste);
		
		
		while ($row = mysqli_fetch_array($result))
		{
			
			$_array[$zahler]= $row ['u_id'];
			 
			echo "<tr>";
			echo "<td>". 	$row['u_id'] 			. "</td>";
			echo "<td>". 	$row['username'] 		. "</td>";
			echo "<td>". 	$row['vorname'] 		. "</td>";
			echo "<td>". 	$row['nachname'] 		. "</td>";
			echo "<td>". 	$row['passwort']		. "</td>";
			echo "<td>". 	$row['email'] 			. "</td>";
			echo "<td>". 	$row['deaktiviert']		. "</td>";
			echo "<td>". 	$row['systemadmin']		. "</td>";
			echo "<br>";
			$zaehler++;
		}
			
		
		
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
