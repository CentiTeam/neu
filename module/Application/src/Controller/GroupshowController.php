<?php

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Application\Model\Gruppe;
use Application\Model\User;
use Application\Model\Gruppenmitglied;
use Application\Model\Nachricht;
use Application\Model\Gruppenereignis;


/**
 * 
 * @author Tanja
 *
 *Übersicht über eine Gruppe:
 *- Gruppendetails
 *- Weiterfuehrende Funktionen
 *- Gruppenmitglieder
 *- Nachrichten und Messageboard
 *- Gruppe verlassen
 * 
 */

class GroupshowController extends AbstractActionController
{
	public function GroupshowAction()
	{
		
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
		
		// Wenn der User, der auf die Gruppe zugreifen will, kein Gruppenmitglied ist wird seine Groupoverview aufgerufen
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
		
		
		
		$user_id=$_SESSION['user']->getU_id();
		$user=$_SESSION['user'];
		$errStr="";

		
		// Gruppen-Objekt laden
		$gruppe= new Gruppe();
		$g_id=$_REQUEST['g_id'];
		$gruppe->laden($g_id);
		
		// Wohl nochmals dieselbe Überprüfung (bleibt stehen, damit keine Bugs entstehen)
		// Wenn der User, der auf die Gruppe zugreifen will, kein Gruppenmitglied ist wird seine Groupoverview aufgerufen
		$allegruppenliste=Gruppenmitglied::listeholen();
		
		$mitglied=false;
		foreach ($allegruppenliste as $mitgliedschaft) {
			if ($mitgliedschaft->getUser()->getU_id()==$user_id && $mitgliedschaft->getGruppe()->getG_id()==$g_id) {
				$mitglied=true;
			}
		}
		
		if ($mitglied==false) {
		
			$errStr="Nicht berechtigt!";
			$gruppenliste=Gruppenmitglied::eigenelisteholen($user_id);
		
		
			$view = new ViewModel([
					'gruppenListe' => $gruppenliste,
					'u_id' => $user_id,
					'err' => $errStr
			]);
		
			$view->setTemplate('application/groupoverview/groupoverview.phtml');
		
			return $view;
		}
		
		// BErechtigungsprüfung Ende
		
	
		// Gruppenadminrechte �ndern
		$mitgliederliste=Gruppenmitglied::gruppenmitgliederlisteHolen($g_id);
		
		$aktgruppenmitglied=new Gruppenmitglied();
		$aktgruppenmitglied->laden($g_id, $user_id);
		
		if ($_REQUEST['gruppenadmin']) {
				
			$admin_U_id=$_REQUEST['u_id'];

			$gruppenmitglied=new Gruppenmitglied();
			$gruppenmitglied->laden($_REQUEST['g_id'],$admin_U_id);
			
			// Wenn der betreffende User bereits Admin ist werden ihm die Rechte entzogen
			if ($gruppenmitglied->getGruppenadmin()=="1") {
				$adminaenderung="0";
				
				$nehmer = $_SESSION['user'];
				
				
				$genommener = $gruppenmitglied->getUser();
				
				Gruppenereignis::gruppenadminrechtenehmenEreignis($nehmer, $genommener, $gruppe);
			
			// Wenn der betreffende User noch nicht Admin ist werden ihm Adminrechte gegeben
			} else {
				$adminaenderung="1";
				
				$geber = $_SESSION['user'];
				
				
				$nehmer = $gruppenmitglied->getUser();
				
				Gruppenereignis::gruppenadminrechteweitergebenEreignis($geber, $nehmer, $gruppe);
			}
		
		
			$gruppenmitglied->bearbeiten($g_id, $admin_U_id, $adminaenderung);
			
			// Aktualisierte Mitgliederliste
			$mitgliederliste=Gruppenmitglied::gruppenmitgliederlisteHolen($g_id);
		}
		
		
		
	
		
		// Messageboard inkl. Bl�tterfunktion
		$nachricht = new Nachricht();
		$user_id=$_SESSION['user']->getU_id();
		$g_id=$_REQUEST ['g_id'];
		$nachricht->setG_id ($g_id);
		
		// Nachrichtenliste laden je Gruppe
		$aktnachrichtliste=Nachricht::messageboard($user_id, $g_id); 
		

		// Aktion nach dem Absenden der Nachricht
		if ($_REQUEST['abschicken']) {
			
				$nachricht = new Nachricht();
				$error = false;
				
			// Werte aus Formular einlesen
				
			$text = $_REQUEST ['text'];
			date_default_timezone_set("Europe/Berlin");
			$timestamp=time();
			$u_id=$_SESSION['user']->getU_id();
			$datum = date('Y-m-d', $timestamp);
			$g_id = $_REQUEST ['g_id'];  
			
			//Wenn keine Nachricht eingegeben wurde, dann Fehler
			if (strlen(trim($text))){
				$feldpruefungsnachricht= "Die Nachricht ist leer!<br>"; 
			
				$view = new ViewModel([
						'nachrichtenfeldpruefungsnachricht' => $nachrichtenfeldpruefungsnachricht
				]);
			}

			
			// Keine Errors vorhanden, Funktion kann ausgef�hrt werden
				
			if (!$error) {
		
			// Nachrichten-Objekt mit Daten aus Request-Array f�llen
				
				$nachricht->setDatum($datum);
				$nachricht->setText ($text);
				$nachricht->setU_id ($u_id);
				$nachricht->setG_id ($g_id);
			}
			
			$nachricht->sendMessage();
			
			
			
			// Nachrichtenliste laden je Gruppe (inkl. neuer Nachricht)
			$aktnachrichtliste=Nachricht::messageboard($user_id, $g_id);
		}
	
		
		// Groupshow-Model zurückgeben	
		return new ViewModel([
				'gruppe' => array($gruppe),
				'nachricht' => $nachricht,
				'mitgliederListe' => $mitgliederliste,
				'mitgliedschaft' => $mitgliedschaft,
				'aktnachricht' => $aktnachrichtliste,
				'aktgruppenmitglied' => $aktgruppenmitglied,
				'user_id' => $user_id,
				'suche' => $suche
		]);
			
		}
}