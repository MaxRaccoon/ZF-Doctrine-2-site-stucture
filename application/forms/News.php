<?php
/**
 * User: raccoon
 * Date: 04.02.12 21:48
 */
class Application_Form_News extends Zend_Form
{

  public function __construct($options = null, $type = "add")
  {
		parent::__construct($options);
		$this->setName('news');
		$this->setMethod('post');

        $title = new Zend_Form_Element_Text('title', array(
            'required'    => true,
            'label'       => $this->getView()->translate('Title'),
            'maxlength'   => '250',
        	'class'		  => 'text',
            'validators'  => array(
                array('Alnum', true, array(true)),
             ),
        ));

        $tags = new Zend_Form_Element_Text('tags', array(
            'required'    => false,
            'label'       => $this->getView()->translate('Tags'),
            'maxlength'   => '250',
        	'class'		  => 'text',
            'validators'  => array(
                array('Alnum', true, array(true)),
             ),
        ));      

        $anons = new Zend_Form_Element_Textarea('anons', array(
            'required'    => false,
            'label'       => $this->getView()->translate('Anons'),
            'maxlength'   => '1000',
        	'class'		  => 'text',
            'validators'  => array(
                array('Alnum', true, array(true)),
             ),
        ));

        $text = new Zend_Form_Element_Textarea('text', array(
            'required'    => true,
            'label'       => $this->getView()->translate('Text'),
        	'class'		  => 'text',
            'validators'  => array(
                array('Alnum', true, array(true)),
             ),
        ));      

        $submit = new Zend_Form_Element_Submit('submit', array(
            'label'=> $this->getView()->translate( ($type == "edit" ? "Edit" : "Add") ),
        	'class'=> 'submit'
        ));

        $this->addElements(array($title, $anons, $text, $tags, $submit));
  }
} 
