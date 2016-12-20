<?php

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Application\Model\User;

class RegistrierenController extends AbstractActionController{

	public function registrierenAction(){
		
		
		$user = new User ();
		$saved = false;
		$msg = array ();
		
		if ($_REQUEST['registrieren']) {
			
			
			// Werte aus Formular einlesen
			
			$username = $_REQUEST ["username"];
			$passwort = $_REQUEST ["passwort"];
			$email = $_REQUEST ["email"];
			$vorname = $_REQUEST ["vorname"];
			$nachname = $_REQUEST ["nachname"];
			
			
			// TODO: Es muss noch überprüft werden, ob Benutzername und Passwort vorhanden
	
			
			// User-Objekt mit Daten aus Request-Array füllen
			$user -> setUsername ($username);
			$user -> setPasswort ($passwort);
			$user -> setEmail ($email);
			$user -> setVorname ($vorname);
			$user -> setNachname ($nachname);
			
			
			
			if($user->getUsername()==null) die("Benutzername wurde nicht eingelesen.");
				
			$user->registrieren();
			
		}
			

		return new ViewModel([
				'user' => array($user),
				'errors'   => $errors,
				'msg' => $msg
		]);
	}
}