<?php

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Application\Model\Nachricht;
use Application\Model\Gruppenmitglied;
use Application\Model\Gruppe;
use Application\Model\User;



class NachrichtbearbeitenController extends AbstractActionController {

	function nachrichtbearbeitenAction() {

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
		
		$n_id=$_REQUEST['n_id'];
		$user_id=$_SESSION['user']->getU_id();
		
		// Abprüfen, ob der aktuelle User der Ersteller der Nachricht ist
		$aktnachricht=new Nachricht();
		$isOK=$aktnachricht->laden($n_id);
		
		if ($isOK == false || $aktnachricht->getUser()->getU_id() != $user_id) {
		
			$msg="Nicht berechtigt!";
				
			$gruppenliste=Gruppenmitglied::eigenelisteholen($user_id);
				
			$view = new ViewModel([
					'gruppenListe' => $gruppenliste,
					'msg' => $msg,
			]);
		
			$view->setTemplate('application/groupoverview/groupoverview.phtml');
		
			return $view;

		} else {
			
			//Ausgew�hlte Nachricht laden
			$nachricht = new Nachricht();
			$n_id = $_REQUEST['n_id'];
			$nachricht->laden($n_id);
			
			//User ID aus der Session holen
			$user_id = $_SESSION['user']->getU_id();
			
			//�berpr�fen, ob User = Absender
			if($user_id != $nachricht->getUser()->getU_id()) {
				
				echo "Sie sind nicht der Verfasser dieser Nachricht";
				
				$gruppenliste=Gruppenmitglied::eigenelisteholen($user_id);
				
				
				$view = new ViewModel([
						'gruppenListe' => $gruppenliste,
						'u_id' => $user_id,
						'err' => $errStr
				]);
				
				$view->setTemplate('application/groupoverview/groupoverview.phtml');
				
				return $view;
			}
			
			//Bearbeiten der Nachricht
			if($_REQUEST['bearbeiten']) {
				
				$text = $_POST['text'];
				
				if ($text == ""){
					$nachrichtenfeldpruefungsnachricht= "Die Nachricht ist leer!<br>";
						
					$view = new ViewModel([
							'nachricht' => array($nachricht)
							'feldpruefungsnachricht' => $nachrichtenfeldpruefungsnachricht
					]);
				
					return $view;
				}
				
				$nachricht=Nachricht::bearbeiten($n_id, $text);
				
				echo "Ihre Nachricht wurde bearbeitet";
				
				$view = new ViewModel ([
						'nachricht' => array($nachricht),
				]);
				
				return $view;
			}
			
			if($_REQUEST['loeschen']) {
			
				$loeschen=Nachricht::loeschen($n_id);
				
				if ($loeschen);
				
				echo "Ihre Nachricht wurde entfernt";
				
				//Relevante Daten f�r Groupshow laden
				$gruppe= new Gruppe();
				$g_id=$_REQUEST['g_id'];
				$gruppe->laden($g_id);
				
				$allegruppenliste=Gruppenmitglied::listeholen();
				
				$mitglied=false;
				foreach ($allegruppenliste as $mitgliedschaft) {
					if ($mitgliedschaft->getUser()->getU_id()==$user_id && $mitgliedschaft->getGruppe()->getG_id()==$g_id) {
						$mitglied=true;
					}
				}
								
				$mitgliederliste=Gruppenmitglied::gruppenmitgliederlisteHolen($g_id);
				
				$aktgruppenmitglied=new Gruppenmitglied();
				$aktgruppenmitglied->laden($g_id, $user_id);
				
				
				$aktnachrichtliste=Nachricht::messageboard($user_id, $g_id);
				
				
				$view = new ViewModel([
						'gruppe' => array($gruppe),
						'nachricht' => $nachricht,
						'mitgliederListe' => $mitgliederliste,
						'mitgliedschaft' => $mitgliedschaft,
						'aktnachricht' => $aktnachrichtliste,
						'aktgruppenmitglied' => $aktgruppenmitglied,
						'user_id' => $user_id,
						'suche' => $suche
				]);
				
				$view->setTemplate('application/groupshow/groupshow.phtml');
				
				return $view;
				
			}
			
			
			
			return new ViewModel([

					'nachricht' => array($nachricht),

			]);
			
		}
	}
}