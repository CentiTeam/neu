<?php



namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Application\Model\DB_connection;
use Application\Model\User;
// require_once('Application/src/model/User.php');


class loginController extends AbstractActionController{
	
	public function loginAction(){
		session_start();
		
		if ($_REQUEST["loginfunc"])
			
		{	
			$uname = $_POST['uname'];
			$pwd = $_POST['pwd'];	
			
			$db = new DB_connection;
		
			//Query, um alle Daten des Benutzers, dessen Benutzername eingegeben wurde aus der Datenbank zu holen
			$query_benutzerdaten = "SELECT * FROM User WHERE username = '".$uname."';";
		
		
			$result= $db->execute($query_benutzerdaten);
			
			//Ausgeben einer Fehlermeldung, falls ein Fehler beim Ausfï¿½hren der Query auftritt und somit $result leer bleibt
			if(!isset($result)){
				echo "Fehler beim Holen der Daten aus der Datenbank";
			}
			//Wenn Werte aus der Datenbank in $result geschrieben wurden, dann wird weitergemacht
			else{
					//Holen der ersten (und hier einzigen, da nur ein Benutzername) Zeile des Ergebnisses
					// Mit derr Funktion mysqli_fetch_array($result) anstelle von mysqli_fetch_row($result) können danach die Parameter 
					// anhand dem Spaltennamen ausgelesen werden
					
					
					
					$row=mysqli_fetch_array($result);
			
					//Prï¿½fen, ob das eingegebene Passwort korrekt ist und der Benutzer aktiviert ist
				
					if($row['passwort'] == $pwd && $row['deaktiviert']==0){
						
						echo "Passwort eingespeichert?";
						echo $row['passwort'];
					//Wenn man angemeldet ist, so wird dies in der Sessionvariable "angemeldet" gespeichert.
					$_SESSION['angemeldet'] = "ja";
					
					// Userobjektdaten in Session speichern
					
					
					// $user = new User($dbHandle, null, $uname);
					$user = new User();
					
					$user->u_id=$row['u_id'];
					$user->username=$row['username'];
					$user->vorname=$row['vorname'];
					$user->nachname=$row['nachname'];
					$user->passwort=$row['passwort'];
					$user->email=$row['email'];
					$user->deaktiviert=$row['deaktiviert'];
					$user->systemadmin=$row['systemadmin'];
					
					$_SESSION['user'] = $user;
					
					echo "Hallo nochmal";
					echo $user->vorname;
					
					
					/**
					$user= new User();
					
					$user->u_id=$row['u_id'];
					$user->username=$row['username'];
					$user->vorname=$row['vorname'];
					$user->nachname=$row['nachname'];
					$user->passwort=$row['passwort'];
					$user->email=$row['email'];
					$user->deaktiviert=$row['deaktiviert'];
					$user->systemadmin=$row['systemadmin'];
					*/
					
					
					
					$view = new ViewModel(array(
							'message' => 'Erfolgreich eingeloggt!',
							'uname' => $uname,
							// 'user' => array($user)
					));
					$view->setTemplate('application/overview/overview.phtml');
					return $view;
	
					}
					else{
						echo "Benutzername oder Passwort falsch, oder Benutzerkonto deaktiviert!";
						return new ViewModel();
					}
			}
		
/**		
	

*/
	}
	return new ViewModel();
	//public function loginfuncAction(){
		
	//	echo "Random Shit";
	//}
	}
}