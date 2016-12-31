<?php

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Application\Model\User;


class BenutzerdeaktivierenController extends AbstractActionController{
	
	public function benutzerdeaktivierenAction(){
		
		//neues Model anlegen
		$user = new User();
		
		// Model anhand der übergebenen $u_id laden lassen und speichern, ob dies funktioniert hat
		$u_id=$_REQUEST['u_id'];
		
		$isOK = $user->laden($u_id);
		
		
		// wenn die Aktion abgebrochen werden soll
		if ($_REQUEST['abbrechen']) {
		
			$userliste=User::listeholen();
		
			$view = new ViewModel([
					'userListe' => $userliste
			]);
		
			$view->setTemplate('application/benutertabelle/benutzertabelle.phtml');
		
			return $view;
		}
		
		
		
		// wenn das Formular zur Bestätigung des Deaktivierens schon abgesendet wurde, soll dies hier ausgewertet werden
		if ($_REQUEST['ja']) {
			echo "test!";
			echo $isOK;
		
			$msg = "";
		
			// wenn der Ladevorgang erfolgreich war, wird versucht den Benutzer zu deaktivieren
			if ($isOK) {
				$user-> setDeaktiviert(1)
				echo $user->getDeaktiviert();
				
		
		
				// ausgeben, dass der Benutzer deaktiviert wurde (kein Template nötig!)
				// array_push($msg, "Benutzer erfolgreich deaktiviert!");
		
				$msg .= "Benutzer erfolgreich deaktiviert!<br>";
		
			} else {
				
				echo "nope";
		
				// ausgeben, dass der Benutzer nicht deaktiviert werden konnte (kein Template nötig!)
				$msg .= "Fehler beim Deaktivieren des Benutzers!<br>";
				return sprintf ( "<div class='error'>Fehler beim Deaktiveren des Benutzers #%s %s!</div>" ,$user->getU_id (), $user->getUsername () );
			}
		} else {
		
			// da das Formular zum Bestätigen des Deaktivierens des Benutzers noch nicht angezeigt wurde, wird es hier generiert und an den ViewModelController
			// zur Ausgabe übergeben
		
			return new ViewModel([
					'user' => $user,
			]);
		}
		
		$userliste=User::listeholen();
		
		$view = new ViewModel([
				'userListe' => $userliste,
				'msg' => $msg
		]);
		
		$view->setTemplate('application/benutzertabelle/benutzertabelle.phtml');
			
		return $view;
		
		
		
		
		
	}
	
}