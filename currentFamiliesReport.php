<?php
session_start();
require_once('header.php');

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

    // Query to fetch required data from the database
    $query = "SELECT cMethod, phone1, email, address, first_name, last_name, birthday, diagnosis, diagnosis_date, hospital, expected_treatment_end_date, allergies, sibling_info FROM dbPersons";

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

function fetch_all_emails() {
    global $connection;

    // Query to fetch all emails
    $query = "SELECT id, email FROM dbPersons";

    $result = mysqli_query($connection, $query);

    if (!$result) {
        die("Database query failed: " . mysqli_error($connection));
    }

    // Fetch data and return as an array
    $emails = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $emails[$row['id']] = $row['email'];
    }

    return $emails;
}

// Fetch all emails
$emails = fetch_all_emails();

// Generate CSV file
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['email'])) {
    $selected_email_id = $_POST['email'];

    // Fetch data for the selected email
    $query = "SELECT cMethod, phone1, email, address, first_name, last_name, birthday, diagnosis, diagnosis_date, hospital, expected_treatment_end_date, allergies, sibling_info FROM dbPersons WHERE id = '$selected_email_id'";
    $result = mysqli_query($connection, $query);

    if (!$result) {
        die("Database query failed: " . mysqli_error($connection));
    }

    // Create CSV file
    $filename = "current_family_report.csv";
    $fp = fopen($filename, 'w');

    // Write CSV header
    fputcsv($fp, array('Preferred Contact Method', 'Phone', 'Email', 'Address', 'First Name', 'Last Name', 'Birthday', 'Diagnosis', 'Diagnosis Date', 'Hospital', 'Expected Treatment End Date', 'Allergies', 'Sibling Info'));

    // Write data rows
    while ($row = mysqli_fetch_assoc($result)) {
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
    <title>Generate CSV</title>
</head>
<body>
    <?php require_once('universal.inc'); ?>
    <h1>Generate CSV</h1>
    <form method="post" action="">
        <label for="email">Select Email:</label>
        <select name="email" id="email">
            <?php foreach ($emails as $id => $email) : ?>
                <option value="<?php echo $id; ?>"><?php echo $email; ?></option>
            <?php endforeach; ?>
        </select>
        <button type="submit">Generate CSV</button>
    </form>

</body>
</html>