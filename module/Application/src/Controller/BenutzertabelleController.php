<?php
/**
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
* @copyright Copyright (c) 2005-2016 Zend Technologies USA Inc. (http://www.zend.com)
* @license   http://framework.zend.com/license/new-bsd New BSD License
*/

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Application\Model\User;
class BenutzertabelleController extends AbstractActionController
{
	public function benutzertabelleAction()
	{
		if ($_SESSION['angemeldet']=="0") {
				
			$msg="Nicht berechtigt!";
				
			$view = new ViewModel([
					'msg' => $msg,
			]);
				
			$view->setTemplate('application/index/index.phtml');
				
			return $view;
		
		} elseif ($_SESSION['user']->getAdmin()=="nein") {
		
			$msg="Nicht berechtigt!";
		
			$view = new ViewModel([
					'msg' => $msg,
			]);
		
			$view->setTemplate('application/index/index.phtml');
		
			return $view;
		}
		

		$liste = User::listeHolen();
	
		return new ViewModel([
				'userListe' => $liste,
		]);
		
		
	}
}