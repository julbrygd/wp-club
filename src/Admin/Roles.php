<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Club\Admin;
use \Club\Admin\Module;

class Roles extends Module{
    
    private $_club;


    public function setClub($club) {
        $this->_club = $club;
    }

    public function init() {
        $this->_club->registerAdminAjax('save_all', array(&$this, 'saveAll'));
    }
    
    public function saveAll() {
        if(array_key_exists("action", $_POST) && $_POST['action'] == 'club_save_all') {
            foreach($_POST['roles'] as $rolename=>$values){
                $role = get_role($rolename);
                foreach ($values as $cap=>$string){
                    $has = filter_var($string, FILTER_VALIDATE_BOOLEAN);
                    if($has){
                        if(!$role->has_cap($cap)){
                            $role->add_cap($cap);
                        }
                    } else {
                        if($role->has_cap($cap)){
                            $role->remove_cap($cap);
                        }
                    }
                }
            }
        }
        echo "ok";
        wp_die();
    }

}