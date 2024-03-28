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
            
            // echo "<ul>";
            echo "<div class=\"item\">ID: " . $person->get_id() . " </div>";
            echo "<div class=\"item\">Start Date: " . $person->get_start_date() . " </div>";
            echo "<div class=\"item\">Venue: " . $person->get_venue() . " </div>";
            echo "<div class=\"item\">First Name: " . $person->get_first_name() . " </div>";
            echo "<div class=\"item\">Last Name: " . $person->get_last_name() . " </div>";
            echo "<div class=\"item\">Address: " . $person->get_address() . " </div>";
            echo "<div class=\"item\">City: " . $person->get_city() . " </div>";
            echo "<div class=\"item\">State: " . $person->get_state() . " </div>";
            echo "<div class=\"item\">Zip: " . $person->get_zip() . " </div>";
            echo "<div class=\"item\">Profile Picture: " . $person->get_profile_pic() . " </div>";
            echo "<div class=\"item\">Phone 1: " . $person->get_phone1() . " </div>";
            echo "<div class=\"item\">Phone 1 Type: " . $person->get_phone1type() . " </div>";
            echo "<div class=\"item\">Phone 2: " . $person->get_phone2() . " </div>";
            echo "<div class=\"item\">Phone 2 Type: " . $person->get_phone2type() . " </div>";
            echo "<div class=\"item\">Birthday: " . $person->get_birthday() . " </div>";
            echo "<div class=\"item\">Email: " . $person->get_email() . " </div>";
            echo "<div class=\"item\">Contact Name: " . $person->get_contact_name() . " </div>";
            echo "<div class=\"item\">Contact Number: " . $person->get_contact_num() . " </div>";
            echo "<div class=\"item\">Relation: " . $person->get_relation() . " </div>";
            echo "<div class=\"item\">Contact Time: " . $person->get_contact_time() . " </div>";
            echo "<div class=\"item\">Contact Method: " . $person->get_cMethod() . " </div>";
            echo "<div class=\"item\">How Did You Hear: " . $person->get_how_did_you_hear() . " </div>";
            echo "<div class=\"item\">Type: " . implode(", ", $person->get_type()) . " </div>"; // implode the array for display
            echo "<div class=\"item\">Status: " . $person->get_status() . " </div>";
            echo "<div class=\"item\">Availability: " . implode(", ", $person->get_availability()) . " </div>"; // implode the array for display
            echo "<div class=\"item\">Schedule: " . implode(", ", $person->get_schedule()) . " </div>"; // implode the array for display
            echo "<div class=\"item\">Hours: " . implode(", ", $person->get_hours()) . " </div>"; // implode the array for display
            echo "<div class=\"item\">Notes: " . $person->get_notes() . " </div>";
            echo "<div class=\"item\">Password: " . $person->get_password() . " </div>";
            echo "<div class=\"item\">Sunday Availability Start: " . $person->get_sunday_availability_start() . " </div>";
            echo "<div class=\"item\">Sunday Availability End: " . $person->get_sunday_availability_end() . " </div>";
            echo "<div class=\"item\">Monday Availability Start: " . $person->get_monday_availability_start() . " </div>";
            echo "<div class=\"item\">Monday Availability End: " . $person->get_monday_availability_end() . " </div>";
            echo "<div class=\"item\">Tuesday Availability Start: " . $person->get_tuesday_availability_start() . " </div>";
            echo "<div class=\"item\">Tuesday Availability End: " . $person->get_tuesday_availability_end() . " </div>";
            echo "<div class=\"item\">Wednesday Availability Start: " . $person->get_wednesday_availability_start() . " </div>";
            echo "<div class=\"item\">Wednesday Availability End: " . $person->get_wednesday_availability_end() . " </div>";
            echo "<div class=\"item\">Thursday Availability Start: " . $person->get_thursday_availability_start() . " </div>";
            echo "<div class=\"item\">Thursday Availability End: " . $person->get_thursday_availability_end() . " </div>";
            echo "<div class=\"item\">Friday Availability Start: " . $person->get_friday_availability_start() . " </div>";
            echo "<div class=\"item\">Friday Availability End: " . $person->get_friday_availability_end() . " </div>";
            echo "<div class=\"item\">Saturday Availability Start: " . $person->get_saturday_availability_start() . " </div>";
            echo "<div class=\"item\">Saturday Availability End: " . $person->get_saturday_availability_end() . " </div>";
            echo "<div class=\"item\">Access Level: " . $person->get_access_level() . " </div>";
            echo "<div class=\"item\">Access Level: " . $person->get_access_level() . " </div>";
            echo "<div class=\"item\">Is Password Change Required? " . ($person->is_password_change_required() ? "Yes" : "No") . " </div>";
            echo "<div class=\"item\">Gender: " . $person->get_gender() . " </div>";
            echo "<div class=\"item\">Diagnosis: " . $person->get_diagnosis() . " </div>";
            echo "<div class=\"item\">Diagnosis Date: " . $person->get_diagnosis_date() . " </div>";
            echo "<div class=\"item\">Hospital: " . $person->get_hospital() . " </div>";
            echo "<div class=\"item\">Permission to Confirm: " . $person->get_permission_to_confirm() . " </div>";
            echo "<div class=\"item\">Expected Treatment End Date: " . $person->get_expected_treatment_end_date() . " </div>"; 
            echo "<div class=\"item\">Allergies: " . $person->get_allergies() . " </div>";
            echo "<div class=\"item\">Sibling Info: " . $person->get_sibling_info() . " </div>";
            echo "<div class=\"item\">Can Share Contact Info? " . ($person->get_can_share_contact_info() ? "Yes" : "No") . " </div>";
            echo "<div class=\"item\">Username: " . $person->get_username() . " </div>";
            echo "<div class=\"item\">Meals: " . $person->get_meals() . " </div>";
            echo "<div class=\"item\">Housecleaning: " . $person->get_housecleaning() . " </div>";
            echo "<div class=\"item\">Lawncare: " . $person->get_lawncare() . " </div>";
            echo "<div class=\"item\">Photography: " . $person->get_photography() . " </div>";
            echo "<div class=\"item\">Gas: " . $person->get_gas() . " </div>";
            echo "<div class=\"item\">Grocery: " . $person->get_grocery() . " </div>";
            echo "<div class=\"item\">AAA Interest: " . $person->get_aaaInterest() . " </div>";
            echo "<div class=\"item\">Social Events: " . $person->get_socialEvents() . " </div>";
            echo "<div class=\"item\">House Projects: " . $person->get_houseProjects() . " </div>";
            echo "<div class=\"item\">Lead Volunteer: " . $person->get_leadVolunteer() . " </div>";
            echo "<div class=\"item\">Gift Card Delivery Method: " . $person->get_gift_card_delivery_method() . " </div>";
            echo "<div class=\"item\">Location: " . $person->get_location() . " </div>";
            echo "<div class=\"item\">Family Info: " . $person->get_familyInfo() . " </div>";
            // echo "</ul>";
            ?>
            </div>
            <!-- Return button -->
            <a class="button cancel" href="viewFamilyAccounts.php">Return to Family List</a>
        </form>
    </body>
    </html>
    