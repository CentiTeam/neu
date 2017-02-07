<?php


namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Application\Model\User;

class PasswortvergessenController extends AbstractActionController
{
	public function passwortvergessenAction()
	{
		// Hier bräuchten wir einen Sicherheitscode für die Berechtigungsprüfung
		
		$user = new User ();
		$error = false;

		
		if ($_REQUEST['passwortvergessen']) {
				
				
			// Werte aus Formular einlesen
				
			$email = $_GET ["email"];
			$passwort = $_REQUEST ["passwort"];
			$passwortwdh = $_REQUEST ["passwortwdh"];
			$pwcode = $_REQUEST ["pwcode"];
			//$passwortcode = $user->getPwcode;
		
	
			// �berpr�fung, ob Passwort zwei mal richtig eingegeben wurde		
			if ($passwort!=$passwortwdh) {
				echo "<center><h4>Keine &Uumlbereinstimmung der Passw&oumlrter! Bitte erneut versuchen</h4></center>";
				$error = true;
			}

			if ($pwcode!=$passwortcode) {
					echo "<center><h4>Keine &Uumlbereinstimmung des Codes! Bitte erneut versuchen</h4></center>";
					$error = true;
			}
			// Keine Errors vorhanden, Funktion kann ausgef�hrt werden
			if (!$error) {
		
				// User-Objekt mit Daten aus Request-Array f�llen
				$user->setEmail ($email);
				$user->setPasswort ($passwort);
				$user->setPasswortwdh($passwortwdh);
				$user->setPwcode ($pwcode);
				$user->setPasswortcode ($passwortcode);
	
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