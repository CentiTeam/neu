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
		
		$gruppenliste=Gruppenmitglied::eigenelisteholen($user_id);
		
		
		return new ViewModel([
			'gruppenListe' => $gruppenliste,
			'u_id' => $user_id,
		]);
	}
}