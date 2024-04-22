<?php
session_start();

// Connect to the database
$hostname = "localhost"; 
$database = "fgp";
$username = "fgp";
$password = "fgp";

$connection = mysqli_connect($hostname, $username, $password, $database);

// Check if the connection was successful
if (!$connection) {
    die("Connection failed: " . mysqli_connect_error());
}

// Function to fetch data for the Current Families Report
function fetch_current_families_data() {
    global $connection;

    // Query to fetch required data from the database where type = 'family'
    $query = "SELECT cMethod, phone1, email, address, first_name, last_name, birthday, diagnosis, diagnosis_date, hospital, expected_treatment_end_date, allergies, sibling_info FROM dbPersons WHERE type = 'family'";

    $result = mysqli_query($connection, $query);

    if (!$result) {
        die("Database query failed: " . mysqli_error($connection));
    }

    // Fetch data and return as an array
    $data = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $data[] = $row;
    }

    return $data;
}

// Fetch data for the Current Families Report
$current_families_data = fetch_current_families_data();

// Generate CSV file
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['generate_csv'])) {

    // Create CSV file
    $filename = "current_family_report.csv";
    $fp = fopen($filename, 'w');

    // Write CSV header
    fputcsv($fp, array('Preferred Contact Method', 'Phone', 'Email', 'Address', 'First Name', 'Last Name', 'Birthday', 'Diagnosis', 'Diagnosis Date', 'Hospital', 'Expected Treatment End Date', 'Allergies', 'Sibling Info'));

    // Write data rows
    foreach ($current_families_data as $row) {
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

// Close the database connection
mysqli_close($connection);
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
            <option value="Remission"> Remission </option>
            <option value="Survivor"> Survivor </option>
            <option value="Stargazer"> Stargazer </option>
        </select>
        <button type="submit" name="generate_csv">Generate CSV for All Families</button>
    </form>
</body>
</html>
