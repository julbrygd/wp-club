<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Club\Tools\Types;

/**
 * Description of PostTypeManger
 *
 * @author stephan
 */
class PostTypeManger {
    public static function registerPostTypes() {
        foreach (\Club\Tools\ClassFinder::findSubclass('\Club\Tools\Types\PostTypes') as $class) {
            $o = new $class();
            $o->register();
        }
    }
    
    public static function registerMetaBoxes() {
        foreach (\Club\Tools\ClassFinder::findSubclass('\Club\Tools\MetaBoxes\MetaBoxesInterface') as $class) {
            $o = new $class();
            add_action( 'add_meta_boxes', array($o, 'register'));
            add_action( 'save_post', array($o, 'save'), 10, 2 );
        }
    }
}
