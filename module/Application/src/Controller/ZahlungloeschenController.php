<?php
namespace Application\Controller;


use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Application\Model\Gruppe;
use Application\Model\User;
use Application\Model\Zahlung;
use Application\Model\Zahlungsteilnehmer;
use Application\Model\Kategorie;
use Application\Model\Gruppenereignis;

class ZahlungloeschenController extends AbstractActionController {

	function zahlungloeschenAction() {
		// TODO Berechtigungspr�fung
		session_start();

		if($_SESSION['angemeldet'] == NULL || $_SESSION['systemadmin']) {
		
			$msg="Nicht berechtigt!";
		
			$view = new ViewModel([
					'msg' => $msg
			]);
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
			
			
			// Berechtigungsprüfung, ob User Zahlungsteilnehmer ist und ob die Gruppe stimmt
			$aktuser_id=$_SESSION['user']->getU_id();
			$gruppen_id=$_REQUEST['g_id'];
			$istTeilnehmer=false;
			
			foreach ($teilnehmerliste as $teilnehmer) {
				if ($aktuser_id==$teilnehmer->getUser()->getU_id() && $gruppen_id==$teilnehmer->getZahlung()->getGruppe()->getG_id()) {
					$istTeilnehmer=true;
				}
			}
			
			// Wenn kein Zahlungsteilnehmer, dann wird die Overview des jew. Users geladen
			if ($istTeilnehmer==false) {
					
				$msg="Nicht berechtigt!";
			
				$user=$_SESSION['user'];
				$uname=$user->getVorname();
			
				$view = new ViewModel([
						'uname' => $uname,
						'user' => array($user),
						'msg' => $msg,
				]);
			
				$view->setTemplate('application/overview/overview.phtml');
			
				return $view;
			
			}
			// Berechtigungsprüfung Ende
			$veraenderbar==false;
			$schonbeglicheneZahlungen=false;
			
			
			if ($ersteller->getZahlungsempfaenger()->getU_id()==$user_id) {
					
				foreach ($teilnehmerliste as $zahlungsteilnehmer)
				{
					//In dem Fall, dass der Restbetrag nicht dem Anteil entspricht, ist die Zahlung teils oder ganz beglichen
					if ($zahlungsteilnehmer->getAnteil()!=$zahlungsteilnehmer->getRestbetrag() AND $zahlungsteilnehmer->getUser()->getU_id()!=$aktuser_id)
					{
						
						$beglichen++;
						$schonbeglicheneZahlungen=true;
						
					}
				}
				
				//Wenn die Variable beglichen auf Null steht, kann die Zahlung gel�scht werden
				if ($beglichen==0 && $schonbeglicheneZahlungen==false)
				{
					$veraenderbar=true;
					
					//F�r jeden Teilnehmer wird die L�schfunktion aufgerufen
					foreach ($teilnehmerliste as $zahlungsteilnehmer)
					{
						$teilnehmerloeschen = Zahlungsteilnehmer::teilnehmerloeschen($z_id, $zahlungsteilnehmer->getUser()->getU_id());
					}
			
					//L�schen der Zahlung
				
						//Erstellen einer Instanz der zu loeschenden Zahlung, um die Ereignisbehandlung nachher durchfuehren zu koennen
						$zahlung_fuer_ereignis = new Zahlung();
						$zahlung_fuer_ereignis->laden($z_id);
				
				
						$zahlungloeschen = Zahlung::loeschen($z_id);
				
						if ($zahlungloeschen) {
							echo "Die Zahlung wurde erfolgreich gel&oumlscht!";
							Gruppenereignis::zahlungloeschenEreignis($zahlung_fuer_ereignis, $zahlung_fuer_ereignis->getGruppe(), $_SESSION['user']);
						}
						else {
							echo "Die Zahlung konnte nicht gel&oumlscht werden!";
						}
					
					
						// Relevante Daten Laden
						$kategorieliste = Kategorie::listeHolen();
						$saldo = Zahlungsteilnehmer::gibsaldo($user_id);
						$zahlungenliste = Zahlungsteilnehmer::teilnehmerzahlungenholen($user_id);
						
						$view = new ViewModel([
								'gruppe' => array($gruppe),
								'errors' => $errors,
								'msg' => $msg,
								'zahlung' => array($zahlung),
								'zahlungenliste' => $zahlungenliste,
								'kategorieliste' => $kategorieliste,
								'u_id' => $user_id,
								'teilnehmerliste' => $teilnehmerliste,
								'saldo' => $saldo
						]);
						
						$view->setTemplate('application/statistiken/statistiken.phtml');
						return $view;
				}
				else
				{
					$veraenderbar==false;
					echo "Diese Zahlung wurde bereits teilweise oder vollst&aumlndig beglichen und kann daher nicht mehr bearbeitet werden";
					
					$view = new ViewModel([
							'gruppe' => array($gruppe),
							'errors' => $errors,
							'msg' => $msg,
							'zahlung' => array($zahlung),
							'teilnehmerliste' => $teilnehmerliste,
							'veraenderbar' => $veraenderbar
					]);
					
					$view->setTemplate('application/zahlunganzeigen/zahlunganzeigen.phtml');
					return $view;
				}
			}
			else {
				$veraenderbar=false;
				echo "Sie k&oumlnnen diese Zahlung nicht l&oumlschen, da Sie sie nicht erstellt haben";
				
				$view = new ViewModel([
						'gruppe' => array($gruppe),
						'errors' => $errors,
						'msg' => $msg,
						'zahlung' => array($zahlung),
						'teilnehmerliste' => $teilnehmerliste,
						'veraenderbar' => $veraenderbar
				]);
				
				$view->setTemplate('application/zahlunganzeigen/zahlunganzeigen.phtml');
				return $view;
			}
			
			$view = new ViewModel([
					'gruppe' => array($gruppe),
					'errors' => $errors,
					'msg' => $msg,
					'zahlung' => array($zahlung),
					'teilnehmerliste' => $teilnehmerliste,
					'veraenderbar' => $veraenderbar
			]);
			
			$view->setTemplate('application/zahlunganzeigen/zahlunganzeigen.phtml');
			return $view;
			
		}
		
		return new ViewModel([
				'zahlung' => array($zahlung)
		]);
		
	}
	
}