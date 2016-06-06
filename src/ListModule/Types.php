<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Club\ListModule;

/**
 * Description of Types
 *
 * @author stephan
 */
class Types {
    private static $INSTANCE;
    
    private $types;
    
    /**
     * 
     * @return Club\ListModule\Types
     */
    public static function getInstance(){
        if(self::$INSTANCE == null){
            self::$INSTANCE = new self();
        }
        return self::$INSTANCE;
    }
    
    private function __construct() {
        $this->types = array(
            new Types\TauchClubAddressen()
        );
    }

    /**
     * 
     * @return array
     */
    function getTypes() {
        return $this->types;
    }


}
