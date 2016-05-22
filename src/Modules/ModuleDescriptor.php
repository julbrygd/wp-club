<?php

namespace Club\Modules;

class ModuleDescriptor implements \JsonSerializable{

    private $name;
    private $class;
    private $caps;
    private $description;
    private $instance;
    private $version;
    private $settings;
    private $menu;
    
     public function __construct($name, $class, $caps, $description, $version, $settings, $menu) {
        $this->name = $name;
        $this->class = $class;
        $this->caps = $caps;
        $this->description = $description;
        $this->version = $version;
        $this->settings = $settings;
        $this->menu = $menu;
    }
    
    public function getInstance(){
        if($this->instance == null){
            $this->instance = new $this->class;
            $this->instance->setClub(\Club\Club::getInstance());
            $this->instance->init();
        }
        return $this->instance;
    }

    function getDescription() {
        return $this->description;
    }

    function setDescription($description) {
        $this->description = $description;
        return $this;
    }

    function getName() {
        return $this->name;
    }

    function setName($name) {
        $this->name = $name;
        return $this;
    }

    function getClass() {
        return $this->class;
    }

    function getCaps() {
        return $this->caps;
    }

    function setClass($class) {
        $this->class = $class;
        return $this;
    }

    function setCaps($caps) {
        $this->caps = $caps;
        return $this;
    }
    public function getVersion() {
        return $this->version;
    }

    public function getSettings() {
        return $this->settings;
    }

    public function setVersion($version) {
        $this->version = $version;
        return $this;
    }
    
    public function getMenu() {
        return $this->menu;
    }

    public function setMenu($menu) {
        $this->menu = $menu;
        return $this;
    }

    
    public function setSettings($settings) {
        $this->settings = $settings;
        return $this;
    }

        public function jsonSerialize() {
        return array(
            "name" => $this->name,
            "class" => $this->class,
            "caps" => $this->caps,
            "description" => $this->description,
            "version" => $this->version,
            "settings" => $this->settings,
            "menu" => $this->menu
        );
    }

}
