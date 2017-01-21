<?php

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Application\Model\Gruppe;
use Application\Model\User;
use Application\Model\Gruppenmitglied;
use Application\Model\Nachricht;
use Application\Model\Gruppenereignis;



class GroupshowController extends AbstractActionController
{
	public function GroupshowAction()
	{
		
		session_start();
		
		$user_id=$_SESSION['user']->getU_id();
		$user=$_SESSION['user'];
		$errStr="";
		

		
		// Gruppen-Objekt laden
		$gruppe= new Gruppe();
		$g_id=$_REQUEST['g_id'];
		$gruppe->laden($g_id);
		
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
	
		// Gruppenadminrechte ändern
		$mitgliederliste=Gruppenmitglied::gruppenmitgliederlisteHolen($g_id);
		
		$aktgruppenmitglied=new Gruppenmitglied();
		$aktgruppenmitglied->laden($g_id, $user_id);
		
		if ($_REQUEST['gruppenadmin']) {
				
			$admin_U_id=$_REQUEST['u_id'];

			$gruppenmitglied=new Gruppenmitglied();
			$gruppenmitglied->laden($_REQUEST['g_id'],$admin_U_id);
			
			
			if ($gruppenmitglied->getGruppenadmin()=="1") {
				$adminaenderung="0";
				
				$nehmer = $_SESSION['user'];
				
				
				$genommener = $gruppenmitglied->getUser();
				
				Gruppenereignis::gruppenadminrechtenehmenEreignis($nehmer, $genommener, $gruppe);
				
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
		
		
		// Messageboard
		$nachricht = new Nachricht();
		$user_id=$_SESSION['user']->getU_id();
		$g_id=$_REQUEST ['g_id'];
		$nachricht->setG_id ($g_id);
		
		// Nachrichtenliste laden je Gruppe
		$aktnachrichtliste=Nachricht::messageboard($user_id, $g_id);
		
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

			
			// Keine Errors vorhanden, Funktion kann ausgefï¿½hrt werden
				
			if (!$error) {
		
				// Nachrichten-Objekt mit Daten aus Request-Array fï¿½llen
				
				$nachricht->setDatum($datum);
				$nachricht->setText ($text);
				$nachricht->setU_id ($u_id);
				$nachricht->setG_id ($g_id);
			}
			
			$nachricht->sendMessage();
			
			// Nachrichtenliste laden je Gruppe (jetzt inkl. neuer Nachricht
			$aktnachrichtliste=Nachricht::messageboard($user_id, $g_id);
		}
		
			
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