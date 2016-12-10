<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Club\Models\ListModule;

use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\GeneratedValue;

/**
 * Description of Lists
 *
 * @Entity
 * @author stephan
 */
class Lists {
    /**
     * @Id
     * @Column(type="integer")
     * @GeneratedValue
     */
    
    private $listId;
    
    /**
     * @Column(type="string", length=50)
     */
    
    private $name;
    
    /**
     * @Column(type="string", length=50)
     */
    
    private $displayName;
    
    /**
     * @Column(type="string", length=255)
     */
    
    private $class;
    
    /**
     * @Column(type="integer")
     */   
    private $version;
    
    public function __construct($name, $displayName, $class) {
        $this->name = $name;
        $this->displayName = $displayName;
        $this->class = $class;
        $o = new $class();
        $this->version = $o->getVersion();
    }

    
    public function getListId() {
        return $this->listId;
    }

    public function getName() {
        return $this->name;
    }

    public function getClass() {
        return $this->class;
    }

    public function setListId($listId) {
        $this->listId = $listId;
        return $this;
    }

    public function setName($name) {
        $this->name = $name;
        return $this;
    }

    public function setClass($class) {
        $this->class = $class;
        return $this;
    }
    
    public function getVersion() {
        return $this->version;
    }

    public function setVersion($version) {
        $this->version = $version;
        return $this;
    }

    public function getDisplayName() {
        return $this->displayName;
    }

    public function setDisplayName($displayName) {
        $this->displayName = $displayName;
        $this->name = str_replace(" ", "_", $displayName);
        return $this;
    }
}
