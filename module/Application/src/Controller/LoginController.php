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
			
			echo $uname;
		}
		return new ViewModel();
/**		
	
		$db = new DB_connection;
		
		//Query, um alle Daten des Benutzers, dessen Benutzername eingegeben wurde aus der Datenbank zu holen
		$query_benutzerdaten = "SELECT * FROM User WHERE username = '".$uname."';";
		
		
		$result= $db->execute($query_benutzerdaten);
*/
	}
	
	//public function loginfuncAction(){
		
	//	echo "Random Shit";
	//}
}