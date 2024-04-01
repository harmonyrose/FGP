<?php

include_once('dbinfo.php');
include_once(dirname(__FILE__).'/../domain/PointsProg.php');

function add_points_prog($pointsprog) {
    if (!$pointsprog instanceof PointsProg)
        die("Error: add_points_prog type mismatch");
    $con=connect();
    $query = "SELECT * FROM dbPointsProg WHERE id = '" . $pointsprog->getId() . "'";
    $result = mysqli_query($con,$query);
    //if there's no entry for this id, add it
    if ($result == null || mysqli_num_rows($result) == 0) {
        mysqli_query($con,'INSERT INTO dbPointsProg VALUES("' .
            $pointsprog->getId() . '","' .
            $pointsprog->getName() . '","' .
            $pointsprog-> getEmail(). '","'.
            $pointsprog->getAddress() . '","' .
            $pointsprog->getFreezerMeals() . '","' .
            $pointsprog->getAllergies() . '","' .
            $pointsprog->getSnacks() . '","' .
            $pointsprog->getSnackNotes() . '","' .
            $pointsprog->getFoodlion() . '","' .
            $pointsprog->getGiant() . '","' .
            $pointsprog->getWalmart() . '","' .
            $pointsprog->getWegmans() . '","' .
            $pointsprog->getSheetz() . '","' .
            $pointsprog->getWawa() . '","' .
            $pointsprog->getHouseCleaning() . '","' .
            $pointsprog->getLawnCare() . '","' .
            $pointsprog->getAAAMembership() . '","' .
            $pointsprog->getAAAMembershipName() . '","' .
            $pointsprog->getAAAMembershipDOB() . '","' .
            $pointsprog->getPhotography() . '","' .
            $pointsprog->getHouseProjects() . '","' .
            $pointsprog->getFinancialRelief() . '","' .
            $pointsprog->getPointsUsed() .
            '");'
        );							
        mysqli_close($con);
        return true;
    }
    mysqli_close($con);
    return false;
}

function remove_points_prog($id) {
    $con=connect();
    $query = 'SELECT * FROM dbPointsProg WHERE id = "' . $id . '"';
    $result = mysqli_query($con,$query);
    if ($result == null || mysqli_num_rows($result) == 0) {
        mysqli_close($con);
        return false;
    }
    $query = 'DELETE FROM dbPointsProg WHERE id = "' . $id . '"';
    $result = mysqli_query($con,$query);
    mysqli_close($con);
    return true;
}

function retrieve_points_prog($id) {
    $con=connect();
    $query = "SELECT * FROM dbPointsProg WHERE id = '" . $id . "'";
    $result = mysqli_query($con,$query);
    if (mysqli_num_rows($result) !== 1) {
        mysqli_close($con);
        return false;
    }
    $result_row = mysqli_fetch_assoc($result);
    // var_dump($result_row);
    $thePointsProg = make_a_points_prog($result_row);
//    mysqli_close($con);
    return $thePointsProg;
}


     
function make_a_points_prog($result_row) {
    $thePointsProg = new PointsProg(
        $result_row['id'],
        $result_row['name'],
        $result_row['email'],
        $result_row['address'],
        $result_row['freezer_meals'],
        $result_row['allergies'],
        $result_row['snacks'],
        $result_row['snack_notes'],
        $result_row['foodlion'],
        $result_row['giant'],
        $result_row['walmart'],
        $result_row['wegmans'],
        $result_row['sheetz'],
        $result_row['wawa'],
        $result_row['house_cleaning'],
        $result_row['lawn_care'],
        $result_row['AAA_membership'],
        $result_row['AAA_membership_name'],
        $result_row['AAA_membership_DOB'],
        $result_row['photography'],
        $result_row['house_projects'],
        $result_row['financial_relief'],
        $result_row['points_used']
    );   
    return $thePointsProg;
}

function find_next_id() {
    $query = "SELECT MAX(id) AS max_id FROM dbPointsProg";
    $connection = connect();

    if (!$connection) {
        // Connection failed, return null or handle error accordingly
        return null;
    }

    $result = mysqli_query($connection, $query);

    if (!$result) {
        // Query execution failed
        mysqli_close($connection);
        return null;
    }

    $row = mysqli_fetch_assoc($result);
    mysqli_free_result($result);
    mysqli_close($connection);

    if (!$row || empty($row['max_id'])) {
        // No max vendorID found
        return null;
    }

    return $row['max_id'];
}