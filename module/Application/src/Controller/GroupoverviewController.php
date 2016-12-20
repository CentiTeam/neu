<?php

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class GroupoverviewController extends AbstractActionController
{
	public function groupoverviewAction()
	{

		return new ViewModel([
				// 'gruppe' -> $gruppe
		]);
	}
}