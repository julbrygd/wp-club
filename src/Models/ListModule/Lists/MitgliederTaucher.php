<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Club\Models\ListModule\Lists;

use Club\ListModule\Lists\ListBase;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\ManyToMany;
use Doctrine\ORM\Mapping\JoinTable;
use Doctrine\ORM\Mapping\JoinColumn;
/**
 * Description of MitgliederTaucher
 * @Entity
 * @author stephan
 */
class MitgliederTaucher extends ListBase{
    /**
     * @Column(type = "integer")
     * @Id
     * @GeneratedValue
     * @var integer 
     */
    protected $mitId;
    
    /**
     * @Column(type = "string", length = 50)
     * @var string 
     */
    protected $preName;
    
    /**
     * @Column(type = "string", length = 50)
     * @var string 
     */
    protected $sureName;
    
    /**
     * @Column(type = "string", length = 200)
     * @var string 
     */
    protected $street;
    
    /**
     * Many User have Many Phonenumbers.
     * @ManyToMany(targetEntity="PhoneNumber")
     * @JoinTable(name="mitgliederTaucher_phonenumbers",
     *      joinColumns={@JoinColumn(name="mitId", referencedColumnName="mitId")},
     *      inverseJoinColumns={@JoinColumn(name="phoneId", referencedColumnName="phoneId", unique=true)}
     *      )
     */
    protected $tel;
            
    /**
     * @Column(type = "string", length = 50)
     * @var string 
     */
    protected $email;
    
    /**
     * @Column()
     * @var type 
     */
    protected $birthDate;
    /**
     * Many User have one PLZ.
     * @ManyToMany(targetEntity="Plz")
     * @JoinTable(name="mitgliederTaucher_Plz",
     *      joinColumns={@JoinColumn(name="mitId", referencedColumnName="mitId")},
     *      inverseJoinColumns={@JoinColumn(name="plzId", referencedColumnName="plzId", unique=true)}
     *      )
     */
    protected $plz;
    
    /**
     * Many User have one Brevet.
     * @ManyToMany(targetEntity="TaucherBrevets")
     * @JoinTable(name="mitgliederTaucher_brevets",
     *      joinColumns={@JoinColumn(name="mitId", referencedColumnName="mitId")},
     *      inverseJoinColumns={@JoinColumn(name="bid", referencedColumnName="bid", unique=true)}
     *      )
     */
    protected $brevet;
    
    /**
     * @Column(type = "string", length = 50)
     * @var string 
     */
    protected $wpUser;
    
    public function __construct() {
        $this->plz = new \Doctrine\Common\Collections\ArrayCollection();
        $this->brevet = new \Doctrine\Common\Collections\ArrayCollection();
        $this->tel = new \Doctrine\Common\Collections\ArrayCollection();
    }
    
    public function getMitId() {
        return $this->mitId;
    }

    public function getPreName() {
        return $this->preName;
    }

    public function getSureName() {
        return $this->sureName;
    }

    public function getStreet() {
        return $this->street;
    }

    public function getTel() {
        return $this->tel;
    }

    public function getEmail() {
        return $this->email;
    }

    public function getBirthDate() {
        return $this->birthDate;
    }

    public function getPlz() {
        return $this->plz;
    }

    public function getBrevet() {
        return $this->brevet;
    }

    public function getWpUser() {
        return $this->wpUser;
    }

    public function setMitId($mitId) {
        $this->mitId = $mitId;
        return $this;
    }

    public function setPreName($preName) {
        $this->preName = $preName;
        return $this;
    }

    public function setSureName($sureName) {
        $this->sureName = $sureName;
        return $this;
    }

    public function setStreet($street) {
        $this->street = $street;
        return $this;
    }

    public function setTel($tel) {
        $this->tel = $tel;
        return $this;
    }

    public function setEmail($email) {
        $this->email = $email;
        return $this;
    }

    public function setBirthDate(type $birthDate) {
        $this->birthDate = $birthDate;
        return $this;
    }

    public function setPlz($plz) {
        $this->plz = $plz;
        return $this;
    }

    public function setBrevet($brevet) {
        $this->brevet = $brevet;
        return $this;
    }

    public function setWpUser($wpUser) {
        $this->wpUser = $wpUser;
        return $this;
    }

    public function getVersion() {
        return 1;
    }

}
