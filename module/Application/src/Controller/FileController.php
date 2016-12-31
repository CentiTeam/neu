<?php
namespace Application\Controller;


use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Application\Model\Gruppe;
use Application\Model\bildupload;


class FileController extends AbstractActionController {

	function uploadAction() {
		// TODO Berechtigungspr�fung
		session_start();

		$errors = array();

		if($_SESSION['angemeldet'] != 'ja') {
				
			array_push($errors, "Sie müssen angemeldet sein um eine Gruppe zu bearbeiten!");
				
			$view = new ViewModel(array(
					$errors
			));
			$view->setTemplate('application/index/index.phtml');
			return $view;
				
		} else {

			$bildupload = new bildupload();
			
			if (! $gruppe->laden($_REQUEST['g_id'])) {
				array_push($errors, "Fehler beim Laden der Gruppe!");	
			}

			$saved= false;
			$msg = array();

			if ($_REQUEST['upload']) {

					
				// Schritt 1:  Werte aus Formular einlesen
				$uploadedfile=$_REQUEST["uploadedfile"];

				$result = $uploadedfile->uploadbild($bildupload);

				echo $result;
				 }
				 
		}
	}

}