<?php

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Application\Model\Gruppe;
use Application\Model\User;
use Application\Model\Gruppenmitglied;
use Application\Model\Nachricht;



class GroupshowController extends AbstractActionController
{
	public function GroupshowAction()
	{
		
		session_start();
		
		$user=$_SESSION['user'];
		$errStr="";
		
		// Gruppen-Objekt laden
		$gruppe= new Gruppe();
		$g_id=$_REQUEST['g_id'];
		$gruppe->laden($g_id);
		
		$allegruppenliste=Gruppenmitglied::listeholen();
		
		$mitglied=false;
		foreach ($allegruppenliste as $mitgliedschaft) {
			if ($mitgliedschaft->getUser()->getU_id()==$user->getU_id() && $mitgliedschaft->getGruppe()->getG_id()==$g_id) {
				$mitglied=true;
			}
		}
		
		if ($mitglied==false) {
		
			$errStr="Nicht berechtigt!";
			$gruppenliste=Gruppenmitglied::eigenelisteholen($user->getU_id());
		
		
			$view = new ViewModel([
					'gruppenListe' => $gruppenliste,
					'u_id' => $user->getU_id(),
					'err' => $errStr
			]);
		
			$view->setTemplate('application/groupoverview/groupoverview.phtml');
		
			return $view;
		}
	
		
		$mitgliederliste=Gruppenmitglied::gruppenmitgliederlisteHolen($g_id);
		
		$aktgruppenmitglied=new Gruppenmitglied();
		$aktgruppenmitglied->laden($g_id, $user_id);
		
		if ($_REQUEST['gruppenadmin']) {
			
			if ($aktgruppenmitglied->getGruppenadmin()=="0" || $aktgruppenmitglied->getUser()->getU_id() == $user->getU_id()) {
				$errStr="Nicht berechtigt!";
			}
			
			if ($errStr=="") {
				$admin_U_id=$_REQUEST['u_id'];
				echo "User_id:";
				echo $admin_U_id;
			

				$gruppenmitglied=new Gruppenmitglied();
				$gruppenmitglied->laden($_REQUEST['g_id'],$admin_U_id);
			
			
				if ($gruppenmitglied->getGruppenadmin()=="1") {
					$adminaenderung="0";
				} else {
					$adminaenderung="1";
				}
			
				echo "Adminaenderung:";
				echo $adminaenderung;
			
				$gruppenmitglied->bearbeiten($adminaenderung);
			}
		}
		
		$nachricht = new Nachricht();
		$user_id=$_SESSION['user']->getU_id();
		$g_id=$_REQUEST ['g_id'];
		$nachricht->setG_id ($g_id);
		
		$aktnachrichtliste=Nachricht::messageboard($user_id);
		
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

			
			// Keine Errors vorhanden, Funktion kann ausgef�hrt werden
				
			if (!$error) {
		
				// Nachrichten-Objekt mit Daten aus Request-Array f�llen
				
				$nachricht->setDatum($datum);
				$nachricht->setText ($text);
				$nachricht->setU_id ($u_id);
				$nachricht->setG_id ($g_id);
			}
			
			
			$nachricht->sendMessage();
				
		}
		
			
		return new ViewModel([
				'gruppe' => array($gruppe),
				'mitgliederListe' => $mitgliederliste,
				'mitgliedschaft' => $mitgliedschaft,
				'aktnachricht' => $aktnachrichtliste,
				'aktgruppenmitglied' => $aktgruppenmitglied,
				'user_id' => $user_id
		]);
			
		}
}