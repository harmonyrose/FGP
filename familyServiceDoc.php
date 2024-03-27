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

// Function to update family information
function update_family_info($id, $location, $start_date, $lead_volunteer, $gift_card_delivery_method) {
    global $connection; // Access the global $connection variable
    $query = "UPDATE dbPersons SET location = '$location', start_date = '$start_date', leadVolunteer = '$lead_volunteer', gift_card_delivery_method = '$gift_card_delivery_method' WHERE id = '$id'";
    $result = mysqli_query($connection, $query);
    if (!$result) {
        die("Update failed: " . mysqli_error($connection));
    }
}

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST['id']; // Family ID
    $location = $_POST['location'];
    $start_date = $_POST['start_date'];
    $lead_volunteer = $_POST['leadVolunteer'];
    $gift_card_delivery_method = $_POST['gift_card_delivery_method'];

    // Update family information
    update_family_info($id, $location, $start_date, $lead_volunteer, $gift_card_delivery_method);
    // Display success message
    echo "Update successful.";
    header("Location: approve.php");

}

// Retrieve family information
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $query = "SELECT * FROM dbPersons WHERE id = '$id'";
    $result = mysqli_query($connection, $query);
    if (!$result) {
        die("Database query failed.");
    }
    $row = mysqli_fetch_assoc($result);
    $location = $row['location'];
    $start_date = $row['start_date'];
    $lead_volunteer = $row['leadVolunteer'];
    $gift_card_delivery_method = $row['gift_card_delivery_method'];
}
?>

<!-- Form to edit family information -->
<form method="POST" action="">
    <input type="hidden" name="id" value="<?php echo $id; ?>">
    <label for="location">Location:</label>
    <input type="text" name="location" id="location" value="<?php echo $location; ?>" required><br>

    <label for="start_date">Start Date:</label>
    <input type="date" name="start_date" id="start_date" value="<?php echo $start_date; ?>" required><br>

    <label for="lead_volunteer">Lead Volunteer:</label>
    <input type="text" name="lead_volunteer" id="lead_volunteer" value="<?php echo $lead_volunteer; ?>" required><br>

    <label for="gift_card_delivery_method">Gift Card Delivery Method:</label>
    <select name="gift_card_delivery_method" id="gift_card_delivery_method" required>
        <option value="Mail" <?php if ($gift_card_delivery_method == 'Mail') echo 'selected'; ?>>Mail</option>
        <option value="In Person" <?php if ($gift_card_delivery_method == 'In Person') echo 'selected'; ?>>In Person</option>
    </select><br>

    <button type="submit">Submit</button>
</form>

<?php require_once('universal.inc'); ?>
