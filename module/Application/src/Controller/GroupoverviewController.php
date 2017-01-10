<?php

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Application\Model\Gruppe;
//use Application\Model\Gruppenmitglied;
use Application\Model\User;

#TODO nur die Gruppen anzeigen, zu denen man geh�rt; hierf�r "Listeholen aus Gruppenmitglied" und 
#"Listeholen aus Gruppe" kombinieren

class GroupoverviewController extends AbstractActionController
{
	public function groupoverviewAction()
	{
		
		session_start();
		
		$user_id=$_SESSION['user']->getU_id();
		$gruppenliste = Gruppe::eigenelisteholen($user_id);

				
			$view = new ViewModel([
					'gruppenListe' => $gruppenliste,
					'user_id' => $user_id
			]);
		
			$view->setTemplate('application/groupoverview/groupoverview.phtml');
				
			return $view;
		}
		
		

		//Alternative zu den oberen Zeilen zum Abruf der aktuellen g_id
		//$user = new User();
		//$user=$_SESSION['user'];
		//$gruppenliste = Gruppe::eigenelisteHolen($user->getU_id());
		
		/** Alternative, falls "normales" gruppeauflisten spinnt
		 $anzahl = 0;
		
		 foreach ($liste as $gruppenliste) {
		 $anzahl++;
		 }
		 */
		
		return new ViewModel([
			'gruppenListe' => $gruppenliste,
			'u_id' => $user_id
			// 'anzahl' => $anzahl
		]);
		
	
	}
}