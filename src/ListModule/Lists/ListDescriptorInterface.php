<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Club\ListModule\Lists;

/**
 * Description of ListDescriptorInterface
 *
 * @author stephan
 */
interface ListDescriptorInterface {
    static function getDescriptor($class);

    /**
     * @return string Description
     */
    static function getClass();
    static function getName();
}
