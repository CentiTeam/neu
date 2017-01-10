<?php

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Application\Model\Gruppe;
use Application\Model\User;
use Application\Model\Gruppenmitglied;

#TODO @Tanja Fehler abpr�fen!

class EinladungannehmenController extends AbstractActionController
{

	public function einladungannehmenAction() {

		session_start();
		/** Pr�fen, ob Gruppenmitglied
		 if (User::getInstance ()->getTeam()->getBezeichnung() != "Personal") {
			return "<div class='error'>Nicht berechtigt!</div>";
			}
			*/
	
		// neues Model anlegen
		$gruppenmitglied = new Gruppenmitglied ();

		// Model anhand der �bergebenen $g_id laden lassen und speichern, ob dies funktioniert hat
		$u_id=$_GET['u_id'];
		$g_id=$_GET['g_id'];
		
		
		$gruppe=new Gruppe();
		$isOKgruppe = $gruppe->laden ($g_id);
		
		$user=new User();
		$isOKuser = $user->laden ($u_id);
		


		// wenn die Aktion abgebrochen werden soll
		if ($_REQUEST['abbrechen']) {
					
			$view = new ViewModel([
			]);

			$view->setTemplate('application/index/index.phtml');
				
			return $view;
		}

		// wenn das Formular zur Best�tigung des L�schens schon abgesendet wurde, soll dies hier ausgewertet werden
		if ($_REQUEST['speichern']) {
				
			$msg = "";
			
			
			$gruppenmitglied->setU_id($u_id);
			$gruppenmitglied->setG_id($g_id);
			$gruppenmitglied->setGruppenadmin(0);
				
			$isOk=$gruppenmitglied->anlegen();
				
			// wenn der Ladevorgang erfolgreich war, wird versucht die Gruppe zu l�schen
			if ($isOK) {
				echo "if";
				$gruppenname=$gruppe->getGruppenname();
				$vorname=$user->getVorname();
				// ausgeben, dass die Gruppe gel�scht wurde (kein Template n�tig!)
				// array_push($msg, "Gruppe erfolgreich gel�scht!");

				$msg .= "Du wurdest erfolgreich zu der Gruppe $gruppenname hinzugefügt, $vorname!<br>";

			} else {
				echo "esle";
				// ausgeben, dass das Team nicht gel�scht werden konnte (kein Template n�tig!)
				$msg .= "Fehler beim Hinzufügen zu der Gruppe!<br>";
				return sprintf ( "<div class='error'>Fehler beim Hinzufügen zu der Gruppe #%s %s!</div>" ,$gruppe->getG_id (), $gruppe->getGruppenname () );
			}
		} else {

			// da das Formular zum Best�tigen des L�schens der Gruppe noch nicht angezeigt wurde, wird es hier generiert und an den ViewModelController
			// zur Ausgabe �bergeben
				
			return new ViewModel([
					'gruppe' => array($gruppe),
					'user' => array($user)
			]);
		}
		
		session_start();
		$view = new ViewModel([
				'msg' => $msg,
				'gruppe' => array($gruppe),
				'user' => array($user)
				
		]);

		$view->setTemplate('application/groupoverview/groupoverview.phtml');
			
		return $view;

	}


}