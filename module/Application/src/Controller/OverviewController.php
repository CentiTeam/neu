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
use Application\Model\Nachricht;
use Application\Model\Gruppenereignis;

class OverviewController extends AbstractActionController
{
	public function OverviewAction(){
	
		session_start();
		
		if ($_SESSION['user']==NULL) {
			$msg="Nicht berechtigt!";
			$view = new ViewModel([
					'msg' => $msg,
			]);
		
			$view->setTemplate('application/index/index.phtml');
		
			return $view;
		
		}
		
		// TEST, um die �bergabe der Elemente des angemeldeten Users an eine andere Funktion anzuzeigen
		$user=$_SESSION['user'];

		
		//Overview Controller laden nach Login
		if ($_REQUEST['neues']) {
			$_SESSION['neuigkeiten']="ja";
		}
		
		
		
		//Zahlungen von den letzten f�nf Tagen anzeigen lassen
		
			//U_ID der Session holen
			$user_id=$_SESSION['user']->getU_id();
		
			$aktzahlungliste=Zahlung::aktuellezahlungenholen($user_id);
			
		//Nachrichten der letzten f�nf Tage anzeigen lassen
		
			$aktnachrichtliste=Nachricht::aktuellenachrichten($user_id);
			
		//Gruppenereignisse der letzten f�nf Tage anzeigen lassen
		
			$aktereignisliste=Gruppenereignis::akutelleereignisse($user_id);
			
		//Aktuelles Datum speichern
			date_default_timezone_set("Europe/Berlin");
			$timestamp=time();
			$datum= date('Y-m-d', $timestamp);
		
		
		// Button "Was gibts neues?" verbergen, falls X aktiviert wird
		$keineneuigkeiten=false;
		
		//Speichern des aktuellen Datums bei Aktivierung von X
		if ($_REQUEST['hide'])
		{		
			$_SESSION['gelesenam']=$datum;
			$keineneuigkeiten=true;
		}
		
			return new ViewModel([
				'user' => array($user),
				'gruppe' => array($gruppe),
				'aktzahlung' => $aktzahlungliste,
				'aktnachricht' => $aktnachrichtliste,
				'aktereignis' => $aktereignisliste,
				'aktdatum' => $datum,
				'keineneuigkeiten' => $keineneuigkeiten
		]);
	}
		

}
