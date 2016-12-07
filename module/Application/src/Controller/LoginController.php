<?php
namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class loginController extends AbstractActionController{
	
	public function loginAction(){
		
		//Speichern der Formulareingaben für Benutzername und Passwort in Variablen.
		$uname = $_POST['uname'];
		$psw = $_POST['psw'];
		
		//Aufbau der Datenbankverbindung (gehört in extraklasse ausgelagert)		
		$con = mysqli_connect("localhost","root","Fup7bytM","gpDB");
		
		// Check connection
		if (mysqli_connect_errno())
		{
			echo "Failed to connect to MySQL: " . mysqli_connect_error();
		}
		else echo "verbunden";
		
		
		//Query, um alle Daten des Benutzers, dessen Benutzername eingegeben wurde aus der Datenbank zu holen
		$query_benutzerdaten = "SELECT * FROM User WHERE username = '".$uname."';";
		
		//Ausführen der Query
		mysqli_query($con, $query_benutzerdaten);
		
		//Funktionierende Einfügequery
		//mysqli_query($con, "INSERT INTO User VALUES (5,'sepp', 'hallo', 'du', 'sfljdsa', 'sepp@gmx.de', 0, 1)");
		

		

	
		return new ViewModel();
	}
}
