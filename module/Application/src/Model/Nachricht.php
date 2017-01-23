<?php
namespace Application\Model;


use Application\Model\DB_connection;
use Application\Model\Gruppe;
use Application\Model\User;

class Nachricht {
	protected  $n_id;
	protected  $datum;
	protected  $text;
	protected  $u_id;
	protected  $g_id;
	
	public function __construct($nachricht_id = null) {
		
		$this->n_id= $nachricht_id;
		echo "$this->n_id";
			
	}
	
	
	public function sendMessage($datum, $text, $u_id, $g_id) {
		
		$db = new DB_connection;
		
		
		$query = "INSERT INTO nachricht (datum, text, u_id, g_id) VALUES (
				
				'".$this->datum."',
				'".$this->text."',
				'".$this->u_id."',
				'".$this->g_id."'
				)";
		
		
		
		$result = $db->execute($query);
		return $result;
	}
	
	public function messageboardladen ($n_id = null, $g_id) {
	
		// Datenbankstatement erzeugen
		$dbStmt = new DB_connection();
	
		// DB-Befehl absetzen: alle Basisinformationen des Teams mit der ï¿½bergebenen $t_id abfragen
	
	
		if ($n_id) {
			$result=$dbStmt->execute("SELECT * FROM nachricht WHERE n_id= '".$n_id."' ;");
		}
		else {
			$result=$dbStmt->execute("SELECT * FROM nachricht WHERE n_id =(SELECT MAX(n_id) FROM nachricht)'");
		}
		// Variable, die speichert, ob das Team geladen werden konnte oder nicht
		$isLoaded=false;
	
		// Ergebnis verarbeiten, falls vorhanden
		if ($row=mysqli_fetch_array($result)) {
			$this->n_id=$row["n_id"];
			$this->datum=$row["datum"];
			$this->text=$row["text"];
	
			$user_id=$row["u_id"];
			$this->user=new User();
			$this->user->laden($user_id);
	
			$gruppen_id=$row["g_id"];
			$this->gruppe=new Gruppe();
			$this->gruppe->laden($gruppen_id);
	
			// speichern, dass die Basisinformationen des Teams erfolgreich geladen werden konnten
			$isLoaded=true;
		}
	
		// zurï¿½ckgeben, ob beim Laden ein Fehler aufgetreten ist
		return $isLoaded;
	}
	
	public function laden ($n_id = null) {
	
		// Datenbankstatement erzeugen
		$dbStmt = new DB_connection();
	
		// DB-Befehl absetzen: alle Basisinformationen des Teams mit der ï¿½bergebenen $t_id abfragen
	
	
		if ($n_id) {
			$result=$dbStmt->execute("SELECT * FROM nachricht WHERE n_id= '".$n_id."';");
		}
		else {
			$result=$dbStmt->execute("SELECT * FROM nachricht WHERE n_id =(SELECT MAX(n_id) FROM nachricht);");
		}
		// Variable, die speichert, ob das Team geladen werden konnte oder nicht
		$isLoaded=false;
	
		// Ergebnis verarbeiten, falls vorhanden
		if ($row=mysqli_fetch_array($result)) {
			$this->n_id=$row["n_id"];
			$this->datum=$row["datum"];
			$this->text=$row["text"];
				
			$user_id=$row["u_id"];
			$this->user=new User();
			$this->user->laden($user_id);
				
			$gruppen_id=$row["g_id"];
			$this->gruppe=new Gruppe();
			$this->gruppe->laden($gruppen_id);
				
			// speichern, dass die Basisinformationen des Teams erfolgreich geladen werden konnten
			$isLoaded=true;
		}
	
		// zurï¿½ckgeben, ob beim Laden ein Fehler aufgetreten ist
		return $isLoaded;
	}
	
	public static function bearbeiten($n_id, $text) {
		
		$db = new DB_connection();
		
		$query="UPDATE nachricht SET text='".$text."'
				WHERE n_id='".$n_id."';";
		
		// Wenn die Datenbankabfrage erfolgreich ausgefï¿½hrt worden ist
		if ($result = $db->execute($query)) {
		
				// neues objekt erzeugen
				$aktuelleNachricht = new Nachricht();
				$aktuelleNachricht->laden($n_id);

			}
		
			// fertige Liste von Gruppe-Objekten zurï¿½ckgeben
			return $aktuelleNachricht;
		
	}
	
	public static function loeschen($n_id) {
		
		$db = new DB_connection();
		
		$query="DELETE FROM nachricht WHERE n_id='".$n_id."';";
		
		$result = $db->execute($query);
		return  $result;
		
	}
	
	public static function aktuellenachrichten($user_id) {
	
		// Liste initialisieren
		$nachrichtenListe = array ();
	
		$db = new DB_connection();
	
		$query="SELECT * FROM `nachricht`
				NATURAL JOIN gruppe NATURAL JOIN gruppenmitglied
				WHERE gruppenmitglied.u_id= '".$user_id."' AND (date(datum) BETWEEN curdate()-INTERVAL 5 DAY AND curdate())
				ORDER BY g_id, n_id DESC LIMIT 10";
	
		// Wenn die Datenbankabfrage erfolgreich ausgefï¿½hrt worden ist
		if ($result = $db->execute($query)) {
	
			// Ergebnis Zeile fï¿½r Zeile verarbeiten
			while ($row = mysqli_fetch_array($result)) {
					
				// neues Model erzeugen
				$model = new Nachricht();
	
				// Model anhand der Nummer aus der Datenbankabfrage laden
				$model->laden($row["n_id"]);
	
				// neues Model ans Ende des $gruppeListe-Arrays anfï¿½gen
				$aktuelleListe[] = $model;
			}
	
			// fertige Liste von Gruppe-Objekten zurï¿½ckgeben
			return $aktuelleListe;
		}
	}
	
	
	public static function messageboard($user_id, $g_id) {
	
		// Liste initialisieren
		$nachrichtenListe = array ();
	
		$db = new DB_connection();
	
		$query="SELECT * FROM `nachricht`
				WHERE g_id='".$g_id."'	
				ORDER BY n_id DESC LIMIT 10;";
		
	
		// Wenn die Datenbankabfrage erfolgreich ausgefï¿½hrt worden ist
		if ($result = $db->execute($query)) {
	
			// Ergebnis Zeile fï¿½r Zeile verarbeiten
			while ($row = mysqli_fetch_array($result)) {
					
				// neues Model erzeugen
				$model = new Nachricht();
	
				// Model anhand der Nummer aus der Datenbankabfrage laden
				$model->messageboardladen($row["n_id"]);
	
				// neues Model ans Ende des $gruppeListe-Arrays anfï¿½gen
				$aktuelleListe[] = $model;
			}
	
			// fertige Liste von Gruppe-Objekten zurï¿½ckgeben
			return $aktuelleListe;
		}
	}
	
	
	// Blätterfunktion
	 
	public function blaettern($user_id, $g_id) {
		
		$db = new DB_connection();
		
		// Liste initialisieren
		
		$nachrichtenListe = array ();
		
		// Ermittlung, wie viele Nachrichteneinträge es in der DB gibt
		
		$result_total = mysqli_query('SELECT COUNT(*) as `total` FROM `Nachricht`');
		$row_total = mysqli_fetch_assoc($result_total);
		$gesamte_anzahl = $row_total['total'];
		
		// Ermittlung, wie viele Einträge pro Seite und Festlegung wie viele Seiten es geben muss
		
		$ergebnisse_pro_seite = 10;
		$gesamt_seiten = ceil($gesamte_anzahl/$ergebnisse_pro_seite);
		
		// Was ist die aktuelle Seite? wird durch die URL übergeben
		
		if (empty($_GET['seite_nr'])) {
			$seite = 1;
		} else {
			$seite = $_GET['seite_nr'];
			if ($seite > $gesamt_seiten) {
				$seite = 1;
			}
		}
	
		// Errechnung, wo in der DB angefangen wird die Daten zu holen
		
		$limit = ($seite*$ergebnisse_pro_seite)-$ergebnisse_pro_seite;
		
		// Holen der Daten aus der DB
		
		$result = mysqli_query('SELECT * FROM `nachricht` LIMIT '.$limit.', '.$ergebnisse_pro_seite);
		while ($row = mysqli_fetch_assoc($result)) {
			
			
			// neues Model erzeugen
			$model = new Nachricht();
			
			// Model anhand der Nummer aus der Datenbankabfrage laden
			$model->messageboardladen($row["n_id"]);
			
			// neues Model ans Ende des $gruppeListe-Arrays anfï¿½gen
			$aktuelleListe[] = $model;
			
		}
			
		
		// Ausgabe der Seitenzahlen
		for ($i=1; $i<=$gesamt_seiten; ++$i) {
			if ($seite == $i) {
				echo '<a href="http://132.231.36.206/groupshow?g_id=174?seite_nr='.$i.'" style="font-weight: bold;">'.$i.'</a>';
			} else {
				echo '<a href="http://132.231.36.206/groupshow?g_id=174?seite_nr='.$i.'">'.$i.'</a>';
			}
		}
		
		// fertige Liste von Nachrichten-Objekten zurï¿½ckgeben
		
		return $aktuelleListe;
	}
	

	// Getter und Setter
	
	public function getN_id () {
		return $this->n_id;
	}
	
	public function setN_Id($n_id) {
		$this->n_id= $n_id;
	}

	public function getDatum () {
		return $this->datum;
	}
	
	public function setDatum ($datum) {
		$this->datum=$datum;
	}
	
	public function getText () {
		return $this->text;
	}
	
	public function setText ($text) {
		$this->text=$text;
	}
	
	public function getG_id () {
		return $this->g_id;
	}
	
	public function setG_Id($g_id) {
		$this->g_id= $g_id;
	}
	
	public function getU_id () {
		return $this->u_id;
	}
	
	public function setU_Id($u_id) {
		$this->u_id= $u_id;
	}
	
	public function getGruppe () {
		return $this->gruppe;
	}
	
	public function setGruppe($gruppe) {
		$this->gruppe= $gruppe;
	}
	
	public function getUser () {
		return $this->user;
	}
	
	public function setUser($user) {
		$this->user= $user;
	}
	
}