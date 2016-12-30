<?php 

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;


class FileController extends AbstractActionController {
	

	public function uploadFormAction() {
    	$form = new UploadForm('upload-form');

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
        	    return $this->redirect()->toRoute('upload-form/success');
        	}
    	}

   	 return array('form' => $form);
	}
}

?>