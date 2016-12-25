<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Club\Tools;

/**
 * Description of RestrictAccess
 *
 * @author stephan
 */
class RestrictAccess implements WordpressActionsInterface {

    public function init() {
        add_action('pre_get_posts', array(&$this, 'wp_query_allow_filters'));
        add_action('template_redirect', array(&$this, 'template_redirect'));
        add_action('get_pages', array(&$this, 'skip_undisclosed_items'), 10, 1);
        //add_filter('wp_nav_menu_args', array(&$this, 'wp_nav_menu_members_only'), 99999);
        add_filter('wp_nav_menu_args', array(&$this, 'wp_nav_menu_args'), 99999);
    }

    public function wp_query_allow_filters($wp_query) {
        $wp_query->set('suppress_filters', false);
    }

    public function template_redirect() {
        global $wp_query;
        if (isset($wp_query) && is_singular() && $post = get_post()) {
            $allowedRoles = get_post_meta($post->ID, 'club_restricted_allowed_roles');
            $user = wp_get_current_user();
            $redirect = true;
            foreach ($user->roles as $role) {
                if (in_array($role, $allowedRoles)) {
                    $redirect = false;
                }
            }
            if ($redirect) {
                echo "not allowed";
                exit();
            }
        }
    }

    public function skip_undisclosed_items($items) {

        $ret = $items;

        return $ret;
    }

    public function wp_nav_menu_args($args) {
        $args['walker'] = new RestrictedPageMenuWalker($args['walker']);
        return $args;
    }

}
