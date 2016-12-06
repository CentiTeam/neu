<?php
namespace User\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class UserController extends AbstractActionController
{
	public function indexAction()
	{
		return new ViewModel(); // HIER EVTL ETWAS ÄNDERN!!
	}


	public function loginAction() {

		// Singleton-User auslesen
		$user = User::getInstance();

		// Prüfen ob das Anmeldenformular bereits ausgef�llt und abgesendet wurde
		if (Request::getValue('absenden') != null) {

			// Übergebene Werte des Formulars auslesen
			$username = Request::getValue("username");
			$passwort = Request::getValue("passwort");

			// Versuchen den Benutzer anzumelden
			if ($user->login($username, $passwort)) {


				$msgStr="";


				$this->viewData["msgStr"]=$msgStr;
				$this->viewData["user"]=$user;
				$this->viewData["action"]="login";

				// bei erfolgreicher Anmeldung wird das Template 'Home' geladen und gefüllt
				return $this->renderView('Home'); // HIER WOHL ETWAS ÄNDERN!!

			} else {

				// ist die Anmeldung gescheitert, ist eine passende Fehlermeldung zu generieren und an das Datenarray des Templates zu �bergeben
				$this->viewData["msg"] = "Fehler bei der Anmeldung!"; // HIER WOHL ETWAS ÄNDERN!!
			}
		}

		// Benutzername an das Template-Daten-Array �bergeben, damit bei fehlerhafter Anmeldung der eingebenene Benutzername wieder ersichtlich ist
		if (isset($username))
			$this->viewData["username"] = $username;

			// das Template 'LoginForm' erstellen und f�llen lassen, um es dann an die aufrufende Stelle - die dispatch-Funktion
			// des FrontControllers - zur�ckzugeben
			return $this->renderView('LoginForm'); // HIER WOHL ETWAS ÄNDERN!!

	}

	public function logoutAction() {

		$user = User::getInstance();

		$user->logout();

		return "<div class='info'>Erfolgreich abgemeldet!</div>";

	}

}