<?php

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class FileController extends AbstractActionController
{
	public function uploadAction()
	{
	
		$adapter = new Zend_File_Transfer_Adapter_Http();
		 
		$adapter->setDestination('\img');
		 
		if (!$adapter->receive()) {
			$messages = $adapter->getMessages();
			echo implode("\n", $messages);
		}
	
	}
}