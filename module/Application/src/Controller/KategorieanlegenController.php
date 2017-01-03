<?php
namespace Application\Controller;


use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Application\Model\Kategorie;




class KategorieanlegenController extends AbstractActionController {

	function kategorieanlegenAction() {
		// TODO Berechtigungspr�fung
		session_start();

		$errors = array();

		if($_SESSION['systemadmin'] != 'ja') {

			array_push($errors, "Sie müssen ein Administrator sein, um eine Kategorie hinzuf�gen zu k�nnen!");

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
				$k_id=$_REQUEST["k_id"];
				$kategoriebeschreibung=$_REQUEST["kategoriebeschreibung"];
				

				// Schritt 2: Daten pr�fen und Fehler in Array füllen
				$errorStr ="";
				$msg="";

					
				// Gruppe-Objekt mit Daten aus Request-Array f�llen
				$kategorie->setK_id($k_id);
				$kategorie->setKategoriebeschreibung($kategoriebeschreibung);
				


				if ($errorStr == "" && $kategorie->anlegen()) {

				
				 $msg .= "Kategorie erfolgreich gespeichert!";
				 $saved = true;
				 	
				 // Neue K_id durch Laden der neu erstellten Gruppe ins Objekt laden
				 $kategorie->laden();
				}	 	
				
			}
		}
			//	} elseif ($errorStr == "") {

			//	 // array_push($msg, "Datenpr�fung in Ordnung, Fehler beim Speichern der Gruppe!");
			//		$msg .= "Datenpr�fung in Ordnung, Fehler beim Speichern der Kategorie!";
			//	 $saved = false;

			//	} else {

			//	 // array_push($msg, "Fehler bei der Datenpr�fung. Gruppe nicht gespeichert!");
			//		$msg .= "Fehler bei der Datenpr�fung. Kategorie nicht gespeichert!";
			//	 $saved = false;

			//	}
			//		
			//	$view = new ViewModel([
			//			'kategorie' => array($kategorie),
			//			'errors'   => $errors,
			//			'msg' => $msg
			//	]);
			//		
			//	$view->setTemplate('application/kategorien/kategorien.phtml');

			//	return $view;
			//}
		//}


		return new ViewModel([
				'kategorie' => array($kategorie),
				'msg' => $msg
		]);

	}

}