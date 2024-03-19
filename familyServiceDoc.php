<?php
session_start();
require_once('header.php');


// Check if the form is submitted
//if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Process the submitted form data

    // Retrieve and sanitize the input data
    $family_id = $_POST['id'];
    $location = $_POST['location'];
    $start_date = $_POST['start_date'];
    $lead_volunteer = $_POST['lead_volunteer'];
    $gift_card_delivery_method = $_POST['gift_card_delivery_method'];

//}

// Display the form to add values for location, start_date, lead_volunteer, and gift_card_delivery_method
?>

<form method="POST" action="">
    <input type="hidden" name="id" value="<?php echo $_GET['id']; ?>">
    <label for="location">Location:</label>
    <input type="text" name="location" id="location" required><br>

    <label for="start_date">Start Date:</label>
    <input type="date" name="start_date" id="start_date" required><br>

    <label for="lead_volunteer">Lead Volunteer:</label>
    <input type="text" name="lead_volunteer" id="lead_volunteer" required><br>

    <label for="gift_card_delivery_method">Gift Card Delivery Method:</label>
    <select name="gift_card_delivery_method" id="gift_card_delivery_method" required>
        <!-- Not sure which options we chose yet -->
        <option value="Email">Email</option>
        <option value="Mail">Mail</option>
        <option value="In Person">In Person</option>
    </select><br>

    <button type="submit">Submit</button>
</form>

<?php
require_once('universal.inc');
?>