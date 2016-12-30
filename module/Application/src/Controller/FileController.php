<?php 

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Application\UploadForm;


class FileController extends AbstractActionController {
	

	public function uploadFormAction() {
    	
		$form = new UploadForm('uploadform');

	    $request = $this->getRequest();
    	if ($request->isPost()) {
        	// Make certain to merge the files info!
 	       $post = array_merge_recursive(
    	        $request->getPost()->toArray(),
        	    $request->getFiles()->toArray()
  	      );

    	    $form->setData($post);
        	if ($form->isValid()) {
   	         $data = $form->getData();
    	        // Form is valid, save the form!
        	    return $this->redirect()->toRoute('uploadform/success');
        	}
    	}

   	 return array('form' => $form);
	}
}

?>