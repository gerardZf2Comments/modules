<?php

namespace ZfModule\Form;

use Zend\Form\Form;
use Zend\InputFilter\InputFilter;
use Zend\Validator\Digits;
/**
 * form can display errors and valiadte comment input
 * @todo 
 */
class CommentReply extends Form 
{
    /**
     * input filter
     * @var Zend\InputFilter\InputFilter
     */
    protected $inputFilter;
     /**
     * set name and instanciate elements and input filter
     * @param string $name
     */
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
    /**
     * instanciate, assign then return the filter 
     * @return Zend\InputFilter\InputFilter
     */
    public function getInputFilter() 
    {
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
                $this->getCommentStringLengthValidator(),
                     
                    ),                   
            ));
            
            $this->inputFilter = $inputFilter;
        }
        
        return $this->inputFilter;
    }   
    /**
     * instanciate and set max and min
     * @return \Zend\Validator\StringLength
     */
    public function getCommentStringLengthValidator()
    {
        $validator = new \Zend\Validator\StringLength();
        $validator->setEncoding('UTF-8');
        $validator->setMin(1);
        $validator->setMax(5000);
        
        return $validator;       
    }
}

