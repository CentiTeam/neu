<?php
namespace Application\Controller;


use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Application\Model\Gruppe;
use Application\Model\User;
use Application\Model\Gruppenmitglied;
use Application\Model\Kategorie;
use Application\Model\Zahlung;
use Application\Model\Zahlungsteilnehmer;
use Application\Model\Gruppenereignis;


class ZahlungpflegenController extends AbstractActionController {

	function zahlungpflegenAction() {
		
		
		
		return new ViewModel([
				
				'msg' => $msg,
				
		]);
	}
	
}


?>