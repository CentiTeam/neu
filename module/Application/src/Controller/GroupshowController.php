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
		
		
		
		/** Kann raus, wenn objektorientierung bleibt
		// Liste der User-Objekte der Gruppenmitglieder holen
		$mitgliederliste = User::gruppenmitgliederlisteholen($g_id); 
		
		$mitgliedschaft=array();
		
		// F�r jedes Gruppenmitglied mit die Gruppenmitgliedschafts-Infos (inkl. Gruppenadmin) laden
		// und Mitgliedschaftsinfos in Array speichern, wenn Gruppenmitgliedschaft besteht
		foreach ($mitgliederliste as $mitglied) {
			
			// Gruppenmitglied instanzieren
			$gruppenmitglied= new Gruppenmitglied();
			$gruppenmitglied->laden ($g_id, $mitglied->getU_id());
			
			// Wenn Gruppenmitgliedschaft dem User-Objekt entspricht wird das Array weiter bef�llt
			if ($gruppenmitglied->getUser()->getU_id() == $mitglied->getU_id()) {
				
				$mitgliedschaft[]=$gruppenmitglied;
				
			}
		}
		
		*/
		
		
		if ($_REQUEST['gruppenadmin']) {
			echo $_REQUEST['gruppenadminwert'];
			if ($_REQUEST['gruppenadminwert']=="1") {
				$adminaenderung="0";
			} else {
				$adminaenderung="1";
			}
			
			$gruppenmitglied=new Gruppenmitglied();
			
			$gruppenmitglied->laden($_REQUEST['g_id'],$_REQUEST["u_id"]);
			
			$gruppenmitglied->bearbeiten($adminaenderung);
			
		}
		
		$user_id=$_SESSION['user']->getU_id();
		$grpnachrichtliste=Nachricht::messageboard($user_id);
		
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
				
			return new ViewModel([
					'gruppe' => array($gruppe),
					'mitgliederListe' => $mitgliederliste,
					'mitgliedschaft' => $mitgliedschaft,
					
			
			
			]);
			
		}
		
		
			
		return new ViewModel([
				'gruppe' => array($gruppe),
				'mitgliederListe' => $mitgliederliste,
				'mitgliedschaft' => $mitgliedschaft,
				'aktnachricht' => $aktuellenachrichtliste,
			
					
		]);
			
		}
		
	
	
	
}