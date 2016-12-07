<?php
namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class loginController extends AbstractActionController{
	
	public function loginAction(){
		
		//Aufbau der Datenbankverbindung (gehört in extraklasse ausgelagert)		
		$link = mysqli_connect('132.231.36.206', 'root', 'Fup7bytM');
		if (!$link) {
			die('Verbindung schlug fehl: ' . mysqli_error());
		}
		echo 'Erfolgreich verbunden';
		mysqli_close($link);

		
		//Speichern der Formulareingaben für Benutzername und Passwort in Variablen.
		$uname = $_POST['uname'];
		$psw = $_POST['psw'];
	
		return new ViewModel();
	}
}
