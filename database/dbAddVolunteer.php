<?php

include_once('dbinfo.php');
include_once(dirname(__FILE__).'/../domain/Volunteer.php');

//add a volunteer to dbvolunteer

function add_volunteer($volunteer) {
    if (!$volunteer instanceof Volunteer)
        die("Error: add_volunteer type mismatch");
    $con=connect();
    $query = "SELECT * FROM dbvolunteer WHERE email = '" . $volunteer->get_id() . "'";
    $result = mysqli_query($con,$query);
    //if there's no entry for this id, add it
    if ($result == null || mysqli_num_rows($result) == 0) {
        mysqli_query($con,'INSERT INTO dbPersons VALUES("' .
            $person->get_id() . '","' .
            $person->get_start_date() . '","' .
            $person->get_venue() . '","' .
            '");'

        );							
        mysqli_close($con);
        return true;
    }
    mysqli_close($con);
    return false;
}