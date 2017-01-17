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
		$mitgliederliste = User::gruppenmitgliederlisteholen($g_id);

		$mitgliedschaft=array();

		// F�r jedes Gruppenmitglied mit die Gruppenmitgliedschafts-Infos (inkl. Gruppenadmin) laden
		// und Mitgliedschaftsinfos in Array speichern, wenn Gruppenmitgliedschaft besteht
		foreach ($mitgliederliste as $mitglied) {
				
			// Gruppenmitglied instanzieren
			$gruppenmitglied= new Gruppenmitglied();
			$gruppenmitglied->laden ($g_id, $mitglied->getU_id());
				
			// Wenn Gruppenmitgliedschaft dem User-Objekt entspricht wird das Array weiter bef�llt
			if ($gruppenmitglied->getUser()->getU_id() == $mitglied->getU_id()) {

				$mitgliedschaft[]=$gruppenmitglied;

			}
		}


		return new ViewModel([
				'gruppe' => array($gruppe),
				'mitgliederListe' => $mitgliederliste,
				'mitgliedschaft' => $mitgliedschaft,


		]);
		return new ViewModel([

		]);
	}




}