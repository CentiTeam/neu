<?php



namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;



class loginController extends AbstractActionController{
	
	public function loginAction(){
		//Starten einer Session
		session_start();
		$_SESSION['uname'] = $_POST['uname'];
		
		//Speichern der Formulareingaben für Benutzername und Passwort in Variablen.
		$uname = $_POST['uname'];
		$pwd = $_POST['pwd'];
		
		//Aufbau der Datenbankverbindung (gehört in extraklasse ausgelagert)		
		$con = mysqli_connect("localhost","root","Fup7bytM","gpDB");
		
		// Check connection
		if (mysqli_connect_errno())
		{
			echo "Failed to connect to MySQL: " . mysqli_connect_error();
		}
		
		
		
		//Query, um alle Daten des Benutzers, dessen Benutzername eingegeben wurde aus der Datenbank zu holen
		$query_benutzerdaten = "SELECT * FROM User WHERE username = '".$uname."';";
		
		//Ausführen der Query und Schreiben der Rückgabe in $result
		$result = mysqli_query($con, $query_benutzerdaten);
		
		//Ausgeben einer Fehlermeldung, falls ein Fehler beim Ausführen der Query auftritt und somit $result leer bleibt
		if(!isset($result)){
			echo "Fehler beim Holen der Daten aus der Datenbank";	
		}
		//Wenn Werte aus der Datenbank in $result geschrieben wurden, dann wird weitergemacht
		else{
			//Holen der ersten (und hier einzigen, da nur ein Benutzername) Zeile des Ergebnisses
			$row=mysqli_fetch_row($result);
			
			//Prüfen, ob das eingegebene Passwort korrekt ist und der Benutzer aktiviert ist
			if($row[4] == $pwd && $row[6]==0){
			echo "Erfolgreich angemeldet";
			//Wenn man angemeldet ist, so wird dies in der Sessionvariable "angemeldet" gespeichert.
			$_SESSION['angemeldet'] = "ja";
			}
			else{
			echo "Benutzername oder Passwort falsch, oder Benutzerkonto deaktiviert!";	
			
			}
			
		}
		
		//Wenn der Benutzer angmeldet ist, so wird überprüft, ob er ein Admin ist
		if($_SESSION['angemeldet'] == 'ja'){
			if($row[7]==1){
				$_SESSION['admin'] = 'ja';
			}
			else{
				$_SESSION['admin'] = 'nein';
			}
		}
		
	

	
		return new ViewModel();
	}
}
