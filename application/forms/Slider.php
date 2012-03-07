<?php
/**
 * User: raccoon
 * Date: 25.02.12 18:12
 */
class Application_Form_Slider extends Zend_Form
{
  public function __construct($options = null, $type)
  {
		parent::__construct($options);
		$this->setName('slider' . $type);
		$this->setMethod('post');

        $file = new Zend_Form_Element_File('pic', array(
            'required'    => ($type == "edit" ? false : true ),
            'label'       => $this->getView()->translate('Picture'),
        	'class'		  => 'text'
        ));
        $file->addValidator('Size', false, 10240000) // 10Mb
            ->addValidator('Extension', false, 'jpg,png,gif');

        $title = new Zend_Form_Element_Text('title', array(
            'required'    => true,
            'label'       => $this->getView()->translate('Title'),
            'maxlength'   => '45',
        	'class'		  => 'text'
        ));

        $text = new Zend_Form_Element_Text('text', array(
            'required'    => true,
            'label'       => $this->getView()->translate('Text'),
            'maxlength'   => '45',
        	'class'		  => 'text'
        ));

        $submit = new Zend_Form_Element_Submit('submit', array(
            'label'=> $this->getView()->translate( ($type == "edit" ? "Edit" : "Add") ),
        	'class'=> 'submit'
        ));

        $this->addElements( array($file, $title, $text, $submit) );
  }
}