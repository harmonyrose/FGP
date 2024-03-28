<?php
    // Template for new VMS pages. Base your new page on this one

    // Make session information accessible, allowing us to associate
    // data with the logged-in user.
    session_cache_expire(30);
    session_start();

    $loggedIn = false;
    $accessLevel = 0;
    $userID = null;
    if (isset($_SESSION['_id'])) {
        $loggedIn = true;
        // 0 = not logged in, 1 = standard user, 2 = manager (Admin), 3 super admin (TBI)
        $accessLevel = $_SESSION['access_level'];
        $userID = $_SESSION['_id'];
    }
    // admin-only access
    if ($accessLevel < 2) {
        header('Location: index.php');
        die();
    }

    require_once ('database/dbPersons.php');
    $person = retrieve_person($_GET['contact_id']);

?>

<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" type="text/css" href="css/familyInfo.css">
    <?php require_once('universal.inc');
    echo '<title> FGP | ' . $person->get_first_name() . ' ' . $person->get_last_name() . '</title>'; ?>
</head>
<body>
    <?php require_once('header.php');
    echo '<h1>' . $person->get_first_name() . '\'s Information</h1>'; ?>
    <form id="family-list" class="general" method="get">
        <!-- The information -->
        <div class="info-box">
        <?php
            // Call each getter function and print the information
            echo "<div class=\"item\">ID: " . $person->get_id() . " </div>";
            echo "<div class=\"item\">Start Date: " . $person->get_start_date() . " </div>";
            echo "<div class=\"item\">Venue: " . $person->get_venue() . " </div>";
            echo "<div class=\"item\">First Name: " . $person->get_first_name() . " </div>";
            echo "<div class=\"item\">Last Name: " . $person->get_last_name() . " </div>";
            echo "<div class=\"item\">Address: " . $person->get_address() . " </div>";
            echo "<div class=\"item\">City: " . $person->get_city() . " </div>";
            echo "<div class=\"item\">State: " . $person->get_state() . " </div>";
            echo "<div class=\"item\">Zip: " . $person->get_zip() . " </div>";
            echo "<div class=\"item\">Phone 1: " . $person->get_phone1() . " </div>";
            echo "<div class=\"item\">Phone 1 Type: " . $person->get_phone1type() . " </div>";
            echo "<div class=\"item\">Birthday: " . $person->get_birthday() . " </div>";
            echo "<div class=\"item\">Email: " . $person->get_email() . " </div>";
            echo "<div class=\"item\">Parent Name: " . $person->get_contact_name() . " </div>";
            echo "<div class=\"item\">Contact Method: " . $person->get_cMethod() . " </div>";
            echo "<div class=\"item\">How Did You Hear: " . $person->get_how_did_you_hear() . " </div>";
            echo "<div class=\"item\">Type: " . implode(", ", $person->get_type()) . " </div>"; // implode the array for display
            echo "<div class=\"item\">Status: " . $person->get_status() . " </div>";
            echo "<div class=\"item\">Notes: " . $person->get_notes() . " </div>";
            echo "<div class=\"item\">Password: " . $person->get_password() . " </div>";
            echo "<div class=\"item\">Is Password Change Required? " . ($person->is_password_change_required() ? "Yes" : "No") . " </div>";
            echo "<div class=\"item\">Diagnosis: " . $person->get_diagnosis() . " </div>";
            echo "<div class=\"item\">Diagnosis Date: " . $person->get_diagnosis_date() . " </div>";
            echo "<div class=\"item\">Hospital: " . $person->get_hospital() . " </div>";
            echo "<div class=\"item\">Permission to Confirm: " . $person->get_permission_to_confirm() . " </div>";
            echo "<div class=\"item\">Expected Treatment End Date: " . $person->get_expected_treatment_end_date() . " </div>"; 
            echo "<div class=\"item\">Allergies: " . $person->get_allergies() . " </div>";
            echo "<div class=\"item\">Sibling Info: " . $person->get_sibling_info() . " </div>";
            echo "<div class=\"item\">Can Share Contact Info? " . ($person->get_can_share_contact_info() ? "Yes" : "No") . " </div>";
            echo "<div class=\"item\">Username: " . $person->get_username() . " </div>";
            if ($person->get_meals() == 0) {
                echo "<div class=\"item\">Meals: Not Interested </div>";
            }
            else {
                echo "<div class=\"item\">Meals: Interested </div>";
            }
             
            if ($person->get_photography() == 0) {
                echo "<div class=\"item\">Photography: Not Interested </div>";
            }
            else {
                echo "<div class=\"item\">Photography: Interested </div>";
            }
            if ($person->get_photography() == 0) {
                echo "<div class=\"item\">Photography: Not Interested </div>";
            }
            else {
                echo "<div class=\"item\">Photography: Interested </div>";
            }
            if ($person->get_photography() == 0) {
                echo "<div class=\"item\">Photography: Not Interested </div>";
            }
            else {
                echo "<div class=\"item\">Photography: Interested </div>";
            }
            if ($person->get_photography() == 0) {
                echo "<div class=\"item\">Photography: Not Interested </div>";
            }
            else {
                echo "<div class=\"item\">Photography: Interested </div>";
            }
            if ($person->get_photography() == 0) {
                echo "<div class=\"item\">Photography: Not Interested </div>";
            }
            else {
                echo "<div class=\"item\">Photography: Interested </div>";
            }
            if ($person->get_photography() == 0) {
                echo "<div class=\"item\">Photography: Not Interested </div>";
            }
            else {
                echo "<div class=\"item\">Photography: Interested </div>";
            }
            if ($person->get_gas() == 0) {
                echo "<div class=\"item\">Gas: Not Interested </div>";
            } else {
                echo "<div class=\"item\">Gas: Interested </div>";
            }
            
            if ($person->get_grocery() == 0) {
                echo "<div class=\"item\">Grocery: Not Interested </div>";
            } else {
                echo "<div class=\"item\">Grocery: Interested </div>";
            }
            
            if ($person->get_aaaInterest() == 0) {
                echo "<div class=\"item\">AAA Interest: Not Interested </div>";
            } else {
                echo "<div class=\"item\">AAA Interest: Interested </div>";
            }
            
            if ($person->get_socialEvents() == 0) {
                echo "<div class=\"item\">Social Events: Not Interested </div>";
            } else {
                echo "<div class=\"item\">Social Events: Interested </div>";
            }
            
            if ($person->get_houseProjects() == 0) {
                echo "<div class=\"item\">House Projects: Not Interested </div>";
            } else {
                echo "<div class=\"item\">House Projects: Interested </div>";
            }
            
            if ($person->get_leadVolunteer() == 0) {
                echo "<div class=\"item\">Lead Volunteer: Not Interested </div>";
            } else {
                echo "<div class=\"item\">Lead Volunteer: Interested </div>";
            }
            
            echo "<div class=\"item\">Gift Card Delivery Method: " . $person->get_gift_card_delivery_method() . " </div>";
            echo "<div class=\"item\">Location: " . $person->get_location() . " </div>";
            echo "<div class=\"item\">Family Info: " . $person->get_familyInfo() . " </div>";
            ?>
            </div>
            <!-- Return button -->
            <a class="button cancel" href="viewFamilyAccounts.php">Return to Family List</a>
        </form>
    </body>
    </html>
    