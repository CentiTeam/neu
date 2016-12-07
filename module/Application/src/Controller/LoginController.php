<?php
namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class loginController extends AbstractActionController{
	
	public function loginAction(){
		
		//Aufbau der Datenbankverbindung (gehört in extraklasse ausgelagert)		
		$db = new Zend_Db_Adapter_Pdo_Mysql(array(
				'host'     => '127.0.0.1',
				'username' => 'webuser',
				'password' => 'xxxxxxxx',
				'dbname'   => 'test'
		));

		
		//Speichern der Formulareingaben für Benutzername und Passwort in Variablen.
		$uname = $_POST['uname'];
		$psw = $_POST['psw'];
	
		return new ViewModel();
	}
}
