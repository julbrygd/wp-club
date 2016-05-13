<?php

namespace Club\Admin;

abstract class Module {

    public abstract function setClub($club);

    public abstract function init();
    
    public abstract function public_init();

    public abstract function addMenu();

    public function install() {
        
    }
    
    public function add_scripts(){}

}
