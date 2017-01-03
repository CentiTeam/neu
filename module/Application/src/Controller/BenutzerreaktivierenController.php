<?php

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Application\Model\User;


class BenutzerreaktivierenController extends AbstractActionController{

	public function benutzerreaktivierenAction(){

		//neues Model anlegen
		$user = new User();

		// Model anhand der übergebenen $u_id laden lassen und speichern, ob dies funktioniert hat
		$u_id=$_REQUEST['u_id'];

		$isOK = $user->laden($u_id);


		// wenn die Aktion abgebrochen werden soll
		if ($_REQUEST['nein']) {

			$userliste=User::listeholen();

			$view = new ViewModel([
					'userListe' => $userliste
			]);

			$view->setTemplate('application/benutzertabelle/benutzertabelle.phtml');

			return $view;
		}



		// wenn das Formular zur Bestätigung des Reaktivierens schon abgesendet wurde, soll dies hier ausgewertet werden
		if ($_REQUEST['ja']) {


			$msg = "";

			// wenn der Ladevorgang erfolgreich war, wird versucht den Benutzer zu reaktivieren
			if ($isOK && $user->reaktivieren()) {




				// ausgeben, dass der Benutzer reaktiviert wurde (kein Template nötig!)
				// array_push($msg, "Benutzer erfolgreich reaktiviert!");

				$msg .= "Benutzer erfolgreich reaktiviert!<br>";

			} else {


				// ausgeben, dass der Benutzer nicht reaktiviert werden konnte (kein Template nötig!)
				$msg .= "Fehler beim Reaktivieren des Benutzers!<br>";
				return sprintf ( "<div class='error'>Fehler beim Reaktiveren des Benutzers #%s %s!</div>" ,$user->getU_id (), $user->getUsername () );
			}
		} else {

			// da das Formular zum Bestätigen des Reaktivierens des Benutzers noch nicht angezeigt wurde, wird es hier generiert und an den ViewModelController
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