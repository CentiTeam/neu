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

		if ($_SESSION['user']==NULL || $_SESSION['systemadmin']) {
			
			$msg="Nicht berechtigt!";
			
			if ($_SESSION['systemadmin']) {
				
				$view = new ViewModel([
						'msg' => $msg,
						'user' => array($user),
				]);
				
				$view->setTemplate('application/adminoverview/adminoverview.phtml');
				
				return $view;
			
			} else {
				
				$view = new ViewModel([
						'msg' => $msg,
				]);
				
				$view->setTemplate('application/index/index.phtml');
				
				return $view;
			}
			
			
			
		}
		
		
 			$user_id=$_SESSION['user']->getU_id();
  			$zahlungenliste = Zahlungsteilnehmer::teilnehmerzahlungenholen($user_id);
  			$kategorieliste = Kategorie::listeHolen();
  			$gruppenliste=Gruppenmitglied::eigenelisteholen($user_id);
  			
  				
  			// Bei Klicken auf "Filtern" in 'statistiken'-View oder bei Klicken auf "Deine Zahlungen in dieser Gruppe" in 'groupshow'-View
  			if ($_REQUEST['filteranwenden'] || $_REQUEST['sofortauslesen']) {
  				if($_REQUEST["kategorie"] != null){
  					$zahlungenliste = $this->katFilter($zahlungenliste, $_REQUEST["kategorie"]);
  				}
  				if($_REQUEST["afterdate"] != null || $_REQUEST["beforedate"] != null){
  					$beforeDate=$_REQUEST["beforedate"];
  					$afterDate=$_REQUEST["afterdate"];
  					$zahlungenliste = $this->datFilter($zahlungenliste,$_REQUEST["afterdate"], $_REQUEST["beforedate"]);
  				}
  				
   				if($_REQUEST["status"] != null){
   					$zahlungenliste = $this->statusFilter($zahlungenliste, $_REQUEST["status"]);
   				}
   				if($_REQUEST["gruppe"] != null){
   					$zahlungenliste = $this->gruppeFilter($zahlungenliste, $_REQUEST["gruppe"]);
   						}
    				if($_REQUEST["ersteller"] != null){
    					$zahlungenliste = $this->erstellerFilter($zahlungenliste, $_REQUEST["ersteller"]);
    						}
  			}		
  			
  			$saldo = Zahlungsteilnehmer::gibsaldo($user_id, $zahlungenliste);
  			
  			// Die ausgewählten Filterwerte wird wieder mitgeladen 
  			$g_id=$_REQUEST["gruppe"];
  			$k_id=$_REQUEST["kategorie"];
  				
  			return new ViewModel([
  					'zahlungenliste' => $zahlungenliste,
  					'u_id' => $user_id,
  					'kategorieliste' => $kategorieliste,
  					'katzahlungen' => $katzahlungen,
  					'saldo' => $saldo,
  					'gruppenliste' => $gruppenliste,
  					'gruppe' => $g_id,
  					'k_id' => $k_id,
  					'beforeDate' => $beforeDate,
  					'afterDate' => $afterDate
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
 	
 	function gruppeFilter($zahlungenliste, $g_id){
 		$filteredlist = array();
 		foreach($zahlungenliste as $zaehler => $zahlungsteilnehmer){
 			if($zahlungsteilnehmer->getZahlung()->getGruppe()->getG_id() == $g_id){
 				$filteredlist[] =  $zahlungsteilnehmer;
 			}
 		}
 		return $filteredlist;
 	}
 	
 	function statusFilter($zahlungenliste, $status){
 		$filteredlist = array();
 		
 		foreach($status as $zaehler => $status){
 			foreach($zahlungenliste as $zaehler => $zahlungsteilnehmer){
 				if($status == 'offen' 
 						&& $zahlungsteilnehmer->istzahlungoffen($zahlungsteilnehmer->getZahlung()->getZ_id())== true){
 					$filteredlist[] =  $zahlungsteilnehmer;
 				}
 				if($status == 'beglichen'
 						&& $zahlungsteilnehmer->istzahlungoffen($zahlungsteilnehmer->getZahlung()->getZ_id())== false){
 					$zahlungsteilnehmer->zahlungsteilnehmerholen($zahlungsteilnehmer->getZahlung()->getZ_id());
 					$filteredlist[] =  $zahlungsteilnehmer;
 				}
 			}
 		}
 		return $filteredlist;
 	}
 	
 	function erstellerFilter($zahlungenliste, $ersteller){
 		$filteredlist = array();
 		foreach($ersteller as $zaehler => $erstellerobj){
 			foreach($zahlungenliste as $zaehler => $zahlungsteilnehmer){
 				if($zahlungsteilnehmer->getStatus()== 'ersteller' && $erstellerobj == 'ersteller'){
 					$filteredlist[] =  $zahlungsteilnehmer;
 				}
 				if($zahlungsteilnehmer->getStatus()!= 'ersteller' && $erstellerobj == 'nersteller'){
 					$filteredlist[] =  $zahlungsteilnehmer;
 					
 				}
 			}
 		}
 		return $filteredlist;
 	}
 	
 	
 	function datFilter($zahlungenliste, $afterdate, $beforedate){
 		$filteredlist = array();
 		if($beforedate == ""){
 			$beforedate = ('2038-01-01');
 		}
 		foreach($zahlungenliste as $zaehler => $zahlungsteilnehmer){
 			if($zahlungsteilnehmer->getZahlung()->getZahlungsdatum() > $afterdate && $zahlungsteilnehmer->getZahlung()->getZahlungsdatum() < $beforedate){
 				$filteredlist[] =  $zahlungsteilnehmer;
 			}
 		}
 			
 		return $filteredlist;
 		
 	}
 	
 	
 	
 	
}