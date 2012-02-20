<?php
/**
 * User: raccoon
 * Date: 20.02.12 18:54
 */
class Application_Form_Customer extends Zend_Form
{
    public function __construct($options = null, $type)
    {
		parent::__construct($options);
		$this->setName('customer' . $type);
		$this->setMethod('post');

        $title = new Zend_Form_Element_Text('title', array(
            'required'    => true,
            'label'       => $this->getView()->translate('Company name'),
            'maxlength'   => '250',
        	'class'		  => 'text',
            'validators'  => array(
                array('Alnum', true, array(true)),
             ),
        ));

        $email = new Zend_Form_Element_Text('email', array(
            'required'    => false,
            'label'       => $this->getView()->translate('Email'),
            'maxlength'   => '150',
        	'class'		  => 'text',
            'disable'     => ($type == "edit" ? true : false ),
            'validators'  => array(
                array('EmailAddress', true, array(true)),
             ),
        ));

        $url = new Zend_Form_Element_Text('url', array(
            'required'    => false,
            'label'       => $this->getView()->translate('url'),
            'maxlength'   => '250',
        	'class'		  => 'text',
            'validators'  => array(
                array('Hostname', true, array(true)),
             ),
        ));

        $phone = new Zend_Form_Element_Text('phone', array(
            'required'    => false,
            'label'       => $this->getView()->translate('Phone'),
            'maxlength'   => '11',
        	'class'		  => 'text',
            'validators'  => array(
                array('Int'),
             ),
        ));           

        $description = new Zend_Form_Element_Textarea('description', array(
            'required'    => false,
            'label'       => $this->getView()->translate('Description'),
        	'class'		  => 'text'
        ));


        $submit = new Zend_Form_Element_Submit('submit', array(
            'label'=> $this->getView()->translate( ($type == "edit" ? "Edit" : "Add") ),
        	'class'=> 'submit'
        ));

        $this->addElements(array($title, $email, $url, $phone, $description, $submit));
    }
} 
