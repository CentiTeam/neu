<?php

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Application\Model\User;


class PasswordresetController extends AbstractActionController
{
	public function passwordresetAction()
	{
		session_start();
		$user = new User ();
		$error = false;
	
		
		if ($_REQUEST['passwordreset']) {
		
		
			// Werte aus Formular einlesen
		
			$altespasswort = $_REQUEST ["altespasswort"];
			$passwort = $_REQUEST ["passwort"];
			$passwortwdh = $_REQUEST ["passwortwdh"];
			$u_id = $_SESSION['user']->getU_id();
			$nochpasswort = $_SESSION['user'] ->getPasswort();
			
			
			// Überprüfung, ob Passwort zwei mal richtig eingegeben wurde
			if ($passwort!=$passwortwdh) {
				echo "<center><h4>Keine &Uumlbereinstimmung der neuen Passw&oumlrter! Bitte erneut versuchen</h4></center>";
				$error = true;
				
				$user->laden($u_id);
				$view = new ViewModel([
						'user' => array($user),
						'errors'   => $errors,
						'msg' => $msg
				]);
				
				$view->setTemplate('application/profil/profil.phtml');
				
				return $view;
				
				
			}
			
			if ($altespasswort != $nochpasswort) {
				echo "<center><h4> Keine &Uumlbereinstimmung mit dem alten Passw&oumlrt! Bitte erneut versuchen </h4></center>";
				$error = true;
				
				$user->laden($u_id);
				$view = new ViewModel([
						'user' => array($user),
						'errors'   => $errors,
						'msg' => $msg
				]);
				
				$view->setTemplate('application/profil/profil.phtml');
				
				return $view;
			}
		
		
			// Keine Errors vorhanden, Funktion kann ausgeführt werden
			if (!$error) {
		
				// User-Objekt mit Daten aus Request-Array füllen
				$user->setU_id($u_id);
				$user->setPasswort ($passwort);
				$user->setPasswortwdh($passwortwdh);
				$user->setAltespasswort ($altespasswort);
				$user->laden($u_id);
				$user->passwordreset();
				$msg = 'Passwort erfolgreich ge&aumlndert!';
			}
		
				
			$view = new ViewModel([
					'user' => array($user),
					'errors'   => $errors,
					'msg' => $msg
			]);
				
			$view->setTemplate('application/profil/profil.phtml');
		
			return $view;
		}
		
		

	return new ViewModel([
			'user' => array($user),
			'errors'   => $errors,
			'msg' => $msg
	]);
	
	

	}
	
}