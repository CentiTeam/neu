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
use Application\Model\Gruppenmitglied;

class ZahlunganzeigenController extends AbstractActionController
{
	public function zahlunganzeigenAction()
	{
		// Berechtigungspr�fung
		session_start();
		
		if($_SESSION['angemeldet'] == NULL || $_SESSION['systemadmin']) {
		
			$msg="Nicht berechtigt!";
		
			$view = new ViewModel([
					'msg' => $msg
			]);
			$view->setTemplate('application/index/index.phtml');
			return $view;
		
		}
		
		// Berechtigungsprüfung, ob Gruppenmitglied
		$g_id=$_REQUEST['g_id'];
		$user_id=$_SESSION['user']->getU_id();
				
		$aktgruppenmitglied=new Gruppenmitglied();
		$isOK=$aktgruppenmitglied->laden($g_id, $user_id);
				
		// Wenn kein Gruppenmitglied, dann wird die Groupoverview des jew. Users geladen
		if ($isOK==false) {
					
			$msg="Nicht berechtigt!";
					
			$gruppenliste=Gruppenmitglied::eigenelisteholen($user_id);
					
			$view = new ViewModel([
					'gruppenListe' => $gruppenliste,
					'msg' => $msg,
			]);
					
			$view->setTemplate('application/groupoverview/groupoverview.phtml');
					
			return $view;
		}
		
				
		$zahlung= new Zahlung();
		$z_id=$_REQUEST['z_id'];
		$zahlung->laden($z_id);
		$teilnehmerliste = Zahlungsteilnehmer::zahlungsteilnehmerholen($z_id);
		
		// Was ist das???
		if ($_REQUEST['zahlungsteilnehmeranzeigen']) {
		}
		
		return new ViewModel([
 				'zahlung' => array($zahlung),
				'teilnehmerliste' => $teilnehmerliste		
		]);
	}
}
