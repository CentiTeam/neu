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


class ZahlungbegleichenController extends AbstractActionController {

	function zahlungbegleichenAction() {
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

			//Holen der z_id aus Formular
			$z_id = $_REQUEST['z_id'];
				
			//Laden des Objektes der Klasse Zahlung mit der übergebenen z_id in die Variable $zahlung
			$zahlung = new Zahlung();
			$zahlung->laden($z_id);
				
			//Holen der u_id aus Session
			$user_id=$_SESSION['user']->getU_id();
			
			
			
				
			//Überprüfen, ob User = ersteller
			$ersteller = new Zahlungsteilnehmer();
			$ersteller->laden($z_id, $user_id);
				
			//Zahlungsteilnehmer der Zahlung holen
			$teilnehmerliste = Zahlungsteilnehmer::zahlungsteilnehmerholen($z_id);
			
			//Überprüfen, ob User ein Teilnehmer der Zahlung ist
			$bool_teilnehmer = 0;
			foreach($teilnehmerliste as $counter => $teilpruef){
				if($teilpruef->getUser()->getU_id() == $user_id){
					$bool_teilnehmer = 1;
				}
				else{
					$bool_teilnehmer = 1;
				}
			}
				
			if ($bool_teilnehmer == 1) {
					
				foreach ($teilnehmerliste as $zahlungsteilnehmer)
				{
					//In dem Fall, dass der Restbetrag nicht dem Anteil entspricht, ist die Zahlung teils oder ganz beglichen
					if ($zahlungsteilnehmer->getAnteil()!=$zahlungsteilnehmer->getRestbetrag() AND $zahlungsteilnehmer->getUser()->getU_id()!=$user_id)
					{
						$beglichen++;
					}
				}
					
					
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


				$saved= false;
				$msg = array();
					
				//Wenn die Variable beglichen auf Null steht, kann die Zahlung bearbeitet werden
				if ($beglichen==0)
				{
						
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



							// Fehlerabfrage, ob mehrere Teilnehmer ausgewählt wurden
							$anzahlteilnehmer=0;
								
							for($anzahl=0; $anzahl<$i; $anzahl++) {
								if ($anteile[$anzahl] != "") {
									$anzahlteilnehmer++;
								}
							}



							$zahlungsbeschreibung=$_POST['zahlungsbeschreibung'];

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

									if($anteile[$counter]=="") {
											
										$counter++;
											
									} else {
											
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
								 		$restbetrag="0";
										} else {
											//Der Status für alle anderen Zahlungsteilnehmer ist offen.
								 		$status="offen";
								 		$restbetrag=$anteil;
										}
										$anteil=$anteile[$counter];

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

								//Schreiben des Ereignisses das die Zahlung bearbeitet wurde in die Ereignistabelle der Datenbank
								Gruppenereignis::zahlungbearbeitenEreignis($zahlung, $gruppe);
									
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
				}
				else {
					echo "Diese Zahlung wurde bereits teilweise oder vollst&aumlndig beglichen und kann daher nicht mehr bearbeitet werden";
					$view = new ViewModel([
							'gruppe' => array($gruppe),
							'errors' => $errors,
							'msg' => $msg,
							'zahlung' => array($zahlung),
							'teilnehmerliste' => $teilnehmerliste
					]);

					$view->setTemplate('application/zahlunganzeigen/zahlunganzeigen.phtml');
					return $view;
				}
			}
			else {
				echo "Sie k&oumlnnen diese Zahlung nicht bearbeiten, da Sie sie nicht erstellt haben";
				$view = new ViewModel([
						'gruppe' => array($gruppe),
						'errors' => $errors,
						'msg' => $msg,
						'zahlung' => array($zahlung),
						'teilnehmerliste' => $teilnehmerliste
				]);

				$view->setTemplate('application/zahlunganzeigen/zahlunganzeigen.phtml');
				return $view;
			}

			$zahlungsteilnehmerliste=Zahlungsteilnehmer::zahlungsteilnehmerholen($z_id);

			return new ViewModel([
					'gruppe' => array($gruppe),
					'zahlungsteilnehmer' => array($teilnehmer),
					'msg' => $msg,
					'kategorieListe' => $kategorieliste,
					'mitgliederListe' => $mitgliederliste,
					'erstellungsdatum' => $erstellungsdatum,
					'zahlung' => array($zahlung),
					'zahlungsteilnehmerliste' => $zahlungsteilnehmerliste
			]);
		}
	}
}