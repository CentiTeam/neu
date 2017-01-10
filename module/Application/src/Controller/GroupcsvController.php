<?php

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Application\Model\Gruppe;
use Application\Model\User;
use Application\Model\Gruppenmitglied;
use Application\Model\Kategorie;
use Application\Model\Zahlung;

class GroupcsvController extends AbstractActionController
{
	public function GroupcsvAction()
	{
		

		 
		// Gruppen-Objekt laden
		$gruppe= new Gruppe();
		$g_id=$_REQUEST['g_id'];
		$gruppe->laden($g_id);
		
		
		// Liste der Zahlungs-Objekte der Gruppen holen
		$zahlungsliste = Zahlung::gruppenzahlungenlisteholen($g_id);
		

		foreach($zahlungsliste as $zahlung){
			echo $zahlung->getBetrag();
		}

		 
		
		return new ViewModel([


		]);
		
	
	}
}