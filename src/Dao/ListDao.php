<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Club\Dao;

use Club\Models\ListModule\Lists;

/**
 * Description of ListDao
 *
 * @author stephan
 */
class ListDao extends Dao{
    
    private $repo;
    private $myClass;
    
    public function __construct($em) {
        parent::__construct($em);
        $this->myClass = '\Club\Models\ListModule\Lists';
        $this->repo = $this->em->getRepository($this->myClass);
    }

    
    public function getDoaName() {
        return "list";
    }
    
    public function findAll() {
       return $this->repo->findBy(array());
    }
}
