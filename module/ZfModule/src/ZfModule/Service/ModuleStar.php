<?php

namespace ZfModule\Service;
use EdpGithub\Api\Repos;
use EdpGithub\Http\Client;

/**
 * 
 *persist the info on starred from github
 * @author gerard
 */
class ModuleStar 
{
    public function updateStars()
    {
        $entityCollection = $this->getModuleMapper()->findAll();
        foreach ($entityCollection as $module) {
            try {
                 $this->updateModuleStar($module);
            } catch (Exception $exc) {
               //hasta mañana
            }

           
        }  
    }
    public function updateModuleStar($module)
    {
        $repo = $module->getOwner();
        $name = $module->getName();
        $sm = $this->getServiceLocator();
        $repository = $sm->get('EdpGithub\Client')->api('repos')->show($owner, $repo);
        $repository = json_decode($repository);
         
        if(!($repository instanceOf \stdClass)) {
                throw new Exception\RuntimeException(
                    'Not able to fetch the repository from github due to an unknown error.',
                    500
                );
            }
        $module->setWatched($repository->watched);
        $this->persist($module);
        
    }
    /*
     * save module using $em
     */
    public function persist($module)
    {
        $em = $this->getEntityManager();
        $em->persist($module);
        $em->flush();
        return $this;
    }

    public function getEntityManager()
    {
        return $this->getServiceLocator()->get('doctrine.entitymanager.orm_default');
    }

    public function getServiceLocator()
    {
        return $this->serviceLocator;
    }

    public function setServiceLocator($sm)
    {
        $this->serviceLocator = $sm;
        return $this;
    }

    /**
    * @param string $location
     * @param array $data 
    * @return EdpGithub\Http\Client
    */
    public function getGithubClient($location, $data)
    {
        return $client = new Client();
    }
}

?>
