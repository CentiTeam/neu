<?php

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Application\Model\User;
use Application\Model\Gruppenereignis;


class BenutzerdeaktivierenController extends AbstractActionController{
	
	public function benutzerdeaktivierenAction(){
		
		session_start();
		
		// Abprüfen, ob angemeldeter User
		if ($_SESSION['angemeldet']==NULL) {
				
			$msg="Nicht berechtigt!";
				
			$view = new ViewModel([
					'msg' => $msg,
			]);
				
			$view->setTemplate('application/index/index.phtml');
				
			return $view;
			// Abprüfen, ob Systemadmin
		} elseif ($_SESSION['user']->getSystemadmin()=="0") {
		
			$msg="Nicht berechtigt!";
		
			$view = new ViewModel([
					'msg' => $msg,
			]);
		
			$view->setTemplate('application/index/index.phtml');
		
			return $view;
		}
		
		//neues Model anlegen
		$user = new User(); 
		
		// Model anhand der uebergebenen $u_id laden lassen und speichern, ob dies funktioniert hat
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
		
		
		
		// wenn das Formular zur Best�tigung des Deaktivierens schon abgesendet wurde, soll dies hier ausgewertet werden
		if ($_REQUEST['ja']) {

		
			$msg = "";
			
			//�berpr�fen, ob ein Admin zur Deaktivierung gesendet wird
			if ($user->getSystemadmin()=='1') {
				
				$msg .= "Systemadministratoren k&oumlnnen nicht deaktiviert werden!<br>";
				
				$userliste=User::listeholen();
				
				$view = new ViewModel([
						'userListe' => $userliste,
						'msg' => $msg
				]);
				
				$view->setTemplate('application/benutzertabelle/benutzertabelle.phtml');
					
				return $view;
			}
		
			// wenn der Ladevorgang erfolgreich war, wird versucht den Benutzer zu deaktivieren
			if ($isOK && $user->deaktivieren()) {
				
				
		
		
				// ausgeben, dass der Benutzer deaktiviert wurde (kein Template n�tig!)
				// array_push($msg, "Benutzer erfolgreich deaktiviert!");
		
				$msg .= "Benutzer erfolgreich deaktiviert!<br>";
				
				Gruppenereignis::benutzerdeaktivierenEreignis($user);
		
			} else {
				
		
				// ausgeben, dass der Benutzer nicht deaktiviert werden konnte (kein Template n�tig!)
				$msg .= "Fehler beim Deaktivieren des Benutzers!<br>";
				return sprintf ( "<div class='error'>Fehler beim Deaktiveren des Benutzers #%s %s!</div>" ,$user->getU_id (), $user->getUsername () );
			}
		} else {
		
			// da das Formular zum Best�tigen des Deaktivierens des Benutzers noch nicht angezeigt wurde, wird es hier generiert und an den ViewModelController
			// zur Ausgabe �bergeben
		
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