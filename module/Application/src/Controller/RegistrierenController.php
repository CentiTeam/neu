<?php

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Application\Model\User;

class RegistrierenController extends AbstractActionController{

	public function registrierenAction(){
		
		
		$user = new User ();
		$error = false;
		$msg="";
		
		if ($_REQUEST['registrieren']) {
			
			// Werte aus Formular einlesen
			
			$username = $_REQUEST ["username"];
			$passwort = $_REQUEST ["passwort"];
			$passwortwdh = $_REQUEST ["passwortwdh"];
			$email = $_REQUEST ["email"];
			$vorname = $_REQUEST ["vorname"];
			$nachname = $_REQUEST ["nachname"];
			

			// Überprüfung, ob Passwort zwei mal richtig eingegeben wurde
			if ($passwort!=$passwortwdh) {
				echo "<center><h4>Keine &Uumlbereinstimmung der Passw&oumlrter! Bitte erneut registrieren</h4></center>";
				$error = true;	
			}

			
			// Keine Errors vorhanden, Funktion kann ausgeführt werden
			if (!$error) {
				
			// User-Objekt mit Daten aus Request-Array füllen
			$user->setUsername ($username);
			$user->setPasswortwdh($passwortwdh);
			$user->setPasswort ($passwort);
			$user->setEmail ($email);
			$user->setVorname ($vorname);
			$user->setNachname ($nachname);
				
			
			$empfaenger= $email;
				
				
			$betreff = "Grouppay: Registrierung bestätigen";
			
			$link= "<a href=\"http://132.231.36.206/confirm\">Registrierung best&auml;tigen</a>";
			
			$text=
			"<html>
			<body>
			<div>Hallo!</div>
			<br>
			<div>&Uuml;ber diesen Link kannst Du Deine Registrierung bestätigen:</div>
			<div>$link</div><br>
			
			<div>Viele Gr&uuml;&szlig;e</div>
			<div>Dein Grouppay-Team</div>
			</body>
			</html>";
			
			$header  = 'MIME-Version: 1.0' . "\r\n";
			$header .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
		
				
			mail($empfaenger, $betreff, $text);
				
			$msg= "Best&aumltigungs-E-Mail wurde erfolgreich versendet!";
				
			}
			
			$user->registrieren();

			
		}
		
		return new ViewModel([
				'user' => array($user),
				'errors'   => $errors,
				'msg' => $msg
		]);
}
}
