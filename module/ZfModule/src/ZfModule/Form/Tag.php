<?php

namespace ZfModule\Form;

use Zend\Form\Form;
use Zend\InputFilter\InputFilter;
use Zend\Validator\Digits;
/**
 * form can display errors and valiadte tag input
 * @todo check if controller needs more elements added
 */
class Tag extends Form 
{
    /**
     * the input filter object
     * @var Zend\InputFilter\InputFilter
     */
    protected $inputFilter;
    /**
     * set name and instanciate elements and input filter
     * @param string $name
     */
    public function __construct($name = null)
    {
        parent::__construct('tag');
        
        $this->setInputFilter($this->getInputFilter()); 
        
        $this->add(array(
            'name' => 'module-id',
            'type' => 'Zend\Form\Element\Hidden',
            'attributes' => array(
              
                'class' => 'module-id',
            ),
        ));
       
        $this->add(array(
            'name' => 'tag',
            'type' => 'Zend\Form\Element\Text',
            'options' => array(
                'label' => 'Tag',
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
                'name' => 'module-id',
                'required' => false,
                'filters' => array(
                    array('name' => 'Int'),
                ),
                'validators' => array(
                $this->getModuleIdIntegerValidator(),                     
                    ),
            ));
            $inputFilter->add(array(
                'name' => 'tag',
                'required' => true,
                'filters' => array(
                    array('name' => 'StripTags'),
                    array('name' => 'StringTrim'),
                ),
                'validators' => array(
                $this->getTagStringLengthValidator(),
                     
                    ),
                   
               
            ));
            
            $this->inputFilter = $inputFilter;
        }
        
        return $this->inputFilter;
    }  
    /**
     * set max 100 and min 1 here 
     * @return \Zend\Validator\StringLength
     */
    public function getTagStringLengthValidator()
    {
        $validator = new \Zend\Validator\StringLength();
        $validator->setEncoding('UTF-8');
        $validator->setMin(1);
        $validator->setMax(100);
       
        return $validator;       
    }
     /**
     * not changing the default functionality of objects here
     * @return \Zend\Validator\Digits
     */
    public function getModuleIdIntegerValidator()
    {
        $validator = new \Zend\Validator\Digits();
               
        return $validator;       
    }

}

