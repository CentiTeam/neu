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


class ZahlungbearbeitenController extends AbstractActionController {

	function zahlungbearbeitenAction() {
		// TODO Berechtigungsprï¿½fung
		session_start();

		$errors = array();

		if($_SESSION['angemeldet'] != 'ja') {

			array_push($errors, "Sie mÃ¼ssen angemeldet sein um eine Zahlung zu bearbeiten!");

			$view = new ViewModel(array(
					$errors
			));
			$view->setTemplate('application/index/index.phtml');
			return $view;

		} else {


			//Liste alle verfï¿½gbaren Kateforien holen
			$kategorieliste = Kategorie::listeHolen();

			$g_id = $_REQUEST['g_id'];	
			$gruppe = new Gruppe();
			$gruppe->laden($g_id);
				
			$mitgliederliste = User::gruppenmitgliederlisteholen($g_id);
			
			// HEutigers Datum als akutellesdatum
			date_default_timezone_set("Europe/Berlin");
			$timestamp=time();
			$aktuellesdatum= date('Y-m-d', $timestamp);
				
			
			
			
			//Holen der z_id aus Formular
			$z_id = $_POST['z_id'];
			
			//Laden des Objektes der Klasse Zahlung mit der übergebenen z_id in die Variable $zahlung
			$zahlung = new Zahlung();
			$zahlung->laden($z_id);


			$saved= false;
			$msg = array();
			
			$user_id=$_SESSION['user']->getU_id();
				
			// Zahlungsteilnehmer-Objekt laden
			$teilnehmer= new Zahlungsteilnehmer();
			$teilnehmer->laden($zahlung->getZ_id(), $user_id);
				
			echo $teilnehmer->getStatus;
			echo "hallo";
			
			if ($_REQUEST['speichern']) {

				// Anteile in Schleife speichern und Ã¼berprÃ¼fen, ob Summe dem Gesamtbetrag entspricht
				$anteile=array();
				$i=0;
				$summe=0;

				foreach ($_POST['anteilsbetrag'] as $zaehler => $anteil) {
					$anteile[]=$anteil;
					$i++;
					$summe += $anteil;
				}


				if($summe != $_REQUEST["betrag"]){
					echo ("Die Anteile mï¿½ssen zusammen der Gesamtsumme entsprechen.");
				}else {

						
					// Schritt 1:  Werte aus Formular einlesen
					$zahlungsbeschreibung=$_REQUEST["zahlungsbeschreibung"];
					$zahlungsdatum=$_REQUEST["zahlungsdatum"];
					$betrag=$_REQUEST["betrag"];
					$kategorie_id=$_REQUEST["kategorie"];
					$aenderungsdatum= date('Y-m-d',$timestamp);
					$gruppen_id=$zahlung->getGruppe()->getG_id();
						

					// verknï¿½pfte Models laden
					if ($kategorie_id != null) {
						$kategorie = new Kategorie();
						if (! $kategorie->laden ($kategorie_id)) {
							$errorStr .= "Keine g&uuml;ltige Kategorie angegeben!<br />";
						}
					}
						
					// verknï¿½pfte Models laden
					if ($gruppen_id != null) {
						$gruppe = new Gruppe();
						if (! $gruppe->laden ($gruppen_id)) {
							$errorStr .= "Keine g&uuml;ltige Gruppe angegeben!<br />";
						}
					}


					// Schritt 2: Daten prï¿½fen und Fehler in Array fÃ¼llen
					$errorStr ="";
					$msg="";

					// #TODO FehlerÃ¼berprÃ¼fung fehlt!


					// Zahlung-Objekt mit Daten aus Request-Array fï¿½llen

					$zahlung->setZahlungsbeschreibung($zahlungsbeschreibung);
					$zahlung->setZahlungsdatum($zahlungsdatum);
					$zahlung->setBetrag($betrag);
					if ($kategorie_id != null){
						$zahlung->setKategorie($kategorie);
					}
					$zahlung->setAenderungsdatum($aenderungsdatum);
						


							// Wenn temporï¿½res Objekt gefï¿½llt wurde kann mit diesen Werten das Objekt ï¿½ber die Bearbeiten-Fkt in die DB geschrieben werden
							if ($errorStr == "" && $zahlung->bearbeiten()) {



								// array_push($msg, "Gruppe erfolgreich gespeichert!");
								//  $msg .= "Gruppe erfolgreich gespeichert!";
								$saved = true;

								// Neue Z_id durch Laden der neu erstellten Gruppe ins Objekt laden
								$zahlung->laden();



								
								// hier war der Code nach if ($_REQUEST['speichern']) { zuvor
								
								//Bisherige Zahlungsteilnehmer löschen
								Zahlungsteilnehmer::loeschen($zahlung->getZ_id());

									
								$counter=0;
								// Legt die zugehï¿½rigen Zahlungsteilnehmer Datensï¿½tze an, auï¿½er fï¿½r sich selbst (info wird aber fï¿½r Anteil benï¿½tigt!)
								foreach ($_POST['zahlungsteilnehmer'] as $key => $value) {

									//Aus der ID des Übergebenen Arrays User-Objekt erstellen
									$akt_benutzer = new User();
									$akt_benutzer->laden($value);
									
									// Variablen befuellen
									$zahlungsteilnehmer=new Zahlungsteilnehmer();

									$zahlungs_id=$zahlung->getZ_id();
										
									if($value == $user_id) {
										//Der Status bei dem, der die Zahlung erstellt hat ist "ersteller", dies erleichtert die nachträgliche Bearbeitung von
										//Zahlungen, da Zahlungen nur bearbeitet werden dürfen, wenn sie nicht den Status "beglichen" Aufweisen.
							 		$status="ersteller";
									} else {
										//Der Status für alle anderen Zahlungsteilnehmer ist offen.
							 		$status="offen";
									}
									$anteil=$anteile[$counter];

									$zahlungsempfaenger=$_SESSION['user'];

									// Variablenwerte in Objekt einlesen
									$zahlungsteilnehmer->setUser($akt_benutzer);
									$zahlungsteilnehmer->setZahlung($zahlung);
									$zahlungsteilnehmer->setStatus($status);
									$zahlungsteilnehmer->setAnteil($anteil);
									$zahlungsteilnehmer->setZahlungsempfaenger($zahlungsempfaenger);

									$zahlungsteilnehmer->anlegen();
										
									$counter++;
								} 
									
								$view = new ViewModel([
										'gruppe' => array($gruppe),
										'errors'   => $errors,
										'msg' => $msg,
										'zahlung' => array($zahlung),
											
								]);

								$view->setTemplate('application/zahlunganzeigen/zahlunganzeigen.phtml');
									
								return $view;
									
								//$zahlungsteilnehmerListe=Zahlungsteilnehmer::listeHolen();
									
								//  Wenn es einen Fehler beim Speichern gab
							} elseif ($errorStr == "") {

								// array_push($msg, "Datenprï¿½fung in Ordnung, Fehler beim Speichern der Gruppe!");
								$msg .= "DatenprÃ¼fung in Ordnung, Fehler beim Speichern der Zahlung!";
								$saved = false;

							} else {

								// array_push($msg, "Fehler bei der Datenprï¿½fung. Gruppe nicht gespeichert!");
								$msg .= "Fehler bei der DatenprÃ¼fung. Zahlung nicht gespeichert!";
								$saved = false;

							}
								
				}
			}




			return new ViewModel([
					'gruppe' => array($gruppe),
					'teilnehmer' => array($teilnehmer),
					'msg' => $msg,
					'kategorieListe' => $kategorieliste,
					'mitgliederListe' => $mitgliederliste,
					'erstellungsdatum' => $erstellungsdatum,
					'zahlung' => array($zahlung)
			]);
		}
	}
}