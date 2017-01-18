<?php

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Application\Model\Gruppe;
use Application\Model\User;
use Application\Model\Gruppenmitglied;
use Application\Model\Nachricht;
use Application\Model\Gruppenereignis;


class GruppenverlaufController extends AbstractActionController
{
	public function GruppenverlaufAction()
	{

		// Gruppen-Objekt laden
		$gruppe= new Gruppe();
		$g_id=$_REQUEST['g_id'];
		$gruppe->laden($g_id);

		// Liste der User-Objekte der Gruppenmitglieder holen
		$mitgliederliste = Gruppenmitglied::gruppenmitgliederlisteholen($g_id);
		 
		$ereignisliste = Gruppenereignis::listeHolen($gruppe);

		return new ViewModel([
				'gruppe' => array($gruppe),
				'mitgliederListe' => $mitgliederliste,
				'ereignisListe' => $ereignisliste,
		]);
		return new ViewModel([

		]);
	}




}