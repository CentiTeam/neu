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
			
			// HEutigers Datum als erstellungsdatum
			date_default_timezone_set("Europe/Berlin");
			$timestamp=time();
			$erstellungsdatum= date('Y-m-d', $timestamp);
			
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
				
				//date_default_timezone_set("Europe/Berlin");
				//$timestamp=time();
				//$erstellungsdatum= date('Y-m-d', $timestamp);
				
				$aenderungsdatum= date('Y-m-d',$timestamp);
				$gruppen_id=$gruppe->getG_id();
				
				
				// Schritt 2: Daten pr�fen und Fehler in Array füllen
				$errorStr ="";
				$msg="";
				
				// #TODO Fehlerüberprüfung fehlt!
				
				
				// Zahlung-Objekt mit Daten aus Request-Array f�llen
				$zahlung->setErstellungsdatum($erstellungsdatum);
				$zahlung->setZahlungsbeschreibung($zahlungsbeschreibung);
				$zahlung->setZahlungsdatum($zahlungsdatum);
				$zahlung->setBetrag($betrag);
				$zahlung->setK_id($k_id);
				$zahlung->setAenderungsdatum($aenderungsdatum);
				$zahlung->setG_id($gruppen_id); 

					
				// Wenn tempor�res Objekt gef�llt wurde kann mit diesen Werten das Objekt �ber die anlegen-Fkt in die DB geschrieben werden
				if ($errorStr == "" && $zahlung->anlegen()) {
				
				
				
				 // array_push($msg, "Gruppe erfolgreich gespeichert!");
				 //  $msg .= "Gruppe erfolgreich gespeichert!";
				 $saved = true;
				 	
				 // Neue Z_id durch Laden der neu erstellten Gruppe ins Objekt laden
				 $zahlung->laden();
				 	
				 $user_id=$_SESSION['user']->getU_id();
				 
				
				 
				 $anteile=array();
				 $i=0;
				 
				 foreach ($_POST['anteilsbetrag'] as $zaehler => $anteil) {

				 	$anteile[]=$anteil;
				 	$i++;
				 }
				 
				 
				 $counter=0;
				 // Legt die zugeh�rigen Zahlungsteilnehmer Datens�tze an, au�er f�r sich selbst (info wird aber f�r Anteil ben�tigt!)
				 foreach ($_POST['zahlungsteilnehmer'] as $key => $value) {
				 	
				 	// Variablen befuellen
			 		$zahlungsteilnehmer=new Zahlungsteilnehmer();
			 		
				 	$zahlungs_id=$zahlung->getZ_id();
				 	
				 	if($value == $user_id) {
					 		$status="beglichen"; 
				 	} else {
					 		$status="offen";
				 	}
				 	$anteil=$anteile[$counter];
				 	
				 	$zahlungsempfaenger=$_SESSION['user']->getU_id();
	
				 	// Variablenwerte in Objekt einlesen
				 	$zahlungsteilnehmer->setU_id($value);
				 	$zahlungsteilnehmer->setZ_id($zahlungs_id);
				 	$zahlungsteilnehmer->setStatus($status);
				 	$zahlungsteilnehmer->setAnteil($anteil);
					$zahlungsteilnehmer->setZahlungsempfaenger($zahlungsempfaenger);
				 		
					$zahlungsteilnehmer->anlegen();
					
					$counter++;
				 }
				 
				 
				} elseif ($errorStr == "") {

				 // array_push($msg, "Datenpr�fung in Ordnung, Fehler beim Speichern der Gruppe!");
					$msg .= "Datenprüfung in Ordnung, Fehler beim Speichern der Zahlung!";
				 $saved = false;

				} else {

				 // array_push($msg, "Fehler bei der Datenpr�fung. Gruppe nicht gespeichert!");
					$msg .= "Fehler bei der Datenprüfung. Zahlung nicht gespeichert!";
				 $saved = false;

				}
					
					
				$view = new ViewModel([
						'gruppe' => array($gruppe),
						'errors'   => $errors,
						'msg' => $msg,
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
				'erstellungsdatum' => $erstellungsdatum
		]);

	}

}