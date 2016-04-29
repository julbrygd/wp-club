<?php

namespace Club;

class Modules implements \JsonSerializable {

    /**
     *
     * @var type array
     */
    private $modules;

    /**
     *
     * @var type array
     */
    private $activated;
    private static $INSTANCE;

    public static function getInstance() {
        if (self::$INSTANCE == NULL) {
            self::$INSTANCE = new self();
        }
        return self::$INSTANCE;
    }

    public function loadDescriptors() {
        $club = \Club\Club::getInstance();
        $path = $club->getBasePath() . "/data/modules/desc/";
        foreach (scandir($path) as $file) {
            if (substr($file, -4) == "json") {
                $array = json_decode(file_get_contents($path . $file), true);
                $desc = new \Club\Modules\ModuleDescriptor(
                        $array["name"], $array["class"], $array["caps"], $array["description"], $array["version"]
                );
                $this->addModule($desc);
            }
        }
    }

    /**
     * 
     * @param type string $json
     * @return \Club\Modules
     */
    public static function fromJson($json) {
        $data = json_decode($json, true);
        $obj = new Modules();
        $obj->setActivated($data["activated"]);
        $modules = array();
        foreach ($data["modules"] as $mod) {
            if(!in_array("version", $mod)){
                $mod["version"] = "1.0";
            }
            $modules[$mod["name"]] = new Modules\ModuleDescriptor(
                    $mod["name"], $mod["class"], $mod["caps"], $mod["description"], $mod["version"]
            );
        }
        $obj->setModules($modules);
        return $obj;
    }

    function __construct() {
        $this->modules = array();
        $this->activated = array();
    }

    public function addModule($descriptor) {
        if (!array_key_exists($descriptor->getName(), $this->modules)) {
            $this->modules[$descriptor->getName()] = $descriptor;
        }
        return $this;
    }

    public function getModule($name) {
        if (array_key_exists($name, $this->modules)) {
            return $this->modules[$name];
        }
        return NULL;
    }
    
    public function runModules() {
        foreach($this->activated as $name){
            $this->modules[$name]->getInstance();
        }
    }
    
    public function runPublicModules() {
        foreach($this->activated as $name){
            $this->modules[$name]->getInstance()->public_init();
        }
    }
    
    public function registerMenus() {
        foreach($this->activated as $name){
            $this->modules[$name]->getInstance()->addMenu();
        }
    }

    public function activateModule($name) {
        if (!in_array($name, $this->activated)) {
            $this->activated[] = $name;
        }
        return $this;
    }

    public function deactivateModule($name) {
        if (in_array($name, $this->activated)) {
            $this->activated = array_diff(
                    $this->activated, array($name)
            );
        }
        return $this;
    }

    public function getModules() {
        return $this->modules;
    }

    public function getActivated() {
        return $this->activated;
    }

    public function setModules($modules) {
        $this->modules = $modules;
        return $this;
    }

    public function setActivated($activated) {
        $this->activated = $activated;
        return $this;
    }

    public function jsonSerialize() {
        return array(
            "modules" => $this->modules,
            "activated" => $this->activated
        );
    }

}
