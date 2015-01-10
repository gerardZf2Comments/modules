<?php

namespace ZfModule\Mapper;
use Comments\Mapper\Comment as Comments;
use Doctrine\ORM\EntityManager;
use ZfModule\Mapper\Module as Module;

use Zend\Stdlib\Hydrator\HydratorInterface;

/**
 * comment mapper uses doctrine
 * @author gerard
 */
class Comment extends Comments
{
    //@todo create abstract getters and setters for these
    protected $relatorField = 'module';
    protected $fKField = 'moduleId';
    protected $entityName = 'ZfModule\Entity\Comment';
    protected function getRelatorField() {
        return $this->relatorField;
    }
    protected function setRelatorField($rf) {
        return $this->relatorField = $rF;
    }
    protected function getFkField() {
        return $this->fKField;
    }
    protected function setFkField($fk) {
        return $this->fKField = $fk;
    }
    public function __construct(EntityManager $em,  $options) {
        parent::__construct($em, $options);
    }
    
}
