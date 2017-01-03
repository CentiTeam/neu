<?php
namespace Application\Controller;


use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Application\Model\Gruppe;
use Application\Model\Bildupload;


class FileController extends AbstractActionController {

	function fileAction() {
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

			$bildupload = new Bildupload();
			

			
			$saved= false;
			$msg = array();

			if ($_REQUEST['upload']) {

					
				// Schritt 1:  Werte aus Formular einlesen
				$uploadedfile=$_REQUEST["uploadedfile"];
				
				//Bilddatei an die Funktion Bildupload �bergeben, R�ckgabe des Bildpfades
				$path = $bildupload->bildupload($uploadedfile);
				
				$g_id=$_REQUEST["g_id"]; 
				
				$result = Gruppe::bild($path, $g_id);
				
				$view = new ViewModel([
						'errors'   => $errors,
						'msg' => $msg
				]);
					
				$view->setTemplate('application/groupoverview/groupoverview.phtml');
				
				return $view;

				}

				return new ViewModel([
				]);
				
				 
		}
	}

}