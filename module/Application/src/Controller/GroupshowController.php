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
		
		$user_id=$_SESSION['user']->getU_id();
		
	
		// Gruppen-Objekt laden
		$gruppe= new Gruppe();
		$g_id=$_REQUEST['g_id'];
		$gruppe->laden($g_id);
		
		$mitgliederliste=Gruppenmitglied::gruppenmitgliederlisteHolen($g_id);
		
		
		if ($_REQUEST['gruppenadmin']) {
			
			
			$admin_U_id=$_GET['u_id'];
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
		]);
			
		}
}