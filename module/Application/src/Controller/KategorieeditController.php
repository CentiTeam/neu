<?php
namespace Application\Controller;


use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Application\Model\Kategorie;


class KategorieeditController extends AbstractActionController {

	function kategorieeditAction() {
		// TODO Berechtigungspr�fung
		session_start();

		// Berechtigungsprüfung
		if ($_SESSION['angemeldet']==NULL) {
		
			$msg="Nicht berechtigt!";
		
			$view = new ViewModel([
					'msg' => $msg,
			]);
		
			$view->setTemplate('application/index/index.phtml');
		
			return $view;
		
		}
		
		// Abprüfen, ob Administrator
		$user=$_SESSION['user'];
		
		if($_SESSION['systemadmin'] != 'ja') {

			$msg= "Sie müssen ein Administrator sein, um eine Kategorie zu bearbeiten!";

			$view = new ViewModel([
					'msg' => $msg,
					'user' => array($user),
			]);
			$view->setTemplate('application/overview/overview.phtml');
			return $view;

		} else {

			$kategorie = new Kategorie();
				
			if (! $kategorie->laden($_REQUEST['k_id'])) {
				$msg= "Fehler beim Laden der Kategorie!";
			}

			$saved= false;

			if ($_REQUEST['speichern']) {

					
				// Schritt 1:  Werte aus Formular einlesen
				$k_id=$_REQUEST["k_id"];
				$kategoriebeschreibung=$_REQUEST["kategoriebeschreibung"];
				


				// Schritt 2: Daten pr�fen und Fehler in Array füllen
				$errorStr ="";
					
					
				// Gruppe-Objekt mit Daten aus Request-Array f�llen
				$kategorie->setK_id($k_id);
				$kategorie->setKategoriebeschreibung($kategoriebeschreibung);
				
					

				if ($errorStr == "" && $kategorie->bearbeiten()) {

				 	$msg .= "Kategorie erfolgreich gespeichert!";
				 	$saved = true;

				} elseif ($errorStr == "") {

					$msg .= "Datenpr�fung in Ordnung, Fehler beim Speichern der Kategorie!";
				 	$saved = false;

				} else {

					$msg .= "Fehler bei der Datenpr�fung. Kategorie nicht gespeichert!";
					$saved = false;

				}
				
				$kategorieListe=Kategorie::listeHolen();
				
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