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
		
		if ($_SESSION['user']==NULL) {
			$msg="Nicht berechtigt!";
			$view = new ViewModel([
					'msg' => $msg,
			]);
		
			$view->setTemplate('application/index/index.phtml');
		
			return $view;
		
		}
		
		
		$user = new User ();
		$error = false;
	
		
		if ($_REQUEST['passwordreset']) {
		
		
			// Werte aus Formular einlesen
		
			$altespasswort = $_REQUEST ["altespasswort"];
			$passwort = $_REQUEST ["passwort"];
			$passwortwdh = $_REQUEST ["passwortwdh"];
			$u_id = $_SESSION['user']->getU_id();
			$nochpasswort = $_SESSION['user']->getPasswort();
			
			
			
			// �berpr�fung, ob Passwort zwei mal richtig eingegeben wurde
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
			
			else if ($altespasswort != $nochpasswort) {
				echo "<center><h4> Keine &Uumlbereinstimmung mit dem alten Passwort! Bitte erneut versuchen </h4></center>";
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
		
		
			// Keine Errors vorhanden, Funktion kann ausgef�hrt werden
			else if (!$error) {
		
				// User-Objekt mit Daten aus Request-Array f�llen
				$user->setU_id($u_id);
				$user->setPasswort ($passwort);
				$user->setPasswortwdh($passwortwdh);
			
				
				$user->passwordreset();
				
				
				$user->laden($u_id);
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