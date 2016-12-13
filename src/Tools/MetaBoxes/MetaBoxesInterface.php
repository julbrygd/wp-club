<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Club\Tools\MetaBoxes;

/**
 *
 * @author stephan
 */
interface MetaBoxesInterface {
    public function register();
    public function form($post);
    public function save($post_id, $post);
    public function getName();
}
