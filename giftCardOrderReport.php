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
function fetch_family_names() {
    global $connection;

    // Query to fetch required data from the database
    $query = "SELECT name FROM dbPointsProg";

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

// Fetch names for report
$family_names = fetch_family_names();

function fetch_all_vendors() {
    global $connection;

    // Query to fetch all vendors
    $query = "SELECT vendorID, vendorName FROM dbGiftCardVendors";

    $result = mysqli_query($connection, $query);

    if (!$result) {
        die("Database query failed: " . mysqli_error($connection));
    }

    // Fetch data and return as an array
    $vendors = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $vendors[$row['vendorID']] = $row['vendorName'];
    }

    return $vendors;
}

// Fetch all vendors
$vendors = fetch_all_vendors();

// Generate CSV file
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Fetch data for the selected email
    //$query = "SELECT cMethod, phone1, email, address, first_name, last_name, birthday, diagnosis, diagnosis_date, hospital, expected_treatment_end_date, allergies, sibling_info FROM dbPersons WHERE id = '$selected_email_id'";
    //$result = mysqli_query($connection, $query);

    //if (!$result) {
    //    die("Database query failed: " . mysqli_error($connection));
    //}

    // Create CSV file
    $filename = "giftCardOrderReport.csv";
    $fp = fopen($filename, 'w');

    // Write CSV header
    fputcsv($fp, array('Preferred Contact Method', 'Phone', 'Email', 'Address', 'First Name', 'Last Name', 'Birthday', 'Diagnosis', 'Diagnosis Date', 'Hospital', 'Expected Treatment End Date', 'Allergies', 'Sibling Info'));

    // Write data rows
    while ($row = mysqli_fetch_assoc($result)) {
        // Extract values from the associative array
        $values = array_values($row);
        // Write the values to the CSV file
        fputcsv($fp, $values);
    }

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
    <title>Generate CSV</title>
</head>
<body>
    <?php require_once('header.php'); ?>
    <h1>Generate CSV</h1>
    <form method="post" action="">
        <label for="email">Select Email:</label>
        <select name="email" id="email">
            <?php foreach ($emails as $id => $email) : ?>
                <option value="<?php echo $id; ?>"><?php echo $email; ?></option>
            <?php endforeach; ?>
        </select>
        <button type="submit">Generate CSV</button>
        <br>
        <br>
        <br>
        <br>
        <br>
        <br>
        <br>
    </form>
    <a href="giftCardManagement.php" class="button cancel">Return to Gift Card Management</a>
    <div class="space-below-button"></div>
    <br>
    <a href="index.php" class="button cancel">Return to Dashboard</a>
</body>
</html>