<?php

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Application\Model\Gruppe;
use Application\Model\User;
use Application\Model\Gruppenmitglied;
use Application\Model\Kategorie;
use Application\Model\Zahlung;
use Application\Model\Csvdownload;

class GroupcsvController extends AbstractActionController
{
	public function GroupcsvAction()
	{
		session_start();
		// Berechtigungsprüfung
		if ($_SESSION['angemeldet']==NULL) {
				
			$msg="Nicht berechtigt!";
				
			$view = new ViewModel([
					'msg' => $msg,
			]);
				
			$view->setTemplate('application/index/index.phtml');
				
			return $view;
		
		}
		// Überprüfen, ob Gruppenmitglied
		$g_id=$_REQUEST['g_id'];
		$user_id=$_SESSION['user']->getU_id();
		
		$aktgruppenmitglied=new Gruppenmitglied();
		$isOK=$aktgruppenmitglied->laden($g_id, $user_id);
		
		if (!$isOK) {
		
			$msg="Nicht berechtigt!";
		
			$view = new ViewModel([
					'msg' => $msg,
			]);
		
			$view->setTemplate('application/index/index.phtml');
		
			return $view;
		}
		 
		// Gruppen-Objekt laden
		$gruppe= new Gruppe();
		$g_id=$_POST['g_id'];
		$gruppe->laden($g_id);
		
		
		// Liste der Zahlungs-Objekte der Gruppen holen
		$zahlungsliste = Zahlung::gruppenzahlungenlisteholen($g_id);
		


		Csvdownload::makeCsv($zahlungsliste);
		 
		

		
	
	}
}