<?php
namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class loginController extends AbstractActionController{
	
	public function loginAction(){
		
		//Speichern der Formulareingaben f�r Benutzername und Passwort in Variablen.
		$uname = $_POST['uname'];
		$psw = $_POST['psw'];
		
		//Aufbau der Datenbankverbindung (geh�rt in extraklasse ausgelagert)		
		$con = mysqli_connect("localhost","root","Fup7bytM","gpDB");
		
		// Check connection
		if (mysqli_connect_errno())
		{
			echo "Failed to connect to MySQL: " . mysqli_connect_error();
		}
		else echo "verbunden";
		
		
		//Query, um alle Daten des Benutzers, dessen Benutzername eingegeben wurde aus der Datenbank zu holen
		$query_benutzerdaten = "SELECT * FROM User WHERE username = '".$uname."';";
		
		//Ausf�hren der Query und Schreiben der R�ckgabe in $result
		$result = mysqli_query($con, $query_benutzerdaten);
		
		//Ausgeben einer Fehlermeldung, falls ein Fehler beim Ausf�hren der Query auftritt und somit $result leer bleibt
		if(!isset($result)){
			echo "Fehler beim Holen der Daten aus der Datenbank";	
		}
		//Wenn Werte aus der Datenbank in $result geschrieben wurden, dann wird weitergemacht
		else{
			//Abarbeiten des Arrays an Ergebnissen (Zeile f�r Zeile)
			while ($row=mysqli_fetch_row($result))
			{
				printf ("%s (%s)\n",$row[0],$row[1]);
			}
			
		
		}
		
		//Funktionierende Einf�gequery
		//mysqli_query($con, "INSERT INTO User VALUES (5,'sepp', 'hallo', 'du', 'sfljdsa', 'sepp@gmx.de', 0, 1)");
		

		

	
		return new ViewModel();
	}
}
