<?php
/**
 * User: raccoon
 * Date: 16.02.12 15:01
 */
class Application_Form_Page extends Zend_Form
{

  public function __construct($options = null, $type = "add")
  {
		parent::__construct($options);
		$this->setName('page');
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

        $url = new Zend_Form_Element_Text('url', array(
            'required'    => true,
            'label'       => $this->getView()->translate('url'),
            'maxlength'   => '250',
        	'class'		  => 'text',
            'validators'  => array(
             ),
        ));
        if ($type == "add")
        {
            $url->setAttrib('readonly','readonly');
        }

      
        $generateURL = new Zend_Form_Element_Checkbox('generateURL', array(
        	'checked'	=> ( $type == "add" ? true : false ),
        	'label'		=> $this->getView()->translate('auto generate url')
        ));

        $meta_tags = new Zend_Form_Element_Text('meta_tags', array(
            'required'    => true,
            'label'       => $this->getView()->translate('Meta tags'),
            'maxlength'   => '250',
        	'class'		  => 'text',
            'validators'  => array(
                array('Alnum', true, array(true)),
             ),
        ));      

        $tags = new Zend_Form_Element_Text('tags', array(
            'required'    => true,
            'label'       => $this->getView()->translate('Tags'),
            'maxlength'   => '250',
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

        $this->addElements(array($title, $url, $generateURL, $text, $meta_tags, $tags, $submit));
  }
}

