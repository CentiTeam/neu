<?php



namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Application\Model\DB_connection;
use Application\Model\User;

class loginController extends AbstractActionController{
	
	public function loginAction(){

		$flag=0;
		
		if ($_REQUEST["loginfunc"])
			
		{	
			$uname = $_POST['uname'];
			$pwd = $_POST['pwd'];	
			
			$db = new DB_connection;
		
			//Query, um alle Daten des Benutzers, dessen Benutzername eingegeben wurde aus der Datenbank zu holen
			$query_benutzerdaten = "SELECT * FROM User WHERE username = '".$uname."';";
		
		
			$result= $db->execute($query_benutzerdaten);
			
			//Ausgeben einer Fehlermeldung, falls ein Fehler beim Ausf�hren der Query auftritt und somit $result leer bleibt
			if(!isset($result)){
				echo "Fehler beim Holen der Daten aus der Datenbank";
			}
			//Wenn Werte aus der Datenbank in $result geschrieben wurden, dann wird weitergemacht
			else{
					//Holen der ersten (und hier einzigen, da nur ein Benutzername) Zeile des Ergebnisses
					$row=mysqli_fetch_row($result);
			
					//Pr�fen, ob das eingegebene Passwort korrekt ist und der Benutzer aktiviert ist
				
					if($row[4] == $pwd && $row[6]==0){
					
					//Wenn man angemeldet ist, so wird dies in der Sessionvariable "angemeldet" gespeichert.
					$_SESSION['angemeldet'] = "ja";
					
					$view = new ViewModel(array(
							'message' => 'Erfolgreich eingeloggt!',
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