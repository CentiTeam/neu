<?php
namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class loginController extends AbstractActionController{
	
	public function loginAction(){
		
		$uname = $_POST['uname'];
		$psw = $_POST['psw'];
	
		return new ViewModel();
	}
}
