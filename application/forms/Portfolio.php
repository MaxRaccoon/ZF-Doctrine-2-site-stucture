<?php
/**
 * User: raccoon
 * Date: 20.02.12 23:08
 */
class Application_Form_Portfolio extends Zend_Form
{

  public function __construct($options = null, $type = "add")
  {
		parent::__construct($options);
		$this->setName('portfolio');
		$this->setMethod('post');

        $pic_dir = new Zend_Form_Element_Hidden('pic_dir', array(
            'required'    => true,
            'maxlength'   => '250'
        ));

        $title = new Zend_Form_Element_Text('title', array(
            'required'    => true,
            'label'       => $this->getView()->translate('Title'),
            'maxlength'   => '250',
        	'class'		  => 'text'
        ));

        $url = new Zend_Form_Element_Text('url', array(
            'required'    => false,
            'label'       => $this->getView()->translate('url'),
            'maxlength'   => '250',
        	'class'		  => 'text',
            'validators'  => array(
             ),
        ));

        $description = new Zend_Form_Element_Textarea('description', array(
            'required'    => false,
            'label'       => $this->getView()->translate('Description'),
        	'class'		  => 'text'
        ));

        $Customers = array();
        foreach (\Zend_Registry::get('doctrine')->getEntityManager()->getRepository('\ZF\Entities\Customer')->findByIsDeleted(false) AS $item)
        {
        	$Customers[ $item->getId() ] = $item->getTitle();
        }
        $customer = new Zend_Form_Element_Select('customer', array(
        	'required'    => true,
            'label'       => $this->getView()->translate('Customer'),
        	'class'		  => 'long',
        	'multiOptions'	  => $Customers
  		));

        $dt_launch = new Zend_Form_Element_Text('dt_launch', array(
            'required'    => false,
            'label'       => $this->getView()->translate('Launch date'),
            'maxlength'   => '11',
        	'class'		  => 'text',
            'validators'  => array(
                array('Date', true, array('format'=>'Y-m-d')),
             ),
        ));

        $submit = new Zend_Form_Element_Submit('submit', array(
            'label'=> $this->getView()->translate( ($type == "edit" ? "Edit" : "Add") ),
        	'class'=> 'submit'
        ));

        $this->addElements(array($pic_dir, $title, $url, $description, $customer, $dt_launch, $submit));
  }
} 