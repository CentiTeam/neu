<?php

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Application\Model\User;

class RegistrierenController extends AbstractActionController{

	public function registrierenAction(){
		
		
		$user = new User ();
		$error = false;
		$msg = array ();
		
		if ($_REQUEST['registrieren']) {
			
			
			// Werte aus Formular einlesen
			
			$username = $_REQUEST ["username"];
			$passwort = $_REQUEST ["passwort"];
			$passwortwdh = $_REQUEST ["passwortwdh"];
			$email = $_REQUEST ["email"];
			$vorname = $_REQUEST ["vorname"];
			$nachname = $_REQUEST ["nachname"];
			
			
			
			// Überprüfung, ob Passwort zwei mal richtig eingegeben wurde
			
			
			if ($passwort!=$passwortwdh) {
				echo "<center><h4>Keine &Uumlbereinstimmung der Passw&oumlrter! Bitte erneut registrieren</h4></center>";
				$error = true;
			}
			
			
		
			
			// Keine Errors vorhanden, Funktion kann ausgeführt werden
			
			if (!$error) {
				
			// User-Objekt mit Daten aus Request-Array füllen
			$user->setUsername ($username);
			$user->setPasswortwdh($passwortwdh);
			$user->setPasswort ($passwort);
			$user->setEmail ($email);
			$user->setVorname ($vorname);
			$user->setNachname ($nachname);
			
			
			
			if($user->getUsername()==null) die("Benutzername wurde nicht eingelesen.");
				
			$user->registrieren();
			
		}
		}
			

		return new ViewModel([
				'user' => array($user),
				'errors'   => $errors,
				'msg' => $msg
		]);
	}
}