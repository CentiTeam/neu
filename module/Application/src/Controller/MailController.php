<?php
namespace Application\Controller;


use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Application\Model\Gruppe;

/**
 * FILE-Controller wird nur noch als Vorlage verwendet!!!! 
 * Die Funktionalit�t wurde in GroupeditController und GruppeanlegenController �bernommen
 * @author Tanja
 *
 */


class FileController extends AbstractActionController {

	function einladenAction() {
		
		
		// TODO Berechtigungspr�fung
		// AUf welche Seite führt dieser Controller denn? :D Hier sollte keine Berechtigungsprüfung notwendig sein
		session_start();
	
		
						 
	
	}

}