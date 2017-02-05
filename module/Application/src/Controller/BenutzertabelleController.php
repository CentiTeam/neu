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
		session_start();
		
		// Abprüfen, ob angemeldeter User
		if ($_SESSION['angemeldet']==NULL) {
				
			$msg="Nicht berechtigt!";
				
			$view = new ViewModel([
					'msg' => $msg,
			]);
				
			$view->setTemplate('application/index/index.phtml');
				
			return $view;
		
		}
		
		// Abprüfen, ob Systemadmin
		$user=$_SESSION['user'];
		
		if($_SESSION['systemadmin'] != 'ja') {
		
			$msg= "Sie müssen ein Administrator sein, um eine Kategorie zu bearbeiten!";
		
			$view = new ViewModel([
					'msg' => $msg,
					'user' => array($user),
			]);
			$view->setTemplate('application/overview/overview.phtml');
			return $view;
		}
		
		// Liste aller User holen
		$liste = User::listeHolen();
	
		return new ViewModel([
				'userListe' => $liste,
		]);
		
		
	}
}