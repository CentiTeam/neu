<?php
namespace Application\Controller;


use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Application\Model\User;
use Application\Model\Schulden;

class FremdesprofilController extends AbstractActionController {

	function fremdesprofilAction() {
		// TODO Berechtigungsprï¿½fung
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
				
			//Berechnen der Schulden
			$schulden = new Schulden();
			$schulden -> laden($user_aufgerufen, $user_angemeldet);
				
			//Berechnen der Schulden, bei denen der aufgerufene Benutzer der Gläubiger ist
			$schulden_an_aufgerufenen = $schulden->getBetragVonSchuldnerAnGlaeubiger();
				
			//Berechnen der Schulden, bei denen der angemeldete Benutzer der Gläubiger ist
			$schulden_an_angemeldeten = $schulden->getBetragVonGlaeubigerAnSchuldner();
				
			
			
			
			
			//Wenn das Formular zum Speichern des Begleichungsbetrags bezüglich der Schulden des angemeldeten an den aufgerufenen abgesendet wurde
			if ($_REQUEST['speichern_an_aufgerufenen']) {
				
				
			
			}
			
			
			
			//Wenn das Formular zum Speichern des Begleichungsbetrags bezüglich der Schulden des aufgerufenen an den angemeldeten abgesendet wurde
			if ($_REQUEST['speichern_an_angemeldeten']) {
				
				$schulden->schuldenVonGlaeubigerAnSchuldnerBegleichen($_POST['offen_an_angemeldeten']);
			
					
			}
			
			
			
			
			else{
			
			
			
						
			
			
			

			
			

			
			
			//Aufbauen des aktuellen Schuldenbetrages, den der angemeldete Benutzer dem aufgerufenen Benutzer schuldet
			
			
			
			//Aufbauen des aktuellen Schuldenbetrages, den der aufgerufene Benutzer dem angemeldeten Benutzer schuldet
			
						
			$view = new ViewModel([
					'user' => array($user),
					'schulden_an_aufgerufenen' => array($schulden_an_aufgerufenen),
					'schulden_an_angemeldeten' => array($schulden_an_angemeldeten)
			]);
						
			$view->setTemplate('application/fremdesprofil/fremdesprofil.phtml');
	
			return $view;
		}
		}
	
	}

}
