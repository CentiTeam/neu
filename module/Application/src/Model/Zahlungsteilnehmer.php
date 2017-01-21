<?php
namespace Application\Model;


use Application\Model\DB_connection;
use Application\Model\User;
use Application\Model\Zahlung;

class Zahlungsteilnehmer {
	protected  $user;
	protected  $zahlung;
	protected  $status;
	protected  $anteil;
	protected  $zahlungsempfaenger; 
	protected  $restbetrag;
	
	// HIER Kann evtl. ein Fehler liegen!!! (Objekt muss beachtet werden?)
	public function __construct($zahlung_id = null, $user_id = null) {
		$this->zahlung= $zahlung_id;
		$this->user=$user_id;
		echo "$this->zahlung";
		echo "$this->user";
	}
	
	public function anlegen () {
	
		$db = new DB_connection();
	
		$query = "INSERT INTO zahlungsteilnehmer (u_id, z_id, status, anteil, zahlungsempfaenger_id, restbetrag) VALUES (
				'".$this->getUser()->getU_id()."',
				'".$this->getZahlung()->getZ_id()."',
				'".$this->status."',
				'".$this->anteil."',
				'".$this->getZahlungsempfaenger()->getU_id()."',
				'".$this->anteil."'
				)" ;
	
		$result = $db->execute($query);
	
		return $result;
	}
	
// 	public function ausgleichenersteller ($user_id, $z_id) {
	
// 		foreach($zahlungenliste as $zaehler => $zahlungsteilnehmer){
			
// 		}
		
// 	}

	public function zahlungbegleichen($betrag){
		if($zahlungsempfaenger == 1){
			echo "Fehler, ein Zahlungsempfaenger kann nichts begleichen";
		}
		if($betrag >= $restbetrag){
			$temp = $restbetrag;
			$this->setRestbetrag(0);
			return ($betrag - $temp);
		}else{
			$this->setRestbetrag($this->getRestbetrag()-$betrag);
			return 0;
		}
	
	}
	public function alleausgleichen($z_id){
		$teilnehmerListe = $this->zahlungsteilnehmerholen($this->getZahlung()->getZ_id());
		foreach($teilnehmerListe as $zaehler => $zahlungsteilnehmer){
			$zahlungsteilnehmer -> ausgleichen($zahlungsteilnehmer);
		}
	}
	
	
	public function ausgleichen($einzahlungsteilnehmer) {
			
		
		// teilnehmerliste ^= alle Teilnehmer der neuen/bearbeiteten/beglichenen Zahlung
		

		$teilnehmerListe= $einzahlungsteilnehmer->removefromteilnehmerListe($this->zahlungsteilnehmerholen($this->getZahlung()->getZ_id()));
		// jetzt wird der eigentliche User (das this Objekt in dieser Klasse) aus der Liste entfernt.
		//Daraufhin wird jeder andere User mit dem entfernten User verglichen

		
 		foreach($teilnehmerListe as $zaehler => $andererzahlungsteilnehmer){
 			//Funktion wird von einem Zahlungsteilnehmer aufgerufen. Jeder andere User der in dieser Zahlung
 			//teilnimmt wird durchlaufen. In jedem Durchlauf werden alle gemeinsamen offenen Zahlungen geholt. 
// 			// Danach wird der Schuldstand errechnet. Erstes Ziel!
	
// 			//was entferntem User geschuldet wird
 			$schuldstand = 0;
// 			//gib mir alle Zahlungen die offen mit diesem User sind.
 			$gemeinsamezahlungen = $einzahlungsteilnehmer->gibgemeinsamezahlungen(
 					$einzahlungsteilnehmer->getUser() ->getU_id(), $andererzahlungsteilnehmer->getUser() ->getU_id() );
 			
 			foreach($gemeinsamezahlungen as $zaehler => $zahlungsteilnehmer){
 				
 				//Rechne Schuldstand. 
 				//Schuldstand verändert sich nur 
 				//falls user selber Ersteller/Empfänger ist
 				
//  				$alleteilnehmerausgeminsamerzahlung = zahlungsteilnehmerholen($zahlungsteilnehmer -> getZahlung() -> getZ_id());
//  				foreach($alleteilnehmerausgeminsamerzahlung as $zaehler => $zufilternderteilnehmer)
 				
 					
 					$ersterzahlungsteilnehmer = $zahlungsteilnehmer-> einenzahlungsteilnehmerholen($zahlungsteilnehmer -> getZahlung() -> getZ_id(), 
 							$einzahlungsteilnehmer->getUser() ->getU_id());
 					
 					$zweiterzahlungsteilnehmer = $zahlungsteilnehmer-> einenzahlungsteilnehmerholen($zahlungsteilnehmer -> getZahlung() -> getZ_id(),
 							$andererzahlungsteilnehmer->getUser() ->getU_id());
 					
 					echo"Zahlungsempfaenger:";
 					var_dump($zweiterzahlungsteilnehmer->getZahlungsempfaenger());
 					echo"user id:";
 					var_dump($ersterzahlungsteilnehmer->getUser() ->getU_id());
 				if($ersterzahlungsteilnehmer->getZahlungsempfaenger()== $ersterzahlungsteilnehmer->getUser() ->getU_id()){
 					$schuldstand += $zweiterzahlungsteilnehmer ->getRestbetrag();
 					echo"test1";
 				}
 				
 				if($zweiterzahlungsteilnehmer->getZahlungsempfaenger()== $zweiterzahlungsteilnehmer->getUser() ->getU_id()){
 					$schuldstand += $ersterzahlungsteilnehmer ->getRestbetrag();
 					echo"test2";
 					
 				}
 				
 			}
 			
// 				//wenn ich ihm etwas schulde
// 				if($schuldstand<0){
// 					while($schuldstand<0){
// 						$schuldstand + zahlungenbegleichen($schuldstand);
							
// 					}
// 				}
// 				else{ //wenn er mir etwas schuldet
// 					while($schuldstand > 0){
// 						$schuldstand - zahlungenbegleichen($schuldstand);
// 					}
		
 			echo "schuldstand :";
 			var_dump($einzahlungsteilnehmer ->getUser() -> getUsername());
 			var_dump($andererzahlungsteilnehmer ->getUser() -> getUsername());
 			var_dump($schuldstand);
 			}
 			
 			
	
 		}
 		public function gibsaldo($user_id){
 			$saldo = 0;
 			foreach(Zahlungsteilnehmer::teilnehmerzahlungenholen($user_id) as $zaehler => $zahlungsteilnehmer){
 				if($zahlungsteilnehmer-> getStatus() == 'offen'){
 					$saldo -= $zahlungsteilnehmer ->getRestbetrag();
 				}
 				if($zahlungsteilnehmer-> getStatus() == 'ersteller'){
 					$teilnehmerliste = $zahlungsteilnehmer-> removefromteilnehmerListe($zahlungsteilnehmer -> zahlungsteilnehmerholen($zahlungsteilnehmer -> getZahlung()->getZ_id()));
 					
 					foreach($teilnehmerliste as $zaehler => $zahlungsteilnehmer){
 						$saldo += $zahlungsteilnehmer ->getRestbetrag();
 					}
 					
 				}
 			}
 			return $saldo;
 		}

	
	
	public function gibgemeinsamezahlungen($user_id1, $user_id2){
		$liste1 = $this -> teilnehmerzahlungenholen($user_id1);
		$liste2 = $this -> teilnehmerzahlungenholen($user_id2);
		$gemeinsamezahlungen = array();
		foreach($liste1 as $zaehler1 => $zahlungsteilnehmer1){
			foreach($liste2 as $zaehler2 => $zahlungsteilnehmer2){
				if($zahlungsteilnehmer1 ->getZahlung() ->getZ_id() == $zahlungsteilnehmer2 ->getZahlung() ->getZ_id()
						&& $zahlungsteilnehmer1 ->getStatus() != 'beglichen'){
					$gemeinsamezahlungen[] = $zahlungsteilnehmer1;
				}
			}
				
		}
		return $gemeinsamezahlungen;
	}
	
	public function removefromteilnehmerListe($teilnehmerListe){
		$ind = 0;
		foreach($teilnehmerListe as $zaehler => $zahlungsteilnehmer){
			if($zahlungsteilnehmer -> getUser() ->getU_id() ==$this->getUser() ->getU_id() ){
				unset($teilnehmerListe[$ind]);
				array_values();
			}
			$ind ++;
		}
		return $teilnehmerListe;
	}
	
	public static function teilnehmerzahlungenholen($user_id) {
	
		// Liste initialisieren
		$zahlungenListe = array ();
	
		$db = new DB_connection();
	
		$query="SELECT * FROM `zahlungsteilnehmer`
				WHERE u_id= '".$user_id."' ";
	
		// Wenn die Datenbankabfrage erfolgreich ausgefï¿½hrt worden ist
		if ($result = $db->execute($query)) {
	
			// Ergebnis Zeile fï¿½r Zeile verarbeiten
			while ($row = mysqli_fetch_array($result)) {
					
				// neues Model erzeugen
				$model = new Zahlungsteilnehmer();
	
				// Model anhand der Nummer aus der Datenbankabfrage laden
				$model->laden($row["z_id"], $row["u_id"]);
	
				// neues Model ans Ende des $gruppeListe-Arrays anfï¿½gen
				$zahlungListe[] = $model;
			}
	
			// fertige Liste von Gruppe-Objekten zurï¿½ckgeben
			return $zahlungListe;
		}
	}
	
	public function zahlungsteilnehmerholen($z_id){
		// Liste initialisieren
		$teilnehmerListe = array ();
		
		$db = new DB_connection();
		
		$query="SELECT * FROM `zahlungsteilnehmer`
				WHERE z_id= '".$z_id."' ";
		
		// Wenn die Datenbankabfrage erfolgreich ausgefï¿½hrt worden ist
		if ($result = $db->execute($query)) {
		
			// Ergebnis Zeile fï¿½r Zeile verarbeiten
			while ($row = mysqli_fetch_array($result)) {
					
				// neues Model erzeugen
				$model = new Zahlungsteilnehmer();
		
				// Model anhand der Nummer aus der Datenbankabfrage laden
				$model->laden($row["z_id"], $row["u_id"]);
		
				// neues Model ans Ende des $gruppeListe-Arrays anfï¿½gen
				$teilnehmerListe[] = $model;
			}
		
			// fertige Liste von Gruppe-Objekten zurï¿½ckgeben
			return $teilnehmerListe;
		}
	}
	
	
	public function einenzahlungsteilnehmerholen($z_id, $u_id){
		// Liste initialisieren
		$zteilnehmer;
	
		$db = new DB_connection();
	
		$query="SELECT * FROM `zahlungsteilnehmer`
				WHERE z_id= '".$z_id."' 
				AND u_id= '".$u_id."'; ";
	
		// Wenn die Datenbankabfrage erfolgreich ausgefï¿½hrt worden ist
		if ($result = $db->execute($query)) {
	
			// Ergebnis Zeile fï¿½r Zeile verarbeiten
			while ($row = mysqli_fetch_array($result)) {
					
				// neues Model erzeugen
				$model = new Zahlungsteilnehmer();
	
				// Model anhand der Nummer aus der Datenbankabfrage laden
				$model->laden($row["z_id"], $row["u_id"]);
	
				// neues Model ans Ende des $gruppeListe-Arrays anfï¿½gen
				$zteilnehmer = $model;
			}
	
			// fertige Liste von Gruppe-Objekten zurï¿½ckgeben
			return $zteilnehmer;
		}
	}
	
	
	
	
	
	public function laden ($z_id, $u_id) {
	
		// Datenbankstatement erzeugen
		$dbStmt = new DB_connection();
	
		// DB-Befehl absetzen: alle Basisinformationen des Teams mit der ï¿½bergebenen $t_id abfragen
	
		$result=$dbStmt->execute("SELECT * FROM zahlungsteilnehmer 
				WHERE z_id= '".$z_id."'
				AND u_id='".$u_id."';");
		
		// Variable, die speichert, ob das Team geladen werden konnte oder nicht
		$isLoaded=false;
	
		// Ergebnis verarbeiten, falls vorhanden
		if ($row=mysqli_fetch_array($result)) {
			$zahlung_id=$row["z_id"];
			$this->zahlung=new Zahlung(); 
			$this->zahlung->laden($zahlung_id);
			
			$user_id=$row["u_id"];
			$this->user=new User();
			$this->user->laden($user_id);
			
			
			$this->status=$row["status"];
			$this->anteil=$row["anteil"];
			
			$zahlungsempfaenger_id=$row["zahlungsempfaenger_id"];
			$this->zahlungsempfaenger=new User();
			$this->zahlungsempfaenger->laden($zahlungsempfaenger_id);
			
			$this->restbetrag=$row["restbetrag"];
				
				
			// speichern, dass die Basisinformationen des Teams erfolgreich geladen werden konnten
			$isLoaded=true;
		}
	
		// zurï¿½ckgeben, ob beim Laden ein Fehler aufgetreten ist
		return $isLoaded;
	}
	
	
	
	public static function loeschen($z_id){
		$db = new DB_connection();
		
		$query = "DELETE FROM zahlungsteilnehmer
					WHERE z_id = '".$z_id."'
					;" ;
		
		$result = $db->execute($query);
		
		return $result;
		
	}
	
	// Getter und Setter
	
	public function getUser () {
		return $this->user;
	}
	
	public function setUser($user) {
		$this->user= $user;
	}
	
	public function getZahlung () {
		return $this->zahlung;
	}
		
	public function setZahlung($zahlung) {
		$this->zahlung= $zahlung;
	}
	
	public function getStatus() {
		return $this->status;
	}
	
	public function setStatus($status) {
		$this->status= $status;
	}
	
	public function getAnteil() {
		return $this->anteil;
	}
	
	public function setAnteil($anteil) {
		$this->anteil= $anteil;
	}
	
	public function getZahlungsempfaenger() {
		return $this->zahlungsempfaenger;
	}
	
	public function setZahlungsempfaenger($zahlungsempfaenger) {
		$this->zahlungsempfaenger= $zahlungsempfaenger;
	}
	
	public function getRestbetrag() {
		return $this->restbetrag;
	}
	
	public function setRestbetrag($restbetrag) {
		$this->restbetrag= $restbetrag;
	}
}