<?php
/**
 * Encapsulated version of a Points Program entry.
 */
class PointsProg {
    private $id;
    private $name;
    private $address;
    private $freezer_meals;
    private $allergies;
    private $snacks;
    private $snack_notes;
    private $foodlion;
    private $giant;
    private $walmart;
    private $wegmans;
    private $sheetz;
    private $wawa;
    private $house_cleaning;
    private $lawn_care;
    private $AAA_membership;
    private $AAA_membership_name;
    private $AAA_membership_DOB;
    private $photography;
    private $house_projects;
    private $financial_relief;
    private $points_used;

    // Constructor
    public function __construct($id, $name, $address, $freezer_meals, $allergies, $snacks, $snack_notes, $foodlion, $giant, $walmart, $wegmans, $sheetz, $wawa, $house_cleaning, $lawn_care, $AAA_membership, $AAA_membership_name, $AAA_membership_DOB, $photography, $house_projects, $financial_relief, $points_used) {
        $this->id = $id;
        $this->name = $name;
        $this->address = $address;
        $this->freezer_meals = $freezer_meals;
        $this->allergies = $allergies;
        $this->snacks = $snacks;
        $this->snack_notes = $snack_notes;
        $this->foodlion = $foodlion;
        $this->giant = $giant;
        $this->walmart = $walmart;
        $this->wegmans = $wegmans;
        $this->sheetz = $sheetz;
        $this->wawa = $wawa;
        $this->house_cleaning = $house_cleaning;
        $this->lawn_care = $lawn_care;
        $this->AAA_membership = $AAA_membership;
        $this->AAA_membership_name = $AAA_membership_name;
        $this->AAA_membership_DOB = $AAA_membership_DOB;
        $this->photography = $photography;
        $this->house_projects = $house_projects;
        $this->financial_relief = $financial_relief;
        $this->points_used = $points_used;
    }

    // Getters
    public function getId() {
        return $this->id;
    }

    public function getName() {
        return $this->name;
    }

    public function getAddress() {
        return $this->address;
    }

    public function getFreezerMeals() {
        return $this->freezer_meals;
    }

    public function getAllergies() {
        return $this->allergies;
    }

    public function getSnacks() {
        return $this->snacks;
    }

    public function getSnackNotes() {
        return $this->snack_notes;
    }

    public function getFoodlion() {
        return $this->foodlion;
    }

    public function getGiant() {
        return $this->giant;
    }

    public function getWalmart() {
        return $this->walmart;
    }

    public function getWegmans() {
        return $this->wegmans;
    }

    public function getSheetz() {
        return $this->sheetz;
    }

    public function getWawa() {
        return $this->wawa;
    }

    public function getHouseCleaning() {
        return $this->house_cleaning;
    }

    public function getLawnCare() {
        return $this->lawn_care;
    }

    public function getAAAMembership() {
        return $this->AAA_membership;
    }

    public function getAAAMembershipName() {
        return $this->AAA_membership_name;
    }

    public function getAAAMembershipDOB() {
        return $this->AAA_membership_DOB;
    }

    public function getPhotography() {
        return $this->photography;
    }

    public function getHouseProjects() {
        return $this->house_projects;
    }

    public function getFinancialRelief() {
        return $this->financial_relief;
    }

    public function getPointsUsed() {
        return $this->points_used;
    }
}