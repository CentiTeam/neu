<?php



namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Application\Model\User;
use Application\Model\Gruppe;


class loginController extends AbstractActionController{
	
	public function loginAction(){
		
		$user = new User();
			
		session_start();
		
		if ($_REQUEST["loginfunc"])	{	
			$email = $_POST['email'];
			$pwd = $_POST['pwd'];	
			
					
					if ($user->login($email, $pwd)){
					
					$_SESSION['user'] = $user;
					
					$view = new ViewModel(array(
							'message' => 'Erfolgreich eingeloggt!',
							'uname' => $uname,
							'user' => array($user),
							'gruppe' => array($gruppe)
					));
					
					// Meldet man sich als Systemadmin an, wird auf ein anderes Template verwiesen
					
					if ($_SESSION['systemadmin'] == "ja")
					{
					$view->setTemplate('application/adminoverview/adminoverview.phtml');
					}
					
					else
					{
					$view->setTemplate('application/overview/overview.phtml');
					}
					
					return $view;
	
					}
					else{
						echo "Benutzername oder Passwort falsch, oder Benutzerkonto deaktiviert!";
						echo "Falls das Passwort vergessen wurde, bitte unten auf den Link klicken!"
						
						
						return new ViewModel();
					}
			}

	
	return new ViewModel();
	//public function loginfuncAction(){
		
	//	echo "Random Shit";
	}
}