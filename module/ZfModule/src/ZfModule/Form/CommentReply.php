<?php

namespace ZfModule\Form;

use Zend\Form\Form;
use Zend\InputFilter\InputFilter;
use Zend\Validator\Digits;
/**
 * @todo test and tidy
 */
class CommentReply extends Form {

    /**
     * 
     * @param Zend\InputFilter\InputFilter
     */
    protected $inputFilter;
    public function __construct($name = null)
     {


        parent::__construct('comment-reply');
        $this->setInputFilter($this->getInputFilter()); 
        
        $this->add(array(
            'name' => 'parent-id',
            'type' => 'Zend\Form\Element\Hidden',
            'attributes' => array(
              
                'class' => 'parent-id',
            ),
        ));
       
        $this->add(array(
            'name' => 'comment',
            'type' => 'Zend\Form\Element\Text',
            'options' => array(
                'label' => 'Comment',
            ),
            'messages' => array( 'error meassage'),
        ));
        $this->add(array(
            'name' => 'submit',
            'type' => 'Zend\Form\Element\Submit',
            'attributes' => array(
                'value' => 'Post',
                'id' => 'submitbutton',
            ),
        ));
    }

    public function getInputFilter() {
        if (!$this->inputFilter) {
            $inputFilter = new InputFilter();
            $inputFilter->add(array(
                'name' => 'parent-id',
                'required' => true,
                'filters' => array(
                    array('name' => 'Int'),
                ),
            ));
            $inputFilter->add(array(
                'name' => 'comment',
                'required' => true,
                'filters' => array(
                    array('name' => 'StripTags'),
                    array('name' => 'StringTrim'),
                ),
                'validators' => array(
                    array(
                        'name' => 'StringLength',
                        'options' => array(
                            'encoding' => 'UTF-8',
                            'min' => 1,
                            'max' => 10000,
                        ),
                       'messages' => array( 'error meassage'),
                    ),
                    $this->getMessageValidator(),
                ),
            ));
            
            $this->inputFilter = $inputFilter;
        }
        return $this->inputFilter;
    }
    public function getMessageValidator () {
       $mv = new Digits;
       $mv->setMessage('why why why ');
       return $mv;
    }

}

