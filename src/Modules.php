<?php

namespace Club;

class Modules implements \JsonSerializable {

    /**
     *
     * @var type array
     */
    private $modules;
    private $protected = array("modules", "settings", "role");

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
                        $array
                );
                $this->addModule($desc, TRUE);
            }
        }
    }

    public function isProtected($name) {
        return in_array($name, $this->protected);
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
            if (!array_key_exists("version", $mod)) {
                $mod["version"] = "1.0";
            }
            if (!array_key_exists("settings", $mod)) {
                $mod["settings"] = array();
            }
            if (!array_key_exists("menu", $mod)) {
                $mod["menu"] = array();
            }
            $modules[$mod["name"]] = new Modules\ModuleDescriptor(
                    $mod
            );
        }
        $obj->setModules($modules);
        return $obj;
    }

    function __construct() {
        $this->modules = array();
        $this->activated = array();
    }

    public function addModule($descriptor, $all = FALSE) {
        if ($all) {
            $this->modules[$descriptor->getName()] = $descriptor;
        } else if (!array_key_exists($descriptor->getName(), $this->modules)) {
            $this->modules[$descriptor->getName()] = $descriptor;
        }
        return $this;
    }

    public function getSettings() {
        $ret = array();
        foreach ($this->activated as $name) {
            $mod = $this->getModule($name);
            $settings = $mod->getSettings();
            if (count($settings) >= 1) {
                $ret = array_merge($settings, $ret);
            }
        }
        return $ret;
    }

    /**
     * 
     * @param type $name
     * @return \Club\Modules\ModuleDescriptor
     */
    public function getModule($name) {
        if (array_key_exists($name, $this->modules)) {
            return $this->modules[$name];
        }
        return NULL;
    }

    /**
     * 
     * @param type $name
     * @return \Club\Admin\Module
     */
    public function getModuleInstance($name) {
        if (array_key_exists($name, $this->modules)) {
            return $this->modules[$name]->getInstance();
        }
        return NULL;
    }

    public function runModules() {
        foreach ($this->activated as $name) {
            $this->modules[$name]->getInstance();
            $this->modules[$name]->getInstance()->registerPages();
            $this->registerPostTypes($this->modules[$name]);
        }
    }
    
    public function registerWidgets() {
        foreach ($this->activated as $name) {
            foreach ($this->modules[$name]->getWidgets() as $widget_class){
                register_widget($widget_class);
            }
        }
    }

    public function addModuleScripts() {
        foreach ($this->activated as $name) {
            $this->modules[$name]->getInstance()->add_scripts();
        }
    }

    public function runPublicModules() {
        foreach ($this->activated as $name) {
            $this->modules[$name]->getInstance()->public_init();
            $this->registerPostTypes($this->modules[$name]);
        }
    }

    public function registerMenus() {
        $club = \Club\Club::getInstance();
        $baseDir = $club->plugin_base;
        foreach ($this->activated as $name) {
            $menus = $this->getModule($name)->getMenu();
            $this->registerJsonMenu($menus, $baseDir, $club, $name);
        }
    }

    private function registerJsonMenu($menus, $baseDir, $club, $name, $parrent = null) {
        foreach ($menus as $menu) {
            $slug = $menu["slug"];
            $title = $menu["title"];
            $caps = $menu["caps"];
            $show = $menu["show"];
            $file = $baseDir . "/" . $menu["file"];
            if (file_exists($file)) {
                $club->add_menu_slug($name, $title, $caps, $slug, $file, !$show, $parrent);
            }
        }
    }

    public function activateModule($name) {
        if (!in_array($name, $this->activated)) {
            $this->activated[] = $name;
            $module = $this->getModule($name);
            $caps = $module->getCaps();
            $module->getInstance()->activate();
            $knownCaps = get_option('club_caps', array());
            $club_admin = get_role('club_admin');
            $admin = get_role('administrator');
            foreach ($caps as $cap) {
                if (!in_array($cap, $knownCaps)) {
                    $admin->add_cap($cap);
                    $club_admin->add_cap($cap);
                    $knownCaps[] = $cap;
                }
            }
            update_option('club_caps', $knownCaps);
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

    /**
     * 
     * @param \Club\Modules\ModuleDescriptor $module
     */
    public function registerPostTypes($module) {
        $types = $module->getPostTypes();
        if (count($types) > 0) {
            foreach ($types as $type) {
                $key = $type["key"];
                unset($type["key"]);
                register_post_type($key, $type);
                add_rewrite_endpoint($key, EP_ALL);
            }
        }
    }

}
