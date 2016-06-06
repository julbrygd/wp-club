<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Club\ListModule;



/**
 * Description of List
 *
 * @author stephan
 */
abstract class Type {
    
    /**
     *
     * @var string 
     */
    private $name;
    
    
    public abstract function createTable();
    
    /**
     * 
     * @return string Header rows
     */
    public abstract function tableHeader();
    
    public abstract function init();
    
    public function __construct() {
        $this->init();
    }

    public function getName() {
        return $this->name;
    }

    public function setName($name) {
        $this->name = $name;
        return $this;
    }

} 