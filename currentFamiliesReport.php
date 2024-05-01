<?php
session_start();
require_once('database/dbPersons.php');
require_once('domain/Person.php');
require_once('database/dbinfo.php');

//fetch all data for active families report
function fetch_active_data(){
    $con=connect();
    $today=date("Y-m-d");

    $query="SELECT dbPersons.expected_treatment_end_date, dbPersons.remission_trans_date,
    dbPersons.contact_name, dbPersons.first_name, dbPersons.last_name, dbPersons.cmethod, 
    dbPersons.phone1, dbPersons.email, dbPersons.address, dbPersons.leadVolunteer, 
    dbPersons.birthday, dbPersons.diagnosis_date, dbPersons.diagnosis, 
    dbPersons.sibling_info, dbPersons.hospital, dbPersons.start_date, 
    dbPointsProg.AAA_membership, dbPointsProg.gas,dbPointsProg.grocery, 
    dbPersons.gift_card_delivery_method, dbPointsProg.freezer_meals, dbPointsProg.snacks,
    dbPointsProg.allergies,dbPointsProg.house_cleaning, dbPointsProg.lawn_care, 
    dbPointsProg.photography, dbPersons.location 
    FROM dbPersons LEFT JOIN dbPointsProg ON dbPersons.id=dbPointsProg.email 
    WHERE dbPersons.status='Active' AND dbPersons.type='family';";
    
    $result = mysqli_query($con, $query);

    if (!$result) {
        die("Database query failed: " . mysqli_error($con));
    }

    // Fetch data and return as an array
    $data = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $data[] = $row;
    }

    return $data;
}




//fetch family data from dbPersons on all families with status remission or survivor
function fetch_remission_survivor_data(){
    $con=connect();
    $today=date("Y-m-d");

    $query="SELECT contact_name, first_name, last_name, remission_end_date, expected_treatment_end_date,
    remission_trans_date, email, phone1, address, location, birthday, diagnosis_date, diagnosis, 
    hospital, sibling_info,leadVolunteer FROM dbPersons WHERE status = 'Remission' OR status='Survivor'"; 
    
    $result = mysqli_query($con, $query);

    if (!$result) {
        die("Database query failed: " . mysqli_error($con));
    }

    // Fetch data and return as an array
    $data = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $data[] = $row;
    }

    return $data;
}

//fetch data from all families with stargazer status
function fetch_stargazer_data(){
    $con=connect();
    $query="SELECT contact_name, first_name, last_name, email, phone1, address, location,
    birthday, remembrance_date, notes FROM dbPersons WHERE status = 'Stargazer';"; 
    
    $result = mysqli_query($con, $query);

    if (!$result) {
        die("Database query failed: " . mysqli_error($con));
    }

    // Fetch data and return as an array
    $data = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $data[] = $row;
    }

    return $data;
}

// Function to fetch data from the pointsprog 
function fetch_pointsprog_data() {
    $con=connect();

    // Query to fetch required data from the "pointsprog" table
    $query = "SELECT snacks, AAA_membership, gas, freezer_meals, house_cleaning FROM dbPointsProg";

    $result = mysqli_query($con, $query);

    if (!$result) {
        die("Database query failed: " . mysqli_error($con));
    }

    // Fetch data and return as an array
    $data = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $data[] = $row;
    }

    return $data;
}

// Function to fetch data for the Current Families Report
function fetch_current_families_data() {
    $con=connect();

    // Query to fetch required data from the database where type = 'family'
    $query = "SELECT contact_name, phone1, email, address, first_name, last_name, birthday, diagnosis, diagnosis_date, hospital, expected_treatment_end_date, allergies, sibling_info FROM dbPersons WHERE type = 'family'";

    $result = mysqli_query($con, $query);

    if (!$result) {
        die("Database query failed: " . mysqli_error($con));
    }

    // Fetch data and return as an array
    $data = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $data[] = $row;
    }

    return $data;
}


// Generate CSV file
if ($_SERVER["REQUEST_METHOD"] == "POST" ) {

    if(isset($_POST['generate_csv_all'])){
    // Create CSV file
    $filename = "current_family_report.csv";
    $fp = fopen($filename, 'w');

    // Write CSV header
    fputcsv($fp, array('Preferred Contact Method', 'Phone', 'Email', 'Address', 'First Name', 'Last Name', 'Birthday', 'Diagnosis', 'Diagnosis Date', 'Hospital', 'Expected Treatment End Date', 'Allergies', 'Sibling Info', ));
    
    // Fetch data from the "pointsprog" table
    $pointsprog_data = fetch_pointsprog_data();

    // Fetch data for the Current Families Report
    $current_families_data = fetch_current_families_data();

    // Write current_families_data
    foreach ($current_families_data as $row) {
        fputcsv($fp, $row);
    }

    // Add an empty row
    fputcsv($fp, array());

    // Write pointsprog_data
    fputcsv($fp, array('Snacks', 'AAA', 'Gas', 'Freezer Meals', 'House Cleaning')); // Label for pointsprog_data
    foreach ($pointsprog_data as $row){
        fputcsv($fp, $row);
    }

    // Close file pointer
    fclose($fp);

    // Prompt download
    header('Content-Type: application/csv');
    header('Content-Disposition: attachment; filename="' . $filename . '"');
    readfile($filename);

    // Delete file
    unlink($filename);
    exit;
    }
    
    if($_POST['status']=="Active"){
        $filename = "active_families_report.csv";
        $fp = fopen($filename, 'w');

        $active_data=fetch_active_data();
        fputcsv($fp, array('Expected Treatment End Date','Remission Transition Date',
        'Parent Name','First Name', 'Last Name', 'Contact Method','Phone','Email', 'Address', 
        'Lead Volunteer', 'Birthday','Diagnosis Date', 'Diagnosis','Sibling Info','Hospital',
        'Start Date', 'AAA Membership', 'Gas', 'Grocery', 'Gift Card Delivery Method', 'Freezer Meals',
        'Snacks', 'Allergies','House Cleaning', 'Lawn Care', 'Photography', 'Location',  ));

        // Write current_families_data
        foreach ($active_data as $row) {
            fputcsv($fp, $row);
        }
        // Close file pointer
        fclose($fp);

        // Prompt download
        header('Content-Type: application/csv');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        readfile($filename);

        // Delete file
        unlink($filename);
        exit;
    }

    //if remission/survivor selected, generate report on all families with remission or survivor status
    if($_POST['status']=="Remission"){
        $filename = "remission_survivor_report.csv";
        $fp = fopen($filename, 'w');
        
        $remission_data=fetch_remission_survivor_data();

        // Write CSV header
        fputcsv($fp, array('Parent Name','First Name', 'Last Name', 'Remission End Date',
        'Expected Treatment End Date', 'Remission Transition Date', 'Email', 'Phone','Address', 
        'Location','Birthday', 'Diagnosis Date', 'Diagnosis','Hospital', 
        'Allergies', 'Sibling Info','Lead Volunteer',));
    
        // Write current_families_data
        foreach ($remission_data as $row) {
            fputcsv($fp, $row);
        }
        // Close file pointer
        fclose($fp);

        // Prompt download
        header('Content-Type: application/csv');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        readfile($filename);

        // Delete file
        unlink($filename);
        exit;
    }

    if($_POST['status']=="Stargazer"){
        $filename = "stargazer_report.csv";
        $fp = fopen($filename, 'w');
        
        $stargazer_data=fetch_stargazer_data();

        // Write CSV header
        fputcsv($fp, array('Parent Name','First Name', 'Last Name', 'Email', 'Phone','Address', 
        'Location','Birthday', 'Remembrance Date','Remembrance Wishes',));
    
        // Write current_families_data
        foreach ($stargazer_data as $row) {
            fputcsv($fp, $row);
        }
        // Close file pointer
        fclose($fp);

        // Prompt download
        header('Content-Type: application/csv');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        readfile($filename);

        // Delete file
        unlink($filename);
        exit;
    }
    echo "<script> location.replace('index.php'); </script>";
}

// Close the database connection
//mysqli_close($con);
?>

<!DOCTYPE html>
<html>
<head>
    <?php require_once('universal.inc'); ?>
    
    <title>Generate CSV</title>
</head>
<body>
    <?php require_once('header.php'); ?>
    <h1>Generate CSV</h1>
    <form method="post" action="">
        <label name="status"> Select the family status you want a report on </label>
        <select name="status" id="status">
            <option value="select"> Select a Status </option>
            <option value="Active"> Active </option>
            <option value="Remission"> Remission/Survivor </option>
            <!--<option value="Survivor"> Survivor </option>-->
            <option value="Stargazer"> Stargazer </option>
        </select>
        <button type="submit" name="generate_csv"> Generate CVS </button>
        <br></br>
        <button type="submit" name="generate_csv_all">Generate CSV for All Families</button>
    </form>
</body>
</html>
