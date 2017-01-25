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
		
		// Berechtigungsprüfung
		if ($_SESSION['angemeldet']==NULL) {
		
			$msg="Nicht berechtigt!";
		
			$view = new ViewModel([
					'msg' => $msg,
			]);
		
			$view->setTemplate('application/index/index.phtml');
		
			return $view;
		
		}

		$g_id=$_GET['g_id'];
		$user_id=$_SESSION['user']->getU_id();
		
		$aktgruppenmitglied=new Gruppenmitglied();
		$aktgruppenmitglied->laden($g_id, $user_id);
		
		var_dump($aktgruppenmitglied);
		
		if ($isOK==false) {
			
			$msg="Nicht berechtigt!";
		
			$view = new ViewModel([
					'msg' => $msg,
			]);
		
			$view->setTemplate('application/index/index.phtml');
		
			return $view;
		}
		
		
		
		$gruppenliste=Gruppenmitglied::eigenelisteholen($user_id);
		
		
		return new ViewModel([
			'gruppenListe' => $gruppenliste,
			'u_id' => $user_id,
		]);
	}
}