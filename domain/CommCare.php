<?php
/**
 * Encapsulated version of a Community Care Package entry.
 */
class CommCare {
    private $id;
    private $email;
    private $adultNames;
    private $childrenInfo;
    private $sportsFan;
    private $sportsInfo;
    private $sitDinner;
    private $fastFood;
    private $sweetTreat;
    private $faveSweet;
    private $faveSalt;
    private $faveCandy;
    private $faveCookie;
    private $forFun;
    private $warmAct;
    private $coldAct;
    private $notes;

    // Constructor
    public function __construct($id, $email, $adultNames, $childrenInfo,
     $sportsFan, $sportsInfo, $sitDinner, $fastFood, $sweetTreat, $faveSweet, 
     $faveSalt, $faveCandy, $faveCookie, $forFun, $warmAct, $coldAct, $notes) {

        $this->id = $id;
        $this->email = $email;
        $this->adultNames = $adultNames;
        $this->childrenInfo = $childrenInfo;
        $this->sportsFan = $sportsFan;
        $this->sportsInfo = $sportsInfo;
        $this->sitDinner = $sitDinner;
        $this->fastFood = $fastFood;
        $this->sweetTreat = $sweetTreat;
        $this->faveSweet = $faveSweet;
        $this->faveSalt = $faveSalt;
        $this->faveCandy = $faveCandy;
        $this->faveCookie = $faveCookie;
        $this->forFun = $forFun;
        $this->warmAct = $warmAct;
        $this->coldAct = $coldAct;
        $this->notes = $notes;
    }

    // Getters
     public function getId() {
        return $this->id;
    }

    public function getEmail() {
        return $this->email;
    }

    public function getAdultNames() {
        return $this->adultNames;
    }

    public function getChildrenInfo() {
        return $this->childrenInfo;
    }

    public function getSportsFan() {
        return $this->sportsFan;
    }

    public function getSportsInfo() {
        return $this->sportsInfo;
    }

    public function getSitDinner() {
        return $this->sitDinner;
    }

    public function getFastFood() {
        return $this->fastFood;
    }

    public function getSweetTreat() {
        return $this->sweetTreat;
    }

    public function getFaveSweet() {
        return $this->faveSweet;
    }

    public function getFaveSalt() {
        return $this->faveSalt;
    }

    public function getFaveCandy() {
        return $this->faveCandy;
    }

    public function getFaveCookie() {
        return $this->faveCookie;
    }

    public function getForFun() {
        return $this->forFun;
    }

    public function getWarmAct() {
        return $this->warmAct;
    }

    public function getColdAct() {
        return $this->coldAct;
    }

    public function getNotes() {
        return $this->notes;
    }
}