<?php

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Application\Model\Gruppe;
use Application\Model\Gruppenmitglied;
use Application\Model\User;

#TODO nur die Gruppen anzeigen, zu denen man gehört; hierfür "Listeholen aus Gruppenmitglied" und 
#"Listeholen aus Gruppe" kombinieren

class GroupoverviewController extends AbstractActionController
{
	public function groupoverviewAction()
	{
		$user = new User();
		session_start();
		
		$user_id=$_SESSION['u_id'];
		$gruppenliste = Gruppe::eigenelisteHolen($user_id);
		var_dump($user_id);
		
		$mitgliederliste = Gruppenmitglied::listeHolen();
		
		
		/** Fehlversuch
		$mitglieder_ids_array=array();
		
		foreach ($mitgliederliste as $gruppenmitglied) {
			$gruppenmitglied = new Gruppenmitglied();
			
			var_dump($gruppenmitglied->getU_id());
			var_dump($_SESSION['u_id']);
			
			if ($gruppenmitglied->getU_id() == $_SESSION['u_id']) {
				
				$mitglieder_ids_array[]=$gruppenmitglied->getG_id();
			}
		}
		*/
		
		/** Alternative, falls "normales" gruppeauflisten spinnt
		 $anzahl = 0;
		
		 foreach ($liste as $gruppenliste) {
		 $anzahl++;
		 }
		 */
		
		return new ViewModel([
			'gruppenListe' => $gruppenliste,
			'mitgliederListe' => $mitgliederliste
			//'mitgliedschaften' => array($mitglieder_ids_array)
			// 'anzahl' => $anzahl
		]);
		
	
	}
}