<?php
namespace Application\Controller;


use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Application\Model\User;

class FremdesprofilController extends AbstractActionController {

	function fremdesprofilAction() {
		// TODO Berechtigungspr�fung
		session_start();

		$errors = array();

		if($_SESSION['angemeldet'] != 'ja') {
		
			$msg="Nicht berechtigt!";
			
			$view = new ViewModel([
			'msg' => $msg
			]);
			
			$view->setTemplate('application/index/index.phtml');
			return $view;
			
		} else {
						
			$u_id=$_REQUEST['u_id'];
			$user = new User();
			$user->laden($u_id);

			$saved= false;
			$msg = array();
						
			$view = new ViewModel([
					'user' => array($user)
			]);
						
			$view->setTemplate('application/fremdesprofil/fremdesprofil.phtml');
	
			return $view;
		}
	
	}

}
