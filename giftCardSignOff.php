<?php
// Authors: Harmony Peura and Grayson Jones
// Allows families to sign off on gift card receival, saves
// date of sign off
require_once('domain/PointsProg.php');
require_once('database/dbPointsProg.php');
// Connect to database
include_once('database/dbinfo.php'); 
$connection=connect(); 
//saves the date in its respective column in points prog
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve the submitted date from the $_POST array
    $date = $_POST['date'];
    // Convert the date to "day-month-year" format
    $date = date("m-d-Y", strtotime($date));
    //gets information from signoff form
    $family_id = $_GET['family_id'];

    // Escape the date to prevent SQL injection
    $date = mysqli_real_escape_string($connection, $date);
    $family_id = mysqli_real_escape_string($connection, $family_id);
    // Connect to database
    include_once('database/dbinfo.php'); 
    $connection=connect(); 
    // Construct the SQL query to insert the date into the dbPointsProg table
    $sql = "UPDATE dbPointsProg SET giftCardPickup = '$date' WHERE id = '$family_id'";

    // Execute the query
    if (mysqli_query($connection, $sql)) {
        //echo "Date inserted successfully into dbPointsProg";
        header("Location: giftCardSignOffTable.php?signOffSuccess");
    } else {
        echo "Error: " . $sql . "<br>" . mysqli_error($connection);
    }
    // Close the database connection
    mysqli_close($connection);

} else {
    require_once('giftCardSignOffForm.php'); 
    
}

exit;
?>