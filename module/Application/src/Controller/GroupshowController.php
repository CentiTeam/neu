<?php

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Application\Model\Gruppe;
use Application\Model\User;


#TODO Gruppenmitglied-Objekt mitladen

class GroupshowController extends AbstractActionController
{
	public function GroupshowAction()
	{
		
		// Gruppen-Objekt laden
		$gruppe= new Gruppe();
		$g_id=$_REQUEST['g_id'];
		$gruppe->laden($g_id);
		
		// Gruppenmitglieder-Liste holen
		$mitgliederliste = User::gruppenmitgliederlisteholen($g_id); 
		
		/**
		$mitgliedschaft=array();
		
		foreach ($mitgliederliste as $mitglied) {
			
			if ($g_id == $mitglied->getG_id()) {
				
				$mitgliedschaft[]=$mitglied;
			}
		}
		*/
		
		// $gruppenmitgliedliste=Gruppenmitglied::gruppelisteholen($g_id);
		
		return new ViewModel([
			'gruppe' => array($gruppe),
			'mitgliederListe' => $mitgliederliste,
			// 'gruppenmitgliedListe' =>$gruppenmitgliedliste
		]);
		
	
	}
}