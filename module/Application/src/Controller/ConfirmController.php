<?php
/**
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2016 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Application\Model\User;

// Hier wird die Email bestätigt 

class ConfirmController extends AbstractActionController
{
	public function ConfirmAction()
	{
		// Hier ist keine Berechtigungsprüfung notwendig
		
		
		$user = new User ();
		$error = false;
		$msg = array ();
		
		if ($_REQUEST['confirm']) {
		
		
			// Werte aus Formular einlesen
		
			$email = $_REQUEST ["email"];
			$emailwdh = $_REQUEST ["emailwdh"];
			
		
		

		// �berpr�fung, ob Email zwei mal richtig eingegeben wurde
			
			
		if ($email!=$emailwdh) {
			echo "<center><h4>Keine &Uumlbereinstimmung der E-Mail! Bitte erneut versuchen</h4></center>";
			$error = true;
			
			return new ViewModel();
		}
		
		// Keine Errors vorhanden, Funktion kann ausgef�hrt werden
			
		if (!$error) {
		
			// User-Objekt mit Daten aus Request-Array f�llen
				
			$user->setEmail ($email);
			$user->setEmailwdh ($emailwdh);
			
		
				
			$user->confirm();
			
			
			
		}
		
		$email=$user->getEmail();
		
		$view = new ViewModel([
				'user' => array($user),
				'email' => $email
		]);
			
		$view->setTemplate('application/login/login.phtml');
		
		return $view;
		
		}
		
		
		return new ViewModel();
		
	}	
}