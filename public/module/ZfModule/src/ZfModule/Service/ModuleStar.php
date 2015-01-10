<?php

namespace ZfModule\Service;

use EdpGithub\Api\Repos;
use EdpGithub\Http\Client;

/**
 * persist the info on starred from github
 * @author gerard
 */
class ModuleStar 
{
    /**
     * get module mapper
     * @return \ZfModule\Mapper\Module
     */
    public function getModuleMapper()
    {
        return  $mapper = $this->getServiceLocator()->get('zfmodule_mapper_module');;
    }
    /**
     * update each module
     */
    public function updateStars()
    {
        $entityCollection = $this->getModuleMapper()->findAll();
        foreach ($entityCollection as $module) {
                 $this->updateModuleStar($module); 
                }  
    }
    /**
     * use github module to get info 
     * @param \ZfModule\entity\Module $module
     * @throws Exception\RuntimeException
     */
    public function updateModuleStar($module)
    {
        $owner = $module->getOwner();
        $repo = $module->getName();
        $sm = $this->getServiceLocator();
        $repository = $sm->get('EdpGithub\Client')->api('repos')->show($owner, $repo);
        $repository = json_decode($repository);
         
        if(!($repository instanceOf \stdClass)) {
                throw new Exception\RuntimeException(
                    'Not able to fetch the repository from github due to an unknown error.',
                    500
                );
            }
        $tagUrl = $repository->tags_url;
        $tagsUri = stristr($tagUrl, 'repos/');
        $tag = $this->getResponse($tagsUri);
        $module->setWatched($repository->watchers);
        $this->persist($module);        
    }
    /**
     * makes http call
     * @param string $uri
     * @return type
     */
    public function getResponse($uri)
    {
        $sm = $this->getServiceLocator();
        
        return $sm->get('EdpGithub\Client')->getHttpClient()->get($uri);
    }
    /**
     * save module using $em
     * @param \ZfModule\entity\Module $module
     * @return \ZfModule\Service\ModuleStar
     */    
    public function persist($module)
    {
        $em = $this->getEntityManager();
        $em->persist($module);
        $em->flush();
        
        return $this;
    }
    /**
     * entity manager
     * @return type
     */
    public function getEntityManager()
    {
        return $this->getServiceLocator()->get('doctrine.entitymanager.orm_default');
    }
    /**
     * service locator
     * @return type
     */
    public function getServiceLocator()
    {
        return $this->serviceLocator;
    }
    /**
     * set service locator
     * @param type $sm
     * @return \ZfModule\Service\ModuleStar
     */
    public function setServiceLocator($sm)
    {
        $this->serviceLocator = $sm;
        return $this;
    }
    /**
    * get the client 
    * @param string $location
    * @param array $data 
    * @return EdpGithub\Http\Client
    */
    public function getGithubClient($location, $data)
    {
        return $client = new Client();
    }
}

