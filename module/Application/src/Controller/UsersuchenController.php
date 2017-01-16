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
			
			echo $suche;
			echo $g_id;
			echo "hallo";
			
			if ($_REQUEST['einladen']) {
				
				$msg="";
				
				// Variablen füllen für E-mail-Text
				$empfaenger= new User();
				$empfaenger->laden($_REQUEST['u_id']);
				$empfaengerMail=$empfaenger->getEmail();
				$empfaengerVorname=$empfaenger->getVorname();
				$empfaengerUsername=$empfaenger->getUsername();
				
				$absenderVorname=$_SESSION['user']->getVorname();
				$absenderNachname=$_SESSION['user']->getNachname();
				
				$gruppenName=$gruppe->getGruppenname();
				$gruppen_id=$gruppe->getG_id();
				$empfaenger_id=$empfaenger->getU_id();
				
				
				// Variablen für Mail füllen: $empfaenger, $betreff, $text, $header
				$empfaenger = "$empfaengerMail";
				$betreff = "Grouppay: Einladung in die Gruppe $gruppenName";
				
				
				$link= "<a href=\"http://132.231.36.206/einladungannehmen?g_id=$g_id&u_id=$empfaenger_id\">Einladung annehmen</a>";		

				$text=
				"<html>
					<body>		
						<div>Hallo $empfaengerVorname!</div>
						<br>
						<div>Du wurdest von $absenderVorname $absenderNachname in die Gruppe $gruppenName eingeladen.</div>
						<br>
						<div>&Uuml;ber diesen Link kannst Du die Einladung annehmen:</div>
						<div>$link</div><br>
						
						<div>Viele Gr&uuml;&szlig;e</div> 
						<div>Dein Grouppay-Team</div>
					</body>
				</html>";
				
				$header  = 'MIME-Version: 1.0' . "\r\n";
				$header .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
			
				// Mail verschicken
				mail($empfaenger, $betreff, $text, $header);
				
				$msg= "$empfaengerUsername wurde erfolgreich eingeladen!";
				
			}
			
			
			
			$view = new ViewModel([
					'suchuserListe' => $liste,
					'gruppe' => array($gruppe),
					'msg' => $msg,
					'suche' => $suche
			]);
				
			$view->setTemplate('application/teilnehmersuchetabelle/teilnehmersuchetabelle.phtml');
			
			return $view;
		}


	}
}