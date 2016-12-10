<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Club\ListModule\Lists;

/**
 * Description of ListDescriptor
 *
 * @author stephan
 */
abstract class ListDescriptor implements ListDescriptorInterface{

    /**
     *
     * @var \Doctrine\ORM\EntityManager 
     */
    protected $em;
    
    public function __construct(\Doctrine\ORM\EntityManager $em) {
        $this->em = $em;
    }

    /**
     * @return string Description
     */
    abstract function getFields();

    /**
     * @return string Description
     */
    abstract function getListTemplate();

    /**
     * @return string Description
     */
    abstract function getEditForm();
    
    public abstract function getMetaData();

    public function getListName() {
        return $this->listName;
    }
}
