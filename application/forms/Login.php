<?php
class Application_Form_Login extends Zend_Form
{
  
  public function __construct($options = null) 
  {
		parent::__construct($options);
		$this->setName('login');    	
		$this->setMethod('post');   
        
        $username = new Zend_Form_Element_Text('username', array(
            'required'    => true,
            'label'       => $this->getView()->translate('User name'),
            'maxlength'   => '45',
        	'class'		  => 'text',
            'validators'  => array(
                array('Alnum', true, array(true)),
             ),
        ));                  
            
        $password = new Zend_Form_Element_Password('password', array(
            'required'    => true,
            'label'       => $this->getView()->translate('Password'),
            'maxlength'   => '30',
        	'class'		  => 'text'
        ));
        $password->addValidator('StringLength', false, array(5,24));

        $submit = new Zend_Form_Element_Submit('submit', array(
            'label'=> $this->getView()->translate('Login'),
        	'class'=> 'submit'
        ));

        $this->addElements(array($username, $password, $submit));
  }
}