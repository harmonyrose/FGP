<?php

include_once('dbinfo.php');
include_once(dirname(__FILE__).'/../domain/Volunteer.php');


// just a random function doesnt do anything
function add_volunteer($volunteer) {
    if (!$volunteer instanceof Volunteer)
        die("Error: add_volunteer type mismatch");
    $con=connect();
    $query = "SELECT * FROM dbvolunteer WHERE email = '" . $volunteer->get_id() . "'";
    $result = mysqli_query($con,$query);
    //if there's no entry for this id, add it
    if ($result == null || mysqli_num_rows($result) == 0) {
        mysqli_query($con,'INSERT INTO dbvolunteer VALUES("' .
            $volunteer->getFirstName() . '","' .
            $volunteer->getLastName() . '","' .
            $volunteer->getEmail() . '","' .
            '");'

        );							
        mysqli_close($con);
        return true;
    }
    mysqli_close($con);
    return false;
}

function create_volunteer($volunteer){
    $con =connect();
    $firstname = $volunteer['first-name'];
    $lastname = $volunteer['last-name'];
    $email = $volunteer['email'];
    $query = "INSERT INTO dbvolunteer (firstName, lastName, email) VALUES('$firstname', '$lastname', '$email')";
    try {
        $result = mysqli_query($con, $query);
        mysqli_commit($con);
        mysqli_close($con);
        return $email;
    } catch (mysqli_sql_exception $e) {
        // Check if the error is due to duplicate entry for vendorName
        if ($e->getCode() === 1062) { // Error code for duplicate entry
            return null; // or handle the error in some other way
        } else {
            throw $e; // Re-throw other exceptions
        }
    }


}