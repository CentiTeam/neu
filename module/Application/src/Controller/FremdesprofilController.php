<?php
namespace Application\Controller;


use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Application\Model\User;

class FremdesprofilController extends AbstractActionController {

	function fremdesprofilAction() {
		// TODO Berechtigungspr�fung
		session_start();

		$errors = array();

		if($_SESSION['angemeldet'] ==NULL) {
		
			$msg="Nicht berechtigt!";
			
			$view = new ViewModel([
			'msg' => $msg
			]);
			
			$view->setTemplate('application/index/index.phtml');
			return $view;
			
		} else {
						
			$u_id=$_REQUEST['u_id'];
			$user = new User();
			$user->laden($u_id);

			$saved= false;
			$msg = array();
			
			//Schreiben des aufgerufenen Benutzers und des angemeldeten Benutzers in Variablen
			$user_aufgerufen = new User();
			$user_id_aufgerugen=$_REQUEST['u_id'];
			$user_aufgerufen->laden($user_id_aufgerufen);
			
			$user_angemeldet = $_SESSION['user'];
			
			echo $user_angemeldet;
			echo $user_aufgerufen;
			
			
			//Aufbauen des aktuellen Schuldenbetrages, den der angemeldete Benutzer dem aufgerufenen Benutzer schuldet
			
			
			
			//Aufbauen des aktuellen Schuldenbetrages, den der aufgerufene Benutzer dem angemeldeten Benutzer schuldet
			
						
			$view = new ViewModel([
					'user' => array($user)
			]);
						
			$view->setTemplate('application/fremdesprofil/fremdesprofil.phtml');
	
			return $view;
		}
	
	}

}
