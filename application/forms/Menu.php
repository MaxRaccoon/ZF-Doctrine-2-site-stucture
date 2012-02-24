<?php
/**
 * User: raccoon
 * Date: 24.02.12 16:55
 */
class Application_Form_Menu extends Zend_Form
{
    public function __construct($options = null, $type)
    {
		parent::__construct($options);
		$this->setName('menu' . $type);
		$this->setMethod('post');

        $title = new Zend_Form_Element_Text('title', array(
            'required'    => true,
            'label'       => $this->getView()->translate('Title'),
            'maxlength'   => '45',
        	'class'		  => 'text',
            'validators'  => array(
                array('Alnum', true, array(true)),
             ),
        ));

        $Controllers = array();
        foreach (\Zend_Registry::get('doctrine')->getEntityManager()->getRepository('\ZF\Entities\AclController')->findAll() AS $Controller)
        {
        	$Controllers[ $Controller->getId() ] = $Controller->getName();
        }
        $acl_controller = new Zend_Form_Element_Select('aclcontroller', array(
        	'required'    => true,
            'label'       => $this->getView()->translate('Controller'),
        	'class'		  => 'long',
        	'multiOptions'	  => $Controllers
  		));

        $Actions = array();
        foreach (\Zend_Registry::get('doctrine')->getEntityManager()->getRepository('\ZF\Entities\AclAction')->findAll() AS $Action)
        {
        	$Actions[ $Action->getId() ] = $Action->getName();
        }
        $acl_action = new Zend_Form_Element_Select('aclaction', array(
        	'required'    => true,
            'label'       => $this->getView()->translate('Action'),
        	'class'		  => 'long',
        	'multiOptions'	  => $Actions
  		));

        $method = new Zend_Form_Element_Text('method', array(
            'required'    => false,
            'label'       => $this->getView()->translate('Method'),
            'maxlength'   => '45',
        	'class'		  => 'text',
            'value'       => '',
            'validators'  => array(
                array('Alnum', true, array(true)),
             ),
        ));        

        $route = new Zend_Form_Element_Text('route', array(
            'required'    => false,
            'label'       => $this->getView()->translate('Route'),
            'maxlength'   => '45',
        	'class'		  => 'text',
            'value'       => 'default',
            'validators'  => array(
                array('Alnum', true, array(true)),
             ),
        ));


        $submit = new Zend_Form_Element_Submit('submit', array(
            'label'=> $this->getView()->translate( ($type == "edit" ? "Edit" : "Add") ),
        	'class'=> 'submit'
        ));

        $this->addElements(array($title, $acl_controller, $acl_action, $method, $route, $submit));
    }
} 
