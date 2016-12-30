<?php
namespace Application\Controller;


use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Application\Model\Gruppe;
use Application\Model\User;
use Application\Model\Gruppenmitglied;


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
				$msg="";

				if ($gruppenname=="Kinderporno") {
					$errorStr .="Der Gruppenname darf nicht Kinderporno heißen!<br>";
				}
					
				// Gruppe-Objekt mit Daten aus Request-Array f�llen
				$gruppe->setG_id($g_id);
				$gruppe->setGruppenname($gruppenname);
				$gruppe->setGruppenbeschreibung($gruppenbeschreibung);
				$gruppe->setGruppenbildpfad($gruppenbildpfad);
				
				
				 if ($errorStr == "" && $gruppe->anlegen()) {
		
				 // array_push($msg, "Gruppe erfolgreich gespeichert!");
				 $msg .= "Gruppe erfolgreich gespeichert!";
				 $saved = true;
				 
				 // Neue G_id durch Laden der neu erstellten Gruppe ins Objekt laden
				 $gruppe->laden();
				 
				 $user = new User();
				 
				 $_SESSION['user'] = $user;
				 echo "USER-OBJEKT:";
				 var_dump($user);
				 
				 $gruppenmitglied = new Gruppenmitglied();
				 	
				 $gruppenmitglied->setU_id($user->getU_id());
				 $gruppenmitglied->setG_id($gruppe->getG_id());
				 $gruppenmitglied->setGruppenadmin(1);
				 
				 echo "GRUPPENMITGLIED-OBJEKT:";
				 var_dump($gruppenmitglied);
				 	
				 
				 $verkn�pfung=$gruppenmitglied->anlegen();
				 
				
				 
				 
				 
				
				 
				 } elseif ($errorStr == "") {

				 // array_push($msg, "Datenpr�fung in Ordnung, Fehler beim Speichern der Gruppe!");
				 	$msg .= "Datenpr�fung in Ordnung, Fehler beim Speichern der Gruppe!";
				 $saved = false;
				 	
				 } else {

				 // array_push($msg, "Fehler bei der Datenpr�fung. Gruppe nicht gespeichert!");
				 	$msg .= "Fehler bei der Datenpr�fung. Gruppe nicht gespeichert!";
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