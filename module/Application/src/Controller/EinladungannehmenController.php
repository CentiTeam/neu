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
	
		// neues Model anlegen
		$gruppenmitglied = new Gruppenmitglied ();

		// Model anhand der �bergebenen $g_id laden lassen und speichern, ob dies funktioniert hat
		$u_id=$_GET['u_id'];
		$g_id=$_GET['g_id'];
		
		
		$gruppe=new Gruppe();
		$isOKgruppe = $gruppe->laden ($g_id);
		
		$user=new User();
		$isOKuser = $user->laden ($u_id);
		
		echo "Gruppeninfos";
		var_dump($isOKgruppe); 

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
			$errorStr = "";
			
			$gruppenmitglied->setUser($isOKuser);
			$gruppenmitglied->setGruppe($isOKgruppe);
			$gruppenmitglied->setGruppenadmin(0);
			var_dump ($gruppenmitglied);
			
			$gruppenmitglied->laden($g_id, $u_id);
			
			
			$gruppenmitgliedListe=Gruppenmitglied::listeholen();
			
			foreach ($gruppenmitgliedListe as $liste) {
				if ($gruppenmitglied->getUser()->getU_id()==$liste->getUser()->getU_id() && $gruppenmitglied->getGruppe()->getG_id()==$liste->getGruppe()->getG_id()) {
					
					$errorStr .= "Du bist bereits Mitglied der Gruppe!<br>";
					
				}
			}
			// wenn der Ladevorgang erfolgreich war, wird versucht die Gruppe zu l�schen
			if ($errorStr=="" && $gruppenmitglied->anlegen()) {
				$gruppenname=$gruppe->getGruppenname();
				$vorname=$user->getVorname();
				// ausgeben, dass die Gruppe gel�scht wurde (kein Template n�tig!)
				// array_push($msg, "Gruppe erfolgreich gel�scht!");

				$msg .= "Du wurdest erfolgreich zu der Gruppe $gruppenname hinzugefügt, $vorname!<br>";

			} else {
				// ausgeben, dass das Team nicht gel�scht werden konnte (kein Template n�tig!)
				$msg .= "Fehler beim Hinzufügen zu der Gruppe!<br>";
				
				
				$view = new ViewModel([
						'msg' => $msg,
						'err' => $errorStr
				]);
				
				$view->setTemplate('application/index/index.phtml');
					
				return $view;
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
		$_SESSION['user']=$user;
		$_SESSION['angemeldet']="ja";
		
		$mitgliederListe=User::gruppenmitgliederlisteHolen($gruppe->getG_id());
		
		$mitgliedschaft=array();
		
		// F�r jedes Gruppenmitglied mit die Gruppenmitgliedschafts-Infos (inkl. Gruppenadmin) laden
		// und Mitgliedschaftsinfos in Array speichern, wenn Gruppenmitgliedschaft besteht
		foreach ($mitgliederListe as $mitglied) {
				
			// Gruppenmitglied instanzieren
			$gruppenmitglied= new Gruppenmitglied();
			$gruppenmitglied->laden ($gruppe->getG_id(), $mitglied->getU_id());
				
			// Wenn Gruppenmitgliedschaft dem User-Objekt entspricht wird das Array weiter bef�llt
			if ($gruppenmitglied->getU_id() == $mitglied->getU_id()) {
		
				$mitgliedschaft[]=$gruppenmitglied;
		
			}
		}
		
		$view = new ViewModel([
				'msg' => $msg,
				'gruppe' => array($gruppe),
				'user' => array($user),
				'mitgliederListe' => $mitgliederListe,
				'mitgliedschaft' => $mitgliedschaft,
				
		]);

		$view->setTemplate('application/groupshow/groupshow.phtml');
			
		return $view;

	}


}