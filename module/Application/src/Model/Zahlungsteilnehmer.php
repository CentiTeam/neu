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
		//echo "$this->zahlung";
		//echo "$this->user";
	}
	
	public function anlegen () {
	
		$db = new DB_connection();
	
		$query = "INSERT INTO zahlungsteilnehmer (u_id, z_id, status, anteil, zahlungsempfaenger_id, restbetrag) VALUES (
				'".$this->getUser()->getU_id()."',
				'".$this->getZahlung()->getZ_id()."',
				'".$this->status."',
				'".$this->anteil."',
				'".$this->getZahlungsempfaenger()->getU_id()."',
				'".$this->restbetrag."'
				)" ;
	
		$result = $db->execute($query);
	
		return $result;
	}
	
// 	public function ausgleichenersteller ($user_id, $z_id) {
	
// 		foreach($zahlungenliste as $zaehler => $zahlungsteilnehmer){
			
// 		}
		
// 	}

	public function zahlungbegleichen($betrag, $zahlungsteilnehmer){
// 		if($zahlungsempfaenger == 1){
// 			echo "Fehler, ein Zahlungsempfaenger kann nichts begleichen";
// 		}
		$restbetrag =0;
		$status = "beglichen";
		if($betrag >= $zahlungsteilnehmer->getRestbetrag()){
			$temp = $zahlungsteilnehmer->getRestbetrag();
			$restbetrag=0;
			$status="beglichen";
			$uebrig=($betrag - $temp);
		}elseif($betrag>=0){
			$restbetrag=($zahlungsteilnehmer->getRestbetrag()-$betrag);
			//$status=$this->getStatus;
			$status = "offen";
			$uebrig=0;
		}
		$this->restbetragQuery($restbetrag, $status, $zahlungsteilnehmer);
	
	return $uebrig;
	
	}
	public function restbetragQuery($restbetrag, $status, $zahlungsteilnehmer){
		$db = new DB_connection();
		
		$query =	"UPDATE zahlungsteilnehmer SET restbetrag = '".$restbetrag."', status = '".$status."'
				WHERE z_id='".$zahlungsteilnehmer->getZahlung()->getZ_id()."'
				AND u_id='".$zahlungsteilnehmer->getUser()->getU_id()."';";
		$result = $db->execute($query);
		
	}
	
// 	public function alleausgleichen($z_id){
// 		$teilnehmerListe = $this->zahlungsteilnehmerholen($this->getZahlung()->getZ_id());
// 		foreach($teilnehmerListe as $zaehler => $zahlungsteilnehmer){
// 			$zahlungsteilnehmer -> ausgleichen($zahlungsteilnehmer);
// 		}
		//eigentlich muss nur jeder einzelne User mit dem Ersteller verglichen werden
		//die Funktion ausgleichen() wird f�r jeden User, der mit der erstellten(oder bearbeiteten)
		//Zahlung etwas zu tuen hat von dieser Funktion aus aufgerufen
	//}
	
	
	public function ausgleichen() {
		$einzahlungsteilnehmer = $this;
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
 				//Schuldstand ver�ndert sich nur 
 				//falls user selber Ersteller/Empf�nger ist
 				
//  				$alleteilnehmerausgeminsamerzahlung = zahlungsteilnehmerholen($zahlungsteilnehmer -> getZahlung() -> getZ_id());
//  				foreach($alleteilnehmerausgeminsamerzahlung as $zaehler => $zufilternderteilnehmer)
 				
 					$ersterzahlungsteilnehmer = $zahlungsteilnehmer-> einenzahlungsteilnehmerholen($zahlungsteilnehmer -> getZahlung() -> getZ_id(), 
 							$einzahlungsteilnehmer->getUser() ->getU_id());
 					
 					$zweiterzahlungsteilnehmer = $zahlungsteilnehmer-> einenzahlungsteilnehmerholen($zahlungsteilnehmer -> getZahlung() -> getZ_id(),
 							$andererzahlungsteilnehmer->getUser() ->getU_id());
 					
 				if($ersterzahlungsteilnehmer->getZahlungsempfaenger()->getU_id()== $ersterzahlungsteilnehmer->getUser() ->getU_id()){
 					$schuldstand += $zweiterzahlungsteilnehmer ->getRestbetrag();
 				}
 				if($zweiterzahlungsteilnehmer->getZahlungsempfaenger()->getU_id()== $zweiterzahlungsteilnehmer->getUser() ->getU_id()){
 					$schuldstand -= $ersterzahlungsteilnehmer ->getRestbetrag();
 				}
 			}
 					//wenn ich ihm etwas schulde
 					
				if(($schuldstand-$andererzahlungsteilnehmer->getRestbetrag())<0){
					
					$this->autAusgleichen($gemeinsamezahlungen, $andererzahlungsteilnehmer);
					
				}
//  			$schuldstand = $schuldstand * (-1);
//  						foreach($gemeinsamezahlungen as $zaehler => $zahlungsteilnehmer){
//  							if($schuldstand>0){
 							//$einzahlungsteilnehmer schuldet $andererzahlungsteilnehmer etwas
 							//falls es nun Zahlungen gibt, in denen $einzahlungsteilnehmer Ersteller ist
 							// und $andererzahlungsteilnehmer einen offenen Restbetrag hat sollten diese Zahlungen
 							// beglichen werden. Und zwar bis es entweder keine entsprechenden Zahlungen mehr gibt
 							//oder bis der Schuldstand von beiden == 0 ist
//  							$ersterzahlungsteilnehmer = $zahlungsteilnehmer-> einenzahlungsteilnehmerholen($zahlungsteilnehmer -> getZahlung() -> getZ_id(),
//  									$einzahlungsteilnehmer->getUser() ->getU_id());
 							
//  							$zweiterzahlungsteilnehmer = $zahlungsteilnehmer-> einenzahlungsteilnehmerholen($zahlungsteilnehmer -> getZahlung() -> getZ_id(),
//  									$andererzahlungsteilnehmer->getUser() ->getU_id());
//  							if($ersterzahlungsteilnehmer ->getUser() ->getU_id()== $ersterzahlungsteilnehmer->getZahlungsempfaenger()->getU_id()
//  								&&($zweiterzahlungsteilnehmer ->getRestbetrag()) >0){
//  									$schuldstand = $zweiterzahlungsteilnehmer -> zahlungbegleichen($schuldstand, $zweiterzahlungsteilnehmer);
 									
//  									}
//  						}
//  					}
//  					$schuldstand = $schuldstand * (-1);
//  		}
 		
//  		if($schuldstand>0){
//  			foreach($gemeinsamezahlungen as $zaehler => $zahlungsteilnehmer){
//  				if($schuldstand>0){
//  					$ersterzahlungsteilnehmer = $zahlungsteilnehmer-> einenzahlungsteilnehmerholen($zahlungsteilnehmer -> getZahlung() -> getZ_id(),
//  							$andererzahlungsteilnehmer->getUser() ->getU_id());
//  					$eigersterzahlungsteilnehmer = $zahlungsteilnehmer-> einenzahlungsteilnehmerholen($zahlungsteilnehmer -> getZahlung() -> getZ_id(),
//  							$ersterzahlungsteilnehmer->getUser() ->getU_id());
//  					if($ersterzahlungsteilnehmer ->getUser() ->getU_id()== $ersterzahlungsteilnehmer->getZahlungsempfaenger()->getU_id()
//  							&&($eigersterzahlungsteilnehmer ->getRestbetrag()) >0){
//  								$schuldstand = $eigersterzahlungsteilnehmer -> zahlungbegleichen($schuldstand, $eigersterzahlungsteilnehmer);
//  								var_dump($schuldstand);
//  					}
//  				}
//  			}
//  		}
 			}
 			
 		}
 		public function autAusgleichen($gemeinsameoffenezahlungen, $neueZahlung){
 			//User A hat gerade eine Zahlung erstellt, in welcher B einen offenen Restbetrag hat. 
 			//A schuldet B basierend auf alten Zahlungen etwas.
 			//Neue Zahlung ist Zahlungsteilnehmer B in der neuen Zahlung
 			
 			foreach($gemeinsameoffenezahlungen as $zaehler => $gemeinsamezahlung){
 				//offener Restbetrag v B der zuerst �ltesten Zahlung wird ben�tigt
 				$zTeilnehmerA = $gemeinsamezahlung-> einenzahlungsteilnehmerholen($gemeinsamezahlung -> getZahlung() -> getZ_id(), 
 						$this->getUser()->getU_id());
 				$restbetragA = $zTeilnehmerA->getRestbetrag();
 				if($restbetragA>0){
 				if($restbetragA>= $neueZahlung->getRestbetrag()){
 					//$this->restbetragQuery($restbetrag, $status, $zTeilnehmerB);
 					$this->zahlungbegleichen($neueZahlung->getRestbetrag(), $zTeilnehmerA);
 					$restbetragA = $restbetragA - $neueZahlung->getRestbetrag();
 					$neueZahlung ->setRestbetrag(0);
 					$this->restbetragQuery(0, "beglichen", $neueZahlung);
 					//jetzt sollte im foreach loop nichts mehr aufgerufen werden.
 				}
 				
 				if($restbetragA< $neueZahlung->getRestbetrag()){
 					$this->zahlungbegleichen($zTeilnehmerA->getRestbetrag(), $neueZahlung);
 					$neueZahlung ->setRestbetrag($neueZahlung->getRestbetrag()-$zTeilnehmerA->getRestbetrag());
 					$this->restbetragQuery(0, "beglichen", $zTeilnehmerA);
 				}
 				}
 			}
 		}
 		public function gibsaldo($user_id, $zahlungenliste){
 			$saldo = 0;
 			foreach($zahlungenliste as $zaehler => $zahlungsteilnehmer){
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
		//$liste2 = $this -> teilnehmerzahlungenholen($user_id2);
		$gemeinsamezahlungen = array();
		foreach($liste1 as $zaehler1 => $zahlungsteilnehmer1){
	//		foreach($liste2 as $zaehler2 => $zahlungsteilnehmer2){
			$zahlungsteilnehmer2 = $this -> einenzahlungsteilnehmerholen($zahlungsteilnehmer1 ->getZahlung() ->getZ_id(), $user_id2);
			if($zahlungsteilnehmer2 != 'null'){
				if($zahlungsteilnehmer1 -> getZahlung() ->getZ_id() == $zahlungsteilnehmer2 ->getZahlung() ->getZ_id()
						&& $zahlungsteilnehmer1 ->getStatus() == 'ersteller' 
						&& $zahlungsteilnehmer2 -> getRestbetrag() > 0){
					$gemeinsamezahlungen[] = $zahlungsteilnehmer1;
				}
				if($zahlungsteilnehmer1 ->getZahlung() ->getZ_id() == $zahlungsteilnehmer2 ->getZahlung() ->getZ_id()
						&& $zahlungsteilnehmer2 ->getStatus() == 'ersteller'
						&& $zahlungsteilnehmer1 -> getRestbetrag() > 0){
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
	
	// Alle Zahlungen, an denen ein User teilnimmt
	public static function teilnehmerzahlungenholen($user_id) {
	
		// Liste initialisieren
		$zahlungenListe = array ();
	
		$db = new DB_connection();
	
		$query="SELECT * FROM `zahlungsteilnehmer`
				WHERE u_id= '".$user_id."' ";
	
		// Wenn die Datenbankabfrage erfolgreich ausgef�hrt worden ist
		if ($result = $db->execute($query)) {
	
			// Ergebnis Zeile f�r Zeile verarbeiten
			while ($row = mysqli_fetch_array($result)) {
					
				// neues Model erzeugen
				$model = new Zahlungsteilnehmer();
	
				// Model anhand der Nummer aus der Datenbankabfrage laden
				$model->laden($row["z_id"], $row["u_id"]);
	
				// neues Model ans Ende des $gruppeListe-Arrays anf�gen
				$zahlungListe[] = $model;
			}
	
			// fertige Liste von Gruppe-Objekten zur�ckgeben
			return $zahlungListe;
		}
	}
	
	
	// Alle Zahlungen aus einer Gruppe mit gruppe_id, an denen ein User user_id teilnimmt (wurde noch nciht verwendet)
	public static function teilnehmerzahlungennachgruppeholen($user_id, $gruppen_id) {
	
		// Liste initialisieren
		$zahlungenListe = array ();
	
		$db = new DB_connection();
	
		$query="SELECT * FROM `zahlungsteilnehmer`
				LEFT JOIN `zahlung` USING (z_id)
				WHERE u_id= '".$user_id."' 
				AND g_id= '".$gruppen_id."' 		
				";
	
		// Wenn die Datenbankabfrage erfolgreich ausgef�hrt worden ist
		if ($result = $db->execute($query)) {
	
			// Ergebnis Zeile f�r Zeile verarbeiten
			while ($row = mysqli_fetch_array($result)) {
					
				// neues Model erzeugen
				$model = new Zahlungsteilnehmer();
	
				// Model anhand der Nummer aus der Datenbankabfrage laden
				$model->laden($row["z_id"], $row["u_id"]);
	
				// neues Model ans Ende des $gruppeListe-Arrays anf�gen
				$zahlungListe[] = $model;
			}
	
			// fertige Liste von Gruppe-Objekten zur�ckgeben
			return $zahlungListe;
		}
	}
	public function istzahlungoffen($z_id){
		$offen = false;
		foreach($this ->zahlungsteilnehmerholen($z_id) as $zaehler=> $zahlungsteilnehmer){
			if($zahlungsteilnehmer->getRestbetrag()>0) $offen = true;
		}
		return $offen;
	}
	
	// Alle Teilnehmer an einer Zahlung, die mit z_id übergeben wird
	public static function  zahlungsteilnehmerholen($z_id){
		// Liste initialisieren
		$teilnehmerListe = array ();
		
		$db = new DB_connection();
		
		$query="SELECT * FROM `zahlungsteilnehmer`
				WHERE z_id= '".$z_id."' ";
		
		// Wenn die Datenbankabfrage erfolgreich ausgef�hrt worden ist
		if ($result = $db->execute($query)) {
		
			// Ergebnis Zeile f�r Zeile verarbeiten
			while ($row = mysqli_fetch_array($result)) {
					
				// neues Model erzeugen
				$model = new Zahlungsteilnehmer();
		
				// Model anhand der Nummer aus der Datenbankabfrage laden
				$model->laden($row["z_id"], $row["u_id"]);
		
				// neues Model ans Ende des $gruppeListe-Arrays anf�gen
				$teilnehmerListe[] = $model;
			}
		
			// fertige Liste von Gruppe-Objekten zur�ckgeben
			return $teilnehmerListe;
		}
	}
	
	
	
	
	public function einenzahlungsteilnehmerholen($z_id, $u_id){
		// Liste initialisieren
		$zteilnehmer = 'null';
	
		$db = new DB_connection();
	
		$query="SELECT * FROM `zahlungsteilnehmer`
				WHERE z_id= '".$z_id."' 
				AND u_id= '".$u_id."'; ";
	
		// Wenn die Datenbankabfrage erfolgreich ausgef�hrt worden ist
		if ($result = $db->execute($query)) {
	
			// Ergebnis Zeile f�r Zeile verarbeiten
			while ($row = mysqli_fetch_array($result)) {
					
				// neues Model erzeugen
				$model = new Zahlungsteilnehmer();
	
				// Model anhand der Nummer aus der Datenbankabfrage laden
				$model->laden($row["z_id"], $row["u_id"]);
	
				// neues Model ans Ende des $gruppeListe-Arrays anf�gen
				$zteilnehmer = $model;
			}
	
			// fertige Liste von Gruppe-Objekten zur�ckgeben
			return $zteilnehmer;
		}
	}
	
	
	
	
	
	public function laden ($z_id, $u_id) {
	
		// Datenbankstatement erzeugen
		$dbStmt = new DB_connection();
	
		// DB-Befehl absetzen: alle Basisinformationen des Teams mit der �bergebenen $t_id abfragen
	
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
	
		// zur�ckgeben, ob beim Laden ein Fehler aufgetreten ist
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
	
	//Separate L�schfunktion, da nicht bekannt, ob obige verwendet wird
	public static function teilnehmerloeschen ($z_id, $u_id) {
		$db = new DB_connection();
		
		$query = "DELETE FROM zahlungsteilnehmer
				  WHERE z_id='".$z_id."' AND u_id='".$u_id."';" ;
		
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