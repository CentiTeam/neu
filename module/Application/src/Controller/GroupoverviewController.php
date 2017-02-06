<?php

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Application\Model\Gruppe;
use Application\Model\Gruppenmitglied;
use Application\Model\User;

// Liste aller eigenen Gruppen anzeigen

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
		
		$user_id=$_SESSION['user']->getU_id();
		
		$gruppenliste=Gruppenmitglied::eigenelisteholen($user_id);
		
		
		return new ViewModel([
			'gruppenListe' => $gruppenliste,
			'u_id' => $user_id,
		]);
	}
}