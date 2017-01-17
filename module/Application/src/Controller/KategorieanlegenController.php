<?php
namespace Application\Controller;


use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Application\Model\Kategorie;




class KategorieanlegenController extends AbstractActionController {

	function kategorieanlegenAction() {
		// TODO Berechtigungsprï¿½fung
		session_start();

		$errors = array();

		if($_SESSION['systemadmin'] != 'ja') {

			array_push($errors, "Sie mÃ¼ssen ein Administrator sein, um eine Kategorie hinzufügen zu können!");

			$view = new ViewModel(array(
					$errors
			));
			$view->setTemplate('application/index/index.phtml');
			return $view;

		} else {

			$kategorie = new Kategorie();

			$saved= false;
			$msg = array();

			if ($_REQUEST['speichern']) {

					
				// Schritt 1:  Werte aus Formular einlesen
				$kategoriebeschreibung=$_REQUEST["kategoriebeschreibung"];
				

				// Schritt 2: Daten prï¿½fen und Fehler in Array fÃ¼llen
				$errorStr ="";
				$msg="";

					
				// Gruppe-Objekt mit Daten aus Request-Array fï¿½llen
				$kategorie->setKategoriebeschreibung($kategoriebeschreibung);
				


				if ($errorStr == "" && $kategorie->anlegen()) {

				
				 $msg .= "Kategorie erfolgreich gespeichert!";
				 $saved = true;
				 	
				 // Neue K_id durch Laden der neu erstellten Gruppe ins Objekt laden
				 $kategorie->laden();
				}	 	
			
			
	
			//	} elseif ($errorStr == "") {

			//	 // array_push($msg, "Datenprï¿½fung in Ordnung, Fehler beim Speichern der Gruppe!");
			//		$msg .= "Datenprï¿½fung in Ordnung, Fehler beim Speichern der Kategorie!";
			//	 $saved = false;

			//	} else {

			//	 // array_push($msg, "Fehler bei der Datenprï¿½fung. Gruppe nicht gespeichert!");
			//		$msg .= "Fehler bei der Datenprï¿½fung. Kategorie nicht gespeichert!";
			//	 $saved = false;

			//	}
			//		
				
				$kategorieListe = Kategorie::listeholen();
				
				$view = new ViewModel([
						'kategorie' => array($kategorie),
						'errors'   => $errors,
						'msg' => $msg,
						'kategorieListe' => $kategorieListe 
				]);
					
				$view->setTemplate('application/kategorien/kategorien.phtml');

				return $view;
			}
		}


		return new ViewModel([
				'kategorie' => array($kategorie),
				'msg' => $msg
		]);

	}

}