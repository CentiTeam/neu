<?php

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Application\Model\Nachricht;
use Application\Model\Gruppenmitglied;
use Application\Model\Gruppe;
use Application\Model\User;
use Application\Model\Nachricht;


class NachrichtbearbeitenController extends AbstractActionController {

	function nachrichtbearbeitenAction() {

		session_start();

		$errors = array();

		if($_SESSION['angemeldet'] != 'ja') {

			array_push($errors, "Sie mÃ¼ssen angemeldet sein um eine Nachricht bearbeiten zu k&oumlnnen!");

			$view = new ViewModel(array(
					$errors
			));
			$view->setTemplate('application/index/index.phtml');
			return $view;

		} else {
			
			//Ausgewählte Nachricht laden
			$nachricht = new Nachricht();
			$n_id = $_REQUEST['n_id'];
			$nachricht->laden($n_id);
			
			//User ID aus der Session holen
			$user_id = $_SESSION['user']->getU_id();
			
			//Überprüfen, ob User = Absender
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
				
				//Relevante Daten laden
				$view = new ViewModel([
						'gruppenListe' => $gruppenliste,
						'u_id' => $user_id,
						'err' => $errStr
				]);
				
				$view->setTemplate('application/groupoverview/groupoverview.phtml');
				
				return $view;
				
			}
			
			
			
			return new ViewModel([

					'nachricht' => array($nachricht),

			]);
			
		}
	}
}