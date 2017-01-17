<?php


namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Application\Model\User;

class PasswortvergessenController extends AbstractActionController
{
	public function passwortvergessenAction()
	{
		$user = new User ();
		$error = false;
		$msg = array ();
		
		if ($_REQUEST['passwortvergessen']) {
				
				
			// Werte aus Formular einlesen
				
			$email = $_REQUEST ["email"];
			$passwort = $_REQUEST ["passwort"];
			$passwortwdh = $_REQUEST ["passwortwdh"];
		
	
			// Überprüfung, ob Passwort zwei mal richtig eingegeben wurde		
			if ($passwort!=$passwortwdh) {
				echo "<center><h4>Keine &Uumlbereinstimmung der Passw&oumlrter! Bitte erneut versuchen</h4></center>";
				$error = true;
			}


			// Keine Errors vorhanden, Funktion kann ausgeführt werden
			if (!$error) {
		
				// User-Objekt mit Daten aus Request-Array füllen
				$user->setEmail ($email);
				$user->setPasswort ($passwort);
				$user->setPasswortwdh($passwortwdh);
	
				$user->passwortvergessen();	
		
			}
		
			
			$view = new ViewModel([
					'user' => array($user),
					'email' => $email,
					'errors'   => $errors,
					'msg' => $msg
			]);
			
			$view->setTemplate('application/login/login.phtml');
		
			return $view;
		}
		
		return new ViewModel([
			'user' => array($user),	
			
		]);
		
		}

}