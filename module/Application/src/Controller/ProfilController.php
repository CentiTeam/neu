<?php
namespace Application\Controller;


use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Application\Model\User;
use Application\Model\Bildupload;

/**
 * FILE-Controller wird nur noch als Vorlage verwendet!!!! 
 * Die Funktionalität wurde in GroupeditController und GruppeanlegenController übernommen
 * @author Tanja
 *
 */


class ProfilController extends AbstractActionController {

	function profilAction() {
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
			
			$u_id=$_SESSION['user']->getU_id();
			$user = new User();
			$user->laden($u_id);
			
			return new ViewModel([
					'user' => array($user),	
			]);
			

			
			$bildupload = new Bildupload();
			
			$saved= false;
			$msg = array();

			if ($_REQUEST['profilbild']) {

					
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
				]);
				
				 
		}
	}

}