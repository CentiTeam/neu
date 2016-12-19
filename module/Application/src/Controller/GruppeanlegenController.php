<?php
namespace Application\Controller;


use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Application\Model\Gruppe;


class GruppeanlegenController extends AbstractActionController {
	 
	function gruppeanlegenAction() {
		// TODO Berechtigungspr�fung
		session_start();
		
		$user=$_SESSION['user'];
		
		$errors = array(); 
		
		// if($_SESSION['angemeldet'] != 'ja') {
		if(!$user[0]->isloggedin()) {
			
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
				
			var_dump("$gruppenname");
				
			
			// Gruppe-Objekt mit Daten aus Request-Array f�llen
			$gruppe->setGruppenname($gruppenname);
			$gruppe->setGruppenbeschreibung($gruppenbeschreibung);
			$gruppe->setGruppenbildpfad($gruppenbildpfad);
			 
			 
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
			 
			var_dump($gruppe);
			 
		}
		}


		return new ViewModel([
				'gruppe' => array($gruppe),
				'errors'   => $errors,
				'msg' => $msg
		]);

	}

}