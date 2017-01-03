<?php

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Application\Model\Gruppe;
use Application\Model\User;
use Application\Model\Gruppenmitglied;


#TODO Gruppenmitglied-Objekt mitladen, um Admin-Rechte anzuzeigen

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
		
		//Gruppenmitglied-Objekt instanzieren
		$gruppenmitglied= new Gruppenmitglied();
		
		$mitgliedschaft=array();
		
		// Für jedes Gruppenmitglied mit die Gruppenmitgliedschafts-Infos (inkl. Gruppenadmin) laden
		// und Mitgliedschaftsinfos in Array speichern
		foreach ($mitgliederliste as $mitglied) {
			
			$gruppenmitglied->laden ($g_id, $mitglied->getU_id());
			
			if ($gruppenmitglied->getU_id() == $mitglied->getU_id()) {
				echo "Test";
				$mitgliedschaft[]=$mitglied;
				
			}
		}
		
		
		// $gruppenmitgliedliste=Gruppenmitglied::gruppelisteholen($g_id);
		
		return new ViewModel([
			'gruppe' => array($gruppe),
			'mitgliederListe' => $mitgliederliste,
			'mitgliedschaft' => $mitgliedschaft
			// 'gruppenmitgliedListe' =>$gruppenmitgliedliste
		]);
		
	
	}
}