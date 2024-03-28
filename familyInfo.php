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
            echo "<div class=\"label\">First Name</div>";
            echo "<div class=\"label\">Last Name</div>";
            echo "<div class=\"label\">Address</div>";
            echo "<div class=\"item\">" . $person->get_first_name() . " </div>";
            echo "<div class=\"item\">" . $person->get_last_name() . " </div>";
            echo "<div class=\"item\">" . $person->get_address() . " </div>";
            echo "<div class=\"label\">ID</div>";
            echo "<div class=\"label\">Start Date</div>";
            echo "<div class=\"label\">Venue</div>";
            echo "<div class=\"item\">" . $person->get_id() . " </div>";
            echo "<div class=\"item\">" . $person->get_start_date() . " </div>";
            echo "<div class=\"item\">" . $person->get_venue() . " </div>";
            echo "<div class=\"label\">City</div>";
            echo "<div class=\"label\">State</div>";
            echo "<div class=\"label\">Zip</div>";
            echo "<div class=\"item\">" . $person->get_city() . " </div>";
            echo "<div class=\"item\">" . $person->get_state() . " </div>";
            echo "<div class=\"item\">" . $person->get_zip() . " </div>";
            echo "<div class=\"label\">Phone 1</div>";
            echo "<div class=\"label\">Phone 1 Type</div>";
            echo "<div class=\"label\">Birthday</div>";
            echo "<div class=\"item\">" . $person->get_phone1() . " </div>";
            echo "<div class=\"item\">" . $person->get_phone1type() . " </div>";
            echo "<div class=\"item\">" . $person->get_birthday() . " </div>";
            echo "<div class=\"label\">Email</div>";
            echo "<div class=\"label\">Parent Name</div>";
            echo "<div class=\"label\">Contact Method</div>";
            echo "<div class=\"item\">" . $person->get_email() . " </div>";
            echo "<div class=\"item\">" . $person->get_contact_name() . " </div>";
            echo "<div class=\"item\">" . $person->get_cMethod() . " </div>";
            echo "<div class=\"label\">How Did You Hear</div>";
            echo "<div class=\"label\">Type</div>";
            echo "<div class=\"label\">Status</div>";
            echo "<div class=\"item\">" . $person->get_how_did_you_hear() . " </div>";
            echo "<div class=\"item\">" . implode(", ", $person->get_type()) . " </div>";
            echo "<div class=\"item\">" . $person->get_status() . " </div>";
            echo "<div class=\"label\">Notes</div>";
            echo "<div class=\"label\">Password</div>";
            echo "<div class=\"label\">Is Password Change Required?</div>";
            echo "<div class=\"item\">" . $person->get_notes() . " </div>";
            echo "<div class=\"item\">" . $person->get_password() . " </div>";
            echo "<div class=\"item\">" . ($person->is_password_change_required() ? "Yes" : "No") . " </div>";
            echo "<div class=\"label\">Diagnosis</div>";
            echo "<div class=\"label\">Diagnosis Date</div>";
            echo "<div class=\"label\">Hospital</div>";
            echo "<div class=\"item\">" . $person->get_diagnosis() . " </div>";
            echo "<div class=\"item\">" . $person->get_diagnosis_date() . " </div>";
            echo "<div class=\"item\">" . $person->get_hospital() . " </div>";
            echo "<div class=\"label\">Permission to Confirm</div>";
            echo "<div class=\"label\">Expected Treatment End Date</div>";
            echo "<div class=\"label\">Allergies</div>";
            echo "<div class=\"item\">" . $person->get_permission_to_confirm() . " </div>";
            echo "<div class=\"item\">" . $person->get_expected_treatment_end_date() . " </div>"; 
            echo "<div class=\"item\">" . $person->get_allergies() . " </div>";
            echo "<div class=\"label\">Sibling Info</div>";
            echo "<div class=\"label\">Can Share Contact Info?</div>";
            echo "<div class=\"label\">Username</div>";
            echo "<div class=\"item\">" . $person->get_sibling_info() . " </div>";
            echo "<div class=\"item\">" . ($person->get_can_share_contact_info() ? "Yes" : "No") . " </div>";
            echo "<div class=\"item\">" . $person->get_username() . " </div>";
            echo "<div class=\"label\">Meals</div>";
            echo "<div class=\"label\">House Cleaning</div>";
            echo "<div class=\"label\">Lawncare</div>";
            if ($person->get_meals() == 0) {
                echo "<div class=\"item\">Not Interested </div>";
            }
            else {
                echo "<div class=\"item\">Interested </div>";
            }
             
            if ($person->get_housecleaning() == 0) {
                echo "<div class=\"item\">Not Interested </div>";
            }
            else {
                echo "<div class=\"item\">Interested </div>";
            }
            if ($person->get_lawncare() == 0) {
                echo "<div class=\"item\">Not Interested </div>";
            }
            else {
                echo "<div class=\"item\">Interested </div>";
            }
            echo "<div class=\"label\">Photography</div>";
            echo "<div class=\"label\">Gas</div>";
            echo "<div class=\"label\">Grocery</div>";
            if ($person->get_photography() == 0) {
                echo "<div class=\"item\">Not Interested </div>";
            }
            else {
                echo "<div class=\"item\">Interested </div>";
            }
            if ($person->get_gas() == 0) {
                echo "<div class=\"item\">Not Interested </div>";
            }
            else {
                echo "<div class=\"item\">Interested </div>";
            }
            if ($person->get_grocery() == 0) {
                echo "<div class=\"item\">Not Interested </div>";
            }
            else {
                echo "<div class=\"item\">Interested </div>";
            }
            echo "<div class=\"label\">AAA Interest</div>";
            echo "<div class=\"label\">Social Events</div>";
            echo "<div class=\"label\">House Projects</div>";
            if ($person->get_aaaInterest() == 0) {
                echo "<div class=\"item\">Not Interested </div>";
            }
            else {
                echo "<div class=\"item\">Interested </div>";
            }
            if ($person->get_socialEvents() == 0) {
                echo "<div class=\"item\">Not Interested </div>";
            } else {
                echo "<div class=\"item\">Interested </div>";
            }
            
            if ($person->get_houseProjects() == 0) {
                echo "<div class=\"item\">Not Interested </div>";
            } else {
                echo "<div class=\"item\">Interested </div>";
            }
            echo "<div class=\"label\">Lead Volunteer</div>";
            echo "<div class=\"label\">Gift Card Delivery Method</div>";
            echo "<div class=\"label\">Location</div>";
            if ($person->get_aaaInterest() == 0) {
                echo "<div class=\"item\">AAA Interest: Not Interested </div>";
            } else {
                echo "<div class=\"item\">AAA Interest: Interested </div>";
            }
            echo "<div class=\"item\">Gift Card Delivery Method: " . $person->get_gift_card_delivery_method() . " </div>";
            echo "<div class=\"item\">Location: " . $person->get_location() . " </div>";
            echo "<div class=\"label\">Family Info</div>";
            echo "<div class=\"label\"></div>";
            echo "<div class=\"label\"></div>";
            echo "<div class=\"item\">Family Info: " . $person->get_familyInfo() . " </div>";
            ?>
            </div>
            <!-- Return button -->
            <a class="button cancel" href="viewFamilyAccounts.php">Return to Family List</a>
        </form>
    </body>
    </html>
    