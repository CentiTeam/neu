<?php

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Application\Model\Gruppe;
use Application\Model\User;
use Application\Model\Gruppenmitglied;
use Application\Model\Nachricht;


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

		return new ViewModel([
				'gruppe' => array($gruppe),
				'mitgliederListe' => $mitgliederliste,
				'mitgliedschaft' => $mitgliedschaft,


		]);
		return new ViewModel([

		]);
	}




}