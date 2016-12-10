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
 * Description of TaucherBrevets
 *
 * @Entity
 * @author stephan
 */
class TaucherBrevets {
    /**
     * @Column(type = "integer")
     * @Id
     * @GeneratedValue
     * @var int 
     */
    private $bid;
    
    /**
     * @Column(type = "string", length = 20)
     * @var string 
     */
    private $name;
    
    function getBid() {
        return $this->bid;
    }

    function getName() {
        return $this->name;
    }

    function setBid($bid) {
        $this->bid = $bid;
        return $this;
    }

    function setName($name) {
        $this->name = $name;
        return $this;
    }


}
