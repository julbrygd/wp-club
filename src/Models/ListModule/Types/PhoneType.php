<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Club\Models\ListModule\Types;

/**
 * Description of PhoneType
 *
 * @author stephan
 */
class PhoneType extends EnumType{
    protected $name = 'PhoneType';
    protected $values = array('home', 'mobile', 'work');
}
