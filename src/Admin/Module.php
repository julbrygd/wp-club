<?php

namespace Club\Admin;

abstract class Module {

    public abstract function setClub($club);

    public abstract function init();

    public abstract function public_init();

    public abstract function addMenu();

    public function install() {
        
    }

    public function add_scripts() {
        
    }

    public function registerPages() {
        
    }

    /**
     * 
     * @param \Club\Modules\Page $page
     */
    protected function registerPage($page) {
        $club = \Club\Club::getInstance();
        $club->registerPage($page);
    }

}
