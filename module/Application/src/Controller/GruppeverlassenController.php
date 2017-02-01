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

		
		
		$msg="";

		// !!!!
		// Pr�fen, ob es noch offene Zahlungen gibt
		// !!!!!

		$offenezahlungen=Zahlungsteilnehmer::offeneteilnehmerzahlungennachgruppeholen($user->getU_id(), $g_id);
		
		var_dump($offenezahlungen);
		

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

		// wenn das Formular zur Best�tigung des L�schens schon abgesendet wurde, soll dies hier ausgewertet werden
		if ($_REQUEST['send']) {
				
			$msg = "";
				
			// wenn der Ladevorgang erfolgreich war, wird versucht die Gruppe zu l�schen
			if ($gruppenmitglied->loeschen ($g_id, $user->getU_id())) {

				$msg .= "Gruppenmitgliedschaft erfolgreich gel&ouml;scht!<br>";
				
				//Schreiben des Gruppenaustritts in die Ereignistabelle der Datenbank
				Gruppenereignis::gruppenmitgliedaustretenEreignis($user, $gruppenmitglied->getGruppe()); 

			} else {

				// ausgeben, dass das Team nicht gel�scht werden konnte (kein Template n�tig!)
				$msg .= "Fehler beim L&ouml;schen der Gruppenmitgliedschaft!<br>";
				return sprintf ( "<div class='error'>Fehler beim L&ouml;schen der Gruppenmitglieschaft in Gruppe #%s %s!</div>" ,$gruppenmitglied->getGruppe()->getG_id (), $gruppenmitglied->getGruppe()->getGruppenname () );
			}
		} else {

			// da das Formular zum Best�tigen des L�schens der Gruppe noch nicht angezeigt wurde, wird es hier generiert und an den ViewModelController
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