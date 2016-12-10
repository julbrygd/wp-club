<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Club\Models\ListModule\Lists;

use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\GeneratedValue;


/**
 * Description of Plz
 * @Entity
 * @author stephan
 */
class Plz {
    
    /**
     * 
     * @Id
     * @GeneratedValue
     * @Column(type = "integer")
     * @var type 
     */
    protected $plzId;
    
    /**
     * @Column(type = "string", length = 10)
     * @var string 
     */
    private $plz;
    
    /**
     * @Column(type = "string", length = 3)
     * @var string 
     */
    private $contry;
    
    /**
     * @Column(type = "string", length = 100)
     * @var string 
     */
    private $name;
    
    function getPlz() {
        return $this->plz;
    }

    function getContry() {
        return $this->contry;
    }

    function getName() {
        return $this->name;
    }

    function setPlz($plz) {
        $this->plz = $plz;
        return $this;
    }

    function setContry($contry) {
        $this->contry = $contry;
        return $this;
    }

    function setName($name) {
        $this->name = $name;
        return $this;
    }


}
