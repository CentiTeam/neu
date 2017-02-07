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
		
		// Berechtigungsprüfung: Pr�fen, ob Angemeldet und danach ob Gruppeadmin
		if ($_SESSION['angemeldet']==NULL) {
		
			$msg="Nicht berechtigt!";
		
			$view = new ViewModel([
					'msg' => $msg,
			]);
		
			$view->setTemplate('application/index/index.phtml');
		
			return $view;
		
		}
		
		
		// Pr�fen, ob Gruppeadmin
		$user_id=$_SESSION['user']->getU_id();
		$gruppen_id=$_REQUEST['g_id'];
		
		$gruppenmitglied=new Gruppenmitglied();
		$isOK=$gruppenmitglied->laden($gruppen_id, $user_id);
		
		if ($isOK==false || $gruppenmitglied->getGruppenadmin()=="0") {
				
			$errStr="Nicht berechtigt!";
			$gruppenliste=Gruppenmitglied::eigenelisteholen($user_id);
				
			$view = new ViewModel([
					'gruppenListe' => $gruppenliste,
					'err' => $errStr,
					'u_id' => $user_id
			]);
		
			$view->setTemplate('application/groupoverview/groupoverview.phtml');
				
			return $view;
		}
		

			$gruppe = new Gruppe();
			
			if (! $gruppe->laden($_REQUEST['g_id'])) {
				$msg= "Fehler beim Laden der Gruppe!";	
			}

			$saved= false;
			$msg = array();
			
			// Wenn das Forumlar abgesendet worden ist
			if ($_REQUEST['speichern']) {

					
				// Schritt 1:  Werte aus Formular einlesen
				$g_id=$_REQUEST["g_id"];
				$gruppenname=$_REQUEST["gruppenname"];
				$gruppenbeschreibung=$_REQUEST["gruppenbeschreibung"];
				
				//Wen kein Gruppenname eingegeben wurde, dann Fehler
				if ($gruppenname == ""){
					$feldpruefungsnachricht= "Der Gruppenname ist leer!<br>";
				
					$view = new ViewModel([
							'gruppe' => array($gruppe),
							'errors'   => $errors,
							'msg' => $msg,
							'mitgliederListe' => $mitgliederliste,
							'aktgruppenmitglied' => $aktgruppenmitglied,
							'feldpruefungsnachricht' => $feldpruefungsnachricht
					]);
				
				// Wenn kein neues Bild hochgeladen wird, wird das bereits existierende Bild geladen
				if ($_FILES ["uploadedfile"]["name"] == NULL) {
					$path=$gruppe->getGruppenbildpfad();
				}
				
				// Ansonsten wird das eingelesene Bild hochgeladen
				else {
					
					$bildupload = new Bildupload();
					
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
					
				// Wenn es keine Fehler bei den eingelesenen Daten gibt wird das Gruppe-Objekt in die DB gespeichert
				 if ($errorStr == "" && $gruppe->bearbeiten()) {
		
					 $msg .= "Gruppe erfolgreich gespeichert!";
					 $saved = true;
				 	
				 } elseif ($errorStr == "") {

				 	$msg .= "Datenpr�fung in Ordnung, Fehler beim Speichern der Gruppe!";
					$saved = false;
				 	
				 } else {
				 	
				 	$msg .= "Fehler bei der Datenpr�fung. Gruppe nicht gespeichert!";
					$saved = false;
				 }
				 
				 
				 // Gruppenmitgliedsdaten des aktuellen Nutzer laden wg. Adminprüfung
				 $aktgruppenmitglied=new Gruppenmitglied();
				 $aktgruppenmitglied->laden($g_id, $user_id);
				 
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


		return new ViewModel([
				'gruppe' => array($gruppe),
				'msg' => $msg
		]);

	}

}