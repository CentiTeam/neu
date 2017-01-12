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
		

		$nachricht = new Nachricht();
		// Gruppen-Objekt laden
		$gruppe= new Gruppe();
		$g_id=$_REQUEST['g_id'];
		$gruppe->laden($g_id);
		
		// Liste der User-Objekte der Gruppenmitglieder holen
		$mitgliederliste = User::gruppenmitgliederlisteholen($g_id); 
		
		$mitgliedschaft=array();
		
		// Fï¿½r jedes Gruppenmitglied mit die Gruppenmitgliedschafts-Infos (inkl. Gruppenadmin) laden
		// und Mitgliedschaftsinfos in Array speichern, wenn Gruppenmitgliedschaft besteht
		foreach ($mitgliederliste as $mitglied) {
			
			// Gruppenmitglied instanzieren
			$gruppenmitglied= new Gruppenmitglied();
			$gruppenmitglied->laden ($g_id, $mitglied->getU_id());
			
			// Wenn Gruppenmitgliedschaft dem User-Objekt entspricht wird das Array weiter befï¿½llt
			if ($gruppenmitglied->getU_id() == $mitglied->getU_id()) {
				
				$mitgliedschaft[]=$gruppenmitglied;
				
			}
		}
		
		
		return new ViewModel([
			'gruppe' => array($gruppe),
			'mitgliederListe' => $mitgliederliste,
			'mitgliedschaft' => $mitgliedschaft,
				

		]);
		
		
		
		
		
		if ($_REQUEST['Abschicken']) {
				
				
			// Werte aus Formular einlesen
				
			$message = $_REQUEST ["text"];
			
			
			
			// Keine Errors vorhanden, Funktion kann ausgeführt werden
				
			if (!$error) {
		
				// Nachrichten-Objekt mit Daten aus Request-Array füllen
				$nachricht->setN_id ($n_id);
				$nachricht->setDatum($datum);
				$nachricht->setText ($text);
				$nachricht->setU_id ($u_id);
				$nachricht->setG_id ($g_id);
				

					
		
			}
				
				
		}
		
		$nachricht->sendMessage();
			
		return new ViewModel([
				
		]);
		}
		
	
	
	
}