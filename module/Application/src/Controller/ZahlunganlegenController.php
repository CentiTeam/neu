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

			// Liste der User-Objekte der Gruppenmitglieder holen
			$gruppe = new Gruppe();
			$gruppe->laden($_GET['g_id']);
			
			$mitgliederliste = User::gruppenmitgliederlisteholen($gruppe->getG_id());
			
			
			$zahlung = new Zahlung();
				

			$saved= false;
			$msg = array();

			if ($_REQUEST['speichern']) {

					
				// Schritt 1:  Werte aus Formular einlesen
				$z_id=$_REQUEST["z_id"];
				$zahlungsbeschreibung=$_REQUEST["zahlungsbeschreibung"];
				$zahlungsdatum=$_REQUEST["zahlungsdatum"];
				$betrag=$_REQUEST["betrag"];
				$k_id=$_REQUEST["k_id"];
				
				date_default_timezone_set("Europe/Berlin");
				$timestamp=time();
				$erstellungsdatum= date('Y-m-d', $timestamp);
				
				$aenderungsdatum= date('Y-m-d',$timestamp);
				
				
				// Schritt 2: Daten pr�fen und Fehler in Array füllen
				$errorStr ="";
				$msg="";
				
				//TODO
				// Fehlerüberprüfung fehlt!
				
				
				// Zahlung-Objekt mit Daten aus Request-Array f�llen
				$zahlung->setErstellungsdatum($erstellungsdatum);
				$zahlung->setZahlungsbeschreibung($zahlungsbeschreibung);
				$zahlung->setZahlungsdatum($zahlungsdatum);
				$zahlung->setBetrag($betrag);
				$zahlung->setK_id($k_id);
				$zahlung->setAenderungsdatum($aenderungsdatum);

					
				// Wenn tempor�res Objekt gef�llt wurde kann mit diesen Werten das Objekt �ber die anlegen-Fkt in die DB geschrieben werden
				if ($errorStr == "" && $zahlung->anlegen()) {
				
				
				
				 // array_push($msg, "Gruppe erfolgreich gespeichert!");
				 //  $msg .= "Gruppe erfolgreich gespeichert!";
				 $saved = true;
				 	
				 // Neue G_id durch Laden der neu erstellten Gruppe ins Objekt laden
				 $zahlung->laden();
				 	
				 $user=$_SESSION['user'];

				 
				 $i = 0;
				 foreach ($_POST['zahlungsteilnehmer'] as $key => $value) {
				 	
				 	var_dump( $_POST['zahlungsteilnehmer']);
				 	echo "Valiueeee jetzt:";
				 	echo $value;
				 	die("Test");
				 	$zahlungsteilnehmer=new Zahlungsteilnehmer();
				 	
				 	}
				 	/**
				 	if ($i == 0) $ausgabe .= $value;
				 	else $ausgabe .= ', '.$value;
				 	$i++;
				 	*/
				 
				 
				 
				 /**	
				 $zahlungsteilnehmer = new Zahlungsteilnehmer();

				 $gruppenmitglied->setU_id($user->getU_id());
				 $gruppenmitglied->setG_id($gruppe->getG_id());
				 $gruppenmitglied->setGruppenadmin(1);
				 	
				 $verkn�pfung=$gruppenmitglied->anlegen();
				 */	

				 
				 
				 
				 
				} elseif ($errorStr == "") {

				 // array_push($msg, "Datenpr�fung in Ordnung, Fehler beim Speichern der Gruppe!");
					$msg .= "Datenprüfung in Ordnung, Fehler beim Speichern der Zahlung!";
				 $saved = false;

				} else {

				 // array_push($msg, "Fehler bei der Datenpr�fung. Gruppe nicht gespeichert!");
					$msg .= "Fehler bei der Datenprüfung. Zahlung nicht gespeichert!";
				 $saved = false;

				}
					

				
				
				/**
				$mitgliedschaft=array();
					
				
				// F�r jedes Gruppenmitglied mit die Gruppenmitgliedschafts-Infos (inkl. Gruppenadmin) laden
				// und Mitgliedschaftsinfos in Array speichern, wenn Gruppenmitgliedschaft besteht
				foreach ($mitgliederliste as $mitglied) {
						
					// Gruppenmitglied instanzieren
					$gruppenmitglied= new Gruppenmitglied();
					$gruppenmitglied->laden ($gruppe->getG_id(), $mitglied->getU_id());
						
					// Wenn Gruppenmitgliedschaft dem User-Objekt entspricht wird das Array weiter bef�llt
					if ($gruppenmitglied->getU_id() == $mitglied->getU_id()) {
							
						$mitgliedschaft[]=$gruppenmitglied;
							
					}
				}
				*/
					
					
				$view = new ViewModel([
						'gruppe' => array($gruppe),
						'errors'   => $errors,
						'msg' => $msg,
						//'mitgliederListe' => $mitgliederliste,
						// 'mitgliedschaft' => $mitgliedschaft
				]);
					
				$view->setTemplate('application/zahlunganzeigen/zahlunganzeigen.phtml');

				return $view;
			}
		}


		return new ViewModel([
				'gruppe' => array($gruppe),
				'msg' => $msg,
				'kategorieListe' => $kategorieliste,
				'mitgliederListe' => $mitgliederliste,
		]);

	}

}