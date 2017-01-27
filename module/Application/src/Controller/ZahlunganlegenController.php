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
		// TODO Berechtigungspr�fung
		session_start();
		  
		$errors = array();

		if($_SESSION['angemeldet'] != 'ja') {

			array_push($errors, "Sie müssen angemeldet sein um eine Zahlung zu erstellen!");

			$view = new ViewModel(array(
					$errors
			));
			$view->setTemplate('application/index/index.phtml');
			return $view;

		} else {
			
		
			//Liste alle verf�gbaren Kateforien holen
			$kategorieliste = Kategorie::listeHolen();

			
			$gruppe = new Gruppe();
			$gruppe->laden($_REQUEST['g_id']);
			
			
			$mitgliederliste = User::gruppenmitgliederlisteholen($gruppe->getG_id());
			
			// HEutigers Datum als erstellungsdatum
			date_default_timezone_set("Europe/Berlin");
			$timestamp=time();
			$erstellungsdatum= date('Y-m-d', $timestamp);
			
			$zahlung = new Zahlung();
				

			$saved= false;
			$msg = array();

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
				
				
				
				echo "Hier sind die Anteile";
				var_dump($anteile);
				echo "Hier ist Anteil-Ende";
				
				/**		TODO Problem: Im Array Zahlungsteilnehmer stehen nur die Werte, die ein H�kchen bekommen
				 					  Im Array Anteile werden jedoch alle Anteile eingespeichert, gleich ob da ein H�kchen war oder nicht
				 					  Das f�hrt dazu, dass in dem Fall, dass ein Teilnehmer ausgelassen wird, der falsche Anteil ausgelesen wird
				
				//Feststellen, ob f�r das gesetzte H�kchen auch ein Anteil angegeben wurde
				$counter=0;
				foreach ($_POST['zahlungsteilnehmer'] as $key => $value) {
						
						
					if ($anteile[$counter]=="")
					{
						echo "Jeder ausgew&aumlhlte Teilnehmer muss einen Anteil zugewiesen bekommen";
				
						$view = new ViewModel([
								'gruppe' => array($gruppe),
								'msg' => $msg,
								'kategorieListe' => $kategorieliste,
								'mitgliederListe' => $mitgliederliste,
								'erstellungsdatum' => $erstellungsdatum
						]);
				
						return $view;
					}
					$counter++;
				}
				*/
				
// 				echo "betrag:";
// 				var_dump ($_REQUEST["betrag"]);
				if($summe != $_REQUEST["betrag"]){
					echo "summe";
					var_dump ($summe);
				
					echo ("Die Anteile m�ssen zusammen der Gesamtsumme entsprechen.");
				}else {
				
					
					// Schritt 1:  Werte aus Formular einlesen
					$z_id=$_REQUEST["z_id"];
					$zahlungsbeschreibung=$_REQUEST["zahlungsbeschreibung"];
					$zahlungsdatum=$_REQUEST["zahlungsdatum"];
					$betrag=$_REQUEST["betrag"];
					$kategorie_id=$_REQUEST["kategorie"];
				
					//date_default_timezone_set("Europe/Berlin");
					//$timestamp=time();
					//$erstellungsdatum= date('Y-m-d', $timestamp);
				
					$aenderungsdatum= date('Y-m-d',$timestamp);
					$gruppen_id=$_REQUEST["g_id"];
					
				
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
					$errorStr ="";
					$msg="";
				
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
						
					if ($anzahlteilnehmer <= 1){
						$msg="Du bist momentan der einzige Zahlungsteilnehmer. W�hl noch ein weiteres Gruppenmitglied aus!";
								
						return new ViewModel([
								'gruppe' => array($gruppe),
								'msg' => $msg,
								'kategorieListe' => $kategorieliste,
								'mitgliederListe' => $mitgliederliste,
								'erstellungsdatum' => $erstellungsdatum,
								'zahlung' => array($zahlung)
					
						]);
					}
// 						echo "gruppe derr Zhalung";
// 						var_dump($zahlung->getGruppe());
						
					
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
						 	
						 	
						 	if($anteile[$counter]=="") {
						 		
						 		$counter++;
						 		
						 	} else {
						 	
							 	//Aus der ID des �bergebenen Arrays User-Objekt erstellen
							 	$akt_benutzer = new User();
							 	$akt_benutzer->laden($value);
				 	
							 	// Variablen befuellen
			 					$zahlungsteilnehmer=new Zahlungsteilnehmer();
			 		
						 		$zahlungs_id=$zahlung->getZ_id();
					 		
						 		$anteil=$anteile[$counter];
					 		
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
							
								var_dump($zahlungsteilnehmer->getUser()->getVorname());
								
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
						
						 $view = new ViewModel([
						 		'gruppe' => array($gruppe),
						 		'errors'   => $errors,
						 		'msg' => $msg,
						 		'zahlung' => array($zahlung),
						 		'teilnehmerliste' => $teilnehmerliste
						 
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
						$msg .= "Fehler bei der Datenprüfung. Zahlung nicht gespeichert!";
					 	$saved = false;

					}
				}
			}
		

		

			return new ViewModel([
					'gruppe' => array($gruppe),
					'msg' => $msg,
					'kategorieListe' => $kategorieliste,
					'mitgliederListe' => $mitgliederliste,
					'erstellungsdatum' => $erstellungsdatum,
					'zahlung' => array($zahlung)
			]);
		}
	}
}