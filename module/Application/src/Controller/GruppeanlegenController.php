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
		// TODO Berechtigungsprï¿½fung
		session_start();
		
		//var_dump($_SESSION['angemeldet']);
		//echo "User_id:";
		//var_dump($user->getU_id());

		$errors = array();

		if($_SESSION['angemeldet'] != 'ja') {
				
			array_push($errors, "Sie mÃ¼ssen angemeldet sein um eine Gruppe zu erstellen!");
				
			$view = new ViewModel(array(
					$errors
			));
			$view->setTemplate('application/index/index.phtml');
			return $view;
				
		} else {

			$gruppe = new Gruppe();
			$bildupload = new Bildupload();

			$saved= false;
			$msg = array();

			if ($_REQUEST['speichern']) {

					
				// Schritt 1:  Werte aus Formular einlesen
				$g_id=$_REQUEST["g_id"];
				$gruppenname=$_REQUEST["gruppenname"];
				$gruppenbeschreibung=$_REQUEST["gruppenbeschreibung"];
	
				$uploadedfile=$_REQUEST["uploadedfile"];
				
				//Bilddatei an die Funktion Bildupload übergeben, Rückgabe des Bildpfades
				$path = $bildupload->bildupload($uploadedfile);
				
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
				$gruppe->setGruppenbildpfad($path);
				
				 if ($errorStr == "" && $gruppe->anlegen()) {
				 
				 	if ($path!=false) {
				 		$result = Gruppe::bild($path, $g_id);
				 	}
				 	
				 // array_push($msg, "Gruppe erfolgreich gespeichert!");
				 //  $msg .= "Gruppe erfolgreich gespeichert!";
				 $saved = true;
				 
				 // Neue G_id durch Laden der neu erstellten Gruppe ins Objekt laden
				 $gruppe->laden();
				 
				 $user=$_SESSION['user'];

				 
				 $gruppenmitglied = new Gruppenmitglied();
				 	
				 $gruppenmitglied->setU_id($user->getU_id());
				 $gruppenmitglied->setG_id($gruppe->getG_id());
				 $gruppenmitglied->setGruppenadmin(1);	
				 
				 $verknüpfung=$gruppenmitglied->anlegen();
				 
		
				 } elseif ($errorStr == "") {

				 // array_push($msg, "Datenprï¿½fung in Ordnung, Fehler beim Speichern der Gruppe!");
				 	$msg .= "Datenprï¿½fung in Ordnung, Fehler beim Speichern der Gruppe!";
				 $saved = false;
				 	
				 } else {

				 // array_push($msg, "Fehler bei der Datenprï¿½fung. Gruppe nicht gespeichert!");
				 	$msg .= "Fehler bei der Datenprï¿½fung. Gruppe nicht gespeichert!";
				 $saved = false;

				 }
				 
				
				 // Liste der User-Objekte der Gruppenmitglieder holen
				 $mitgliederliste = User::gruppenmitgliederlisteholen($gruppe->getG_id());
				 
				 $mitgliedschaft=array();
				 
				 // Für jedes Gruppenmitglied mit die Gruppenmitgliedschafts-Infos (inkl. Gruppenadmin) laden
				 // und Mitgliedschaftsinfos in Array speichern, wenn Gruppenmitgliedschaft besteht
				 foreach ($mitgliederliste as $mitglied) {
				 		
				 	// Gruppenmitglied instanzieren
				 	$gruppenmitglied= new Gruppenmitglied();
				 	$gruppenmitglied->laden ($gruppe->getG_id(), $mitglied->getU_id());
				 		
				 	// Wenn Gruppenmitgliedschaft dem User-Objekt entspricht wird das Array weiter befüllt
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