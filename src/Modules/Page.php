<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Club\Modules;

/**
 * Description of Page
 *
 * @author stephan
 */
class Page {

    /**
     *
     * @var string  
     */
    private $slug;

    /**
     *
     * @var string 
     */
    private $view;

    /**
     *
     * @var callable 
     */
    private $controller;

    /**
     *
     * @var Club\Admin\Modul 
     */
    private $module;

    /**
     *
     * @var string 
     */
    private $file;

    /**
     * 
     * @param string $slug
     * @param string $view
     * @param callable $controller
     * @param Club\Admin\Modul $module
     * @param string $file 
     */
    public function __construct($slug, $view, $controller, $module, $file) {
        $this->slug = $slug;
        $this->view = $view;
        $this->controller = $controller;
        $this->module = $module;
        $this->file = $file;
    }

    /**
     * 
     * @return string
     */
    public function getSlug() {
        return $this->slug;
    }

    /**
     * 
     * @return string
     */
    public function getView() {
        return $this->view;
    }

    /**
     * 
     * @return callable
     */
    public function getController() {
        return $this->controller;
    }

    /**
     * 
     * @return Club\Admin\Modul 
     */
    public function getModule() {
        return $this->module;
    }

    /**
     * 
     * @param type $slug
     * @return \Club\Modules\Page
     */
    public function setSlug($slug) {
        $this->slug = $slug;
        return $this;
    }

    /**
     * 
     * @param type $view
     * @return \Club\Modules\Page
     */
    public function setView($view) {
        $this->view = $view;
        return $this;
    }

    /**
     * 
     * @param type $controller
     * @return \Club\Modules\Page
     */
    public function setController($controller) {
        $this->controller = $controller;
        return $this;
    }

    /**
     * 
     * @param type $module
     * @return \Club\Modules\Page
     */
    public function setModule($module) {
        $this->module = $module;
        return $this;
    }

    /**
     * 
     * @return string
     */
    public function getFile() {
        return $this->file;
    }

    /**
     * 
     * @param type $file
     * @return string
     */
    public function setFile($file) {
        $this->file = $file;
        return $this;
    }

    /**
     * 
     * @return string
     */
    public function getMd5() {
        return self::calcHash($this->slug, $this->view);
    }
    
    /**
     * 
     * @param string $slug
     * @param string $view
     * @return string
     */
    public static function calcHash($slug, $view){
        return md5($slug . "_view_" . $view);
    }

}
