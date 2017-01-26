<?php
/**
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
* @copyright Copyright (c) 2005-2016 Zend Technologies USA Inc. (http://www.zend.com)
* @license   http://framework.zend.com/license/new-bsd New BSD License
*/

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class AdminoverviewController extends AbstractActionController
{
	public function adminoverviewAction()
	{
		
		if ($_SESSION['angemeldet']==NULL) {
			
			$msg="Nicht berechtigt!";
			
			$view = new ViewModel([
					'msg' => $msg,
			]);
			
			$view->setTemplate('application/index/index.phtml');
			
			return $view;

		} elseif ($_SESSION['angemeldet']==ja && $_SESSION['user']->getAdmin()=="nein") {
		
			$msg="Nicht berechtigt!";
		
			$view = new ViewModel([
					'msg' => $msg,
			]);
		
			$view->setTemplate('application/index/index.phtml');
		
			return $view;
		}
		elseif ($_SESSION['angemeldet']==ja && $_SESSION['user']->getAdmin()=="ja") {
		
		
		
		return new ViewModel();
		}
	}
}