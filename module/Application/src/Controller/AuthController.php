<?php
class AuthController extends Zend\Controller\Action
{

	public function loginAction()
	{
		$db = $this->_getParam('db');

		$loginForm = new Default_Form_Auth_Login($_POST);

		if ($loginForm->isValid()) {

			$adapter = new Zend\Auth\Adapter\DbTable(
					$db,
					'user',
					'username',
					'passwort',
					);

			$adapter->setIdentity($loginForm->getValue('username'));
			$adapter->setCredential($loginForm->getValue('passwort'));

			$result = $auth->authenticate($adapter);

			if ($result->isValid()) {
				$this->_helper->FlashMessenger('Successful Login');
				$this->redirect('/');
				return;
			}

		}

		$this->view->loginForm = $loginForm;

	}

}