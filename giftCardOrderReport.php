<?php
session_start();


//Function to fetch data for the family names
function fetch_family_names() {
    include_once('database/dbinfo.php'); 
    $con=connect();  
    // Query to fetch required data from the database
    $query = "SELECT * FROM dbPointsProg";

    $result = mysqli_query($con, $query);

    if (!$result) {
        die("Database query failed: " . mysqli_error($con));
    }

    // Fetch data and return as an array
    $family_names = [];
    while ($family= mysqli_fetch_assoc($result)) {
        $family_names[] = $family['name'];
    }
    mysqli_close($con);
    return $family_names;
}

//Fetch names for report
$family_names = fetch_family_names();

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
            $vendors[] = $vendor['vendorName'];
        }
    }
    mysqli_close($con);
    return $vendors;

} 
// Fetch all vendors
$vendors = fetch_all_vendors();
// Fetch family name and store-numberofcards string
function fetch_gcinfo(){
    include_once('database/dbinfo.php'); 
    $con=connect();  
    // Query to fetch required data from the database
    $query = "SELECT name, CONCAT(grocery, ',', gas) AS combined_stores FROM dbPointsProg";

    $result = mysqli_query($con, $query);

    if (!$result) {
        die("Database query failed: " . mysqli_error($con));
    }
    // Check if any rows were returned
    if ($result->num_rows > 0) {
    // Initialize an empty array to store the data
    $info = [];
    // Fetch all vendors
    $vendors = fetch_all_vendors();
    // Fetch associative array
    while($row = $result->fetch_assoc()) {

        // Split the string by comma to get individual store-name, number pairs
        $store_info_pairs = explode(',', $row['combined_stores']);
        //print_r($store_info_pairs);
        // Initialize an empty array to store the numbers associated with vendors
        $vendor_numbers = [$row['name']];

        // Iterate through each vendor
        foreach ($vendors as $vendor) {
            // Initialize the number as null for the current vendor
            $number = null;
            
            // Iterate through each store number pair
            foreach ($store_info_pairs as $store_number) {
                // Split the store number pair into store name and number
                $store_info = explode('-', $store_number);
                
                // Check if the store_info array has both store name and number
                if (count($store_info) == 2) {
                    $store_name = $store_info[0];
                    $store_num = $store_info[1];
                    
                    // If the store name matches the current vendor, store the number
                    if ($store_name === $vendor) {
                        $number = $store_num;
                        break; // Break out of the loop once a match is found
                    }
                }else{$number = "-";}
            }
            
            // Store the number associated with the vendor
            $vendor_numbers[] = $number;
        }

        array_push($info, $vendor_numbers);
    }

    } else {
        echo "No data found";
    }
    mysqli_close($con);
    return $info;
}

$row_info=fetch_gcinfo();
    
// Generate CSV file
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    //MAKE FILE NAME THE MONTH AND YEAR
    // Get the current month and year
    $month = date('F');
    $year = date('Y');

    $filename = $month.$year."giftCardOrderReport.csv";
    $fp = fopen($filename, 'w');
    
    // Write CSV header
    $header = array_merge(array('Family'), $vendors);
    fputcsv($fp, $header);
    
    // Loop through the array and write each array as a row in the CSV file
    foreach ($row_info as $row) {
        fputcsv($fp, $row);
    }
    // Close file pointer
    fclose($fp);
    //Calculate giftcard order totals
    // Initialize an array to store column totals
    $columnTotals = [];

    // Open the CSV file for reading
    if (($handle = fopen($filename, 'r')) !== false) {
        // Loop through each row in the CSV file
        while (($data = fgetcsv($handle)) !== false) {
            // Loop through each column in the row
            for ($columnIndex = 1; $columnIndex < count($data); $columnIndex++) {
                // Initialize column total if not already initialized
                if (!isset($columnTotals[$columnIndex])) {
                    $columnTotals[$columnIndex] = 0;
                }
                
                // Check if the value is an integer
                $value = $data[$columnIndex];
                if (ctype_digit($value)) {
                    // If it's an integer, add it to the column total
                    $columnTotals[$columnIndex] += (int)$value;
                }
            }
        }

        // Close the CSV file
        fclose($handle);
    }

    // Append the column totals as a new row to the CSV data
    // Initialize the new row with a "Totals:" value for the first column
    $totalsRow = ['Totals:'];

    // Append the column totals to the new row, starting from the second column
    foreach ($columnTotals as $total) {
        $cash = '$'.((int)$total * 25); //multiply column total by 25 to create cash amount
        $totalsRow[] = $cash;
    }

    // Open the CSV file for appending
    if (($handle = fopen($filename, 'a')) !== false) {
        // Write the new row to the end of the file
        fputcsv($handle, $totalsRow);

        // Close the CSV file
        fclose($handle);
    }

    // Prompt download
    header('Content-Type: application/csv');
    header('Content-Disposition: attachment; filename="' . $filename . '"');
    readfile($filename);

    // Delete file
    unlink($filename);
    exit;
}
?>

<!DOCTYPE html>
<html>
    <head>
        <?php require_once('universal.inc'); ?>
        <title>FGP | Gift Card Order Report</title>
    </head>
    <body>
        <?php require_once('header.php'); ?>
        <h1>Gift Card Order Report</h1>
        <form method="post">
            <br>
            <style>
                .generate-csv-btn {
                    padding: 25px 25px; /* Adjust padding for height and width */
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
                    height: 40vh; /* Make the container fill 70% of the viewport height */
                }
                p {
                    margin-left:150px;
                    margin-right: 150px;
                }

            </style>

            <p> Click the button to generate this month's <b>Gift Card Order Report.</b> The report
                will display the number of $25 gift cards requested by each family for each
                vendor. The total dollar amounts needed from each vendor are displayed at the
                bottom of the report.
            </p>
            <br>
            <br>
            <!-- Container to center the button -->
            <div class="button-container">
            <!-- Apply the class to the button -->
                <button type="submit" class="generate-csv-btn">Generate Gift Card Order Report</button>
            </div>
            <a class="button cancel" href="giftCardManagement.php">Return to Gift Card Management</a>
            <div class="space-below-button"></div>
            <br>
            <a href="index.php" class="button cancel">Return to Dashboard</a>
        </form>
    </body>
</html>