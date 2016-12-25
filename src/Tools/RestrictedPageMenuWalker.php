<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Club\Tools;

/**
 * Description of newPHPClass
 *
 * @author stephan
 */
class RestrictedPageMenuWalker extends \Walker_Nav_Menu {

    /**
     * @var boolean
     */
    protected $in_private = false;

    /**
     * @var integer
     */
    protected $private_depth = 0;

    /**
     * @var Walker_Nav_Menu
     */
    protected $original_walker;

    /**
     * @var array
     */
    protected $track_ids = array();

    /**
     * Constructor.
     *
     * @param Walker_Nav_Menu $original_walker
     */
    public function __construct($original_walker = null) {
        if (empty($original_walker)) {
            $original_walker = new \Walker_Nav_Menu;
        }

        $this->original_walker = $original_walker;
        $this->sync_vars_with_original_walker();
    }

    /**
     * Sync public variables to the original walker.
     */
    public function sync_vars_with_original_walker() {
        $this->tree_type = $this->original_walker->tree_type;
        $this->db_fields = $this->original_walker->db_fields;
        $this->max_pages = $this->original_walker->max_pages;
    }

    /**
     * {@inheritdoc}
     */
    public function display_element($element, &$children_elements, $max_depth, $depth, $args, &$output) {
        // If it's a nav menu item, check permissions against the post/page that the
        // nav menu item points to, otherwise check the element itself.
        if (isset($element->post_type) && $element->post_type == 'nav_menu_item') {
            $object_id = $element->object_id;
        } else {
            $object_id = $element->{$this->db_fields['id']};
        }
        $allowedRoles = get_post_meta($object_id, MetaBoxes\RestrictedPagesBox::getKeyAllowedRoles(), true);
        $render = false;
        if (is_array($allowedRoles) && count($allowedRoles) >= 1) {
            $user_roles = wp_get_current_user()->roles;
            $ok = false;
            foreach ($user_roles as $role) {
                if (in_array($role, $allowedRoles)) {
                    $ok = true;
                }
            }
            if ($ok) {
                $render = true;
            }
        } else {
            $render = TRUE;
        }
        if ($render) {
            $this->original_walker->display_element($element, $children_elements, $max_depth, $depth, $args, $output);
        }
    }

    /**
     * Divert all other method calls directly to the original walker class.
     *
     * @param  string $method
     * @param  array  $arguments
     * @return mixed
     */
    public function __call($method, $arguments) {
        return call_user_func_array(array($this->original_walker, $method), $arguments);
    }

    /**
     * Divert all property access requests to the original walker class.
     *
     * @param  string $var
     * @return mixed
     */
    public function __get($var) {
        return $this->original_walker->{$var};
    }

    /**
     * Divert all property access requests to the original walker class.
     *
     * @param  string $var
     * @return mixed
     */
    public function __set($var, $value) {
        $this->original_walker->{$var} = $value;
    }

    /**
     * Divert all property access requests to the original walker class.
     *
     * @param  string $var
     * @return mixed
     */
    public function __isset($var) {
        return isset($this->original_walker->{$var});
    }

    /**
     * Recursively removes any child nav menu item that the user doesn't have the
     * correct permission to view.
     *
     * @param integer $id
     * @param array   $children
     */
    protected function filter_children($id, &$children_elements) {
        if (isset($children_elements[$id])) {
            foreach ($children_elements[$id] as $index => $element) {
                // If it's a nav menu item, check permissions against the post/page that the
                // nav menu item points to, otherwise check the element itself.
                if (isset($element->post_type) && $element->post_type == 'nav_menu_item') {
                    $object_id = $element->object_id;
                } else {
                    $object_id = $element->{$this->db_fields['id']};
                }

                // Make sure the page isn't an orphan (e.g. this is a page walker and the
                // element was a child of a members only page that got removed, so we
                // shouldn't show it)
                if (WordPressAccessControl::check_conditions($object_id) || get_option('wpac_show_in_menus', 'with_access') == 'always') {
                    if (in_array($element->{$this->db_fields['parent']}, $this->track_ids) || $element->{$this->db_fields['parent']} == 0) {
                        $this->track_ids[] = $element->{$this->db_fields['id']};
                        $this->filter_children($element->{$this->db_fields['id']}, $children_elements);
                    } else {
                        $this->truncate_children($element->{$this->db_fields['id']}, $children_elements);
                        unset($children_elements[$id][$index]);
                    }
                } else {
                    $this->truncate_children($element->{$this->db_fields['id']}, $children_elements);
                    unset($children_elements[$id][$index]);
                }
            }
        }
    }

    /**
     * Recursively remote children of a menu item that has been removed.
     *
     * @param integer $id
     * @param array   $children
     */
    protected function truncate_children($id, &$children) {
        if (isset($children[$id])) {
            foreach ($children[$id] as $child) {
                $this->truncate_children($child->{$this->db_fields['id']}, $children);
            }

            $children[$id] = array();
        }
    }

}
