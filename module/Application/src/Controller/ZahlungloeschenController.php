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


class ZahlungbearbeitenController extends AbstractActionController {

	function zahlungbearbeitenAction() {
		// TODO Berechtigungspr�fung
		session_start();

		$errors = array();

		if($_SESSION['angemeldet'] != 'ja') {

			array_push($errors, "Sie müssen angemeldet sein um eine Zahlung zu bearbeiten!");

			$view = new ViewModel(array(
					$errors
			));
			$view->setTemplate('application/index/index.phtml');
			return $view;

		} else {

			//Holen der z_id aus Formular
			$z_id = $_REQUEST['z_id'];			
			
			//Laden des Objektes der Klasse Zahlung mit der �bergebenen z_id in die Variable $zahlung
			$zahlung = new Zahlung();
			$zahlung->laden($z_id);
			
			//Holen der u_id aus Session
			$user_id=$_SESSION['user']->getU_id();
			
			//�berpr�fen, ob User = ersteller
			$ersteller = new Zahlungsteilnehmer();
			$ersteller->laden($z_id, $user_id);		
			
			//Zahlungsteilnehmer der Zahlung holen
			$teilnehmerliste = Zahlungsteilnehmer::zahlungsteilnehmerholen($z_id);
			
			if ($ersteller->getZahlungsempfaenger()==$user_id) {
			
				foreach ($teilnehmerliste as $zahlungsteilnehmer)
				{
					//In dem Fall, dass der Restbetrag nicht dem Anteil entspricht, ist die Zahlung teils oder ganz beglichen
					if ($zahlungsteilnehmer->getAnteil()!=$zahlungsteilnehmer->getRestbetrag())
					{
						$beglichen++;
					}
				}
				
				//F�r jeden Teilnehmer wird die L�schfunktion aufgerufen
				foreach ($teilnehmerliste as $zahlungsteilnehmer)
				{
					$teilnehmerloeschen = Zahlungsteilnehmer::teilnehmerloeschen($z_id, $zahlungsteilnehmer->getU_id());
				}
			
				//L�schen der Zahlung
					$zahlungloeschen = Zahlung::loeschen($z_id);
				
			}
			else {
				echo "Sie k&oumlnnen diese Zahlung nicht l&oumlschen, da Sie sie nicht erstellt haben";
				$view = new ViewModel([
						'gruppe' => array($gruppe),
						'errors' => $errors,
						'msg' => $msg,
						'zahlung' => array($zahlung),
						'teilnehmerliste' => $teilnehmerliste
				]);
				
				$view->setTemplate('application/zahlunganzeigen/zahlunganzeigen.phtml');
				return $view;
			}
			
			$view = new ViewModel([
					'gruppe' => array($gruppe),
					'errors' => $errors,
					'msg' => $msg,
					'zahlung' => array($zahlung),
					'teilnehmerliste' => $teilnehmerliste
			]);
			
			$view->setTemplate('application/zahlunganzeigen/zahlunganzeigen.phtml');
			return $view;
			
		}
		
	}
	
}