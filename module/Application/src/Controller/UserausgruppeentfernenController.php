<?php

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Application\Model\Gruppe;
use Application\Model\Gruppenmitglied;
use Application\Model\Gruppenereignis;
use Application\Model\Zahlungsteilnehmer;

class UserausgruppeentfernenController extends AbstractActionController
{

	public function userausgruppeentfernenAction() {

		session_start();
		
		// Berechtigungsprüfung: Pr�fen, ob Angemeldet und danach ob Gruppeadmin
		if ($_SESSION['angemeldet']==NULL || $_SESSION['systemadmin']) {
		
			$msg="Nicht berechtigt!";
		
			$view = new ViewModel([
					'msg' => $msg,
			]);
		
			$view->setTemplate('application/index/index.phtml');
		
			return $view;
		
		}
		
		
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
		// Ende Berechtigungsprüfung
		

		// neues Model anlegen
		$gruppenmitglied = new Gruppenmitglied ();

		// Model anhand der �bergebenen $g_id laden lassen und speichern, ob dies funktioniert hat
		$g_id=$_REQUEST['g_id'];
		$user_id=$_REQUEST['u_id'];

		$gruppenmitglied->laden ($g_id, $user_id);

		
		// Pr�fen, ob es noch offene Zahlungen gibt
		$offeneZahlungen=false;
		
		$zahlungenListe=Zahlungsteilnehmer::teilnehmerzahlungennachgruppeholen($user_id, $g_id);
		
		foreach ($zahlungenListe as $zahlungen) {
			if ($zahlungen->getStatus()=="offen") {
				$offeneZahlungen=true;
			}
		}
		
		// Wenn der User noch offene Zahungen in dieser Gruppe hat darf er die Gruppe nicht verlassen
		// Es wird eine Fehlermeldung generiert
		if ($offeneZahlungen==true) {
			
			$username=$gruppenmitglied->getUser()->getUsername();
			
			$msg="Du darfst das Gruppenmitglied '$username' nicht aus der Gruppe entfernen, da '$username' noch offene Zahlungen in dieser Gruppe zu begleichen hat!";
				
				
			$aktuser_id=$_SESSION['user']->getU_id();
		
			$mitgliederliste=Gruppenmitglied::gruppenmitgliederlisteHolen($g_id);
		
			$gruppe=new Gruppe();
			$gruppe->laden($g_id);
		
			$view = new ViewModel([
					'mitgliederListe' => $mitgliederliste,
					'gruppe' => array($gruppe),
					'u_id' => $aktuser_id,
					'aktgruppenmitglied' => $aktgruppenmitglied,
					'msg' => $msg
			]);
				
			$view->setTemplate('application/groupshow/groupshow.phtml');
				
			return $view;
				
		}
		
		


		// wenn die Aktion abgebrochen werden soll
		if ($_REQUEST['abbrechen']) {
				
			$aktuser_id=$_SESSION['user']->getU_id();
				
			// L�sung ist hier mit Objektorientierung
			$gruppenliste=Gruppenmitglied::eigenelisteholen($aktuser_id);
				
			$view = new ViewModel([
					'gruppenListe' => $gruppenliste,
					'u_id' => $aktuser_id,
					'msg' => $msg
			]);

			$view->setTemplate('application/groupoverview/groupoverview.phtml');
				
			return $view;
		}

		// wenn das Formular zur Best�tigung des L�schens schon abgesendet wurde, soll dies hier ausgewertet werden
		if ($_REQUEST['send']) {
				
			$msg = "";
			
			// wenn der Ladevorgang erfolgreich war, wird versucht die Gruppenmitgliedschaft zu l�schen
			if ($gruppenmitglied->loeschen ($g_id, $user_id)) {


				$vorname=$gruppenmitglied->getUser()->getVorname();
				$nachname=$gruppenmitglied->getUser()->getNachname();
				$gruppenname=$gruppenmitglied->getGruppe()->getGruppenname();

				$msg .= "$vorname $nachname wurde erfolgreich aus der Gruppe '$gruppenname' entfernt!<br>";
				
				
	
				// Muss geändert werden zu: (sollte jetzt passen)
				Gruppenereignis::userausgruppeentfernenEreignis($gruppenmitglied);

			} else {

				// ausgeben, dass die Gruppenmitgliedschaft nicht gel�scht werden konnte (kein Template n�tig!)
				$msg .= "Fehler beim Entfernen des Gruppenmitglieds!<br>";
				return sprintf ( "<div class='error'>Fehler beim Entfernen des Gruppenmitglieds!</div>");
			}
		} else {

			// da das Formular zum Best�tigen des L�schens der Gruppenmitgliedschaft noch nicht angezeigt wurde, wird es hier generiert und an den ViewModelController
			// zur Ausgabe �bergeben
				
			return new ViewModel([
					'gruppenmitglied' => $gruppenmitglied,
			]);
		}


		$user_id=$_SESSION['user']->getU_id();
		$gruppenliste=Gruppenmitglied::eigenelisteholen($user_id);


		$view = new ViewModel([
				'gruppenListe' => $gruppenliste,
				'msg' => $msg
		]);

		$view->setTemplate('application/groupoverview/groupoverview.phtml');
			
		return $view;

	}


}
?>