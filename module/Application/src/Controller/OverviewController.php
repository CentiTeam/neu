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
		
		//Zahlungen von den letzten fünf Tagen anzeigen lassen
		
			//U_ID der Session holen
			$user_id=$_SESSION['user']->getU_id();
		
			$aktzahlungliste=Zahlung::aktuellezahlungenholen($user_id);
			
		//Nachrichten der letzten fünf Tage anzeigen lassen
		
			$aktnachrichtliste=Nachricht::aktuellenachrichten($user_id);
			
		//Aktuelles Datum speichern
			date_default_timezone_set("Europe/Berlin");
			$timestamp=time();
			$datum= date('Y-m-d', $timestamp);
			
		//Speichern des aktuellen Datums bei Aktivierung von X
		if ($_REQUEST['hide'])
		{		
			$_SESSION['gelesenam']='$datum';
		}
		
			return new ViewModel([
				'user' => array($user),
				'gruppe' => array($gruppe),
				'aktzahlung' => $aktzahlungliste,
				'aktnachricht' => $aktnachrichtliste,
				'aktdatum' => $datum
		]);
	}
		

}
