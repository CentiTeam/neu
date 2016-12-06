<?php
class Default_Form_Auth_Login extends Zend_Form
{
	public function init()
	{
		$this->setMethod('post');

		$this->addElement(
				'text', 'username', array(
						'label' => 'Username:',
						'required' => true,
						'filters'    => array('StringTrim'),
				));

		$this->addElement('password', 'passwort', array(
				'label' => 'Passwort:',
				'required' => true,
		));

		$this->addElement('submit', 'submit', array(
				'ignore'   => true,
				'label'    => 'Login',
		));

	}
}