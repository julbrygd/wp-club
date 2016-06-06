<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Club\ListModule\Types;

use Club\ListModule\Type;

/**
 * Description of Address
 *
 * @author stephan
 */
class TauchClubAddressen extends Type{
    
    public function createTable() {
        
    }

    public function init() {
        $this->setName("Tauchclub Address List");
    }

    public function tableHeader() {
        return file_get_contents(dirname(__FILE__).DIRECTORY_SEPARATOR."TauchClubAddressen".DIRECTORY_SEPARATOR."header.html");
    }

}
