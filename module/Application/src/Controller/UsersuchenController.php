<?php

/**
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
* @copyright Copyright (c) 2005-2016 Zend Technologies USA Inc. (http://www.zend.com)
* @license   http://framework.zend.com/license/new-bsd New BSD License
*/

namespace Application\Controller;

header("Content-Type: text/html; charset=utf-8");

// Wenn Sytemadmin, wird die Benutzertabelle zurückgegeben, damit der Admin einen User suchen und reaktivieren/deaktivieren kann
// Wenn regulärer User, wird die Usersuche zum Einladen neuer Gruppenmitglieder aufgerufen

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Application\Model\User;
use Application\Model\Gruppe;
use Application\Model\Gruppenmitglied;



class UsersuchenController extends AbstractActionController
{
	public function usersuchenAction()
	{
		session_start(); 
		// Abprüfen, ob User angemeldet ist
		if ($_SESSION['user']==NULL) {
			$msg="Nicht berechtigt!";
			$view = new ViewModel([
					'msg' => $msg,
			]);
			
			$view->setTemplate('application/index/index.phtml');
				
			return $view;
		}
		
		$suche = $_REQUEST ["suche"];
		
		// Wenn der User Systemadmin ist wird die gesamte Usertabelle als Suchliste ausgegeben 
		// Die View "Benutzertabelle für Admins wird aufgerufen
		if ($_SESSION['user']->getSystemadmin()==true) {
			
			
			$userListe = User::suchlisteHolen($suche);
			
			$msg="Die Suche nach '$suche' ergab folgende Ergebnisse: ";
			
			$view = new ViewModel([
					'userListe' => $userListe,
					'suche' => $suche,
					'msg' => $msg
			]);
			
			$view->setTemplate('application/benutzertabelle/benutzertabelle.phtml');
				
			return $view;
		}
		
		// Wenn der User nicht Systemadmin ist werden aus der Usertabelle nur die User, die noch nicht in der Gruppe sind,
		// als Suchliste ausgegeben
		else {
			
			// Pr�fen, ob Gruppeadmin
			$aktuser_id=$_SESSION['user']->getU_id();
			$gruppen_id=$_REQUEST['g_id'];
			
			$aktgruppenmitglied=new Gruppenmitglied();
			
			$isOK=$aktgruppenmitglied->laden($gruppen_id, $aktuser_id);
				
			if ($isOK==false || $aktgruppenmitglied->getGruppenadmin()=="0") {
			
				$errStr="Nicht berechtigt!";
				$gruppenliste=Gruppenmitglied::eigenelisteholen($aktuser_id);
			
				$view = new ViewModel([
						'gruppenListe' => $gruppenliste,
						'err' => $errStr,
						'u_id' => $aktuser_id
				]);
			
				$view->setTemplate('application/groupoverview/groupoverview.phtml');
			
				return $view;
			}
			
			$user_id=$_SESSION['user']->getU_id();
			
			$aktgruppenmitglied=new Gruppenmitglied();
			$aktgruppenmitglied->laden($g_id, $user_id);
			
			$gruppe = new Gruppe();
			$g_id= $_REQUEST["g_id"];
			$gruppe->laden($g_id);
			
			$liste = User::gruppensuchlisteHolen($suche, $g_id);
			// Formular wird abgeschickt 
			if ($_REQUEST['einladen']) {
				
				if ($aktgruppenmitglied->getGruppenadmin()=="0") {
					$errStr="Nicht berechtigt!";
				}
				
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