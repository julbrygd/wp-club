<?php

namespace Club\Modules;

class ModuleManagement extends \Club\Admin\Module {
    /**
     *
     * @var type \Club
     */
    private $club;

    public function init() {   
        $this->club->registerAdminAjax('toggle_module', array(&$this, 'toggle'));
    }

    public function setClub($club) {
        $this->club = $club;
    }

    public function addMenu() {
        $this->club->add_menu("Club Module", 'club_admin', "club/admin/modules.php");
    }
    
    public function toggle() {
        if (array_key_exists("action", $_POST) && $_POST['action'] == 'club_toggle_module') {
            $action = $_POST['modules']['do'];
            $name = $_POST['modules']['module'];
            if($action == 'enable'){
                $this->club->getModules()->activateModule($name);
            } else {
                $this->club->getModules()->deactivateModule($name);
            }
            $this->club->saveModules();
            echo "ok";
        }
        wp_die();
    }

    public function public_init() {
        
    }

}
