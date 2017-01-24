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
use Application\Model\Gruppenmitglied;


class NeuesmitgliedeinladenController extends AbstractActionController
{
	public function neuesmitgliedeinladenAction()
	{
		session_start();

		if ($_SESSION['user']==NULL) {
			$msg="Nicht berechtigt!";
			$view = new ViewModel([
					'msg' => $msg,
			]);
				
			$view->setTemplate('application/index/index.phtml');

			return $view;
		
		} else {
			
			if ($_REQUEST['einladen']) {

				$msg="";

				// Variablen füllen für E-mail-Text
				$empfaengerMail=$_REQUEST['email'];
				
				
				$userListe=User::listeHolen();
				
				foreach ($userListe as $liste) {
					if ($liste->getEmail()==$empfaengerMail) {
						
						$empfaenger_id=$liste->getU_id();
						$link= "<a href=\"http://132.231.36.206/fremdesprofil?u_id=$empfaenger_id\"></a>";
						
						$msg="Unter dieser E-Mail-Adresse ist bereits ein User registriert! <br>
								Hier geht es zum Profil: $link ";
						
						
						return new ViewModel([
								'msg' => $msg
						]);
					}
				}
				
				
				$absenderVorname=$_SESSION['user']->getVorname();
				$absenderNachname=$_SESSION['user']->getNachname();


				// Variablen für Mail füllen: $empfaenger, $betreff, $text, $header
				$empfaenger = "$empfaengerMail";
				$betreff = "Einladung zum Zahlungsverwaltungstool Grouppay!";


				$link= "<a href=\"http://132.231.36.206\">Schau Dir Grouppay an!</a>";

				$text=
					"<html>
					<body>
					<div>Hallo!</div>
					<br>
					<div>Du wurdest von $absenderVorname $absenderNachname zu dem Zahlungsverwaltungstool Grouppay eingeladen.</div>
					<br>
					<div>&Uuml;ber diesen Link kannst Du Dir Grouppay anschauen:</div>
					<div>$link</div><br>

					<div>Viele Gr&uuml;&szlig;e</div>
					<div>Dein Grouppay-Team</div>
					</body>
					</html>";

					$header  = 'MIME-Version: 1.0' . "\r\n";
					$header .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
					
					// Mail verschicken
					mail($empfaenger, $betreff, $text, $header);

					$msg= "An die E-Mail-Adresse '$empfaengerMail' wurde erfolgreich eine Einladung zu Grouppay verschickt!";

				}
			
			}
				
					
			$view = new ViewModel([
					'msg' => $msg,
			]);

			$view->setTemplate('application/neuesmitgliedeinladen/neuesmitgliedeinladen.phtml');
				
			return $view;
		}


}
