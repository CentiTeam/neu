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
		

		 
		// Gruppen-Objekt laden
		$gruppe= new Gruppe();
		$g_id=$_POST['g_id'];
		$gruppe->laden($g_id);
		
		
		// Liste der Zahlungs-Objekte der Gruppen holen
		$zahlungsliste = Zahlung::gruppenzahlungenlisteholen($g_id);
		


		Csvdownload::makeCsv($zahlungsliste);
		 
		
		return new ViewModel([
				'zahlungsliste' => $zahlungsliste,


		]);
		
	
	}
}