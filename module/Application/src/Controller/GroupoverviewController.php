<?php

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Application\Model\Gruppe;
use Application\Model\Gruppenmitglied;
use Application\Model\User;

#TODO nur die Gruppen anzeigen, zu denen man geh�rt; hierf�r "Listeholen aus Gruppenmitglied" und 
#"Listeholen aus Gruppe" kombinieren

class GroupoverviewController extends AbstractActionController
{
	public function groupoverviewAction()
	{
		
		session_start();
		
		$user_id=$_SESSION['user']->getU_id();
		//$gruppenliste = Gruppe::eigenelisteholen($user_id);
		
		/**
		// Liste der User-Objekte der Gruppenmitglieder holen
		$mitgliederliste = User::gruppenmitgliederlisteholen($g_id);
		
		$gruppenadminListe=array();
		
		// F�r jede Gruppe speichern, ob aktueller USer Admin ist und diese Gruppenmitglied-Datensätze
		// in Array speichern
		foreach ($gruppenliste as $liste) {
				
			// Gruppenmitglied instanzieren
			$gruppenmitglied= new Gruppenmitglied();
			$gruppenmitglied->laden ($liste->getG_id(), $user_id);
				
			// Wenn Gruppenmitgliedschaft dem User-Objekt entspricht wird das Array weiter bef�llt
			if ($gruppenmitglied->getGruppenadmin() == true) {
		
				$gruppenadminListe[]=$gruppenmitglied;
		
			}
		}
		*/
		
		$gruppenliste=Gruppenmitglied::eigenelisteholen($user_id);
		
		return new ViewModel([
			'gruppenListe' => $gruppenliste,
			'u_id' => $user_id,
			'gruppenadminListe' => $gruppenadminListe,
		]);
		
	
	}
}