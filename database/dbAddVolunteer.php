<?php

include_once('dbinfo.php');
include_once(dirname(__FILE__).'/../domain/Volunteer.php');


function create_volunteer($volunteer){
    $con =connect();
    $id = find_next_id()+1;
    $firstname = $volunteer['first-name'];
    $lastname = $volunteer['last-name'];
    $email = $volunteer['email'];
    $query = "INSERT INTO dbvolunteer (volunteerID, firstName, lastName, email) VALUES('$id' ,'$firstname', '$lastname', '$email')";
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

// Finds the highest id in the database that is not already used so it can be assigned to the next volunteer.
function find_next_id() {
    $query = "SELECT MAX(volunteerID) AS max_id FROM dbvolunteer";
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

function volunteerObj($obj){ // creates a volunteer obj to add inside an array
    $volunteerData = new Volunteer(
                    $obj = ['volunteerID'],
                    $obj = ['firstName'],
                    $obj = ['lastName'],
                    $obj = ['email']
                );
    return $volunteerData;
}

function display_volunteer(){ // function to list all volunteers
    $query = "SELECT * FROM dbvolunteer";
    $con = connect();
    $result = mysqli_query($con, $query);
    if (!$result) {
        mysqli_close($con);
        return [];
    }
    $volunteers = array();
    //while($result_row = mysqli_fetch_assoc($result)){
        //$volunteers[] = $result_row;

    //}
    while($result_row = mysqli_fetch_assoc($result)){
        $id = $result_row['volunteerID'];
        $firstName = $result_row['firstName'];
        $lastName = $result_row['lastName'];
        $email = $result_row['email'];
        echo '<tr>
        <th scope="row">'.$id.'</th>
        <td>'.$firstName.'</td>
        <td>'.$lastName.'</td>
        <td>'.$email.'</td>
        <td>
            <a class="button delete" id="deleteButton" style="background-color: red">Delete</a>
        </td>
    </tr>';
    }
}

function delete_volunteer(){
    

}