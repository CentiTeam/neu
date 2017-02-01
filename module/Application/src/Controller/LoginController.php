<?php



namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Application\Model\User;
use Application\Model\Gruppe;
use Application\Model\Nachricht;
use Application\Model\Zahlung;


class LoginController extends AbstractActionController{
	
	public function loginAction(){
		
		// Keine Berechtigungsprüfung notwendig
		
		$user = new User();
			
		session_start();
		
		if ($_REQUEST["loginfunc"])	{	
			$email = $_POST['email'];
			$pwd = $_POST['pwd'];	
			
					
					if ($user->login($email, $pwd) && $email !="" && $pwd !=""){
					
					$_SESSION['user'] = $user;
					
					$user_id=$_SESSION['user']->getU_id();
					
					
					$aktzahlungliste=Zahlung::aktuellezahlungenholen($user_id); 
					
					$aktnachrichtliste=Nachricht::aktuellenachrichten($user_id);
					
					
					$view = new ViewModel([
							'message' => 'Erfolgreich eingeloggt!',
							'uname' => $uname,
							'user' => array($user),
							'aktzahlung' => $aktzahlungliste,
							'aktnachricht' => $aktnachrichtliste
					]);
					
					// Meldet man sich als Systemadmin an, wird auf ein anderes Template verwiesen
					
					if ($_SESSION['systemadmin'] == "ja")
					{
						$view->setTemplate('application/adminoverview/adminoverview.phtml');
					}
					
					else
					{
						$view->setTemplate('application/overview/overview.phtml');
						return $view;
					}
					
					return $view;
	
					}
					else{
						
						if ($email == "" || $pwd=="")
							$msg= "Der Benutzername oder das Passwort sind leer!<br>";
						 else 
							$msg= "Der Benutzername oder das Passwort sind falsch oder Dein Benutzerkonto wurde deaktiviert!<br>";	
						
						
						$msg .= "Falls Du das Passwort vergessen hast, klicke bitte auf den Link unten!";
						
						return new ViewModel([
							'email' => $email,
							'msg' => $msg
						]);
					}
			}

	
	return new ViewModel([
			'user' => array($user),
			'gruppe' => array($gruppe),
			'aktzahlung' => $aktzahlungliste,
			'aktnachricht' => $aktnachrichtliste
	]);
	}
	
}