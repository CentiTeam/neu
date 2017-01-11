<?php
namespace Application\Controller;


use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Application\Model\User;
use Application\Model\Bildupload;

/**
 * FILE-Controller wird nur noch als Vorlage verwendet!!!! 
 * Die Funktionalität wurde in GroupeditController und GruppeanlegenController übernommen
 * @author Tanja
 *
 */


class ProfilController extends AbstractActionController {

	function profilAction() {
		// TODO Berechtigungsprï¿½fung
		session_start();
	
		$errors = array();

		if($_SESSION['angemeldet'] != 'ja') {
				
			array_push($errors, "Sie mÃ¼ssen angemeldet sein um eine Gruppe zu bearbeiten!");
				
			$view = new ViewModel(array(
					$errors
			));
			$view->setTemplate('application/index/index.phtml');
			return $view;
				
		} else {
			
			$user_id=$_SESSION['user']->getU_id();
			
			$user = new User();
				
			if (! $user->laden($user_id)) {
				array_push($errors, "Fehler beim Laden des Profils!");
			}

			$bildupload = new Bildupload();
			

			
			$saved= false;
			$msg = array();

			if ($_REQUEST['upload']) {

					
				// Schritt 1:  Werte aus Formular einlesen
				$uploadedfile=$_REQUEST["uploadedfile"];
				
				//Bilddatei an die Funktion Bildupload übergeben, Rückgabe des Bildpfades
				$path = $bildupload->bildupload($uploadedfile);
				
				$g_id=$_REQUEST["g_id"]; 
				
				if ($path!=false) {

					$result = Gruppe::bild($path, $g_id);
				
					$gruppenliste = Gruppe::listeholen();
				
					$view = new ViewModel([
							'gruppenListe'=>$gruppenliste
					]);
				
					$view->setTemplate('application/groupoverview/groupoverview.phtml');
					
					return $view;
				}				
				else {
					
					$gruppe = new Gruppe();
					
					$gruppe->laden($g_id);
					
					$view = new ViewModel([
							'gruppe' => array($gruppe)
					]);
					
					$view->setTemplate('application/groupedit/groupedit.phtml');
						
					return $view;
				}
				
			}

				return new ViewModel([
				]);
				
				 
		}
	}

}