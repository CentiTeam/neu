<?php

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Application\Model\Gruppe;
use Application\Model\User;
use Application\Model\Gruppenmitglied;
use Application\Model\Gruppenereignis;

#TODO @Tanja Fehler abpr�fen!

class EinladungannehmenController extends AbstractActionController
{

	public function einladungannehmenAction() {
	
		// Überprüfung des Links mithilfe von Sicherheitscode wäre gut - Ansonsten keine Berechtigungspruefung notwendig
		
		// neues Model anlegen
		$gruppenmitglied = new Gruppenmitglied ();

		// Model anhand der �bergebenen $g_id laden lassen und speichern, ob dies funktioniert hat
		$u_id=$_GET['u_id'];
		$g_id=$_GET['g_id'];
		
		
		$gruppe=new Gruppe();
		$gruppe->laden ($g_id);
		
		$user=new User();
		$user->laden ($u_id);

		// wenn die Aktion abgebrochen werden soll
		if ($_REQUEST['abbrechen']) {
					
			$view = new ViewModel([
			]);

			$view->setTemplate('application/index/index.phtml');
				
			return $view;
		}

		// wenn das Formular zur Best�tigung des Annehmens der Einladung schon abgesendet wurde, soll dies hier ausgewertet werden
		if ($_REQUEST['speichern']) {
				
			$msg = "";
			$errorStr = "";
			
			$gruppenmitglied->setUser($user);
			$gruppenmitglied->setGruppe($gruppe);
			$gruppenmitglied->setGruppenadmin(0);
			
			
			$gruppenmitgliedListe=Gruppenmitglied::listeholen();
			
			foreach ($gruppenmitgliedListe as $liste) {
				if ($gruppenmitglied->getUser()->getU_id()==$liste->getUser()->getU_id() && $gruppenmitglied->getGruppe()->getG_id()==$liste->getGruppe()->getG_id()) {
					
					$errorStr .= "Du bist bereits Mitglied der Gruppe!<br>";
					
				}
			}
			// wenn der Ladevorgang erfolgreich war, wird der eingeladene User der Gruppe hinzugefügt
			if ($errorStr=="" && $gruppenmitglied->anlegen()) {
				$gruppenname=$gruppe->getGruppenname();
				$vorname=$user->getVorname();
				// ausgeben, dass die Gruppe gel�scht wurde (kein Template n�tig!)
				// array_push($msg, "Gruppe erfolgreich gel�scht!");

				$msg .= "Du wurdest erfolgreich zu der Gruppe '$gruppenname' hinzugef&uuml;gt, $vorname!<br>";
				
				Gruppenereignis::gruppenmitgliedbeitretenEreignis($gruppe, $user);

			} else {
				// ausgeben, dass der User ncht zur Gruppe hinzugefügt werden konnte
				$msg .= "Fehler beim Hinzufügen zu der Gruppe!<br>";
				
				
				$view = new ViewModel([
						'msg' => $msg,
						'err' => $errorStr
				]);
				
				$view->setTemplate('application/index/index.phtml');
					
				return $view;
			}
		} else {

			// da das Formular zum Best�tigen der Gruppeneinladung noch nicht angezeigt wurde, wird es hier generiert 
				
			return new ViewModel([
					'gruppe' => array($gruppe),
					'user' => array($user)
			]);
		}
		
		
		$email=$user->getEmail();
		
		$view = new ViewModel([
				'msg' => $msg,
				'email' => $email
		]);

		$view->setTemplate('application/login/login.phtml');
			
		return $view;

	}


}