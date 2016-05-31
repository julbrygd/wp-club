<?php

namespace Club\Modules;

class ModuleDescriptor implements \JsonSerializable {

    /**
     *
     * @var string 
     */
    private $name;
    private $class;

    /**
     *
     * @var array 
     */
    private $caps;

    /**
     *
     * @var string
     */
    private $description;

    /**
     *
     * @var Club\Admin\Module 
     */
    private $instance;

    /**
     *
     * @var string 
     */
    private $version;

    /**
     *
     * @var array 
     */
    private $settings;

    /**
     *
     * @var array 
     */
    private $menu;

    /**
     * 
     * @var array
     */
    private $postTypes;

    /**
     * 
     * @param array $data
     */
    public function __construct($data) {

        $this->name = ModuleDescriptor::getData($data, "name", "");
        $this->class = ModuleDescriptor::getData($data, "class", "");
        $this->caps = ModuleDescriptor::getData($data, "caps", "");
        $this->description = ModuleDescriptor::getData($data, "description", "");
        $this->version = ModuleDescriptor::getData($data, "version", "1.0");
        $this->settings = ModuleDescriptor::getData($data, "settings", array());
        $this->menu = ModuleDescriptor::getData($data, "menu", array());
        $this->postTypes = ModuleDescriptor::getData($data, "posttype", array());
    }

    private static function getData($data, $key, $default) {
        if (array_key_exists($key, $data)) {
            return $data[$key];
        } else {
            return $default;
        }
    }

    public function getInstance() {
        if ($this->instance == null) {
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

    public function getPostTypes() {
        return $this->postTypes;
    }

    public function setPostTypes($postTypes) {
        $this->postTypes = $postTypes;
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
            "menu" => $this->menu,
            "posttype" => $this->postTypes,
        );
    }

}
