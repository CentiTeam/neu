<?php

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Application\Model\Gruppe;
use Application\Model\Gruppenmitglied;

class GroupoverviewController extends AbstractActionController
{
	public function groupoverviewAction()
	{
		session_start();
		
		$gruppenliste = Gruppe::listeHolen();
		
		$mitgliederliste = Gruppenmitglied::listeHolen();
		
		
		
		$mitglieder_ids_array=array();
		
		
		foreach ($mitgliederliste as $gruppenmitglied) {
			if ($gruppenmitglied->getU_id() == $_SESSION['u_id']) {
				
				$mitglieder_ids_array[]=$gruppenmitglied->getG_id();
			}
		}
		
		/** Alternative, falls "normales" gruppeauflisten spinnt
		 $anzahl = 0;
		
		 foreach ($liste as $gruppenliste) {
		 $anzahl++;
		 }
		 */
		
		return new ViewModel([
			'gruppenListe' => $liste,
			'mitgliedschaften' => array($mitglieder_ids_array)
			// 'anzahl' => $anzahl
		]);
		
	
	}
}