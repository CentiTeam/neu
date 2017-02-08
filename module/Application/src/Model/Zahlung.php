<?php
namespace Application\Model;


use Application\Model\DB_connection;
use Application\Model\Gruppe;
use Application\Model\Kategorie;

class Zahlung {
	protected  $z_id;
	protected  $zahlungsbeschreibung;
	protected  $erstellungsdatum;
	protected  $zahlungsdatum;
	protected  $betrag;
	protected  $kategorie;
	protected  $aenderungsdatum;
	protected  $gruppe;
	
	public function __construct($zahlung_id = null) {
		
		$this->z_id= $zahlung_id;
		echo "$this->z_id";
			
	}
	
	// Zahlung anlegen
	public function anlegen () {
	
		$db = new DB_connection();
	
		$query = "INSERT INTO zahlung (zahlungsbeschreibung, erstellungsdatum, zahlungsdatum, betrag, k_id, aenderungsdatum, g_id) VALUES (
				'".$this->zahlungsbeschreibung."',
				CURDATE(),
				'".$this->zahlungsdatum."',
				'".$this->betrag."',
				'".$this->getKategorie()->getK_id()."',
				'".$this->aenderungsdatum."',
				'".$this->getGruppe()->getG_id()."'
				)" ;
	
		$result = $db->execute($query);
		
	
		return $result;
	}
	
	// Zahlung bearbeiten
	public function bearbeiten() {
		
		$db = new DB_connection();
		
		$query = "UPDATE zahlung
					SET zahlungsbeschreibung ='".$this->zahlungsbeschreibung."',
		 				zahlungsdatum ='".$this->zahlungsdatum."',
						betrag ='".$this->betrag."',
						k_id ='".$this->getKategorie()->getK_id()."',
						aenderungsdatum ='".$this->aenderungsdatum."'
						WHERE z_id ='".$this->getZ_id()."'
								;" ;
		
		$result = $db->execute($query);
		
		return $result;
	}
	
	// Zahlung laden
	public function laden ($z_id = null) {
	
		// Datenbankstatement erzeugen
		$dbStmt = new DB_connection();
	
		// DB-Befehl absetzen: alle Basisinformationen des Teams mit der �bergebenen $t_id abfragen
	
	
		if ($z_id) {
			$result=$dbStmt->execute("SELECT z_id, zahlungsbeschreibung, DATE_FORMAT(erstellungsdatum,'%d.%m.%Y') as erstellungsdatum, zahlungsdatum, betrag, k_id, DATE_FORMAT(aenderungsdatum,'%d.%m.%Y') as aenderungsdatum, g_id FROM zahlung WHERE z_id= '".$z_id."';");
		}
		else {
			$result=$dbStmt->execute("SELECT z_id, zahlungsbeschreibung, DATE_FORMAT(erstellungsdatum,'%d.%m.%Y') as erstellungsdatum, zahlungsdatum, betrag, k_id, DATE_FORMAT(aenderungsdatum,'%d.%m.%Y') as aenderungsdatum, g_id FROM zahlung WHERE z_id =(SELECT MAX(z_id) FROM zahlung)");
		}
		// Variable, die speichert, ob das Team geladen werden konnte oder nicht
		$isLoaded=false;
	
		// Ergebnis verarbeiten, falls vorhanden
		if ($row=mysqli_fetch_array($result)) {
			$this->z_id=$row["z_id"];
			$this->zahlungsbeschreibung=$row["zahlungsbeschreibung"];
			$this->erstellungsdatum=$row["erstellungsdatum"];
			$this->zahlungsdatum=$row["zahlungsdatum"];
			$this->betrag=$row["betrag"];
			
			$this->aenderungsdatum=$row["aenderungsdatum"]; 
			
			$kategorie_id=$row["k_id"];
			$this->kategorie=new Kategorie();
			$this->kategorie->laden($kategorie_id);
			
			$gruppen_id=$row["g_id"];
			$this->gruppe=new Gruppe();
			$this->gruppe->laden($gruppen_id);
			
			// speichern, dass die Basisinformationen des Teams erfolgreich geladen werden konnten
			$isLoaded=true;
		}
	
		// zur�ckgeben, ob beim Laden ein Fehler aufgetreten ist
		return $isLoaded;
	}
	
	// Liste aller Zahlungen in einer Gruppe $gruppen_id holen
	public static function gruppenzahlungenlisteHolen($gruppen_id) {
	
		// Liste initialisieren
		$zahlungsListe = array ();
	
		$db = new DB_connection();
	
		$query="SELECT z_id, zahlungsbeschreibung, DATE_FORMAT(erstellungsdatum,'%d.%m.%Y') as erstellungsdatum, DATE_FORMAT(zahlungsdatum,'%d.%m.%Y') as zahlungsdatum, betrag, k_id, DATE_FORMAT(aenderungsdatum,'%d.%m.%Y') as aenderungsdatum, g_id FROM `zahlung`
				WHERE g_id= '".$gruppen_id."'  
				ORDER BY erstellungsdatum ASC
				";
	
		// Wenn die Datenbankabfrage erfolgreich ausgef�hrt worden ist
		if ($result = $db->execute($query)) {
	
			// Ergebnis Zeile f�r Zeile verarbeiten
			while ($row = mysqli_fetch_array($result)) {
					
				// neues Model erzeugen
				$model = new Zahlung();
	
				// Model anhand der Nummer aus der Datenbankabfrage laden
				$model->laden($row["z_id"]);
	
				// neues Model ans Ende des $gruppeListe-Arrays anf�gen
				$zahlungsListe[] = $model;
			}
	
			// fertige Liste von Gruppe-Objekten zur�ckgeben
			return $zahlungsListe;
		}
	}
	
	// Liste aller Zahlungen eines Users $user_id holen
	public static function eigenezahlungenholen($user_id) {
	
		// Liste initialisieren
		$zahlungenListe = array ();
	
		$db = new DB_connection();
	
		$query="SELECT z_id, zahlungsbeschreibung, DATE_FORMAT(erstellungsdatum,'%d.%m.%Y') as erstellungsdatum, DATE_FORMAT(zahlungsdatum,'%d.%m.%Y') as zahlungsdatum, betrag, k_id, DATE_FORMAT(aenderungsdatum,'%d.%m.%Y') as aenderungsdatum, g_id FROM `zahlung`
				LEFT JOIN zahlungsteilnehmer ON (zahlung.z_id=zahlungsteilnehmer.z_id)
				WHERE u_id= '".$user_id."' ";
	
		// Wenn die Datenbankabfrage erfolgreich ausgef�hrt worden ist
		if ($result = $db->execute($query)) {
	
			// Ergebnis Zeile f�r Zeile verarbeiten
			while ($row = mysqli_fetch_array($result)) {
					
				// neues Model erzeugen
				$model = new Zahlung();
	
				// Model anhand der Nummer aus der Datenbankabfrage laden
				$model->laden($row["z_id"]);
				
				// neues Model ans Ende des $gruppeListe-Arrays anf�gen
				$gruppeListe[] = $model;
			}
	
			// fertige Liste von Gruppe-Objekten zur�ckgeben
			return $gruppeListe;
		}
	}
	
	// Liste aller aktuellen Zahlungen eines Users $user_id holen (innerhalb der letzten 5 Tage)
	public static function aktuellezahlungenholen($user_id) {
	
		// Liste initialisieren
		$zahlungenListe = array ();
	
		$db = new DB_connection();
	
		$query="SELECT z_id, zahlungsbeschreibung, DATE_FORMAT(erstellungsdatum,'%d.%m.%Y') as erstellungsdatum, DATE_FORMAT(zahlungsdatum,'%d.%m.%Y') as zahlungsdatum, betrag, k_id, DATE_FORMAT(aenderungsdatum,'%d.%m.%Y') as aenderungsdatum, g_id FROM `zahlung`
				NATURAL JOIN zahlungsteilnehmer NATURAL JOIN gruppenmitglied
				WHERE u_id= '".$user_id."' AND (date(erstellungsdatum) BETWEEN curdate()-INTERVAL 5 DAY AND curdate())
				ORDER BY g_id, erstellungsdatum DESC";
	
		// Wenn die Datenbankabfrage erfolgreich ausgef�hrt worden ist
		if ($result = $db->execute($query)) {
	
			// Ergebnis Zeile f�r Zeile verarbeiten
			while ($row = mysqli_fetch_array($result)) {
					
				// neues Model erzeugen
				$model = new Zahlung();
	
				// Model anhand der Nummer aus der Datenbankabfrage laden
				$model->laden($row["z_id"]);
	
				// neues Model ans Ende des $gruppeListe-Arrays anf�gen
				$aktuelleListe[] = $model;
			}
	
			// fertige Liste von Gruppe-Objekten zur�ckgeben
			return $aktuelleListe;
		}
	}
	
	// Zahlung loeschen
	public static function loeschen ($z_id) {
		$db = new DB_connection();
		
		$query = "DELETE FROM zahlung WHERE z_id = '".$z_id."';";
		
		$result = $db->execute($query);
		
		return $result;
	}
	
	
	// Getter und Setter
	
	public function getZ_id () {
		return $this->z_id;
	}
	
	public function setZ_Id($z_id) {
		$this->z_id= $z_id;
	}

	public function getZahlungsbeschreibung () {
		return $this->zahlungsbeschreibung;
	}
	
	public function setZahlungsbeschreibung($zahlungsbeschreibung) {
		$this->zahlungsbeschreibung= $zahlungsbeschreibung;
	}
	
	public function getErstellungsdatum () {
		return $this->erstellungsdatum;
	}
	
	public function setErstellungsdatum($erstellungsdatum) {
		$this->erstellungsdatum= $erstellungsdatum;
	}
	
	public function getZahlungsdatum () {
		return $this->zahlungsdatum;
	}
	
	public function setZahlungsdatum($zahlungsdatum) {
		$this->zahlungsdatum= $zahlungsdatum;
	}
	
	public function getBetrag () {
		return $this->betrag;
	}
	
	public function setBetrag($betrag) {
		$this->betrag= $betrag;
	}
	
	public function getKategorie () {
		return $this->kategorie;
	}
	
	public function setKategorie($kategorie) {
		$this->kategorie= $kategorie; 
	}
	
	public function getAenderungsdatum () {
		return $this->aenderungsdatum;
	}
	
	public function setAenderungsdatum($aenderungsdatum) {
		$this->aenderungsdatum= $aenderungsdatum;
	}
	
	public function getGruppe () {
		return $this->gruppe;
	}
	
	public function setGruppe($gruppe) {
		$this->gruppe= $gruppe;
	}
	
}