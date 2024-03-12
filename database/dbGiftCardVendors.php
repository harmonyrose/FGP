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

function make_a_vendor($result_row) {
    $theVendor = new Vendor(
                    $result_row['vendorID'],
                    $result_row['vendorName'],
                    $result_row['vendorType'],
                    $result_row['vendorLocation']
                );   
    return $theVendor;
}