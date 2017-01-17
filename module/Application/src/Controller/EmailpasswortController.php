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

class EmailpasswortController extends AbstractActionController
{
	public function emailpasswortAction()
	{
		session_start();
		
		if ($_REQUEST['emailpasswort']) {
		
			
			$msg="";
			
			$empfaenger= $_REQUEST ['email'];
			
			$userListe=User::listeHolen();
			$emailvorhanden=false;
			
			foreach ($userListe as $liste) {
				if ($liste->getEmail()==$empfaenger) {
					$emailvorhanden=true;
				}
			}

			if ($emailvorhanden==false) {
				
				$msg="E-Mail nicht vorhanden! Bitte erneut eingeben!";
				
			} else {
			
			$betreff = "Grouppay: Passwort zurücksetzen";
			/**
			$link="http://132.231.36.206/passwortvergessen?email=$empfaenger";

			$text =
"Hallo!
Über diesen Link kannst du dein Passwort zurücksetzen:
$link
			
Viele Grüße
Dein Grouppay-Team";
*/			


$link= "<a href=\"http://132.231.36.206/passwortvergessen?email=$empfaenger\">Passwort zur&uuml;cksetzen</a>";

$text=
"<html>
<body>
<div>Hallo!</div>
<br>
<div>&Uuml;ber diesen Link kannst Du Dein Passwort zurücksetzen:</div>
<div>$link</div><br>

<div>Viele Gr&uuml;&szlig;e</div>
<div>Dein Grouppay-Team</div>
</body>
</html>";
			
			mail($empfaenger, $betreff, $text);
			
			$msg= "E-Mail wurde erfolgreich versendet!";
			
			}
			
		}
				
		
			return new ViewModel([
					'msg' => $msg
			]);
			

	}
}
