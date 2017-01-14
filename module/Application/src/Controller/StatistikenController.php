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
  				if($_REQUEST["kategorie"] != null){
  					$zahlungenliste = $this->katFilter($zahlungenliste, $_REQUEST["kategorie"]);
  				}
  				if($_REQUEST["datum"] != null){
  					$zahlungenliste = $this->datFilter($zahlungenliste, $_REQUEST["datum"]);
  				}
  			}		

  			return new ViewModel([
  					'zahlungenliste' => $zahlungenliste,
  					'u_id' => $user_id,
  					'kategorieliste' => $kategorieliste,
  					'katzahlungen' => $katzahlungen
  			]);
  			


}

 		function katFilter($zahlungenliste, $kategorie_id){
 	$filteredlist = array();
 	foreach($zahlungenliste as $zaehler => $zahlungsteilnehmer){
 		if($zahlungsteilnehmer->getZahlung()->getKategorie()->getK_id() == $kategorie_id){
 			$filteredlist[] =  $zahlungsteilnehmer;
 		}
 	}
 	return $filteredlist;
 	}
 	function datFilter($zahlungenliste, $datum){
 		$filteredlist = array();
 		foreach($zahlungenliste as $zaehler => $zahlungsteilnehmer){
 			if($zahlungsteilnehmer->getZahlung()->getZahlungsdatum() > $datum){
 				$filteredlist[] =  $zahlungsteilnehmer;
 			}
 		}
 		return $filteredlist;
 	}
 	
 	
 	
 	
}