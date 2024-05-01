<?php
/*
 * Copyright 2013 by Allen Tucker. 
 * This program is part of RMHC-Homebase, which is free software.  It comes with 
 * absolutely no warranty. You can redistribute and/or modify it under the terms 
 * of the GNU General Public License as published by the Free Software Foundation
 * (see <http://www.gnu.org/licenses/ for more information).
 * 
 */

/*
 * Created on Mar 28, 2008
 * @author Oliver Radwan <oradwan@bowdoin.edu>, Sam Roberts, Allen Tucker
 * @version 3/28/2008, revised 7/1/2015
 */

$accessLevelsByRole = [
	//'volunteer' => 1,
	'admin' => 2,
	'superadmin' => 3
];

/*enum Status{
	case pending; //family waiting for admin approval
	case active; //family has been approved
	case inactive; //family was rejected
	case remission;
	case survivor;
	case stargazer;
}*/

class Person {
	private $id;         // id (unique key) = first_name . phone1
	private $start_date; // format: 99-03-12
	private $venue;      // portland or bangor
	private $first_name; // first name as a string
	private $last_name;  // last name as a string
	private $address;   // address - string
	private $city;    // city - string
	private $state;   // state - string
	private $zip;    // zip code - integer
  	private $profile_pic; // image link
	private $phone1;   // primary phone -- home, cell, or work
	private $phone1type; // home, cell, or work
	private $phone2;   // secondary phone -- home, cell, or work
	private $phone2type; // home, cell, or work
	//child dob
	private $birthday;     // format: 64-03-12 
	private $email;
	//parent contact name and number
	private $contact_name;   // emergency contact name
	private $contact_num;   // emergency cont. phone number
	private $relation;   // relation to emergency contact
	private $contact_time; //best time to contact volunteer
	private $cMethod;    // best contact method for volunteer (email, phone, text)
	private $type;       // array of "volunteer", "weekendmgr", "sub", "guestchef", "events", "projects", "manager"
	private $access_level;
	private $status;     // a person may be "active" or "inactive"
	private $availability; // array of day:hours:venue triples; e.g., Mon:9-12:bangor, Sat:afternoon:portland
	private $schedule;     // array of scheduled shift ids; e.g., 15-01-05:9-12:bangor
	private $hours;        // array of actual hours logged; e.g., 15-01-05:0930-1300:portland:3.5
	private $notes;        // notes that only the manager can see and edit
	private $password;     // password for calendar and database access: default = $id
	// Volunteer availability start and end for each week day in 24h format, hh:mm
	private $sundaysStart;
	private $sundaysEnd;
	private $mondaysStart;
	private $mondaysEnd;
	private $tuesdaysStart;
	private $tuesdaysEnd;
	private $wednesdaysStart;
	private $wednesdaysEnd;
	private $thursdaysStart;
	private $thursdaysEnd;
	private $fridaysStart;
	private $fridaysEnd;
	private $saturdaysStart;
	private $saturdaysEnd;
	private $mustChangePassword;
	private $gender;
	private $diagnosis;
	private $diagnosis_date;
	private $hospital;
	private $permission_to_confirm;
	private $expected_treatment_end_date;
	//private $services_interested_in;
	private $allergies;
	private $sibling_info;
	private $can_share_contact_info;
	private $username;
	private $meals;
	private $housecleaning;
	private $lawncare;
	private $photography;
	private $gas;
	private $grocery;
	private $aaaInterest;
	private $socialEvents;
	private $houseProjects;
	private $how_did_you_hear;
	private $familyInfo;
	private	$leadVolunteer;
	private	$gift_card_delivery_method;
	private	$location;
	private $remission_trans_date;
	private $remission_end_date;
	private $remembrance_date;
	

	function __construct($f, $l, $v, $a, $c, $s, $z, $pp, $p1, $p1t, $p2, $p2t, $e, 
			//$ts, $comp, $cam, $tran, 
			$cn, $cpn, $rel,
			$ct, $t, $st, $cntm, 
			//$pos, $credithours, $comm, $mot, $spe,$convictions, 
			$av, $sch, $hrs, $bd, $sd, 
			//$hdyh, 
			$notes, $pass,
			$suns, $sune, $mons, $mone, $tues, $tuee, $weds, $wede,
			$thus, $thue, $fris, $frie, $sats, $sate, $mcp, $gender, 
			$diagnosis,$diagnosis_date,$hospital,$permission_to_confirm,
			$expected_treatment_end_date, 
			//$services_interested_in,
			$allergies,
			$sibling_info,$can_share_contact_info,$username,$meals,
			$housecleaning,$lawncare,$photography, $gas,$grocery,$aaaInterest,
			$socialEvents,$houseProjects,$how_did_you_hear,$familyInfo,
			$leadVolunteer,$gift_card_delivery_method,$location,
			$remission_trans_date, $remission_end_date, $remembrance_date
			) {
		$this->id = $e;
		$this->start_date = $sd;
		$this->venue = $v;
		$this->first_name = $f;
		$this->last_name = $l;
		$this->address = $a;
		$this->city = $c;
		$this->state = $s;
		$this->zip = $z;
    		$this->profile_pic = $pp;
		$this->phone1 = $p1;
		$this->phone1type = $p1t;
		$this->phone2 = $p2;
		$this->phone2type = $p2t;
		$this->birthday = $bd;
		$this->email = $e;
		$this->contact_name = $cn;
		$this->contact_num = $cpn;
		$this->relation = $rel;
		$this->contact_time = $ct;
		$this->cMethod = $cntm;
		$this->mustChangePassword = $mcp;
		$this->type = $t;
		// I think these might not matter but doesn't hurt to have them
		if (strtolower($this->type) == "family") {
			$this->access_level = 1;
		}
		elseif (strtolower($this->type) == "admin") {
			$this->access_level = 2;
		}
		// vmsroot has no type assigned so you have to check for it separately
		elseif (strtolower($this->type) == "superadmin" || strtolower($this->first_name) == "vmsroot") { 
			$this->access_level = 3;
		}
		else{
			$this->access_level = 0;
		}
		$this->status = $st;
		if ($av == "")
			$this->availability = array();
		else
			$this->availability = explode(',', $av);
		if ($sch !== "")
			$this->schedule = explode(',', $sch);
		else
			$this->schedule = array();
		if ($hrs !== "")
			$this->hours = explode(',', $hrs);
		else
			$this->hours = array();
		$this->notes = $notes;
		if ($pass == "")
			//$this->password = password_hash($this->birthday, PASSWORD_BCRYPT); // default password
			$this->password =$this->birthday;
		else
			$this->password = $pass;
		$this->sundaysStart = $suns;
		$this->sundaysEnd = $sune;
		$this->mondaysStart = $mons;
		$this->mondaysEnd = $mone;
		$this->tuesdaysStart = $tues;
		$this->tuesdaysEnd = $tuee;
		$this->wednesdaysStart = $weds;
		$this->wednesdaysEnd = $wede;
		$this->thursdaysStart = $thus;
		$this->thursdaysEnd = $thue;
		$this->fridaysStart = $fris;
		$this->fridaysEnd = $frie;
		$this->saturdaysStart = $sats;
		$this->saturdaysEnd = $sate;
		$this->gender = $gender;
		$this->diagnosis =$diagnosis;
		$this->diagnosis_date=$diagnosis_date;
		$this->hospital=$hospital;
		$this->permission_to_confirm=$permission_to_confirm;
		$this->expected_treatment_end_date=$expected_treatment_end_date;
		//$this->services_interested_in=$services_interested_in;
		$this->allergies=$allergies;
		$this->sibling_info=$sibling_info;
		$this->can_share_contact_info=$can_share_contact_info;
		$this->username=$username;
		$this->meals=$meals;
		$this->housecleaning=$housecleaning;
		$this->lawncare=$lawncare;
		$this->photography=$photography;
		$this->gas=$gas;
		$this->grocery=$grocery;
		$this->aaaInterest=$aaaInterest;
		$this->socialEvents=$socialEvents;
		$this->houseProjects=$houseProjects;
		$this->how_did_you_hear=$how_did_you_hear;
		$this->familyInfo=$familyInfo;
		$this->leadVolunteer=$leadVolunteer;
		$this->gift_card_delivery_method=$gift_card_delivery_method;
		$this->location=$location;
		$this->remission_trans_date=$remission_trans_date;
		$this->remission_end_date=$remission_end_date;
		$this->remembrance_date=$remembrance_date;
	}

	function get_id() {
		return $this->id;
	}

	function get_start_date() {
		return $this->start_date;
	}

	function get_venue() {
		return $this->venue;
	}

	function get_first_name() {
		return $this->first_name;
	}

	function get_last_name() {
		return $this->last_name;
	}

	function get_address() {
		return $this->address;
	}

	function get_city() {
		return $this->city;
	}

	function get_state() {
		return $this->state;
	}

	function get_zip() {
		return $this->zip;
	}

  function get_profile_pic() {
    return $this->profile_pic;
  }

	function get_phone1() {
		return $this->phone1;
	}

	function get_phone1type() {
		return $this->phone1type;
	}

	function get_phone2() {
		return $this->phone2;
	}

	function get_phone2type() {
		return $this->phone2type;
	}

	function get_birthday() {
		return $this->birthday;
	}

	function get_email() {
		return $this->email;
	}

	function get_shirt_size() {
		return $this->shirt_size;
	}

	function get_computer() {
		return $this->computer;
	}

	function get_camera() {
		return $this->camera;
	}

	function get_transportation() {
		return $this->transportation;
	}

	function get_contact_name() {
		return $this->contact_name;
	}

	function get_contact_num() {
		return $this->contact_num;
	}

	function get_relation() {
		return $this->relation;
	}

	function get_contact_time() {
		return $this->contact_time;
	}

	function get_cMethod() {
		return $this->cMethod;
	}

	function get_position() {
		return $this->position;
	}

	function get_credithours() {
		return $this->credithours;
	}

	function get_how_did_you_hear() {
		return $this->how_did_you_hear;
	}

	function get_commitment() {
		return $this->commitment;
	}

	function get_motivation() {
		return $this->motivation;
	}

	function get_specialties() {
		return $this->specialties;
	}

	function get_convictions() {
		return $this->convictions;
	}

	function get_type() {
		return $this->type;
	}

	function get_status() {
		$today=date("YYYY-MM-DD");
		if($this->remission_end_date>=$today && $this->status=="Remission"){
			return "Survivor";
		}
		else if($this->status=="Survivor"){
			return $this->status;
		}
		else{
			return $this->status;
		}
	}

	function get_availability() { // array of day:hours:venue
		return $this->availability;
	}

	function set_availability($dayscolonhours) { // tack on the venue for each pair
		$this->availability = array();
		foreach($dayscolonhours as $dayhour) {
			$dh = explode(":",$dayscolonhours);
			$this->availability[] = $dh[0].":".$dh[1].":".$this->venue;
		}
	}

	function get_schedule() {
		return $this->schedule;
	}

	function get_hours() {
		return $this->hours;
	}

	function get_notes() {
		return $this->notes;
	}

	function get_password() {
		return $this->password;
	}

	function get_sunday_availability_start() {
		return $this->sundaysStart;
	}

	function get_sunday_availability_end() {
		return $this->sundaysEnd;
	}

	function get_monday_availability_start() {
		return $this->mondaysStart;
	}

	function get_monday_availability_end() {
		return $this->mondaysEnd;
	}

	function get_tuesday_availability_start() {
		return $this->tuesdaysStart;
	}

	function get_tuesday_availability_end() {
		return $this->tuesdaysEnd;
	}

	function get_wednesday_availability_start() {
		return $this->wednesdaysStart;
	}

	function get_wednesday_availability_end() {
		return $this->wednesdaysEnd;
	}

	function get_thursday_availability_start() {
		return $this->thursdaysStart;
	}

	function get_thursday_availability_end() {
		return $this->thursdaysEnd;
	}

	function get_friday_availability_start() {
		return $this->fridaysStart;
	}

	function get_friday_availability_end() {
		return $this->fridaysEnd;
	}

	function get_saturday_availability_start() {
		return $this->saturdaysStart;
	}

	function get_saturday_availability_end() {
		return $this->saturdaysEnd;
	}

	function get_access_level() {
		return $this->access_level;
	}

	function is_password_change_required() {
		return $this->mustChangePassword;
	}

	function get_gender() {
		return $this->gender;
	}

	function get_diagnosis() {
		return $this->diagnosis;
	}

	function get_diagnosis_date() {
		return $this->diagnosis_date;
	}

	function get_hospital() {
		return $this->hospital;
	}

	function get_permission_to_confirm() {
		return $this->permission_to_confirm;
	}
	function get_expected_treatment_end_date() {
		return $this->expected_treatment_end_date;
	}
	function get_services_interested_in() {
		return $this->services_interested_in;
	}
	function get_allergies() {
		return $this->allergies;
	}
	function get_sibling_info() {
		return $this->sibling_info;
	}
	function get_can_share_contact_info() {
		return $this->can_share_contact_info;
	}
	function get_username() {
		return $this->username;
	}

	function get_meals() {
		return $this->meals;
	}
	
	function get_housecleaning() {
		return $this->housecleaning;
	}
	
	function get_lawncare() {
		return $this->lawncare;
	}

	function get_photography() {
		return $this->photography;
	}

	function get_gas() {
		return $this->gas;
	}

	function get_grocery() {
		return $this->grocery;
	}

	function get_aaaInterest() {
		return $this->aaaInterest;
	}

	function get_socialEvents() {
		return $this->socialEvents;
	}

	function get_houseProjects() {
		return $this->houseProjects;
	}	

	function get_leadVolunteer() {
		return $this->houseProjects;
	}	

	function get_gift_card_delivery_method() {
		return $this->gift_card_delivery_method;
	}	

	function get_location() {
		return $this->location;
	}	

	function get_familyInfo() {
		return $this->familyInfo;
	}

	function get_remission_trans_date(){
		return $this->remission_trans_date;
	}

	function get_remission_end_date(){
		return $this->remission_end_date;
	}

	function get_remembrance_date(){
		return $this->remembrance_date;
	}

	function setLocation($location) {
        $this->location = $location;
    }

    function setStartDate($start_date) {
        $this->start_date = $start_date;
    }

    function setLeadVolunteer($leadVolunteer) {
        $this->leadVolunteer = $leadVolunteer;
    }

    function setGiftCardDeliveryMethod($gift_card_delivery_method) {
        $this->gift_card_delivery_method = $gift_card_delivery_method;
    }



	// Function to update a person in the database
function update_person($person) {
    $con = connect(); // Assuming connect() is a function to establish a database connection
    // Construct your update query here based on the provided Person object
    $query = "UPDATE dbPersons SET location = '{$person->get_location()}', start_date = '{$person->get_start_date()}', lead_volunteer = '{$person->get_lead_volunteer()}', gift_card_delivery_method = '{$person->get_gift_card_delivery_method()}' WHERE id = '{$person->get_id()}'";
    $result = mysqli_query($con, $query);
    return $result; // Return true if update is successful, false otherwise
}


}
