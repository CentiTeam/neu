<?php

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Application\Model\Gruppe;
use Application\Model\Gruppenmitglied;
use Application\Model\Gruppenereignis;
use Application\Model\Zahlungsteilnehmer;

#TODO @Tanja Fehler abpr�fen!

class GroupdeleteController extends AbstractActionController
{

	public function groupdeleteAction() {
		
		session_start();
		
		// Berechtigungsprüfung: Pr�fen, ob Angemeldet und danach ob Gruppeadmin
		if ($_SESSION['angemeldet']==NULL) {
		
			$msg="Nicht berechtigt!";
		
			$view = new ViewModel([
					'msg' => $msg,
			]);
		
			$view->setTemplate('application/index/index.phtml');
		
			return $view;
		
		} 
		

		// Pr�fen, ob Gruppeadmin
		$user_id=$_SESSION['user']->getU_id();
		$gruppen_id=$_REQUEST['g_id'];
		
		$gruppenmitglied=new Gruppenmitglied();
		$isOK=$gruppenmitglied->laden($gruppen_id, $user_id);
		
		if ($isOK==false || $gruppenmitglied->getGruppenadmin()=="0") {
			
			$errStr="Nicht berechtigt!";
			$gruppenliste=Gruppenmitglied::eigenelisteholen($user_id);
			
			$view = new ViewModel([
					'gruppenListe' => $gruppenliste,
					'err' => $errStr,
					'u_id' => $user_id
			]);
				
			$view->setTemplate('application/groupoverview/groupoverview.phtml');
			
			return $view;
		}
		
		
		// neues Model anlegen
		$gruppe = new Gruppe ();
		
		// Model anhand der �bergebenen $g_id laden lassen und speichern, ob dies funktioniert hat
		$g_id=$_REQUEST['g_id'];
		
		$isOK = $gruppe->laden ($g_id);
		
		
		
		$errStr="";
		
		// !!!!
		// Pr�fen, ob es noch offene Zahlungen gibt
		// !!!!!
		$offeneZahlungen=false;
		
		$gruppenmitgliederListe=Gruppenmitglied::gruppenmitgliederlisteHolen($g_id);
		$counter=0;
		$offeneTeilnehmer=array();
		
		foreach ($gruppenmitgliederListe as $gruppenmitglied) { 
			
			$offeneZahlungen=false;
			
			$zahlungenListe=Zahlungsteilnehmer::teilnehmerzahlungennachgruppeholen($gruppenmitglied->getUser()->getU_id(), $g_id);
		
			foreach ($zahlungenListe as $zahlungen) {
				if ($zahlungen->getStatus()=="offen") {
					$offeneZahlungen=true;
					$offeneTeilnehmer[]=$gruppenmitglied;
				}
			}
			
			$counter++;
		}
		
		if ($counter>0) {
			
			// Fehlermeldung generieren
			$gruppenname=$gruppenmitglied->getGruppe()->getGruppenname();
				
			// $msg="Du darfst die Gruppe '$gruppenname' nicht l&ouml;schen, da mindestens ein Teilnehmer noch offene Zahlungen in dieser Gruppe zu begleichen hat!";
			if ($counter==1) {
					$msg="Du darfst die Gruppe '$gruppenname' nicht l&ouml;schen, da noch $counter Teilnehmer offene Zahlungen in dieser Gruppe zu begleichen hat!";
					
					$msg.= "$offeneTeilnehmer[0]->getUser()->getUsername()";
					
			} else { 
					$msg="Du darfst die Gruppe '$gruppenname' nicht l&ouml;schen, da noch $counter Teilnehmer offene Zahlungen in dieser Gruppe zu begleichen haben!";
			}
			// View Groupoverview wieder aufrufen
			$user_id=$_SESSION['user']->getU_id();
				
			$gruppenliste=Gruppenmitglied::eigenelisteholen($user_id);
				
			$view = new ViewModel([
					'gruppenListe' => $gruppenliste,
					'u_id' => $user_id,
					'msg' => $msg
			]);
			
			$view->setTemplate('application/groupoverview/groupoverview.phtml');
				
			return $view;
		
		}
		
		
		
		// wenn die Aktion abgebrochen werden soll
		if ($_REQUEST['abbrechen']) {
			
			$user_id=$_SESSION['user']->getU_id();
			
			// L�sung ist hier mit Objektorientierung
			$gruppenliste=Gruppenmitglied::eigenelisteholen($user_id);
			
			$view = new ViewModel([
					'gruppenListe' => $gruppenliste,
					'u_id' => $user_id,
					'msg' => $msg
			]);
				
			$view->setTemplate('application/groupoverview/groupoverview.phtml');
			
			return $view;
		}
				
		// wenn das Formular zur Best�tigung des L�schens schon abgesendet wurde, soll dies hier ausgewertet werden
		if ($_REQUEST['send']) {
			
			$msg = "";
			
			// wenn der Ladevorgang erfolgreich war, wird versucht die Gruppe zu l�schen
			if ($isOK && $gruppe->loeschen ($g_id)) {

				
				// ausgeben, dass die Gruppe gel�scht wurde (kein Template n�tig!)
				// array_push($msg, "Gruppe erfolgreich gel�scht!");
				
				$msg .= "Gruppe erfolgreich gel&ouml;scht!<br>";
				
				//Schreiben des Ereignisses das die Gruppe gel�scht wurde in die Ereignistabelle der Datenbank
				Gruppenereignis::gruppeloeschenEreignis($gruppe);
				
			} else {
		
				// ausgeben, dass das Team nicht gel�scht werden konnte (kein Template n�tig!)
				$msg .= "Fehler beim L&ouml;schen der Gruppe!<br>";
				return sprintf ( "<div class='error'>Fehler beim L�schen der Gruppe #%s %s!</div>" ,$gruppe->getG_id (), $gruppe->getGruppenname () );
			}
		} else {
				
			// da das Formular zum Best�tigen des L�schens der Gruppe noch nicht angezeigt wurde, wird es hier generiert und an den ViewModelController
			// zur Ausgabe �bergeben
			
			return new ViewModel([
				'gruppe' => $gruppe,
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