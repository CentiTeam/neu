<?php

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Application\Model\Gruppe;

#TODO @Tanja Fehler abprüfen!

class GroupdeleteController extends AbstractActionController
{

	public function groupdeleteAction() {
		
		session_start();
		/** Prüfen, ob Gruppenmitglied
		if (User::getInstance ()->getTeam()->getBezeichnung() != "Personal") {
			return "<div class='error'>Nicht berechtigt!</div>";
		}
		*/
		
		// neues Model anlegen
		$gruppe = new Gruppe ();
		
		// Model anhand der übergebenen $g_id laden lassen und speichern, ob dies funktioniert hat
		$g_id=$_REQUEST['g_id'];
		
		$isOK = $gruppe->laden ($g_id);
		
		
		// !!!!
		// Prüfen, ob es noch offene Zahlungen gibt
		// !!!!!
		
		
		
		// wenn die Aktion abgebrochen werden soll
		if ($_REQUEST['abbrechen']) {
			
			$gruppenliste=Gruppe::listeholen(); 
			
			$view = new ViewModel([
					'gruppenListe' => $gruppenliste
			]);
				
			$view->setTemplate('application/groupoverview/groupoverview.phtml');
			
			return $view;
		}
				
		// wenn das Formular zur Bestätigung des Löschens schon abgesendet wurde, soll dies hier ausgewertet werden
		if ($_REQUEST['send']) {
			
			$msg = "";
			
			// wenn der Ladevorgang erfolgreich war, wird versucht die Gruppe zu löschen
			if ($isOK && $gruppe->loeschen ($g_id)) {

				
				// ausgeben, dass die Gruppe gelöscht wurde (kein Template nötig!)
				// array_push($msg, "Gruppe erfolgreich gelöscht!");
				
				$msg .= "Gruppe erfolgreich gel&ouml;scht!<br>";
				
			} else {
		
				// ausgeben, dass das Team nicht gelöscht werden konnte (kein Template nötig!)
				$msg .= "Fehler beim L&ouml;schen der Gruppe!<br>";
				return sprintf ( "<div class='error'>Fehler beim Löschen der Gruppe #%s %s!</div>" ,$gruppe->getG_id (), $gruppe->getGruppenname () );
			}
		} else {
				
			// da das Formular zum Bestätigen des Löschens der Gruppe noch nicht angezeigt wurde, wird es hier generiert und an den ViewModelController
			// zur Ausgabe übergeben
			
			return new ViewModel([
				'gruppe' => $gruppe,
			]);
		}
		
		$user_id=$_SESSION['user']->getU_id();
		
		$gruppenliste=Gruppe::eigenelisteholen($user_id);
		
		
		$view = new ViewModel([
				'gruppenListe' => $gruppenliste,
				'msg' => $msg
		]);
		
		$view->setTemplate('application/groupoverview/groupoverview.phtml');
			
		return $view;
		
	}
	
	
}