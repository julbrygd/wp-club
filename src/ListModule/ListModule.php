<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
namespace Club\ListModule;


use \Club\Modules\Page;

/**
 * Description of List
 *
 * @author stephan
 */
class ListModule extends \Club\Admin\Module  {
    
    /**
     *
     * @var \Club\Club 
     */
    private $club;
    
    public function init() {
        
    }

    public function public_init() {
        
    }

    public function setClub($club) {
        $this->club = $club;
    }
    
    public function registerPages() {
        $this->registerPage(new Page("club_list", "", null, $this, "admin/list.php"));
        $this->registerPage(new Page("club_list", "edit", null, $this, "admin/list/edit.php"));
    }

}
