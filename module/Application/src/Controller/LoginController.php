<?php



namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Application\Model\DB_connection;
use Application\Model\User;
// require_once('Application/src/model/User.php');


class loginController extends AbstractActionController{
	
	public function loginAction(){
		
		$user = new User();
			
		session_start();
		
		if ($_REQUEST["loginfunc"])	{	
			$email = $_POST['email'];
			$pwd = $_POST['pwd'];	
			
					
					if ($user->login($email, $pwd)){
					
					// echo $user->getVorname(); nur Beispiel f�r Zugriff aufs Objekt: Kann gel�scht werden
					
					$_SESSION['user'] = $user;
					
					$gruppe= new Gruppe();
					$g_id= 1 ;
					
					$gruppe->laden($g_id);
					
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
						return new ViewModel();
					}
			}

	
	return new ViewModel();
	//public function loginfuncAction(){
		
	//	echo "Random Shit";
	}
}