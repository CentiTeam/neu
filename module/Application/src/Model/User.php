<?php
namespace Application\Model;


use Application\Model\DB_connection;

class User extends DataObject implements Serializable
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
	
	
	/**
	 * PDO Datenbankverbindung
	 * @param PDO
	 */
	protected $dbHandle = null;
	
	/**
	 * Erzeugt ein neues User Datenobjekt
	 *
	 * @param PDO     $dbHandle  PDO Datenbankverbindung
	 * @param integer $userID    ID eines Benutzerdatensatzes
	 * @param string  $username  Benutzername
	 */
	public function __construct(PDO $dbHandle, $userID = null, $username = null) {
		$this->dbHandle = $dbHandle;
	
		// Datenbankabfrage anhand User ID oder Benutzername
		try {
			$sql = "SELECT * FROM User WHERE '".$username."';";
			$stmt = $this->dbHandle->prepare($sql);
			$stmt->execute();
			$row = $stmt->fetch();
		} catch (PDOException $e) {
			throw new SystemException();
		}
	
		parent::__construct($row);
	}
	
	/**
	 * @see Serializable::serialize()
	 */
	public function serialize() {
		return serialize($this->data);
	}
	
	/**
	 * @see Serializable::unserialize()
	 */
	public function unserialize($serializedData) {
		$this->data = unserialize($serializedData);
	}
	
	
	
	
	

	
	public function login($username, $passwort) {
		
		echo "TEst";
		
		$db = new DB_connection;
		
		//Query, um alle Daten des Benutzers, dessen Benutzername eingegeben wurde aus der Datenbank zu holen
		$query_benutzerdaten = "SELECT * FROM User WHERE username = '".$username."' AND passwort = '".$passwort."';";
		
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
				echo "Erfolgreich angemeldet_USerKlasse";
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
				
				var_dump($this->vorname);
				
				$this->isloggedin = true;
			}
			else{
				echo "Benutzername oder Passwort falsch, oder Benutzerkonto deaktiviert!";
			}
		
		}
		
		return $this->isloggedin;
		
	}
	
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
	
	*/
	
	//Setter für vorname
	public function setVorname($value) {
		$this->vorname = $value;
	}
	//Getter für vorname
	public function getVorname() {
		return $this->vorname;
	}
	
	// Getter für die EIgenschaft isloggedin
	public function isloggedin() {
		return $this->isloggedin;
	}

}
