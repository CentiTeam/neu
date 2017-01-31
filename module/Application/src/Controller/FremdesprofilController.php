<?php
namespace Application\Controller;


use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Application\Model\User;
use Application\Model\Schulden;

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
			$user_aufgerufen = $user;
			
			$user_angemeldet = $_SESSION['user'];
			
			
			
			//Test
			$Schulden = new Schulden();
			$Schulden->laden($user_angemeldet, $user_aufgerufen);
			$betrag1 = $Schulden->getBetragVonSchuldnerAnGlaeubiger();
			echo $betrag1;
			
			$betrag2 = $Schulden->getBetragVonGlaeubigerAnSchuldner();
			echo $betrag2;
			
			

			
			
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
