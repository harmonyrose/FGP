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

require_once('database/dbPersons.php');
$person = retrieve_person($_GET['family_id']);

if (!$person) {
    die("Person not found.");
}

function update_family_info1($id, $location, $gift_card_delivery_method, $first_name, $last_name, $phone1, $phone1type, $contact_name, $cMethod, $hospital, $expected_treatment_end_date, $can_share_contact_info) {
    global $connection; // Access the global $connection variable
    
    $query = "UPDATE dbPersons SET 
              location = '$location', 
              gift_card_delivery_method = '$gift_card_delivery_method', 
              first_name = '$first_name', 
              last_name = '$last_name', 
              phone1 = '$phone1', 
              phone1type = '$phone1type', 
              contact_name = '$contact_name', 
              cMethod = '$cMethod', 
              hospital = '$hospital', 
              expected_treatment_end_date = '$expected_treatment_end_date', 
              can_share_contact_info = '$can_share_contact_info' 
              WHERE id = '$id'";
    
    
    $result = mysqli_query($connection, $query);
    if (!$result) {
        die("Update failed: " . mysqli_error($connection));
    }
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST['id']; // Family ID

    // Sanitize and retrieve other form input values
    $location = mysqli_real_escape_string($connection, $_POST['location']);
    $gift_card_delivery_method = mysqli_real_escape_string($connection, $_POST['gift_card_delivery_method']);
    $first_name = mysqli_real_escape_string($connection, $_POST['first_name']);
    $last_name = mysqli_real_escape_string($connection, $_POST['last_name']);
    $phone1 = mysqli_real_escape_string($connection, $_POST['phone1']);
    $phone1type = mysqli_real_escape_string($connection, $_POST['phone1type']);
    $contact_name = mysqli_real_escape_string($connection, $_POST['contact_name']);
    $cMethod = mysqli_real_escape_string($connection, $_POST['cMethod']);
    $hospital = mysqli_real_escape_string($connection, $_POST['hospital']);
    $expected_treatment_end_date = mysqli_real_escape_string($connection, $_POST['expected_treatment_end_date']);
    $can_share_contact_info = mysqli_real_escape_string($connection, $_POST['can_share_contact_info']);

    // Update family information
    update_family_info1($id, $location, $gift_card_delivery_method, $first_name, $last_name, $phone1, $phone1type, $contact_name, $cMethod, $hospital, $expected_treatment_end_date, $can_share_contact_info);
    
    // Redirect to viewFamilyAccounts.php after updating
    header("Location: viewFamilyAccounts.php");
    exit(); // Terminate script execution after redirect
}
?>

<!DOCTYPE html>
<html>
<head>
    <?php require_once('universal.inc') ?>
    <title>FGP | Modify Family</title>
</head>
<body>
    <?php require_once('header.php') ?>
    
    <h1>Modify Family</h1>
    <form id="modify-family" class="general" method="post">
        <!-- Modify family form -->
        <input type="hidden" name="id" value="<?php echo $person->get_id(); ?>">
        <!-- Example input fields -->
    
        <label for="first_name">First Name:</label> Current First Name: <?php echo $person->get_first_name(); ?>
        <input type="text" id="first_name" name="first_name" value="<?php echo $person->get_first_name(); ?>" required>

        <label for="last_name">Last Name:</label> Current Last Name: <?php echo $person->get_last_name(); ?>
        <input type="text" id="last_name" name="last_name" value="<?php echo $person->get_last_name(); ?>" required>

        <label for="phone1">Phone Number:</label> Current Phone Number: <?php echo $person->get_phone1(); ?>
        <input type="text" id="phone1" name="phone1" value="<?php echo $person->get_phone1(); ?>" required>

        <label for="phone1type">Phone 1 Type:</label> Current Phone 1 Type: <?php echo $person->get_phone1type(); ?>
        <input type="text" id="phone1type" name="phone1type" value="<?php echo $person->get_phone1type(); ?>" required>

        <label for="contact_name">Contact Name:</label> Current Contact Name: <?php echo $person->get_contact_name(); ?>
        <input type="text" id="contact_name" name="contact_name" value="<?php echo $person->get_contact_name(); ?>" required>

        <label for="cMethod">Contact Method:</label> Current Contact Method: <?php echo $person->get_cMethod(); ?>
        <input type="text" id="cMethod" name="cMethod" value="<?php echo $person->get_cMethod(); ?>" required>

        <label for="hospital">Hospital:</label> Current Hospital: <?php echo $person->get_hospital(); ?>
        <input type="text" id="hospital" name="hospital" value="<?php echo $person->get_hospital(); ?>" required>

        <label for="expected_treatment_end_date">Expected Treatment End Date:</label> Current Expected Treatment End Date: <?php echo $person->get_expected_treatment_end_date(); ?>
        <input type="text" id="expected_treatment_end_date" name="expected_treatment_end_date" value="<?php echo $person->get_expected_treatment_end_date(); ?>" required>

        <label for="can_share_contact_info">Can Share Contact Info:</label> Current Can Share Contact Info: <?php echo $person->get_can_share_contact_info(); ?>
        <input type="text" id="can_share_contact_info" name="can_share_contact_info" value="<?php echo $person->get_can_share_contact_info(); ?>" required>

        <label for="location">Location:</label> Current Location: <?php echo $person->get_location(); ?>
        <input type="text" id="location" name="location" value="<?php echo $person->get_location(); ?>" required>

        <label for="gift_card_delivery_method">Gift Card Delivery Method:</label> Current Gift Card Delivery Method: <?php echo $person->get_gift_card_delivery_method(); ?>
        <input type="text" id="gift_card_delivery_method" name="gift_card_delivery_method" value="<?php echo $person->get_gift_card_delivery_method(); ?>" required>

        <!-- Other input fields for modifying family details -->
        <button type="submit" class="button">Save Changes</button>
        <a class="button cancel" href="viewFamilyAccounts.php">Cancel</a>
    </form>
</body>
</html>
<?php require_once('universal.inc'); ?>
