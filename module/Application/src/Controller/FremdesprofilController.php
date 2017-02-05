<?php
namespace Application\Controller;


use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Application\Model\User;
use Application\Model\Schulden;

// Das Profil eines anderen Users anzeigen. Hier kann man Zahlungen mit diesem User begleichen

class FremdesprofilController extends AbstractActionController {

	function fremdesprofilAction() {
		// TODO Berechtigungspr�fung
		session_start();

		$errors = array();
		
		// Abprüfen, ob User angemeldet ist
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
				
			//Berechnen der Schulden, bei denen der aufgerufene Benutzer der Gl�ubiger ist
			$schulden_an_aufgerufenen = $schulden->getBetragVonSchuldnerAnGlaeubiger();
				
			//Berechnen der Schulden, bei denen der angemeldete Benutzer der Gl�ubiger ist
			$schulden_an_angemeldeten = $schulden->getBetragVonGlaeubigerAnSchuldner();
				
			
			
			
			
			//Wenn das Formular zum Speichern des Begleichungsbetrags bez�glich der Schulden des angemeldeten an den aufgerufenen abgesendet wurde
			if ($_REQUEST['speichern_an_aufgerufenen']) {
				
				$schulden->schuldenVonSchuldnerAnGlaeubigerBegleichen($_POST['begleichen_an_aufgerufenen']);
				
				
				//Neuberechnen der Schulden, bei denen der aufgerufene Benutzer der Gl�ubiger ist
				$schulden_an_aufgerufenen = $schulden->getBetragVonSchuldnerAnGlaeubiger();
				
				//Neuberechnen der Schulden, bei denen der angemeldete Benutzer der Gl�ubiger ist
				$schulden_an_angemeldeten = $schulden->getBetragVonGlaeubigerAnSchuldner();
				
				$view = new ViewModel([
						'user' => array($user),
						'schulden_an_aufgerufenen' => array($schulden_an_aufgerufenen),
						'schulden_an_angemeldeten' => array($schulden_an_angemeldeten)
				]);
				
				$view->setTemplate('application/fremdesprofil/fremdesprofil.phtml');
				
				return $view;
				
				
				 
			
			}
			
			
			
			//Wenn das Formular zum Speichern des Begleichungsbetrags bez�glich der Schulden des aufgerufenen an den angemeldeten abgesendet wurde
			if ($_REQUEST['speichern_an_angemeldeten']) {
				
				$schulden->schuldenVonGlaeubigerAnSchuldnerBegleichen($_POST['begleichen_an_angemeldeten']);
				
				
				//Neuberechnen der Schulden, bei denen der aufgerufene Benutzer der Gl�ubiger ist
				$schulden_an_aufgerufenen = $schulden->getBetragVonSchuldnerAnGlaeubiger();
				
				//Neuberechnen der Schulden, bei denen der angemeldete Benutzer der Gl�ubiger ist
				$schulden_an_angemeldeten = $schulden->getBetragVonGlaeubigerAnSchuldner();
				
				$view = new ViewModel([
						'user' => array($user),
						'schulden_an_aufgerufenen' => array($schulden_an_aufgerufenen),
						'schulden_an_angemeldeten' => array($schulden_an_angemeldeten)
				]);
				
				$view->setTemplate('application/fremdesprofil/fremdesprofil.phtml');
				
				return $view;
			
					
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
