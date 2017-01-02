<?php
namespace Application\Controller;


use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Application\Model\Kategorie;


class KategorieeditController extends AbstractActionController {

	function kategorieeditAction() {
		// TODO Berechtigungsprï¿½fung
		session_start();

		$errors = array();

		if($_SESSION['systemadmin'] != 'ja') {

			array_push($errors, "Sie mÃ¼ssen ein Administrator sein, um eine Kategorie zu bearbeiten!");

			$view = new ViewModel(array(
					$errors
			));
			$view->setTemplate('application/index/index.phtml');
			return $view;

		} else {

			$kategorie = new Kategorie();
				
			if (! $kategorie->laden($_REQUEST['k_id'])) {
				array_push($errors, "Fehler beim Laden der Kategorie!");
			}

			$saved= false;
			$msg = array();

			if ($_REQUEST['speichern']) {

					
				// Schritt 1:  Werte aus Formular einlesen
				$k_id=$_REQUEST["k_id"];
				$kategoriebeschreibung=$_REQUEST["kategoriebeschreibung"];
				


				// Schritt 2: Daten prï¿½fen und Fehler in Array fÃ¼llen
				$errorStr ="";
				$msg="";
					
					
				// Gruppe-Objekt mit Daten aus Request-Array fï¿½llen
				$kategorie->setK_id($k_id);
				$kategorie->setKategoriebeschreibung($kategoriebeschreibung);
				
					

				if ($errorStr == "" && $kategorie->bearbeiten()) {

				 // array_push($msg, "Gruppe erfolgreich gespeichert!");
				 $msg .= "Kategorie erfolgreich gespeichert!";
				 $saved = true;

				} elseif ($errorStr == "") {

				 // array_push($msg, "Datenprï¿½fung in Ordnung, Fehler beim Speichern der Gruppe!");
					$msg .= "Datenprüfung in Ordnung, Fehler beim Speichern der Kategorie!";
				 $saved = false;

				} else {

				 // array_push($msg, "Fehler bei der Datenprï¿½fung. Gruppe nicht gespeichert!");
					$msg .= "Fehler bei der Datenprüfung. Kategorie nicht gespeichert!";
				 $saved = false;

				}
					
				$view = new ViewModel([
						'kategorie' => array($kategorie),
						'errors'   => $errors,
						'msg' => $msg
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