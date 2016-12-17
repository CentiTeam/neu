<?php
namespace Application\Controller;
 
 
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Application\Model\Gruppe;
use Zend\View\Renderer\PhpRenderer;
use Application\Controller\Resolver\TemplateMapResolver;
use Zend\View\Resolver;

 
 
/**
 *
 */
class GruppeController extends AbstractActionController {
     
    function anlegenAction() {
        // TODO Berechtigungsprüfung
                 
        $gruppe = new Gruppe();       
         
        if ($_REQUEST['speichern']) {
            // Array, das eventuelle Fehlermeldungen enthält
            
        	$saved= false;
        	
        	$errors = array();
             
            // Pruefung-Objekt mit Daten aus Request-Array füllen
            $gruppe->setGruppenname($_REQUEST["gruppenname"]);
            $gruppe->setGruppenbeschreibung($_REQUEST["gruppenbeschreibung"]);
            $gruppe->setGruppenbildpfad($_REQUEST["gruppenbildpfad"]);
            
            // Funktion der Klasse 
            $isOk = $gruppe->anlegen();
            
            if (!$isOk) {
            	array_push($errors, "Fehler beim anlegen der Gruppe!");
            } else {
            	$saved = true;
            }
            	
        } 
        
        /**
        $renderer = new PhpRenderer();
        
        $map = new Resolver\TemplateMapResolver(array(
        		'anlegen' => __DIR__ . '/anlegen.phtml',
        ));
        
        $resolver = new Resolver\TemplateMapResolver($map);
        $renderer->setResolver($resolver);
        
        $viewModel = new ViewModel ();
        $viewModel->setTemplate ('anlegen');
        return new $viewModel;
        */
        
   		     
        return new ViewModel([
                'gruppe' => array($gruppe),
                'errors'   => $errors
        ]);
        
    }
}