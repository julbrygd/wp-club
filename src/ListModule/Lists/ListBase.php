<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Club\ListModule\Lists;

use Doctrine\ORM\Mapping\MappedSuperclass;
use Doctrine\ORM\Mapping\Column;
/**
 * Description of ListBase
 * @MappedSuperclass
 * @author stephan
 */
abstract class ListBase {
    /**
     * @Column(type = "string", length = 50)
     */
    protected $listName;
    
    public function getListName() {
        return $this->listName;
    }

    public function setListName($listName) {
        $this->listName = $listName;
        return $this;
    }
    
    public abstract function getVersion();


}
