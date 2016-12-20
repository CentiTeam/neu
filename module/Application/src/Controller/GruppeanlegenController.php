<?php
namespace Application\Controller;


use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Application\Model\Gruppe;


class GruppeanlegenController extends AbstractActionController {

	function gruppeanlegenAction() {
		// TODO Berechtigungspr�fung
		session_start();

		$errors = array();

		if($_SESSION['angemeldet'] != 'ja') {
				
			array_push($errors, "Sie müssen angemeldet sein um eine Gruppe zu erstellen!");
				
			$view = new ViewModel(array(
					$errors
			));
			$view->setTemplate('application/index/index.phtml');
			return $view;
				
		} else {

			$gruppe = new Gruppe();

			$saved= false;
			$msg = array();

			if ($_REQUEST['speichern']) {

					
				// Schritt 1:  Werte aus Formular einlesen
				$g_id=$_REQUEST["g_id"];
				$gruppenname=$_REQUEST["gruppenname"];
				$gruppenbeschreibung=$_REQUEST["gruppenbeschreibung"];
				$gruppenbildpfad=$_REQUEST["gruppenbildpfad"];


				// Schritt 2: Daten pr�fen und Fehler in Array füllen
				$errorStr ="";

				if ($gruppenname=="Kinderporno") {
					$errorStr .="Der Gruppenname darf nicht Kinderporno heißen!<br>";
				}
					
					
				// Gruppe-Objekt mit Daten aus Request-Array f�llen
				$gruppe->setG_id($g_id);
				$gruppe->setGruppenname($gruppenname);
				$gruppe->setGruppenbeschreibung($gruppenbeschreibung);
				$gruppe->setGruppenbildpfad($gruppenbildpfad);

				echo "Jetzt kommt der neue Test";
				var_dump($gruppe->getGruppenname());
					
				if($gruppe->getGruppenname()==null) die("Gruppenname wurde nicht eingelesen.");
					
				// $gruppe->anlegen();
				// if ($isOK) die ("Gruppeanlegen funktion wurde ausgef�hrt!!");
				
				 if ($errorStr == "" && $gruppe->anlegen()) {

				 array_push($msg, "Gruppe erfolgreich gespeichert!");
				 $saved = true;
				 	
				 } elseif ($errorStr == "") {

				 array_push($msg, "Datenpr�fung in Ordnung, Fehler beim Speichern der Gruppe!");
				 $saved = false;
				 	
				 } else {

				 array_push($msg, "Fehler bei der Datenpr�fung. Gruppe nicht gespeichert!");
				 $saved = false;

				 }
				 
				 $view = new ViewModel([
				 		'gruppe' => array($gruppe),
				 		'errors'   => $errors,
				 		'msg' => $msg
				 ]);
				 
				 $view->setTemplate('application/groupoverview/groupoverview.phtml');
				 	
				 return $view;
			}
		}


		return new ViewModel();

	}

}