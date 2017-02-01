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
		
		if ($_REQUEST["loginfunc"] && $_POST['email'] != "" && $_POST['pwd'] != "")	{	
			$email = $_POST['email'];
			$pwd = $_POST['pwd'];	
			
					
					if ($user->login($email, $pwd)){
					
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
						echo "Benutzername oder Passwort falsch, oder Benutzerkonto deaktiviert!";
						echo "Falls das Passwort vergessen wurde, bitte unten auf den Link klicken!";
						
						
						return new ViewModel([
							'email' => $email	
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