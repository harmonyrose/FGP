<?php
// Authors: Harmony Peura and Grayson Jones
include_once('dbinfo.php');
include_once(dirname(__FILE__).'/../domain/CommCare.php');

// Add a row to the CommCare database. If there is already a row
// with the given email, update it.
function add_comm_care($commcare) {
    if (!$commcare instanceof CommCare)
        die("Error: add_comm_care type mismatch");
    $con=connect();

    $query = "SELECT * FROM dbCommCare WHERE email = '" . $commcare->getEmail() . "'";
    $result = mysqli_query($con, $query);

    // Check if there's any entry for this email
    if ($result && mysqli_num_rows($result) > 0) {
        // Update the existing entry with the new data
        mysqli_query($con, "UPDATE dbCommCare SET 
            id = '" . $commcare->getId() . "',
            email = '" . $commcare->getEmail() . "',
            adultNames = '" . $commcare->getAdultNames() . "',
            childrenInfo = '" . $commcare->getChildrenInfo() . "',
            sportsFan = '" . $commcare->getSportsFan() . "',
            sportsInfo = '" . $commcare->getSportsInfo() . "',
            sitDinner = '" . $commcare->getSitDinner() . "',
            fastFood = '" . $commcare->getFastFood() . "',
            sweetTreat = '" . $commcare->getSweetTreat() . "',
            faveSweet = '" . $commcare->getFaveSweet() . "',
            faveSalt = '" . $commcare->getFaveSalt() . "',
            faveCandy = '" . $commcare->getFaveCandy() . "',
            faveCookie = '" . $commcare->getFaveCookie() . "',
            forFun = '" . $commcare->getForFun() . "',
            warmAct = '" . $commcare->getWarmAct() . "',
            coldAct = '" . $commcare->getColdAct() . "',
            notes = '" . $commcare->getNotes() . "'
            WHERE email = '" . $commcare->getEmail() . "'
        ");
        mysqli_close($con);
        return true;
    } else {
        $query = "SELECT * FROM dbCommCare WHERE id = '" . $commcare->getId() . "'";
        $result = mysqli_query($con,$query);
        //if there's no entry for this id, add it
        if ($result == null || mysqli_num_rows($result) == 0) {
            mysqli_query($con,'INSERT INTO dbCommCare VALUES("' .
                $commcare->getId() . '","' .
                $commcare->getEmail() . '","' .
                $commcare-> getAdultNames(). '","'.
                $commcare->getChildrenInfo() . '","' .
                $commcare->getSportsFan() . '","' .
                $commcare->getSportsInfo() . '","' .
                $commcare->getSitDinner() . '","' .
                $commcare->getFastFood() . '","' .
                $commcare->getSweetTreat() . '","' .
                $commcare->getFaveSweet() . '","' .
                $commcare->getFaveSalt() . '","' .
                $commcare->getFaveCandy() . '","' .
                $commcare->getFaveCookie() . '","' .
                $commcare->getForFun() . '","' .
                $commcare->getWarmAct() . '","' .
                $commcare->getColdAct() . '","' .
                $commcare->getNotes() . 
                '");'
            );							
            mysqli_close($con);
            return true;
        }
        mysqli_close($con);
        return false;
    }
}

// Remove a row from the CommCare table. Not currently used anywhere.
function remove_comm_care($id) {
    $con=connect();
    $query = 'SELECT * FROM dbCommCare WHERE id = "' . $id . '"';
    $result = mysqli_query($con,$query);
    if ($result == null || mysqli_num_rows($result) == 0) {
        mysqli_close($con);
        return false;
    }
    $query = 'DELETE FROM dbCommCare WHERE id = "' . $id . '"';
    $result = mysqli_query($con,$query);
    mysqli_close($con);
    return true;
}

// Retrieve a row from the CommCare table. Not currently used anywhere.
function retrieve_comm_care($id) {
    $con=connect();
    $query = "SELECT * FROM dbCommCare WHERE id = '" . $id . "'";
    $result = mysqli_query($con,$query);
    if (mysqli_num_rows($result) !== 1) {
        mysqli_close($con);
        return false;
    }
    $result_row = mysqli_fetch_assoc($result);
    $theCommCare = make_a_comm_care($result_row);

    return $theCommCare;
}


//added new function because to view comm care the id needed is email and not the databse id
function email_retrieve_comm_care($email){
    $con=connect();
    $query = "SELECT * FROM dbCommCare WHERE email ='" . $email . "'";
    $result = mysqli_query($con,$query);
    if(mysqli_num_rows($result)!==1){
        mysqli_close($con);
        return false;
    }
    $result_row = mysqli_fetch_assoc($result);
    $theCommCare = make_a_comm_care($result_row);
    return $theCommCare;
}


// Make an instance of a CommCare row. Not currently used anywhere.
function make_a_comm_care($result_row) {
    $theCommCare = new CommCare(
        $result_row['id'],
        $result_row['email'],
        $result_row['adultNames'],
        $result_row['childrenInfo'],
        $result_row['sportsFan'],
        $result_row['sportsInfo'],
        $result_row['sitDinner'],
        $result_row['fastFood'],
        $result_row['sweetTreat'],
        $result_row['faveSweet'],
        $result_row['faveSalt'],
        $result_row['faveCandy'],
        $result_row['faveCookie'],
        $result_row['forFun'],
        $result_row['warmAct'],
        $result_row['coldAct'],
        $result_row['notes'],
    );   
    return $theCommCare;
}

// Used to create a unique ID for each row in the CommCare table.
function find_next_id() {
    $query = "SELECT MAX(id) AS max_id FROM dbCommCare";
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