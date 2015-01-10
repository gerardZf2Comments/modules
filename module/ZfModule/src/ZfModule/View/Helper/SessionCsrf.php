<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
namespace ZfModule\View\Helper;
use Zend\Form\View\Helper\FormInput;
/**
 * Description of SessionCsrf
 *
 * @author gerard
 */
class SessionCsrf extends FormInput
{
    public function render(ElementInterface $element)
    {
        $name = $element->getName();
        if ($name === null || $name === '') {
            throw new Exception\DomainException(sprintf(
                '%s requires that the element has an assigned name; none discovered',
                __METHOD__
            ));
        }

        $attributes          = $element->getAttributes();
        $attributes['name']  = $name;
        $attributes['type']  = $this->getType($element);
        $attributes['value'] = $element->getValue();

        return sprintf(
            '<input %s%s',
            $this->createAttributesString($attributes),
            $this->getInlineClosingBracket()
        );
    }
    
    public function addToUserSession()
    {
        
    }
}
