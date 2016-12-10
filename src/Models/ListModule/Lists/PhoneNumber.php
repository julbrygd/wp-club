<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Club\Models\ListModule\Lists;

use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Entity;
/**
 * Description of PhoneNumber
 * @Entity
 * @author stephan
 */
class PhoneNumber {
    
    /**
     * @Id
     * @GeneratedValue
     * @Column(type = "integer")
     */
    protected $phoneId;
    
    /**
     * @Column(type = "integer")
     */
    protected $type;
    
    /**
     * @Column(type = "string", length = 20)
     */
    protected $number;
    
    public function getPhoneId() {
        return $this->phoneId;
    }

    public function getType() {
        return $this->type;
    }

    public function getNumber() {
        return $this->number;
    }

    public function setPhoneId($phoneId) {
        $this->phoneId = $phoneId;
        return $this;
    }

    public function setType($type) {
        $this->type = $type;
        return $this;
    }

    public function setNumber($number) {
        $this->number = $number;
        return $this;
    }


}
