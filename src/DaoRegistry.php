<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Club;

/**
 * Description of DaoRegistry
 *
 * @author stephan
 */
class DaoRegistry {
    private $daos;
    private $instances;
    
    private static $INSTANCE = null;
    
    public static function getInstance() {
        if(self::$INSTANCE == null){
            self::$INSTANCE = new self();
        }
        return self::$INSTANCE;
    }


    private function __construct() {
        $this->daos = array();
        $this->instances = array();
    }
    
    public function registerDao($name, $class){
        $this->daos[$name] = $class;
    }
    
    public function isRegistered($name) {
        return array_key_exists($name, $this->daos);
    }
    
    public function getDao($name, $em){
        if(!array_key_exists($name, $this->instances)){
            $this->instances[$name] = new $this->daos[$name]($em);
        }
        if(array_key_exists($name, $this->instances)){
            return $this->instances[$name];
        }
        return null;
    }

}
