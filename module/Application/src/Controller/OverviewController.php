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

class overviewController extends AbstractActionController
{
	public function overviewAction(){
	
		session_start();
		
		
		if ($_SESSION['angemeldet']=='ja')
		{
			echo "Hier kommen die Links zu den spezifischen Rollen hin";
		}
		else 
		{
			echo "Sie haben keine Berechtigung, auf diese Seite zuzugreifen!";
			$view = new ViewModel(array(
					'message' => 'Sie haben keine Berechtigung, auf diese Seite zuzugreifen!',
			));
			$view->setTemplate('application/index/index.phtml');
			return $view;
		}
		
		// TEST, um die Übergabe der Elemente des angemeldeten Users an eine andere Funktion anzuzeigen
		$user=$_SESSION['user'];
		echo "Nachname des angemeldeten Users: ";
		echo $user->getNachname();
		
		$gruppenliste = Gruppe::listeHolen();
		
		return new ViewModel([
				'user' => array($user),
				'gruppeliste' => array($gruppenliste)
		]
				);
	}
		

}
