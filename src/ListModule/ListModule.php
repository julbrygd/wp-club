<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Club\ListModule;

use \Club\Modules\Page;
use Doctrine\ORM\Tools\SchemaTool;
use Club\Models\ListModule\Lists;

/**
 * Description of List
 *
 * @author stephan
 */
class ListModule extends \Club\Admin\Module {

    /**
     *
     * @var \Club\Club
     */
    private $club;
    private $types = array();
    private $dbVersion = 4;

    /**
     *
     * @var Doctrine\ORM\EntityManager
     */
    private $em;

    public function init() {
        $typePath = $this->club->getBasePath() . "/data/modules/list/fields/";
        foreach (scandir($typePath) as $file) {
            if (substr($file, -4) == "json") {
                $array = json_decode(file_get_contents($typePath . $file), true);
                $this->types[$array["name"]] = $array;
            }
        }
        $this->em = $this->club->getEm();
        $version = $this->club->getOption("list_version", 0);
        if ($version < $this->dbVersion) {
            $this->updateDoctrine();
            $this->club->setOption("list_version", $this->dbVersion);
        }
        
        \Club\DaoRegistry::getInstance()->registerDao("list", '\Club\Dao\ListDao');

        $this->registerTypes();

        $this->club->registerAdminAjax("list_new", array(&$this, 'newList'));
    }

    public function activate() {
        $this->updateDoctrine();
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

    private function updateDoctrine() {
        $em = $this->club->getEm();
        $classes = array(
            $em->getClassMetadata('\Club\Models\ListModule\Lists'),
            $em->getClassMetadata('\Club\Models\ListModule\Lists\Plz'),
        );
        $s = new SchemaTool($em);
        $s->updateSchema($classes, true);
    }

    private function registerTypes() {
        \Doctrine\DBAL\Types\Type::addType("PhoneType", '\Club\Models\ListModule\Types\PhoneType');
    }

    public function getListClasses() {
        $finder = new \Symfony\Component\Finder\Finder();
        $iter = new \hanneskod\classtools\Iterator\ClassIterator($finder->in(\Club\Club::getInstance()->plugin_base . '/src'));
        $iter->enableAutoloading();
        $ret = array();
        foreach ($iter->type('\Club\ListModule\Lists\ListDescriptor')->where('isInstantiable') as $class) {
            $class = $class->getName();
            $ret[] = $class::getDescriptor($class);
        }
        return $ret;
    }

    public function newList() {
        if (array_key_exists("action", $_POST) && $_POST['action'] == 'club_list_new') {
            $name = "";
            $class = "";
            $error = array();
            if (array_key_exists("name", $_POST)) {
                $name = htmlspecialchars($_POST["name"]);
            } else {
                $error[] = "Bitte Namen eingeben";
            }
            if (array_key_exists("class", $_POST)) {
                $class = str_replace("\\\\", "\\", htmlspecialchars($_POST["class"]));
            } else {
                $error[] = "Bitte Typ auswählen";
            }
            if (count($error) >= 1) {
                echo json_encode(array(
                    "status" => "error",
                    "error" => $error
                ));
                wp_die();
            }
            $obj = new $class($this->em);

            $s = new SchemaTool($this->em);
            $s->updateSchema($obj->getMetaData(), true);
            $list = new Lists(str_replace(" ", "_", $name), $name, $class);
            $this->em->persist($list);
            $this->em->flush();
            echo json_encode(array("status" => "ok"));
            wp_die();
        }
    }

}
