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

// Wenn man sein Passwort vergessen hat kann man hier seine E-Mail angeben um ein neues zugeschickt bekommen

class EmailpasswortController extends AbstractActionController
{
	public function emailpasswortAction()
	{
		
		//Hier sollte keine BerechtigungsprÃ¼fung notwendig sein
		
		session_start();
		
		if ($_REQUEST['emailpasswort']) {
		
			
			$msg="";
			
			$empfaenger= $_REQUEST ['email'];
			
		 
			
			$userListe=User::listeHolen();
			$emailvorhanden=false;
			
			foreach ($userListe as $liste) {
				if ($liste->getEmail()==$empfaenger) {
					$emailvorhanden=true;
					$pwcode=$liste->getPwcode();
				
				}
			}

			if ($emailvorhanden==false) {
				
				$msg="E-Mail nicht vorhanden! Bitte erneut eingeben!";
				
			} else {
			
				$user=new User();
				
				
				// $pwcode = $user->getPwcode();
				
			$betreff = "Grouppay: Passwort zurücksetzen";		
	

$link= "<a href=\"http://132.231.36.206/passwortvergessen?email=$empfaenger\">Passwort zur&uuml;cksetzen</a>";

$text=
"<html>
<body>
<div>Hallo!</div>
<br>
<div>&Uuml;ber diesen Link kannst Du Dein Passwort zur&uuml;cksetzen:</div>
<div>$link</div><br>
<div> Bitte den Code eingeben: $pwcode </div><br>

<div>Viele Gr&uuml;&szlig;e</div>
<div>Dein Grouppay-Team</div>
</body>
</html>";

$header  = 'MIME-Version: 1.0' . "\r\n";
$header .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
			
			mail($empfaenger, $betreff, $text, $header);
			
			$msg= "E-Mail wurde erfolgreich versendet!";
			
			}
			
		}
				
		
			return new ViewModel([
					'msg' => $msg
			]);
			

	}
}
