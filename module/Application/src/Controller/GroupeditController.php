<?php
namespace Application\Controller;


use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Application\Model\Gruppe;
use Application\Model\Bildupload;
use Application\Model\User;
use Application\Model\Gruppenmitglied;

class GroupeditController extends AbstractActionController {

	function groupeditAction() {
		// TODO Berechtigungspr�fung
		session_start();
		
		// Pr�fen, ob Gruppeadmin
		
		$user_id=$_SESSION['user']->getU_id();
		$gruppen_id=$_GET['g_id'];
		
		$gruppenmitglied=new Gruppenmitglied();
		$gruppenmitglied->laden($gruppen_id, $user_id);
		
		// Berechtigungsprüfung: Pr�fen, ob Gruppeadmin
		if ($gruppenmitglied->getGruppenadmin()=="0") {
				
			$errStr="Nicht berechtigt!";
			$gruppenliste=Gruppe::eigenelisteholen($user_id);
	
		
			$view = new ViewModel([
					'gruppenListe' => $gruppenliste,
					'u_id' => $user_id,
					'err' => $errStr
			]);
		
			$view->setTemplate('application/groupoverview/groupoverview.phtml');
				
			return $view;
		}
		
		
		
		
		$errors = array();

		if($_SESSION['angemeldet'] != 'ja') {

			array_push($errors, "Sie müssen angemeldet sein um eine Gruppe zu bearbeiten!");
			$view = new ViewModel(array(
					$errors
			));
			$view->setTemplate('application/index/index.phtml');
			return $view;
				
		} else {

			$gruppe = new Gruppe();
			
			if (! $gruppe->laden($_REQUEST['g_id'])) {
				array_push($errors, "Fehler beim Laden der Gruppe!");	
			}

			$saved= false;
			$msg = array();

			if ($_REQUEST['speichern']) {

					
				// Schritt 1:  Werte aus Formular einlesen
				$g_id=$_REQUEST["g_id"];
				$gruppenname=$_REQUEST["gruppenname"];
				$gruppenbeschreibung=$_REQUEST["gruppenbeschreibung"];
				
				
				if ($_FILES ["uploadedfile"]["name"] == NULL) {
					$path=$gruppe->getGruppenbildpfad();
				}
				else {
					
					$bildupload = new Bildupload();
					
					// Schritt 1:  Werte aus Formular einlesen
					$uploadedfile=$_REQUEST["uploadedfile"];
					
					//Bilddatei an die Funktion Bildupload �bergeben, R�ckgabe des Bildpfades
					$path = $bildupload->bildupload($uploadedfile);
					
					
					if ($path == false) {
							
						$gruppe = new Gruppe();
							
						$gruppe->laden($g_id);
							
						$view = new ViewModel([
								'gruppe' => array($gruppe)
						]);
							
						$view->setTemplate('application/groupedit/groupedit.phtml');
					
						return $view;
					}
				}


				// Schritt 2: Daten pr�fen und Fehler in Array füllen
				$errorStr ="";
				$msg="";

				if ($gruppenname=="Kinderporno") {
					$errorStr .="Der Gruppenname darf nicht Kinderporno heißen!<br>";
				}
					
					
				// Gruppe-Objekt mit Daten aus Request-Array f�llen
				$gruppe->setG_id($g_id);
				$gruppe->setGruppenname($gruppenname);
				$gruppe->setGruppenbeschreibung($gruppenbeschreibung);
				$gruppe->setGruppenbildpfad($path);
					
				
				 if ($errorStr == "" && $gruppe->bearbeiten()) {
		
				 // array_push($msg, "Gruppe erfolgreich gespeichert!");
				 $msg .= "Gruppe erfolgreich gespeichert!";
				 $saved = true;
				 	
				 } elseif ($errorStr == "") {

				 // array_push($msg, "Datenpr�fung in Ordnung, Fehler beim Speichern der Gruppe!");
				 	$msg .= "Datenpr�fung in Ordnung, Fehler beim Speichern der Gruppe!";
				 $saved = false;
				 	
				 } else {

				 // array_push($msg, "Fehler bei der Datenpr�fung. Gruppe nicht gespeichert!");
				 	$msg .= "Fehler bei der Datenpr�fung. Gruppe nicht gespeichert!";
				 $saved = false;

				 }
				 
				 
				 // Gruppenmitgliedsdaten des aktuellen Nutzer laden wg. Adminprüfung
				 $aktgruppenmitglied=$gruppenmitglied;
				 
				 // Gruppenmitgliederliste anzeigen
				 $mitgliederliste=Gruppenmitglied::gruppenmitgliederlisteHolen($g_id);
				 
				 
				 $view = new ViewModel([
				 		'gruppe' => array($gruppe),
				 		'errors'   => $errors,
				 		'msg' => $msg,
				 		'mitgliederListe' => $mitgliederliste,
				 		'aktgruppenmitglied' => $aktgruppenmitglied
				 ]);
				 
				 $view->setTemplate('application/groupshow/groupshow.phtml');
				 	
				 return $view;
			}
		}


		return new ViewModel([
				'gruppe' => array($gruppe),
				'msg' => $msg
		]);

	}

}