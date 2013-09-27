<?php

namespace ZfModule\Options;

use Zend\Stdlib\AbstractOptions;

/**
 * Description of ModuleOptions
 *@todo review posibility of mering with general config
 *@todo pretty sure the abstract class uses classmethods to hydrate from array
 * @author gerard
 */
class ModuleOptions extends AbstractOptions 
{

    /**
     *
     * @var string
     */
    protected $userEntityClassName;
    /**
     *
     * @var string
     */
    protected $moduleEntityClass;
     /**
     *
     * @var string
     */
    protected $commentEntityClass;
    /**
     *
     * @var string
     */
    protected $tagEntityClass;
    /**
     *
     * @var string
     */
    protected $moduleIndexPath;
    /**
     *
     * @var string
     */
    protected $viewExceptionTemplateName;
   

    /**
     * 
     * @param string $className
     * @return \ZfModule\Options\ModuleOptions
     */
    public function setCommentEntityClassName($className)
    {
        $this->commentEntityClass=$className;
        
        return $this;
    }
    /**
     * 
     * @return string
     */
    public function getUserEntityClassName()
    {
        return $this->userEntityClassName;
    }
    /**
     * 
     * @param string $className
     * @return \ZfModule\Options\ModuleOptions
     */
    public function setUserEntityClassName($className)
    {
         $this->userEntityClassName=$className;
         
         return $this;
    }
    

    /**
     * 
     * @return string
     */
    public function getCommentEntityClassName()
    {
        return $this->commentEntityClass;
    }
    /**
     * 
     * @param string $className
     * @return \ZfModule\Options\ModuleOptions
     */
    public function setModuleEntityClass($className) {
        $this->moduleEntityClass = $className;
        return $this;
    }
    /**
     * 
     * @return string
     */
    public function getModuleEntityClass() 
    {
        return $this->moduleEntityClass = $string;
    }
    /**
     * 
     * @return string
     */
    public function getViewExceptionTemplateName()
    {
        return $this->viewExceptionTemplateName;
    }
    /**
     * 
     * @param string $templateName
     * @return \ZfModule\Options\ModuleOptions
     */
    public function setViewExceptionTemplateName($templateName)
    {
        $this->viewExceptionTemplateName = $templateName;
        
        return $this;
    }
    /**
     * 
     * @param string $string
     * @return \ZfModule\Options\ModuleOptions
     */
    public function setTagEntityClassName($className) {
        $this->tagEntityClass = $className;
        return $this;
    }
    /**
     * 
     * @return string
     */
    public function getTagEntityClassName() {
        return $this->tagEntityClass = $string;
    }

    /**
     * base path with /tag appended
     * @return string
     */
    public function getTagIndexPath() {
        return $this->getBaseSearchIndexPath() . '/tag';
    }
    /**
     * base path with /module appended
     * @return string
     */
    public function getModuleIndexPath() 
    {
        return $this->getBaseSearchIndexPath() . '/module';
    }
    /**
     * jumping around directories like an idiot is better than recursively scaning them me thinks
     * @return string
     */
    public function getBaseSearchIndexPath(){
        $path = dirname(__FILE__); // should be /zfmodule/src/zfmodules/options
        $path .= '/../../../../../'; //go back
        $path .= 'data/search';
        return $path;
    }
}

?>
