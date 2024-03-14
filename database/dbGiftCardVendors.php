<?php

include_once('dbinfo.php');
include_once(dirname(__FILE__).'/../domain/Vendor.php');

/*
 * add a vendor to dbGiftCardVendors table: if already there, return false
 */

// function add_Vendor($animal) {
//     if (!$animal instanceof Animal)
//         die("Error: add_event type mismatch");
//     $con=connect();
//     $query = "SELECT * FROM dbAnimals WHERE id = '" . $animal->get_id() . "'";
//     $result = mysqli_query($con,$query);
//     //if there's no entry for this id, add it
//     if ($result == null || mysqli_num_rows($result) == 0) {
//         mysqli_query($con,'INSERT INTO dbEvents VALUES("' .
//                 $vendor->get_id() . '","' .
//                 $vendor->get_name() . '","' .
//                 $vendor->get_type() . '","' .
//                 $vendor->get_location() . '","' .           
//                 '");');							
//         mysqli_close($con);
//         return true;
//     }
//     mysqli_close($con);
//     return false;
// }

/*
 * remove an event from dbEvents table.  If already there, return false
 */

// function remove_vendor($id) {
//     $con=connect();
//     $query = 'SELECT * FROM dbEvents WHERE id = "' . $id . '"';
//     $result = mysqli_query($con,$query);
//     if ($result == null || mysqli_num_rows($result) == 0) {
//         mysqli_close($con);
//         return false;
//     }
//     $query = 'DELETE FROM dbEvents WHERE id = "' . $id . '"';
//     $result = mysqli_query($con,$query);
//     mysqli_close($con);
//     return true;
// }

function find_vendors() {
    $query = "select * from dbGiftCardVendors";

    $connection = connect();

    $result = mysqli_query($connection, $query);
    if (!$result) {
        mysqli_close($connection);
        return [];
    }
    $raw = mysqli_fetch_all($result, MYSQLI_ASSOC);
    $vendors = [];
    foreach ($raw as $row) {
        $vendors []= make_a_vendor($row);
    }
    mysqli_close($connection);
    return $vendors;
}

function find_next_id() {
    $query = "SELECT COUNT(*) FROM dbGiftCardVendors";
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

    $raw = mysqli_fetch_all($result, MYSQLI_ASSOC);
    mysqli_free_result($result);
    mysqli_close($connection);

    if (!$raw || empty($raw)) {
        // No rows returned
        return null;
    }

    return $raw[0]['COUNT(*)'];
}

function make_a_vendor($result_row) {
    $theVendor = new Vendor(
                    $result_row['vendorID'],
                    $result_row['vendorName'],
                    $result_row['vendorType'],
                    $result_row['vendorLocation']
                );   
    return $theVendor;
}

function create_vendor($vendor) {
    $connection = connect();
    $id = find_next_id() + 1;
	$vendorName = $vendor["vendorName"];
    $vendorType = $vendor["vendorType"];
	$vendorLocation = $vendor["vendorLocation"];
    $query = "
        INSERT INTO dbGiftCardVendors (vendorID, vendorName, vendorType, vendorLocation)
        values ('$id','$vendorName','$vendorType', '$vendorLocation')
    ";
    try {
        $result = mysqli_query($connection, $query);
        mysqli_commit($connection);
        mysqli_close($connection);
        return $id;
    } catch (mysqli_sql_exception $e) {
        // Check if the error is due to duplicate entry for vendorName
        if ($e->getCode() === 1062) { // Error code for duplicate entry
            return null; // or handle the error in some other way
        } else {
            throw $e; // Re-throw other exceptions
        }
    }
}