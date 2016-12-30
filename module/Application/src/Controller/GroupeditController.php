<?php
namespace Application\Controller;


use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Application\Model\Gruppe;


class GroupeditController extends AbstractActionController {

	function groupeditAction() {
		// TODO Berechtigungsprï¿½fung
		session_start();

		$errors = array();

		if($_SESSION['angemeldet'] != 'ja') {
				
			array_push($errors, "Sie mÃ¼ssen angemeldet sein um eine Gruppe zu bearbeiten!");
				
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
				$gruppenbildpfad=$_REQUEST["gruppenbildpfad"];


				// Schritt 2: Daten prï¿½fen und Fehler in Array fÃ¼llen
				$errorStr ="";
				$msg="";

				if ($gruppenname=="Kinderporno") {
					$errorStr .="Der Gruppenname darf nicht Kinderporno heiÃŸen!<br>";
				}
					
					
				// Gruppe-Objekt mit Daten aus Request-Array fï¿½llen
				$gruppe->setG_id($g_id);
				$gruppe->setGruppenname($gruppenname);
				$gruppe->setGruppenbeschreibung($gruppenbeschreibung);
				$gruppe->setGruppenbildpfad($gruppenbildpfad);
					
				
				 if ($errorStr == "" && $gruppe->bearbeiten()) {
		
				 // array_push($msg, "Gruppe erfolgreich gespeichert!");
				 $msg .= "Gruppe erfolgreich gespeichert!";
				 $saved = true;
				 	
				 } elseif ($errorStr == "") {

				 // array_push($msg, "Datenprï¿½fung in Ordnung, Fehler beim Speichern der Gruppe!");
				 	$msg .= "Datenprüfung in Ordnung, Fehler beim Speichern der Gruppe!";
				 $saved = false;
				 	
				 } else {

				 // array_push($msg, "Fehler bei der Datenprï¿½fung. Gruppe nicht gespeichert!");
				 	$msg .= "Fehler bei der Datenprüfung. Gruppe nicht gespeichert!";
				 $saved = false;

				 }
				 
				 $view = new ViewModel([
				 		'gruppe' => array($gruppe),
				 		'errors'   => $errors,
				 		'msg' => $msg
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