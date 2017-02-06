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
		
		$g_id=$_GET['g_id'];
		$user_id=$_SESSION['user']->getU_id();
		
		// Überprüfen, ob Gruppenmitglied
		$aktgruppenmitglied=new Gruppenmitglied();
		$isOK=$aktgruppenmitglied->laden($g_id, $user_id);
		
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