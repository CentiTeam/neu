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
use Application\Model\Gruppe;
use Application\Model\Zahlung;


class statistikenController extends AbstractActionController
{
	public function statistikenAction(){

		session_start();

			
 			$user_id=$_SESSION['user']->getU_id();
  			$zahlungenliste = Zahlung::eigenezahlungenholen($user_id);
			
			
 			foreach ($zahlungenliste as $liste) {
			
 				// Gruppenmitglied instanzieren
 				$gruppenmitglied= new Gruppenmitglied();
 				$gruppenmitglied->laden ($liste->getG_id(), $user_id);
			
 				// Wenn Gruppenmitgliedschaft dem User-Objekt entspricht wird das Array weiter bef�llt
 				if ($gruppenmitglied->getGruppenadmin() == true) {
			
 					$gruppenadminListe[]=$gruppenmitglied;
			
 				}
 			}


		return new ViewModel([
				'user' => array($user),
				'gruppe' => array($gruppe)
		]);
	}


}
