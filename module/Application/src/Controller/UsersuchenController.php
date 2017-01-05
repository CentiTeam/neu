<?php

/**
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
* @copyright Copyright (c) 2005-2016 Zend Technologies USA Inc. (http://www.zend.com)
* @license   http://framework.zend.com/license/new-bsd New BSD License
*/

namespace Application\Controller;

header("Content-Type: text/html; charset=utf-8");

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
				
				$msg="";
				
				$empfaenger= new User();
				$empfaenger->laden($_REQUEST['u_id']);
				$empfaengerMail=$empfaenger->getEmail();
				$empfaengerVorname=$empfaenger->getVorname();
				$empfaengerUsername=$empfaenger->getUsername();
				
				$absenderVorname=$_SESSION['user']->getVorname();
				$absenderNachname=$_SESSION['user']->getNachname();
				
				$gruppenName=$gruppe->getGruppenname();
				
				//$link=132.231.36.206/einladungannehmen;
				//echo "<a href='einladungannehmen?g_id=<?=$gruppe->getG_id()
				
				
				$empfaenger = "$empfaengerMail";
				$betreff = "Grouppay: Einladung in die Gruppe $gruppenName";
				//$from = "From: Franz Reimers <absender@domain.de>";
$text = 
"Hallo $empfaengerVorname!
				
Du wurdest von $absenderVorname $absenderNachname in die Gruppe $gruppenName eingeladen.
						
Über diesen Link kannst die die Einladung annehmen:
<a href='132.231.36.206/einladungannehmen?g_id=<?=$gruppe->getG_id()?>?u_id=<?=$empfaenger->getU_id()?>'>Einladung annehmen</a>

Viele Grüße
Dein Grouppay-Team";
				
				mail($empfaenger, $betreff, $text);
				
				$msg= "$empfaengerUsername wurde erfolgreich eingeladen!";
				
			}
			
			
			
			$view = new ViewModel([
					'suchuserListe' => $liste,
					'gruppe' => array($gruppe),
					'msg' => $msg
			]);
				
			$view->setTemplate('application/teilnehmersuchetabelle/teilnehmersuchetabelle.phtml');
			
			return $view;
		}


	}
}