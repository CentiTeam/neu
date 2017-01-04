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
class UsersuchenController extends AbstractActionController
{
	public function usersuchenAction()
	{
		session_start(); 
		
		$suche = $_REQUEST ["suche"];
		
		  

		if ($_SESSION['user']->getSystemadmin()==true) {
			
			$liste = User::suchlisteHolen($suche);
			
			return new ViewModel([
					'suchuserListe' => $liste,
			]);
		}
		
		else {
			
			$g_id= $_REQUEST["g_id"];
			
			
			$liste = User::gruppensuchlisteHolen($suche, $g_id);
			
			$view = new ViewModel([
					'suchuserListe' => $liste,
			]);
				
			$view->setTemplate('application/teilnehmersuchetabelle/teilnehmersuchetabelle.phtml');
			
			return $view;
		}


	}
}