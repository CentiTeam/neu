<?php

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Application\Model\Gruppe;

class GroupshowController extends AbstractActionController
{
	public function GroupshowAction()
	{
	
		$gruppe= new Gruppe();
		
		$g_id=$_REQUEST['g_id'];
		
		$gruppe->laden($g_id);
		
		return new ViewModel([
			'gruppe' => array($gruppe),
		]);
		
	
	}
}