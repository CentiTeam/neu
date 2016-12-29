<?php

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Application\Model\Gruppe;

class GroupoverviewController extends AbstractActionController
{
	public function groupoverviewAction()
	{

		$liste = Gruppe::listeHolen();
		
		/** Alternative, falls "normales" gruppeauflisten spinnt
		 $anzahl = 0;
		
		 foreach ($liste as $gruppenliste) {
		 $anzahl++;
		 }
		 */
		
		return new ViewModel([
			'gruppenListe' => $liste,
			// 'anzahl' => $anzahl
		]);
		
	
	}
}