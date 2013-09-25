<?php

namespace ZfModule\Service;

use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use ZendSearch\Lucene\Document;
use ZendSearch\Lucene\Index;
use ZfModule\Entity\ModuleInterface;
use Zend\Stdlib\Hydrator\ClassMethods;
use ZfcBase\EventManager\EventProvider;

/**
 * Description of ModuleIndexer
 *
 * @author gerard
 */
class ModuleIndexer extends EventProvider implements ServiceLocatorAwareInterface 
{

    //works frm base of vendor or module directory of a 
    //normal sample app
    protected $indexPath;
    
    protected $index = null;

    public function getIndexPath(){
        $options = new \ZfModule\Options\ModuleOptions;
         return $options->getModuleIndexPath();
} 
    public function addAll() 
    {
        $this->initIndex(true);
        $mapper = $this->getServiceLocator()->get('zfmodule_mapper_module');

        $results = $mapper->findAll();
        foreach ($results as $entity) {
            $this->addToModuleIndex($entity);
        }
        $this->optimizeIndex();
    }

    /**
     * $this->index->optimize();
     * @todo find out exactly when to call this. i think it might be called internaly or something so 
     * @todo only calling after index creation might be ok
     */
    public function optimizeIndex() 
    {
        $this->index->optimize();
    }
    /**
     * return $this->getIndex($this->indexPath, $create);
     * @param bool $create
     */
    public function initIndex($create = false) 
    {
        /** @var index \ZendSearch\Lucene\Index  */
        
        return $this->getIndex($this->getIndexPath(), $create);        
    }
    /**
     * 
     * @param \ZendSearch\Lucene\Index $index
     * @return \ZfModule\Service\ModuleIndexer
     */
    public function setIndex( Index $index)
    {
         $this->index = $index;
         return $this;
    }
    /**
     * 
     * @param string $path
     * @param bool $create
     * @return ZendSearch\Lucene\Index
     */
    public function getIndex( $path = null, $create = false )
    {
        if( !$this->index && $path ){
            $this->index = new Index( $path, $create );
        }
        return $this->index;
    }

    /**
     * uses mapper to find all Module's ModuleTag entities. returns concat of the tag field
     * @param \ZfModule\Entity\ModuleInterface $entity
     * @return string
     */
    public function getTags( ModuleInterface $entity ) 
    {
        
        $tags = $entity->getTags();
        
        $finalTag = '';
        foreach ( $tags as $tag ) {
            $finalTag .= ' ' . $tag->getTag();
        }
        
        return $finalTag;
    }
   /**
     * @param \ZfModule\Entity\ModuleInterface $entity
     * @return \ZfModule\Service\ModuleIndexer
     */
    private function addToModuleIndex( ModuleInterface $entity ) 
    {
        $tags = $this->getTags( $entity );
        $doc = new Document();
        //create text fields for anylisis

        $this->addFields( $doc, array(
            
            //create text fields for analyis
            Document\Field::text( 'name', $entity->getName() ),
            Document\Field::text( 'description', $entity->getDescription() ),
            Document\Field::text( 'tags', $tags ),
            //create unindexed field for easy rendering of search results;
            Document\Field::unIndexed(  'moduleId' , $entity->getId() ),
            
            )
        );

        $this->index->addDocument($doc);
        
        return $this;
    }
    /**
     * 
     * @param \ZendSearch\Lucene\Document $doc
     * @param array $fields
     * @return \ZendSearch\Lucene\Document
     */
    public function addFields( Document $doc, array $fields ) 
    {
        foreach ( $fields as $field ) {
            $doc->addField( $field );
        }
        
        return $doc;
    }
    /**
     * @return \ZfModule\Service\ModuleIndexer
     */
    public function getServiceLocator() 
    {
        return $this->serviceLocator;
    }

    /**
     * 
     * @param \Zend\ServiceManager\ServiceLocatorInterface $sm
     * @return \ZfModule\Service\ModuleIndexer
     */
    public function setServiceLocator( ServiceLocatorInterface $sm ) 
    {
        $this->serviceLocator = $sm;
        
        return $this;
    }

}

?>
