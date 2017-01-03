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

		if ($_REQUEST['username']) {
				
				
			// Werte aus Formular einlesen
				
			$username = $_REQUEST ["username"];
			$user->suchlisteHolen();
				

		$liste = User::suchlisteHolen();

		return new ViewModel([
				'userListe' => $liste,
		]);


		}}
}