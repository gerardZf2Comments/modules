<?php

namespace ZfModule\Entity;
/**
 * Description of Users
 *
 * @author gerard
 */
class Users {
    private $id;
    private $name;


    public function setId($id){
        $this->id = $id;
        return $this;
    }
    public function setName($name){
        $this->name = $name;
        return $this;
        
    }
}

?>
