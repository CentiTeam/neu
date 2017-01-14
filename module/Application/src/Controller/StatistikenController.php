<?php
/**
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
* @copyright Copyright (c) 2005-2016 Zend Technologies USA Inc. (http://www.zend.com)
* @license   http://framework.zend.com/license/new-bsd New BSD License
*/

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Application\Model\User;
use Application\Model\Gruppe;
use Application\Model\Zahlung;
use Application\Model\Kategorie;
use Application\Model\Zahlungsteilnehmer;
use Application\Model\Gruppenmitglied;


class StatistikenController extends AbstractActionController
{
	public function statistikenAction(){

		session_start();

			
 			$user_id=$_SESSION['user']->getU_id();
  			$zahlungenliste = Zahlungsteilnehmer::teilnehmerzahlungenholen($user_id);
  			$kategorieliste = Kategorie::listeHolen();
  				
  			
  			if ($_REQUEST['filteranwenden']) {
  				$kategorie_id=$_REQUEST["kategorie"];

  				echo $_REQUEST["kategorie"];
  				$katzahlungen = array ();
  		
  			if($_REQUEST["kategorie"] != null){
  				$zahlungenliste = katFilter($zahlungenliste, $_REQUEST["kategorie"];)
  			
  			}
  			}
//  			foreach ($zahlungenliste as $liste) {
			
//  				// Gruppenmitglied instanzieren
//  				$gruppenmitglied= new Gruppenmitglied();
//  				$gruppenmitglied->laden ($liste->getG_id(), $user_id);
			
//  				// Wenn Gruppenmitgliedschaft dem User-Objekt entspricht wird das Array weiter bef�llt
//  				if ($gruppenmitglied->getGruppenadmin() == true) {
			
//  					$gruppenadminListe[]=$gruppenmitglied;
			
//  				}
//  			}
			

  			return new ViewModel([
  					'zahlungenliste' => $zahlungenliste,
  					'u_id' => $user_id,
  					'kategorieliste' => $kategorieliste,
  					'katzahlungen' => $katzahlungen
  			]);
  			


}

 		public static function katFilter($zahlungenliste, $k_id){
 	$katzahlungen = array();
 	foreach($zahlungenliste as $zaehler => $zahlungsteilnehmer){
 		if($zahlungsteilnehmer->getZahlung()->getKategorie()->getK_id() == $k_id){
 			$katzahlungen[] =  $zahlungsteilnehmer;
 		}
 	}
 	return $katzahlungen;
 	}
}