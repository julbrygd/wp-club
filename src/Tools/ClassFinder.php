<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Club\Tools;

/**
 * Description of ClassFinder
 *
 * @author stephan
 */
class ClassFinder {

    public static function findSubclass($class) {
        $finder = new \Symfony\Component\Finder\Finder();
        $iter = new \hanneskod\classtools\Iterator\ClassIterator($finder->in(\Club\Club::getInstance()->plugin_base . '/src'));
        $iter->enableAutoloading();
        $ret = array();
        foreach ($iter->type($class)->where('isInstantiable') as $class) {
            $ret[] = $class->getName();
        }
        return $ret;
    }

}
