<?php
namespace Application\Controller;


use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Application\Model\User;
use Application\Model\Bildupload;

class ProfilController extends AbstractActionController {

	function profilAction() {
		
		// Berechtigungspr�fung, ob User angemeldet oder systemadmin ist
		session_start();
		
		if ($_SESSION['user']==NULL && $_SESSION['systemadmin']==NULL) {
			$msg="Nicht berechtigt!";
			$view = new ViewModel([
					'msg' => $msg,
			]);
		
			$view->setTemplate('application/index/index.phtml');
		
			return $view;
		
		}
		
		$user=$_SESSION['user'];
		
		// Wenn das Formular "Profilbild aendern" abgesendet wird
			if ($_REQUEST['profilbild']) {
				
					$bildupload = new Bildupload();
				
					// Schritt 1:  Werte aus Formular einlesen
					$uploadedfile=$_REQUEST["uploadedfile"];
				
					//Bilddatei an die Funktion Bildupload �bergeben, R�ckgabe des Bildpfades
					$path = $bildupload->bildupload($uploadedfile);
				
					$u_id=$_REQUEST["u_id"]; 
				
					if ($path!=false) {

						$result = User::bild($path, $u_id);
					
						$user->laden($u_id);
						
						return new ViewModel([
							'user' => array($user),
						]);
				
						$view->setTemplate('application/profil/profil.phtml');
					
						return $view;
					}				
					 
					else {
					
						$user = new User();
					
						$user->laden($u_id);
					
						$view = new ViewModel([
							'user' => array($user)
						]);
					
						$view->setTemplate('application/profil/profil.phtml');
						
						return $view;
					}
				
			}
			
			// Zurückgeben der Profil-View
				return new ViewModel([
						'user' => array($user),
				]);	

				
		}

}