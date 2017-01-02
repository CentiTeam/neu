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
			$upload=$_REQUEST["upload"];
			echo $upload;
		
			$uploadedfile=$_FILES["uploadedfile"]["name"];
			echo $uploadedfile;	
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
				$uploadedfile=$_FILES["uploadedfile"]["name"];

				
				
				$result = $bildupload->bildupload($uploadedfile);

				echo $result;
				}

				 
				 
		}
	}

}