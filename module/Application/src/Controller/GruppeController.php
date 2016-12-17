<?php
namespace Application\Controller;
 
 
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Application\Model\Gruppe;

 
 
/**
 *
 */
class GruppeController extends AbstractActionController {
     
    function anlegenAction() {
        // TODO Berechtigungsprüfung
                 
        $gruppe = new Gruppe();       
        
        $saved= false;
        $msg = array();
        
        if ($_REQUEST['speichern']) {
            // Array, das eventuelle Fehlermeldungen enthält
            
        	$errorStr = $this->allgGruppeInfosVerarbeiten ($gruppe);
        	
        	
        	
        	if ($errorStr == "" && $gruppe->anlegen()) {
        		
        		array_push($msg, "Gruppe erfolgreich gespeichert!"); 
        		$saved = true;
        	
        	} elseif ($errorStr == "") {
        		
        		array_push($msg, "Datenprüfung in Ordnung, Fehler beim Speichern der Gruppe!");
        		$saved = false;
        	
        	} else {
        		
        		array_push($msg, "Fehler bei der Datenprüfung. Gruppe nicht gespeichert!");
        		$saved = false;
        		
        	}
        	
        	var_dump($gruppe);
        	
        	$errors = array();
             
        
            //var_dump($gruppe);
            
            // Funktion der Klasse 
            //$isOk = $gruppe->anlegen();
            
            //var_dump($gruppe);
            
            if (!$isOk) {
            	array_push($errors, "Fehler beim anlegen der Gruppe!");
            } else {
            	$saved = true;
            }
            	
        } 
        
   		     
        return new ViewModel([
                'gruppe' => array($gruppe),
                'errors'   => $errors,
        		'msg' => $msg
        ]);
        
    }
    
    private function allgGruppeInfosVerarbeiten (Gruppe $gruppe) {
    	
    	// Schritt 1:  Werte aus Formular einlesen
    	$g_id=$_REQUEST["g_id"];
    	$gruppenname=$_REQUEST["gruppenname"];
    	$gruppenbeschreibung=$_REQUEST["gruppenbeschreibung"];
    	$gruppenbildpfad=$_REQUEST["gruppenbildpfad"];
    	
    	
    	// Schritt 2: Daten prüfen
    	$errorStr ="";
    	
    	if (empty($gruppenname)) {
    		$errorStr .="Der Gruppenname darf nicht leer sein!<br>";
    	}
    	
    	var_dump("$gruppenname");
    	
    	 
    	// Gruppe-Objekt mit Daten aus Request-Array füllen
    	$gruppe->setGruppenname($gruppenname);
    	$gruppe->setGruppenbeschreibung($gruppenbeschreibung);
    	$gruppe->setGruppenbildpfad($gruppenbildpfad);
    	
    	
    	
    	return $errorStr; 
    }
}