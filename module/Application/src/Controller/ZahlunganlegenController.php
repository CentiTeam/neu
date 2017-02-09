<?php
namespace Application\Controller;


use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Application\Model\Gruppe;
use Application\Model\User;
use Application\Model\Gruppenmitglied;
use Application\Model\Kategorie;
use Application\Model\Zahlung;
use Application\Model\Zahlungsteilnehmer;
use Application\Model\Gruppenereignis;


class ZahlunganlegenController extends AbstractActionController {

	function zahlunganlegenAction() {
		
		// Berechtigungspr�fung
		session_start();

		if($_SESSION['angemeldet'] == NULL || $_SESSION['systemadmin']) {

			$msg="Nicht berechtigt!";

			$view = new ViewModel([
					'msg' => $msg
		]);
			$view->setTemplate('application/index/index.phtml');
			return $view;

		} else { 
			
			// Berechtigungsprüfung, ob Gruppenmitglied
			$g_id=$_REQUEST['g_id'];
			$user_id=$_SESSION['user']->getU_id();
			
			$aktgruppenmitglied=new Gruppenmitglied();
			$isOK=$aktgruppenmitglied->laden($g_id, $user_id);
			
			// Wenn kein Gruppenmitglied, dann wird die Groupoverview des jew. Users geladen
			if ($isOK==false) {
			
				$msg="Nicht berechtigt!";
					
				$gruppenliste=Gruppenmitglied::eigenelisteholen($user_id);
					
				$view = new ViewModel([
						'gruppenListe' => $gruppenliste,
						'msg' => $msg,
				]);
			
				$view->setTemplate('application/groupoverview/groupoverview.phtml');
			
				return $view;
			}
			
			
			//Liste alle verf�gbaren Kateforien holen
			$kategorieliste = Kategorie::listeHolen();

			
			$gruppe = new Gruppe();
			$gruppe->laden($_REQUEST['g_id']);
			
			
			$mitgliederliste = User::gruppenmitgliederlisteholen($gruppe->getG_id());
			
		// Heutiges Datum als erstellungsdatum
			date_default_timezone_set("Europe/Berlin");
			$timestamp=time();
			$erstellungsdatum= date('Y-m-d', $timestamp);
			
			$zahlung = new Zahlung();
				

			$saved= false;
			

			if ($_REQUEST['speichern']) {
				
				// Anteile in Schleife speichern und überprüfen, ob Summe dem Gesamtbetrag entspricht
				$anteile=array();
				$i=0;
				$summe=0;
				
				foreach ($_POST['anteilsbetrag'] as $zaehler => $anteil) {
					$anteile[]=$anteil;
					$i++;
					$summe += $anteil;
				}
				
				/** SIEHE UNTEN; Fehlermeldung anteile wurde weoter unten reingepackt
				if($summe != $_REQUEST["betrag"]){
				
					echo ("Die Anteile m&uuml;ssen zusammen der Gesamtsumme entsprechen.");
					
				}else {
				*/ 
				
					
					// Schritt 1:  Werte aus Formular einlesen
					$z_id=$_REQUEST["z_id"];
					$zahlungsbeschreibung=$_REQUEST["zahlungsbeschreibung"];
					$zahlungsdatum=$_REQUEST["zahlungsdatum"];
					$betrag=$_REQUEST["betrag"];
					$kategorie_id=$_REQUEST["kategorie"];
					
					//Pr�fen ob Zahlungsdatum das richtige Format hat
					if(check_date($zahlungsdatum,"YYYYmmdd","-")){
						echo '<meta http-equiv="refresh" content="3; URL=http://www.example.com/">';
					}
					else{
						echo '<meta http-equiv="refresh" content="3; URL=http://www.example.com/">';
					}
					
					
				
					//date_default_timezone_set("Europe/Berlin");
					//$timestamp=time();
					//$erstellungsdatum= date('Y-m-d', $timestamp);
				
					$aenderungsdatum= date('Y-m-d',$timestamp);
					$gruppen_id=$_REQUEST["g_id"];
					
					
					
					//Wen Zahlungbeschreibung, Betrag oder Zahlungsdatum nicht eingegeben wurde, dann Fehler
					if ($zahlungsbeschreibung == "" || $betrag == "" || $zahlungsdatum == ""){
						$feldpruefungsnachricht= "Zahlungsbeschreibung, Betrag oder Zahlungsdatum ist leer!<br>";
							
						return new ViewModel([
								'gruppe' => array($gruppe),
								'msg' => $msg,
								'kategorieListe' => $kategorieliste,
								'mitgliederListe' => $mitgliederliste,
								'erstellungsdatum' => $erstellungsdatum,
								'zahlung' => array($zahlung),
								'k_id' => $kategorie_id,
								'err' => $errorStr,
								'feldpruefungsnachricht' => $feldpruefungsnachricht
						]);
					}
					
				
					// verkn�pfte Models laden
					if ($kategorie_id != null) {
						$kategorie = new Kategorie();
						if (! $kategorie->laden ($kategorie_id)) {
							$errorStr .= "Keine g&uuml;ltige Kategorie angegeben!<br />";
						}
					}
					
					// verkn�pfte Models laden
					if ($gruppen_id != null) {
						$gruppe = new Gruppe();
						if (! $gruppe->laden ($gruppen_id)) {
							$errorStr .= "Keine g&uuml;ltige Gruppe angegeben!<br />";
						}
					}
				
				
					// Schritt 2: Daten pr�fen und Fehler in Array füllen
					if($summe != $_REQUEST["betrag"]){
					
						$errorStr.= "Die Anteile m&uuml;ssen zusammen der Gesamtsumme entsprechen.";
							
					}
					
					
					// #TODO Fehlerüberprüfung fehlt!
				
				
					// Zahlung-Objekt mit Daten aus Request-Array f�llen
					$zahlung->setErstellungsdatum($erstellungsdatum);
					$zahlung->setZahlungsbeschreibung($zahlungsbeschreibung);
					$zahlung->setZahlungsdatum($zahlungsdatum);
					$zahlung->setBetrag($betrag);
					if ($kategorie_id != null)
						$zahlung->setKategorie($kategorie);
					$zahlung->setAenderungsdatum($aenderungsdatum);
					if ($gruppen_id != null)
						$zahlung->setGruppe($gruppe); 
					
					
						
						
					// Fehlerabfrage, ob mehrere Teilnehmer ausgew�hlt wurden	
					$anzahlteilnehmer=0;
					
					for($anzahl=0; $anzahl<$i; $anzahl++) {
						if ($anteile[$anzahl] != "") {
							$anzahlteilnehmer++;
						}
					}
						
					$zahlungsbeschreibung=$_POST['zahlungsbeschreibung'];
					
					/** Sollte wegfallen, da man nicht selbst teilnehmer sein muss
					if ($anzahlteilnehmer <= 1){
						$msg="Du bist momentan der einzige Zahlungsteilnehmer. W&auml;hl noch ein weiteres Gruppenmitglied aus!";
								
						return new ViewModel([
								'gruppe' => array($gruppe),
								'msg' => $msg,
								'kategorieListe' => $kategorieliste,
								'mitgliederListe' => $mitgliederliste,
								'erstellungsdatum' => $erstellungsdatum,
								'zahlung' => array($zahlung)
					
						]);
					}
					*/				
						
					
					// Wenn tempor�res Objekt gef�llt wurde kann mit diesen Werten das Objekt �ber die anlegen-Fkt in die DB geschrieben werden
					if ($errorStr == "" && $zahlung->anlegen()) {
				
				
						 // array_push($msg, "Gruppe erfolgreich gespeichert!");
						 //  $msg .= "Gruppe erfolgreich gespeichert!";
						 $saved = true;
				 	
					 	// Neue Z_id durch Laden der neu erstellten Gruppe ins Objekt laden
						 $zahlung->laden();
				 	
						 $user_id=$_SESSION['user']->getU_id();
				 
				
				 
						// hier war der Code nach if ($_REQUEST['speichern']) { zuvor
 				 		
				 
						 $counter=0;
						 $temp;
						 // Legt die zugeh�rigen Zahlungsteilnehmer Datens�tze an, au�er f�r sich selbst (info wird aber f�r Anteil ben�tigt!)
						 foreach ($_POST['zahlungsteilnehmer'] as $key => $value) {
						 	
						 	if($anteile[$counter]=="" && $value !=$user_id) {
						 		
						 		$counter++;
						 		
						 	} else {
						 	
							 	//Aus der ID des �bergebenen Arrays User-Objekt erstellen
							 	$akt_benutzer = new User();
							 	$akt_benutzer->laden($value);
				 	
							 	// Variablen befuellen
			 					$zahlungsteilnehmer=new Zahlungsteilnehmer();
			 		
						 		$zahlungs_id=$zahlung->getZ_id();
					 		
						 		// Wenn der Ersteller nicht mitzahlt
						 		if($anteile[$counter]) {
						 			$anteil=$anteile[$counter];
						 		} else {
						 			$anteil="0";
						 		}
						 		
					 		
						 		if($value == $user_id) {
							 		//Der Status bei dem, der die Zahlung erstellt hat ist "ersteller", dies erleichtert die nachtr�gliche Bearbeitung von
							 		//Zahlungen, da Zahlungen nur bearbeitet werden d�rfen, wenn sie nicht den Status "beglichen" Aufweisen.
								 		$status="ersteller"; 
								 		$restbetrag="0";
								 		$temp = $zahlungsteilnehmer;
							 	} else {
							 		//Der Status f�r alle anderen Zahlungsteilnehmer ist offen.
								 		$status="offen";
								 		$restbetrag=$anteil;
						 		}
				 	
							 	$zahlungsempfaenger=$_SESSION['user'];
	
							 	// Variablenwerte in Objekt einlesen
							 	$zahlungsteilnehmer->setUser($akt_benutzer); 
						 		$zahlungsteilnehmer->setZahlung($zahlung);
					 			$zahlungsteilnehmer->setStatus($status);
							 	$zahlungsteilnehmer->setAnteil($anteil); 
								$zahlungsteilnehmer->setZahlungsempfaenger($zahlungsempfaenger);
								$zahlungsteilnehmer->setRestbetrag($restbetrag);
						 		
								$zahlungsteilnehmer->anlegen();
								
								$counter++;
						 	}
						 	
						 }
						 $temp ->ausgleichen();
						
						 
						//Erstellen des Ereignisses f�r Gruppenverlauf und Speicher in DB
						$zahlungsersteller = $_SESSION['user']; 						 
						Gruppenereignis::zahlunganlegenEreignis($zahlung, $gruppe, $zahlungsersteller);
						
						//PArt von Tanja: Zahlungsteilnehmerliste laden beim Anlegen
						$teilnehmerliste=Zahlungsteilnehmer::zahlungsteilnehmerholen($zahlungs_id);
						// Part Tanja zu Ende
						
						$veraenderbar=false;
						$schonbeglicheneZahlungen=false;
						
						// Abprüfen, ob die Zahlung gleich automatisch bereits ganz oder teilweise beglichen worden ist
						foreach ($teilnehmerliste as $zahlungsteilnehmer)
						{
						
							//In dem Fall, dass der Restbetrag nicht dem Anteil entspricht, ist die Zahlung teils oder ganz beglichen
							if ($zahlungsteilnehmer->getAnteil()!=$zahlungsteilnehmer->getRestbetrag() && $zahlungsteilnehmer->getUser()->getU_id()!=$user_id)
							{
								$schonbeglicheneZahlungen=true;
							}
						}
						
						
						if ($schonbeglicheneZahlungen==false) {
							$veraenderbar=true;
						}
						
						
						
						
						
						 $view = new ViewModel([
						 		'gruppe' => array($gruppe),
						 		'errors'   => $errors,
						 		'msg' => $msg,
						 		'zahlung' => array($zahlung),
						 		'teilnehmerliste' => $teilnehmerliste,
						 		'veraenderbar' => $veraenderbar
						 
						 ]);
						 	
						 $view->setTemplate('application/zahlunganzeigen/zahlunganzeigen.phtml');
						 
						 return $view;
				 
					//$zahlungsteilnehmerListe=Zahlungsteilnehmer::listeHolen();
				 
					//  Wenn es einen Fehler beim Speichern gab
					} elseif ($errorStr == "") {

						 // array_push($msg, "Datenpr�fung in Ordnung, Fehler beim Speichern der Gruppe!");
						$msg .= "Datenprüfung in Ordnung, Fehler beim Speichern der Zahlung!";
						$saved = false;
				
					} else {

						// array_push($msg, "Fehler bei der Datenpr�fung. Gruppe nicht gespeichert!");
						$msg .= "Fehler bei der Datenprüfung. Zahlung nicht gespeichert!<br>";
					 	$saved = false;

					}
				// Siehe OBEN!!!}
			}
		

		

			return new ViewModel([
					'gruppe' => array($gruppe),
					'msg' => $msg,
					'kategorieListe' => $kategorieliste,
					'mitgliederListe' => $mitgliederliste,
					'erstellungsdatum' => $erstellungsdatum,
					'zahlung' => array($zahlung),
					'k_id' => $kategorie_id,
					'err' => $errorStr
			]);
		}
	}
}