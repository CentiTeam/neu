<?php

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Application\Model\Gruppe;

class GroupoverviewController extends AbstractActionController
{
	public function groupoverviewAction()
	{
	
		// Testzweck: Gruppe 1 auslesen
		//$gruppe= new Gruppe();
		//$g_id= 2; 
		//$gruppe->laden($g_id);
		
		
		$liste = Gruppe::listeHolen();
		
		
		return new ViewModel([
			// 'gruppe' => array($gruppe),
			'gruppenListe' => array($liste),
		]);
		
	
	}
}