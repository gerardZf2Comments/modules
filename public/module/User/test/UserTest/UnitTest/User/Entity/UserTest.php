<?php

namespace UserTest\UnitTest\User\Entity;
use GolTest\PHPUnit\Entity\EntityAbstract;



class UserTest extends EntityAbstract
{
    /**
     *class to be instanciated for testing
     * @var string
     */
    protected $_entityClass = 'User\Entity\User';
    /**
     * data for comparison with newly contructed entity
     * property values
     * @return array
     */
    public function getExpectedConstructedData()
    {
        $userComments = new \ArrayObject();
        $data = array(
            'user_comments'=> $userComments,
            'created_at' => null,
            'photo_url' => null,
            'id' => null,
            'username' => null,
            'email' => null,
            'display_name' => null,
            'password' => null,
            'state'=>null,
        );
        
        return $data;
    }

    
    /**
     * this function must return the default data
     * std lib methods will be used
     * @return array
     */
    public function provider()
    {
        return array(
            array(
                array(
            'user_comments'=> 1,
            'created_at' => 1,
            'photo_url' => 1,
            'username' => 1,
            'email' => 1,
            'display_name' => 1,
            'password' => 1,
            'id' => 1,
            'state'=>1,
                ),
            ),
        );     
      
    }

}
