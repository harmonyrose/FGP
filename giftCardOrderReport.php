<?php
session_start();
//include_once('dbinfo.php');
// Check if the connection was successful
// if (!$connection) {
//     die("Connection failed: " . mysqli_connect_error());
// }

// Function to fetch data for the Current Families Report
// function fetch_family_names() {
//     $connection = connect();

//     // Query to fetch required data from the database
//     $query = "SELECT name FROM dbPointsProg";

//     $result = mysqli_query($connection, $query);

//     if (!$result) {
//         die("Database query failed: " . mysqli_error($connection));
//     }

//     // Fetch data and return as an array
//     $data = [];
//     while ($row = mysqli_fetch_assoc($result)) {
//         $data[] = $row;
//     }
//     mysqli_close($connection);
//     return $data;
// }

// Fetch names for report
//$family_names = fetch_family_names();

function fetch_all_vendors() {

    include_once('database/dbinfo.php'); 
    $con=connect();  
    $sql = "SELECT * FROM `dbGiftCardVendors`";
    $all_vendors = mysqli_query($con,$sql);
    // Check if there are any vendors
    if (mysqli_num_rows($all_vendors) > 0) {
        // Loop through each row in the result set
        $vendors = [];
        while ($vendor = mysqli_fetch_array($all_vendors, MYSQLI_ASSOC)) {
            // Check if the vendor type is "gas"
            $vendors[] = $vendor['vendorName'];
        }
    }
    return $vendors;

} 

// Fetch all vendors
$vendors = fetch_all_vendors();


// Generate CSV file
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $filename = "giftCardOrderReport.csv";
    $fp = fopen($filename, 'w');
    
    // Write CSV header
    $header = array_merge(array('Family'), $vendors);
    fputcsv($fp, $header);
    // // Write data rows
    // while ($row = mysqli_fetch_assoc($result)) {
    //     // Extract values from the associative array
    //     $values = array_values($row);
    //     // Write the values to the CSV file
    //     fputcsv($fp, $values);
    // }

    // Close file pointer
    fclose($fp);

    // Prompt download
    header('Content-Type: application/csv');
    header('Content-Disposition: attachment; filename="' . $filename . '"');
    readfile($filename);

    // Delete file
    unlink($filename);
    mysqli_close($connection);
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
<?php require_once('universal.inc'); ?>
    <title>Gift Card Order Report</title>
</head>
<body>
    <?php require_once('header.php'); ?>
    <h1>Gift Card Order Report</h1>
    <form method="post" action="">
        <br><br>
    <style>
        .generate-csv-btn {
            padding: 15px 15px; /* Adjust padding for height and width */
            background-color: green; /* Change background color to green */
            color: white; /* Change text color to white */
            border: none; /* Remove border */
            border-radius: 5px; /* Add border radius for rounded corners */
            cursor: pointer; /* Change cursor to pointer on hover */
            width: auto;
            display: inline-block; /* Make the button inline-block to make it respect height */
            font-size: 24px; /* Increase font size */
        }

        /* Style for hover effect */
        .generate-csv-btn:hover {
            background-color: darkgreen; /* Darken the background color on hover */
        }

        /* Container to center the button */
        .button-container {
            display: flex;
            justify-content: center;
            align-items: flex-start; /* Align items to the start (top) of the container */
            height: 30vh; /* Make the container fill 70% of the viewport height */
        }
    </style>

    <!-- Container to center the button -->
    <div class="button-container">
        <!-- Apply the class to the button -->
        <button type="submit" class="generate-csv-btn">Generate Gift Card Order Report</button>
    </div>

    </form>
    <a href="giftCardManagement.php" class="button cancel">Return to Gift Card Management</a>
    <div class="space-below-button"></div>
    <br>
    <a href="index.php" class="button cancel">Return to Dashboard</a>
</body>
</html>