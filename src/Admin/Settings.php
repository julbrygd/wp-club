<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Club\Admin;

use \Club\Admin\Module;

/**
 * Description of Settings
 *
 * @author stephan
 */
class Settings extends Module {
    private $_club;

    public function init() {
        
    }

    public function public_init() {
        
    }

    public function setClub($club) {
        $this->_club = $club;
    }

}
