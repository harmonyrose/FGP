<?php

include_once('dbinfo.php');
include_once(dirname(__FILE__).'/../domain/Vendor.php');

// Finds all vendors in the database and returns them as an array. Used in listVendors.php to fill the table.
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

// Finds the highest id in the database that is not already used so it can be assigned to the next vendor. Used in create_vendor.
function find_next_id() {
    $query = "SELECT MAX(vendorID) AS max_id FROM dbGiftCardVendors";
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

// Takes in a dictionary, creates a vendor object with it, and returns that vendor object. Used in addVendor.php
function make_a_vendor($result_row) {
    $theVendor = new Vendor(
                    $result_row['vendorID'],
                    $result_row['vendorName'],
                    $result_row['vendorType'],
                    $result_row['vendorLocation']
                );   
    return $theVendor;
}

// Takes in a vendor object, creates a row in database with their information. Returns their vendor id if successful.
// Returns null if there is already a vendor with the given name in the database. Used in addVendor.php
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

// Takes in an array of vendor Ids, turns them into a string separated by commas, and then removes them all from the database.
// Used in listVendors.php for deleting vendors.
function remove_vendor($vendorIDs) {
    $connection = connect();
    $idString = implode(',', $vendorIDs);
    $query = "
        DELETE FROM dbGiftCardVendors WHERE vendorID IN ($idString)
    ";
    try {
        $result = mysqli_query($connection, $query);
        mysqli_commit($connection);
        mysqli_close($connection);
        return 1;
    } catch (mysqli_sql_exception $e) {
        throw $e;
    }
}