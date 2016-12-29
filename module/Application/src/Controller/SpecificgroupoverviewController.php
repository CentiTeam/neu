<?php

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Application\Model\Gruppe;

class SpecificgroupoverviewController extends AbstractActionController
{
	public function specificgroupoverviewAction()
	{
	
		$gruppe= new Gruppe();
		
		$g_id=$_REQUEST['g_id'];
		
		$gruppe->laden($g_id);
		
		return new ViewModel([
			'gruppe' => array($gruppe),
		]);
		
	
	}
}