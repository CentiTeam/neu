<?php
/**
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
* @copyright Copyright (c) 2005-2016 Zend Technologies USA Inc. (http://www.zend.com)
* @license   http://framework.zend.com/license/new-bsd New BSD License
*/

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Application\Model\Zahlung;
use Application\Model\User;
use Application\Model\Zahlungsteilnehmer;
use Application\Model\Gruppe;

class ZahlunganzeigenController extends AbstractActionController
{
	public function zahlunganzeigenAction()
	{
		$zahlung= new Zahlung();
		$z_id=$_REQUEST['z_id'];
		$zahlung->laden($z_id);

		return new ViewModel([
 				'zahlung' => array($zahlung)
// 				'mitgliederListe' => $mitgliederliste,
// 				'mitgliedschaft' => $mitgliedschaft,
		
		]);
	}
}
