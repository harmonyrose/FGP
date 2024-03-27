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
    <?php require_once('universal.inc');
    echo '<title> FGP | ' . $person->get_first_name() . ' ' . $person->get_last_name() . '</title>'; ?>
</head>
<body>
    <?php require_once('header.php');
    echo '<h1>' . $person->get_first_name() . '\'s Information</h1>'; ?>
    <form id="family-list" class="general" method="get">
        <!-- The information -->
        <?php
            // Call each getter function and print the information
            echo "<ul>";
            echo "<li>ID: " . $person->get_id() . "</li>";
            echo "<li>Start Date: " . $person->get_start_date() . "</li>";
            echo "<li>Venue: " . $person->get_venue() . "</li>";
            echo "<li>First Name: " . $person->get_first_name() . "</li>";
            echo "<li>Last Name: " . $person->get_last_name() . "</li>";
            echo "<li>Address: " . $person->get_address() . "</li>";
            echo "<li>City: " . $person->get_city() . "</li>";
            echo "<li>State: " . $person->get_state() . "</li>";
            echo "<li>Zip: " . $person->get_zip() . "</li>";
            echo "<li>Profile Picture: " . $person->get_profile_pic() . "</li>";
            echo "<li>Phone 1: " . $person->get_phone1() . "</li>";
            echo "<li>Phone 1 Type: " . $person->get_phone1type() . "</li>";
            echo "<li>Phone 2: " . $person->get_phone2() . "</li>";
            echo "<li>Phone 2 Type: " . $person->get_phone2type() . "</li>";
            echo "<li>Birthday: " . $person->get_birthday() . "</li>";
            echo "<li>Email: " . $person->get_email() . "</li>";
            echo "<li>Contact Name: " . $person->get_contact_name() . "</li>";
            echo "<li>Contact Number: " . $person->get_contact_num() . "</li>";
            echo "<li>Relation: " . $person->get_relation() . "</li>";
            echo "<li>Contact Time: " . $person->get_contact_time() . "</li>";
            echo "<li>Contact Method: " . $person->get_cMethod() . "</li>";
            echo "<li>How Did You Hear: " . $person->get_how_did_you_hear() . "</li>";
            echo "<li>Type: " . implode(", ", $person->get_type()) . "</li>"; // implode the array for display
            echo "<li>Status: " . $person->get_status() . "</li>";
            echo "<li>Availability: " . implode(", ", $person->get_availability()) . "</li>"; // implode the array for display
            echo "<li>Schedule: " . implode(", ", $person->get_schedule()) . "</li>"; // implode the array for display
            echo "<li>Hours: " . implode(", ", $person->get_hours()) . "</li>"; // implode the array for display
            echo "<li>Notes: " . $person->get_notes() . "</li>";
            echo "<li>Password: " . $person->get_password() . "</li>";
            echo "<li>Sunday Availability Start: " . $person->get_sunday_availability_start() . "</li>";
            echo "<li>Sunday Availability End: " . $person->get_sunday_availability_end() . "</li>";
            echo "<li>Monday Availability Start: " . $person->get_monday_availability_start() . "</li>";
            echo "<li>Monday Availability End: " . $person->get_monday_availability_end() . "</li>";
            echo "<li>Tuesday Availability Start: " . $person->get_tuesday_availability_start() . "</li>";
            echo "<li>Tuesday Availability End: " . $person->get_tuesday_availability_end() . "</li>";
            echo "<li>Wednesday Availability Start: " . $person->get_wednesday_availability_start() . "</li>";
            echo "<li>Wednesday Availability End: " . $person->get_wednesday_availability_end() . "</li>";
            echo "<li>Thursday Availability Start: " . $person->get_thursday_availability_start() . "</li>";
            echo "<li>Thursday Availability End: " . $person->get_thursday_availability_end() . "</li>";
            echo "<li>Friday Availability Start: " . $person->get_friday_availability_start() . "</li>";
            echo "<li>Friday Availability End: " . $person->get_friday_availability_end() . "</li>";
            echo "<li>Saturday Availability Start: " . $person->get_saturday_availability_start() . "</li>";
            echo "<li>Saturday Availability End: " . $person->get_saturday_availability_end() . "</li>";
            echo "<li>Access Level: " . $person->get_access_level() . "</li>";
            echo "<li>Access Level: " . $person->get_access_level() . "</li>";
            echo "<li>Is Password Change Required? " . ($person->is_password_change_required() ? "Yes" : "No") . "</li>";
            echo "<li>Gender: " . $person->get_gender() . "</li>";
            echo "<li>Diagnosis: " . $person->get_diagnosis() . "</li>";
            echo "<li>Diagnosis Date: " . $person->get_diagnosis_date() . "</li>";
            echo "<li>Hospital: " . $person->get_hospital() . "</li>";
            echo "<li>Permission to Confirm: " . $person->get_permission_to_confirm() . "</li>";
            echo "<li>Expected Treatment End Date: " . $person->get_expected_treatment_end_date() . "</li>";
            echo "<li>Services Interested In: " . $person->get_services_interested_in() . "</li>";
            echo "<li>Allergies: " . $person->get_allergies() . "</li>";
            echo "<li>Sibling Info: " . $person->get_sibling_info() . "</li>";
            echo "<li>Can Share Contact Info? " . ($person->get_can_share_contact_info() ? "Yes" : "No") . "</li>";
            echo "<li>Username: " . $person->get_username() . "</li>";
            echo "<li>Meals: " . $person->get_meals() . "</li>";
            echo "<li>Housecleaning: " . $person->get_housecleaning() . "</li>";
            echo "<li>Lawncare: " . $person->get_lawncare() . "</li>";
            echo "<li>Photography: " . $person->get_photography() . "</li>";
            echo "<li>Gas: " . $person->get_gas() . "</li>";
            echo "<li>Grocery: " . $person->get_grocery() . "</li>";
            echo "<li>AAA Interest: " . $person->get_aaaInterest() . "</li>";
            echo "<li>Social Events: " . $person->get_socialEvents() . "</li>";
            echo "<li>House Projects: " . $person->get_houseProjects() . "</li>";
            echo "<li>Lead Volunteer: " . $person->get_leadVolunteer() . "</li>";
            echo "<li>Gift Card Delivery Method: " . $person->get_gift_card_delivery_method() . "</li>";
            echo "<li>Location: " . $person->get_location() . "</li>";
            echo "<li>Family Info: " . $person->get_familyInfo() . "</li>";
            echo "</ul>";
            ?>
            <!-- Return button -->
            <a class="button cancel" href="viewFamilyAccounts.php">Return to Family List</a>
        </form>
    </body>
    </html>
    