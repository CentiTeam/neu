<?php
namespace Application\Controller;


use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Application\Model\User;
use Application\Model\Bildupload;

class ProfilController extends AbstractActionController {

	function profilAction() {
		// TODO Berechtigungsprï¿½fung
		session_start();
	
		$errors = array();

		if($_SESSION['angemeldet'] == 'ja' || $_SESSION['systemadmin'] == 'ja') {
			
			$u_id=$_SESSION['user']->getU_id();
			$user = new User();
			$user->laden($u_id);
						
				$saved= false;
				$msg = array();				

			if ($_REQUEST['profilbild']) {
				
					echo "Hallo";
				
					$bildupload = new Bildupload();
				
					// Schritt 1:  Werte aus Formular einlesen
					$uploadedfile=$_REQUEST["uploadedfile"];
				
					//Bilddatei an die Funktion Bildupload übergeben, Rückgabe des Bildpfades
					$path = $bildupload->bildupload($uploadedfile);
				
					$u_id=$_REQUEST["u_id"]; 
				
					if ($path!=false) {

						$result = User::bild($path, $u_id);
					
						$user->laden($u_id);
						
						return new ViewModel([
							'user' => array($user),
						]);
				
						$view->setTemplate('application/profil/profil.phtml');
					
						return $view;
					}				
					 
					else {
					
						$user = new User();
					
						$user->laden($u_id);
					
						$view = new ViewModel([
							'user' => array($user)
						]);
					
						$view->setTemplate('application/profil/profil.phtml');
						
						return $view;
					}
				
			}

				return new ViewModel([
						'user' => array($user),
				]);	

				
		} else {
			
			array_push($errors, "Sie mÃ¼ssen angemeldet sein um Ihr Profil zu sehen!");
				
			$view = new ViewModel(array(
					$errors
			));
			$view->setTemplate('application/index/index.phtml');
			return $view;		
				 
		}
	}

}