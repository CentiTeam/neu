<?php
namespace Application\Controller;


use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Application\Model\Gruppe;
use Application\Model\User;
use Application\Model\Gruppenmitglied;
use Application\Model\Bildupload;


class GruppeanlegenController extends AbstractActionController {

	function gruppeanlegenAction() {
		// TODO Berechtigungspr�fung
		session_start();
		
		//var_dump($_SESSION['angemeldet']);
		//echo "User_id:";
		//var_dump($user->getU_id());

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
	
				
				if ($_FILES ["uploadedfile"]["name"] == NULL) {
					$path="";
				} 
				else {
					
					$bildupload = new Bildupload();
				
					$uploadedfile=$_REQUEST["uploadedfile"];
				
				
					//Bilddatei an die Funktion Bildupload �bergeben, R�ckgabe des Bildpfades
					$path = $bildupload->bildupload($uploadedfile);
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
				
				// Gruppenbildpfad setzen oder auch nicht
				
				// Wenn kein Bild hochgeladen werden soll
				if ($_FILES["uploadedfile"]["name"] == NULL) {
					$path= "/img/gruppe_anonym.jpg";
					$gruppe->setGruppenbildpfad($path);
				// Wenn es einen Fehler/Problem beim Upload gibt
				} elseif ($path==false) {
					
					return new ViewModel([
							'gruppe' => array($gruppe),
							'msg' => $msg
					]);
				// Wenn man ein Bild hochladen will und es keine Fehlermeldungen beim Upload gibt
				} else {
					$gruppe->setGruppenbildpfad($path);
				}
				
			
				// Wenn tempor�res Objekt gef�llt wurde kann mit diesen Werten das Objekt �ber die anlegen-Fkt in die DB geschrieben werden
				 if ($errorStr == "" && $gruppe->anlegen()) {
				 	
				 // array_push($msg, "Gruppe erfolgreich gespeichert!");
				 //  $msg .= "Gruppe erfolgreich gespeichert!";
				 $saved = true;
				 
				 // Neue G_id durch Laden der neu erstellten Gruppe ins Objekt laden
				 $gruppe->laden();
				 
				 $user=$_SESSION['user'];
				 $user_id=$_SESSION['user']->getU_id();

				
				 
				 $gruppenmitglied = new Gruppenmitglied();
				 	
				 $gruppenmitglied->setUser($user_id);
				 $gruppenmitglied->setGruppe($gruppe->getG_id());
				 $gruppenmitglied->setGruppenadmin(1);	
				 
				// echo $gruppenmitglied->getUser();
				 echo $gruppenmitglied->getUser()->getU_id();
				 
				 $verknuepfung=$gruppenmitglied->anlegen();
				 
		
				 } elseif ($errorStr == "") {

				 // array_push($msg, "Datenpr�fung in Ordnung, Fehler beim Speichern der Gruppe!");
				 	$msg .= "Datenpr�fung in Ordnung, Fehler beim Speichern der Gruppe!";
				 $saved = false;
				 	
				 } else {

				 // array_push($msg, "Fehler bei der Datenpr�fung. Gruppe nicht gespeichert!");
				 	$msg .= "Fehler bei der Datenpr�fung. Gruppe nicht gespeichert!";
				 $saved = false;

				 }
				 
				
				 // Liste der User-Objekte der Gruppenmitglieder holen
				 $mitgliederliste = User::gruppenmitgliederlisteholen($gruppe->getG_id());
				 
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
				 
				 
				 $view = new ViewModel([
				 		'gruppe' => array($gruppe),
				 		'errors'   => $errors,
				 		'msg' => $msg,
				 		'mitgliederListe' => $mitgliederliste,
				 		'mitgliedschaft' => $mitgliedschaft
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