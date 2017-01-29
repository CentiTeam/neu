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
			$view = new ViewModel([
					'msg' => $msg,
			]);
		
			$view->setTemplate('application/index/index.phtml');
		
			return $view;
		
		}
		
		
 			$user_id=$_SESSION['user']->getU_id();
  			$zahlungenliste = Zahlungsteilnehmer::teilnehmerzahlungenholen($user_id);
  			$kategorieliste = Kategorie::listeHolen();
  			$gruppenliste=Gruppenmitglied::eigenelisteholen($user_id);
  			
  				
  			
  			if ($_REQUEST['filteranwenden']) {
  				if($_REQUEST["kategorie"] != null){
  					$zahlungenliste = $this->katFilter($zahlungenliste, $_REQUEST["kategorie"]);
  				}
  				if($_REQUEST["afterdate"] != null || $_REQUEST["beforedate"] != null){
  					$zahlungenliste = $this->datFilter($zahlungenliste,$_REQUEST["afterdate"], $_REQUEST["beforedate"]);
  					echo"afterdate:";
  					var_dump($_REQUEST["afterdate"]);
  					echo"beforedate:";
  					var_dump($_REQUEST["beforedate"]);
  				}
  				
   				if($_REQUEST["status"] != null){
   					$zahlungenliste = $this->statusFilter($zahlungenliste, $_REQUEST["status"]);
   				}
   				if($_REQUEST["gruppe"] != null){
   					$zahlungenliste = $this->gruppeFilter($zahlungenliste, $_REQUEST["gruppe"]);
   						}
  			}		
  			
  			$saldo = Zahlungsteilnehmer::gibsaldo($user_id, $zahlungenliste);
  				
  			return new ViewModel([
  					'zahlungenliste' => $zahlungenliste,
  					'u_id' => $user_id,
  					'kategorieliste' => $kategorieliste,
  					'katzahlungen' => $katzahlungen,
  					'saldo' => $saldo,
  					'gruppenliste' => $gruppenliste
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
 		echo "status Funktion aufgerufen";
 		var_dump($status);
 		$filteredlist = array();
 		foreach($status as $zaehler => $status){
 			foreach($zahlungenliste as $zaehler => $zahlungsteilnehmer){
 				if($zahlungsteilnehmer->getStatus()== 'offen' && $status == 'offen'){
 					$filteredlist[] =  $zahlungsteilnehmer;
 				}
 				if($zahlungsteilnehmer->getStatus()== 'beglichen' && $status == 'beglichen'){
 					$filteredlist[] =  $zahlungsteilnehmer;
 				}
 				if($zahlungsteilnehmer->getStatus()== 'ersteller' && $status == 'ersteller'){
 					$filteredlist[] =  $zahlungsteilnehmer;
 				}
 			}
 		}
 		return $filteredlist;
 	}
 	
 	
 	function datFilter($zahlungenliste, $afterdate, $beforedate){
 		echo "im datFilter angekommen";
 		$filteredlist = array();
 		if($beforedate == ""){
 			$beforedate = ('2038-01-01');
 			echo "im datFilter if Zweig angekommen";
 		}
 		foreach($zahlungenliste as $zaehler => $zahlungsteilnehmer){
 			if($zahlungsteilnehmer->getZahlung()->getZahlungsdatum() > $afterdate && $zahlungsteilnehmer->getZahlung()->getZahlungsdatum() < $beforedate){
 				$filteredlist[] =  $zahlungsteilnehmer;
 			}
 		}
 		echo "Zahlungsdatum:";
 		var_dump($zahlungsteilnehmer->getZahlung()->getZahlungsdatum());
 			
 		return $filteredlist;
 		
 	}
 	
 	
 	
 	
}