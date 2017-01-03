<?php
namespace Application\Controller;


use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Application\Model\Gruppe;
use Application\Model\Bildupload;


class FileController extends AbstractActionController {

	function fileAction() {
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

			$bildupload = new Bildupload();
			

			
			$saved= false;
			$msg = array();

			if ($_REQUEST['upload']) {

					
				// Schritt 1:  Werte aus Formular einlesen
				$uploadedfile=$_REQUEST["uploadedfile"];
				
				//Bilddatei an die Funktion Bildupload übergeben, Rückgabe des Bildpfades
				$path = $bildupload->bildupload($uploadedfile);
				
				$g_id=$_REQUEST["g_id"]; 
				
				if ($path!=false) {

					$result = Gruppe::bild($path, $g_id);
				
					$gruppenliste = Gruppe::listeholen();
				
					$view = new ViewModel([
							'gruppenListe'=>$gruppenliste
					]);
				
					$view->setTemplate('application/groupoverview/groupoverview.phtml');
					
					return $view;
				}				
				else {
					
					$gruppe = new Gruppe();
					
					$gruppe->laden($g_id);
					
					$view = new ViewModel([
							'gruppe' => $gruppe
					]);
					
					$view->setTemplate('application/groupedit/groupedit.phtml');
						
					return $view;
				}
				
			}

				return new ViewModel([
				]);
				
				 
		}
	}

}