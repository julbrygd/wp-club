<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Club\Admin;

use \Club\Admin\Module;

class Roles extends Module {

    private $_club;

    public function setClub($club) {
        $this->_club = $club;
    }

    public function init() {
        $this->_club->registerAdminAjax('save_all_role', array(&$this, 'saveAll'));
        $this->_club->registerAdminAjax('save_new_role', array(&$this, 'saveNew'));
        $this->_club->registerAdminAjax('delete_role', array(&$this, 'delete'));
    }

    public function saveAll() {
        if (array_key_exists("action", $_POST) && $_POST['action'] == 'club_save_all_role') {
            foreach ($_POST['roles'] as $rolename => $values) {
                $role = get_role($rolename);
                foreach ($values as $cap => $string) {
                    $has = filter_var($string, FILTER_VALIDATE_BOOLEAN);
                    if ($has) {
                        if (!$role->has_cap($cap)) {
                            $role->add_cap($cap);
                        }
                    } else {
                        if ($role->has_cap($cap)) {
                            $role->remove_cap($cap);
                        }
                    }
                }
            }
        }
        echo "ok";
        wp_die();
    }

    public function saveNew() {
        global $wp_roles;
        if (array_key_exists("action", $_POST) && $_POST['action'] == 'club_save_new_role') {
            $parrent = get_role($_POST["parrent"]);
            $role = $wp_roles->add_role('club_' . $_POST['name'], 'Club ' . $_POST['displayName']);
            foreach ($parrent->capabilities as $cap => $has) {
                if ($has == 1) {
                    $role->add_cap($cap);
                }
            }
            echo "ok";
        }
        wp_die();
    }

    public function delete() {
        global $wp_roles;
        if (array_key_exists("action", $_POST) && $_POST['action'] == 'club_delete_role') {
            $wp_roles->remove_role($_POST['key']);
            echo "ok";
        }
        wp_die();
    }

    public function public_init() {
        
    }

}
