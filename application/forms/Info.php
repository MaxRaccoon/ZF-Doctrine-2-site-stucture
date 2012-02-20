<?php
/**
 * User: raccoon
 * Date: 20.02.12 16:55
 */
class Application_Form_Info extends Zend_Form
{

  public function __construct($options = null, $type = "add")
  {
		parent::__construct($options);
		$this->setName('info');
		$this->setMethod('post');

        $key = new Zend_Form_Element_Text('info_key', array(
            'required'    => true,
            'label'       => $this->getView()->translate('Key'),
            'maxlength'   => '250',
        	'class'		  => 'text',
            'validators'  => array(
                array('Alpha', true, array(true)),
             ),
        ));

        $value = new Zend_Form_Element_Text('info_value', array(
            'required'    => false,
            'label'       => $this->getView()->translate('Value'),
            'maxlength'   => '250',
        	'class'		  => 'text'
        ));

        $submit = new Zend_Form_Element_Submit('submit', array(
            'label'=> $this->getView()->translate( ($type == "edit" ? "Edit" : "Add") ),
        	'class'=> 'submit'
        ));

        $this->addElements(array($key, $value, $submit));
  }
}
