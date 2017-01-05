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
use Application\Model\Gruppe;

class UsersuchenController extends AbstractActionController
{
	public function usersuchenAction()
	{
		session_start(); 
		
		$suche = $_REQUEST ["suche"];
		
		  

		if ($_SESSION['user']->getSystemadmin()==true) {
			
			$liste = User::suchlisteHolen($suche);
			
			return new ViewModel([
					'suchuserListe' => $liste,
			]);
		}
		
		else {
			
			$gruppe = new Gruppe();
			$g_id= $_REQUEST["g_id"];
			$gruppe->laden($g_id);
			
			$liste = User::gruppensuchlisteHolen($suche, $g_id);
			
			
			if ($_REQUEST['einladen']) {
				
				//$empfaenger= new User();
				//$empfaenger->laden($_REQUEST['g_id']);
				
				
				
				$empfaenger = "zehentnersebastian@web.de";
				$betreff = "Die Mail-Funktion";
				//$from = "From: Franz Reimers <absender@domain.de>";
				$text = "Hier lernt Ihr, wie man mit PHP Mails verschickt";
				
				mail($empfaenger, $betreff, $text);
				
				
			}
			
			
			
			
			
			
			$view = new ViewModel([
					'suchuserListe' => $liste,
					'gruppe' => array($gruppe)
			]);
				
			$view->setTemplate('application/teilnehmersuchetabelle/teilnehmersuchetabelle.phtml');
			
			return $view;
		}


	}
}