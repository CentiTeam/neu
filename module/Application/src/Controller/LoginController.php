<?php



namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Application\Model\DB_connection;
use Application\Model\User;


class loginController extends AbstractActionController{
	
	public function loginAction(){
		//Starten einer Session
		session_start();
		$_SESSION['uname'] = $_POST['uname'];
		//Speichern der Formulareingaben f�r Benutzername und Passwort in Variablen.
		$uname = $_POST['uname'];
		$pwd = $_POST['pwd'];
		
															/** kann weg
															//Aufbau der Datenbankverbindung (geh�rt in extraklasse ausgelagert)		
															$con = mysqli_connect("localhost","root","Fup7bytM","gpDB");
			
															// Check connection
															if (mysqli_connect_errno())
															{
															echo "Failed to connect to MySQL: " . mysqli_connect_error();
															}
		
															*/
		
		$db = new DB_connection;
		
		//Query, um alle Daten des Benutzers, dessen Benutzername eingegeben wurde aus der Datenbank zu holen
		$query_benutzerdaten = "SELECT * FROM User WHERE username = '".$uname."';";
		
															/** kann weg
															//Ausf�hren der Query und Schreiben der R�ckgabe in $result
															$result = mysqli_query($con, $query_benutzerdaten);
															*/
		
		$result= $db->execute($query_benutzerdaten);
		
															/** Zweiter Ansatz, in else muss etwas verändert werden
															 * 
															if(!isset($result)){
															echo "Fehler beim Holen der Daten aus der Datenbank";
															}
															else {
															$user = User::getInstance();
															}
															*/
		
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
			echo "Erfolgreich angemeldet";
			//Wenn man angemeldet ist, so wird dies in der Sessionvariable "angemeldet" gespeichert.
			$_SESSION['angemeldet'] = "ja";
			}
			else{
			echo "Benutzername oder Passwort falsch, oder Benutzerkonto deaktiviert!";	
			}
			
		}
		
		//Wenn der Benutzer angmeldet ist, so wird �berpr�ft, ob er ein Admin ist
		if($_SESSION['angemeldet'] == 'ja'){
			if($row[7]==1){
				$_SESSION['admin'] = 'ja';
			}
			else{
				$_SESSION['admin'] = 'nein';
			}
		}
		
		$viewModel = new ViewModel();
		
		if ($_REQUEST["absenden"]) {
			$viewModel = setTemplate('angemeldet');
		} else {
			$viewModel = setTemplate('login');
		}
		
		return $viewModel;
		
		// return new ViewModel();
	} 
	
															/**
															public function logoutAction() {
															if ($_SESSION['angemeldet'] == "ja") {
															$user->logout();
															return "Erfolgreich abgemeldet!";
															}
															}
															*/
}
