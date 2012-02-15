<?php
class Application_Form_User extends Zend_Form
{  
    public function __construct($options = null, $type)
    {
		parent::__construct($options);
		$this->setName('user' . $type);
		$this->setMethod('post');   
        
        $nickname = new Zend_Form_Element_Text('nickname', array(
            'required'    => true,
            'label'       => $this->getView()->translate('Nickname'),
            'maxlength'   => '45',
        	'class'		  => 'text',
            'validators'  => array(
                array('Alnum', true, array(true)),
             ),
        ));

        $entiry = \Zend_Registry::get('doctrine')->getEntityManager()->getRepository('\ZF\Entities\User');
        $validator = new \ZF\Validate\NoDbRecordExists($entiry,
                                                        "findOneByNickname",
                                                        \ZF\Validate\NoDbRecordExists::USER_NICKNAME_EXISTS);
        $nickname->addValidator($validator);
        
        $email = new Zend_Form_Element_Text('email', array(
            'required'    => ($type == "edit" ? false : true ),
            'label'       => $this->getView()->translate('Email'),
            'maxlength'   => '45',
        	'class'		  => 'text',
            'disable'     => ($type == "edit" ? true : false ),
            'validators'  => array(
                array('EmailAddress', true, array(true)),
             ),
        ));

        $validator = new \ZF\Validate\NoDbRecordExists($entiry,
                                                        "findOneByEmail",
                                                        \ZF\Validate\NoDbRecordExists::USER_EMAIL_EXISTS);
        $email->addValidator($validator);

        $Roles = array();
        foreach (\Zend_Registry::get('doctrine')->getEntityManager()->getRepository('\ZF\Entities\AclRole')->findAll() AS $AclRole)
        {
            if ($AclRole->getId() == 1)
            {
                continue;
            }
        	$Roles[ $AclRole->getId() ] = $AclRole->getName();
        }
        $role = new Zend_Form_Element_Select('aclrole', array(
        	'required'    => true,
            'label'       => $this->getView()->translate('User type'),
        	'class'		  => 'long',
        	'multiOptions'	  => $Roles
  		));

        $first_name = new Zend_Form_Element_Text('first_name', array(
            'required'    => false,
            'label'       => $this->getView()->translate('First name'),
            'maxlength'   => '45',
        	'class'		  => 'text',
            'validators'  => array(
                array('Alnum', true, array(true)),
             ),
        ));

        $last_name = new Zend_Form_Element_Text('last_name', array(
            'required'    => false,
            'label'       => $this->getView()->translate('Last name'),
            'maxlength'   => '45',
        	'class'		  => 'text',
            'validators'  => array(
                array('Alnum', true, array(true)),
             ),
        ));        

        $submit = new Zend_Form_Element_Submit('submit', array(
            'label'=> $this->getView()->translate( ($type == "edit" ? "Edit" : "Add") ),
        	'class'=> 'submit'
        ));

        $this->addElements(array($nickname, $email, $role, $first_name, $last_name, $submit));
    }
}