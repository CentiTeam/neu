<?php

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Application\Model\Gruppe;
use Application\Model\Gruppenmitglied;
use Application\Model\User;
use Application\Model\Gruppenereignis;  
use Application\Model\Zahlungsteilnehmer;

#TODO @Tanja Fehler abpr�fen!

class GruppeverlassenController extends AbstractActionController
{

	public function gruppeverlassenAction() {

		session_start();


		// Berechtigungsprüfung
		if ($_SESSION['angemeldet']==NULL) {
		
			$msg="Nicht berechtigt!";
		
			$view = new ViewModel([
					'msg' => $msg,
			]);
		
			$view->setTemplate('application/index/index.phtml');
		
			return $view;
		
		}
		
		$g_id=$_REQUEST['g_id'];
		$user_id=$_SESSION['user']->getU_id();
		
		// Überprüfen, ob Gruppenmitglied
		$aktgruppenmitglied=new Gruppenmitglied();
		$isOK=$aktgruppenmitglied->laden($g_id, $user_id);
		
		if ($isOK==false) {
		
			$msg="Nicht berechtigt!";
		
			$gruppenliste=Gruppenmitglied::eigenelisteholen($user_id);
		
			$view = new ViewModel([
					'gruppenListe' => $gruppenliste,
					'msg' => $msg,
			]);
		
			$view->setTemplate('application/groupoverview/groupoverview.phtml');
		
			return $view;
		}
		

		//neues Model anlegen
		$gruppenmitglied=new Gruppenmitglied();
		
		// Model anhand der �bergebenen $g_id laden lassen und speichern, ob dies funktioniert hat
		$g_id=$_REQUEST['g_id'];
		$user=$_SESSION['user'];
		
		$gruppenmitglied->laden($g_id, $user->getU_id());

		
		// Pr�fen, ob es noch offene Zahlungen gibt
		$offeneZahlungen=false;
		
		$zahlungenListe=Zahlungsteilnehmer::teilnehmerzahlungennachgruppeholen($user->getU_id(), $g_id);
		
		foreach ($zahlungenListe as $zahlungen) {
			if ($zahlungen->getStatus()=="offen") {
				$offeneZahlungen=true;
			}
		}
		
		// Wenn der User noch offene Zahungen in dieser Gruppe hat darf er die Gruppe nicht verlassen
		// Es wird eine Fehlermeldung generiert
		if ($offeneZahlungen==true) {
			
			$msg="Du darfst die Gruppe noch nicht verlassen, da Du noch Zahlungen in dieser Gruppe zu begleichen hast";
			
			
			$user_id=$_SESSION['user']->getU_id();	
				
			$mitgliederliste=Gruppenmitglied::gruppenmitgliederlisteHolen($g_id);
				
			$gruppe=new Gruppe();
			$gruppe->laden($g_id);
				
			$view = new ViewModel([
					'mitgliederListe' => $mitgliederliste,
					'gruppe' => array($gruppe),
					'u_id' => $user_id,
					'aktgruppenmitglied' => $aktgruppenmitglied,
					'msg' => $msg
			]);
			
			$view->setTemplate('application/groupshow/groupshow.phtml');
			
			return $view;
			
		}
		

		// wenn die Aktion abgebrochen werden soll
		if ($_REQUEST['abbrechen']) {
				
			$user_id=$_SESSION['user']->getU_id();
			
				
			// L�sung ist hier mit Objektorientierung
			
			$mitgliederliste=Gruppenmitglied::gruppenmitgliederlisteHolen($g_id);
			
			$gruppe=new Gruppe();
			$gruppe->laden($g_id);
			
			$view = new ViewModel([
					'mitgliederListe' => $mitgliederliste,
					'gruppe' => array($gruppe),
					'u_id' => $user_id,
					'aktgruppenmitglied' => $aktgruppenmitglied,
					'msg' => $msg
			]);

			$view->setTemplate('application/groupshow/groupshow.phtml');
				
			return $view;
		} 

		// wenn das Formular zur Best�tigung des Austretens aus der Gruppe schon abgesendet wurde, soll dies hier ausgewertet werden
		if ($_REQUEST['send']) {
				
			$msg = "";
				
			// wenn der Ladevorgang erfolgreich war, wird versucht die Gruppenmitgliedschaft geloescht
			if ($gruppenmitglied->loeschen ($g_id, $user->getU_id())) {

				$msg .= "Gruppenmitgliedschaft erfolgreich gel&ouml;scht!<br>";
				
				//Schreiben des Gruppenaustritts in die Ereignistabelle der Datenbank
				Gruppenereignis::gruppenmitgliedaustretenEreignis($user, $gruppenmitglied->getGruppe()); 

			} else {

				// ausgeben, dass daie Gruppenmitgliedschaft nicht gel�scht werden konnte (kein Template n�tig!)
				$msg .= "Fehler beim L&ouml;schen der Gruppenmitgliedschaft!<br>";
				return sprintf ( "<div class='error'>Fehler beim L&ouml;schen der Gruppenmitglieschaft in Gruppe #%s %s!</div>" ,$gruppenmitglied->getGruppe()->getG_id (), $gruppenmitglied->getGruppe()->getGruppenname () );
			}
		} else {

			// da das Formular zum Best�tigen des L�schens der Gruppenmitgliedschaft noch nicht angezeigt wurde, wird es hier generiert und 
			// zur Ausgabe �bergeben
				
			return new ViewModel([
					'gruppenmitglied' => $gruppenmitglied,
			]);
		}

		// Nach erfolgreichem Löschen der Gruppenmitgliedschaft wird die persönl. Groupoverview geladen
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