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


class ConfirmController extends AbstractActionController
{
	public function ConfirmAction()
	{
		$user = new User ();
		$error = false;
		$msg = array ();
		
		if ($_REQUEST['confirm']) {
		
		
			// Werte aus Formular einlesen
		
			$email = $_REQUEST ["email"];
			$emailwdh = $_REQUEST ["emailwdh"];
			
		}
		

		// Überprüfung, ob Email zwei mal richtig eingegeben wurde
			
		
			
		if ($email!=$emailwdh) {
			echo "<center><h4>Keine &Uumlbereinstimmung der E-Mail! Bitte erneut versuchen</h4></center>";
			$error = true;
		}
		
		// Keine Errors vorhanden, Funktion kann ausgeführt werden
			
		if (!$error) {
		
			// User-Objekt mit Daten aus Request-Array füllen
				
			$user->setEmail ($email);
			$user->setEmailwdh ($emailwdh);
			
		
				
			$user->confirm();
			
			$view = new ViewModel([
					
			]);
			
			$view->setTemplate('application/login/login.phtml');
			echo "Bestätigung erfolgreich. Sie können sich jetzt anmelden!";
				
			return $view;
		
		}
		return new ViewModel();
		
	}	
}