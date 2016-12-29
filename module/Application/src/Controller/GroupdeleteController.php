<?php

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Application\Model\Gruppe;

class GroupdeleteController extends AbstractActionController
{

	public function groupdeleteAction() {
		
		/** Pr�fen, ob Gruppenmitglied
		if (User::getInstance ()->getTeam()->getBezeichnung() != "Personal") {
			return "<div class='error'>Nicht berechtigt!</div>";
		}
		*/
		
		// neues Model anlegen
		$gruppe = new Gruppe ();
		
		// Model anhand der �bergebenen $g_id laden lassen und speichern, ob dies funktioniert hat
		$g_id=$_REQUEST['g_id'];
		
		$isOK = $gruppe->laden ($g_id);
		
		$gruppenliste=Gruppe::listeholen();
		// !!!!
		// Pr�fen, ob es noch offene Zahlungen gibt
		// !!!!!
		
		
		
		// wenn die Aktion abgebrochen werden soll
		if ($_REQUEST['abbrechen']) {
			
			
			$view = new ViewModel([
					'gruppenListe' => $gruppenliste
			]);
				
			$view->setTemplate('application/groupoverview/groupoverview.phtml');
			
			return $view;
		}
				
		// wenn das Formular zur Best�tigung des L�schens schon abgesendet wurde, soll dies hier ausgewertet werden
		if ($_REQUEST['send']) {
			
			$msg = array();
			
			// wenn der Ladevorgang erfolgreich war, wird versucht die Gruppe zu l�schen
			if ($isOK && $gruppe->loeschen ($g_id)) {

				
				// ausgeben, dass die Gruppe gel�scht wurde (kein Template n�tig!)
				array_push($msg, "Gruppe erfolgreich gel�scht!");
				
			} else {
		
				// ausgeben, dass das Team nicht gel�scht werden konnte (kein Template n�tig!)
				return sprintf ( "<div class='error'>Fehler beim L�schen der Gruppe #%s %s!</div>" ,$gruppe->getG_id (), $gruppe->getGruppenname () );
			}
		} else {
				
			// da das Formular zum Best�tigen des L�schens der Gruppe noch nicht angezeigt wurde, wird es hier generiert und an den ViewModelController
			// zur Ausgabe �bergeben
			
			return new ViewModel([
				'gruppe' => $gruppe,
			]);
		}
		
		
		$view = new ViewModel([
				'gruppenListe' => $gruppenliste,
				'msg' => array($msg)
		]);
		
		$view->setTemplate('application/groupoverview/groupoverview.phtml');
			
		return $view;
		
	}
	
	
}