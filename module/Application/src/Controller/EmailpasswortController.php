<?php
/**
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
* @copyright Copyright (c) 2005-2016 Zend Technologies USA Inc. (http://www.zend.com)
* @license   http://framework.zend.com/license/new-bsd New BSD License
*/

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class EmailpasswortController extends AbstractActionController
{
	public function emailpasswortAction()
	{
		session_start();
		
		if ($_REQUEST['emailpasswort']) {
		
			
			$msg="";
			
			$empfaenger= $_REQUEST ['email'];
			
			// $empfaengerVorname=$empfaenger->getVorname();
			// $empfaengerUsername=$empfaenger->getUsername();

			
			$betreff = "Grouppay: Passwort zur�cksetzen";
			
			$link="http://132.231.36.206/passwortvergessen";
			
			$text =
			"Hallo!
			
			�ber diesen Link kannst du dein Passwort zur�cksetzen:
			$link
			
			Viele Gr��e
			Dein Grouppay-Team";
			
			mail($empfaenger, $betreff, $text);
			
			$msg= "Passwort wurde erfolgreich zur�ckgesetzt!";
			
			}
				
		
			return new ViewModel();

	}
}
