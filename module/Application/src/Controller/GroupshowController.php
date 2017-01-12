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
		

	
		// Gruppen-Objekt laden
		$gruppe= new Gruppe();
		$g_id=$_REQUEST['g_id'];
		$gruppe->laden($g_id);
		
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
			if ($gruppenmitglied->getU_id() == $mitglied->getU_id()) {
				
				$mitgliedschaft[]=$gruppenmitglied;
				
			}
		}
		
		if ($_REQUEST['gruppenadmin']) {
			
		}
		
		
		// Nachricht abschicken
		$nachricht = new Nachricht ();
		$nachricht-> getMessage($g_id);
		
		
		if ($_REQUEST['abschicken']) {
			
				$nachricht = new Nachricht();
				$error = false;
				
			// Werte aus Formular einlesen
				
			$text = $_REQUEST ['text'];
			date_default_timezone_set("Europe/Berlin");
			$timestamp=time();
			$datum = date('Y-m-d', $timestamp);
			//$u_id = $_REQUEST ['u_id'];
			$g_id = $_REQUEST ['g_id'];

			
			// Keine Errors vorhanden, Funktion kann ausgef�hrt werden
				
			if (!$error) {
		
				// Nachrichten-Objekt mit Daten aus Request-Array f�llen
				
				$nachricht->setDatum($datum);
				$nachricht->setText ($text);
				//$nachricht->setU_id ($u_id);
				$nachricht->setG_id ($g_id);
				

		
			}
			
			
			$nachricht->sendMessage();
				
			return new ViewModel([
					'gruppe' => array($gruppe),
					'mitgliederListe' => $mitgliederliste,
					'mitgliedschaft' => $mitgliedschaft,
					'nachricht' => array ($nachricht),
					
			
			
			]);
			
		}
		
		
			
		return new ViewModel([
				'gruppe' => array($gruppe),
				'mitgliederListe' => $mitgliederliste,
				'mitgliedschaft' => $mitgliedschaft,
				'nachricht' => array ($nachricht),
			
					
		]);
			
		}
		
	
	
	
}