<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Club\ListModule\Types;

use Club\ListModule\Types\Options;

/**
 * Description of AbstractType
 *
 * @author stephan
 */
abstract class AbstractType {
    
    /**
     * The name of the Field in the Database
     *
     * @var string name 
     */
    protected $name;
    
    /**
     * The name of the Field shown on the Form
     *
     * @var string $displayName 
     */
    protected $displayName;
    
    /**
     * The Name of the List
     * 
     * @var string 
     */
    protected $list;
    
    /**
     *
     * @var array 
     */
    protected $options;




    /**
     * Creates a new Type
     * @param string $name
     * @param string $displayName
     * @param string $list
     */
    function __construct($name, $displayName, $list) {
        $this->name = $name;
        $this->displayName = $displayName;
        $this->list = $list;
    }

    
    /**
     * Returns the SQL statement for creating the Field
     * 
     * @return string The sql Statement
     */
    public abstract function getCreateStatement();
    
    /**
     * Returns the SQL statement for adding the Field
     * 
     * @return string The sql Statement
     */
    public abstract function getAddStatement();
    
    /**
     * Returns the SQL statement for Edit the Field
     * 
     * @return string The sql Statement
     */
    public abstract function getEditStatement();
    
    /**
     * Returns the SQL statement for delting the Field
     * 
     * @return string The sql Statement
     */
    public abstract function getDeleteStatement();
            
    function getName() {
        return $this->name;
    }

    function getDisplayName() {
        return $this->displayName;
    }

    function getList() {
        return $this->list;
    }

    function setName(String $name) {
        $this->name = $name;
        return $this;
    }

    function setDisplayName(String $displayName) {
        $this->displayName = $displayName;
        return $this;
    }

    function setList(String $list) {
        $this->list = $list;
        return $this;
    }


}
