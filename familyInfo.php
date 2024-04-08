<!-- familyInfo.php-->
<!-- Lists all the info of the family in the get requests in a table for easy access -->
<!-- Joshua Cottrell -->

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

    // Get the dbPersons information of the family in the get request so we can display all their information
    require_once ('database/dbPersons.php');
    $person = retrieve_person($_GET['contact_id']);


    // Check if status and contact_id are provided
    if (isset($_GET['status']) && isset($_GET['contact_id'])) {
        // Call the update_status() function
        $status = $_GET['status'];
        $contact_id = $_GET['contact_id'];
        update_status($contact_id, $status);
        // Redirect to the current page without the 'status' parameter but with the 'contact_id' parameter
        // This is not only done to make the url look good but also because it won't update the default option in the table if I don't
        $redirect_url = strtok($_SERVER["REQUEST_URI"], '?'); // Get the current URL without query parameters
        $contact_id_param = "contact_id=" . urlencode($_GET['contact_id']); // Get contact_id
        header("Location: $redirect_url?$contact_id_param"); // Combine to get full URL and then redirect there
        exit();
    }
    

?>

<!DOCTYPE html>
<html>
<head>
    <!-- Makes the page title unique to the family even though its the same base file -->
    <link rel="stylesheet" type="text/css" href="css/familyInfo.css">
    <?php require_once('universal.inc');
    echo '<title> FGP | ' . $person->get_first_name() . ' ' . $person->get_last_name() . '</title>'; ?>
</head>
<body>
    <!-- Unique family header for same reason as above -->
    <?php require_once('header.php');
    echo '<h1>' . $person->get_first_name() . '\'s Information</h1>'; ?>
    <form id="family-list" class="general" method="get">
        <!-- The families information presented in a table since it was the best way I could think of -->
        <!-- All the infromation is formatted the same, but I don't know if you can loop through each getter without stating each getter -->
        <!-- And since each getter is called only once, I think this was unforunately the most efficient way to do it -->
        <!-- But if I'm wrong update it or tell me how to update it -->
        <div class="table-wrapper"><table class="general" id="BooleanTable" style="margin-top: 30px">
                <tr>
                    <td>First Name</td>
                    <td><?php echo $person->get_first_name(); ?></td>
                </tr>
                <tr>
                    <td>Last Name</td>
                    <td><?php echo $person->get_last_name(); ?></td>
                </tr>
                <tr>
                    <td>Address</td>
                    <td><?php echo $person->get_address(); ?></td>
                </tr>
                <tr>
                    <td>ID</td>
                    <td><?php echo $person->get_id(); ?></td>
                </tr>
                <tr>
                    <td>Start Date</td>
                    <td><?php echo $person->get_start_date(); ?></td>
                </tr>
                <tr>
                    <td>Venue</td>
                    <td><?php echo $person->get_venue(); ?></td>
                </tr>
                <tr>
                    <td>City</td>
                    <td><?php echo $person->get_city(); ?></td>
                </tr>
                <tr>
                    <td>State</td>
                    <td><?php echo $person->get_state(); ?></td>
                </tr>
                <tr>
                    <td>Zip</td>
                    <td><?php echo $person->get_zip(); ?></td>
                </tr>
                <tr>
                    <td>Phone 1</td>
                    <td><?php echo $person->get_phone1(); ?></td>
                </tr>
                <tr>
                    <td>Phone 1 Type</td>
                    <td><?php echo $person->get_phone1type(); ?></td>
                </tr>
                <tr>
                    <td>Birthday</td>
                    <td><?php echo $person->get_birthday(); ?></td>
                </tr>
                <tr>
                    <td>Email</td>
                    <td><?php echo $person->get_email(); ?></td>
                </tr>
                <tr>
                    <td>Parent Name</td>
                    <td><?php echo $person->get_contact_name(); ?></td>
                </tr>
                <tr>
                    <td>Contact Method</td>
                    <td><?php echo $person->get_cMethod(); ?></td>
                </tr>
                <tr>
                    <td>How Did You Hear</td>
                    <td><?php echo $person->get_how_did_you_hear(); ?></td>
                </tr>
                <tr>
                    <td>Type</td>
                    <td><?php echo implode(", ", $person->get_type()); ?></td>
                </tr>
                <tr>
                    <td>Status</td>
                    <td>
                        <form id="status-form">
                            <select name="status" id="status" onchange="updateStatus()">
                                <option value="pending" <?php if (strtolower($person->get_status()) == 'pending') echo 'selected="selected"'; ?>>Pending</option>
                                <option value="active" <?php if (strtolower($person->get_status()) == 'active') echo 'selected="selected"'; ?>>Active</option>
                                <option value="remission" <?php if (strtolower($person->get_status()) == 'remission') echo 'selected="selected"'; ?>>Remission</option>
                            </select>
                        </form>
                    </td>
                </tr>
                <tr>
                    <td>Notes</td>
                    <td><?php echo $person->get_notes(); ?></td>
                </tr>
                <tr>
                    <td>Password</td>
                    <td><?php echo $person->get_password(); ?></td>
                </tr>
                <tr>
                    <td>Is Password Change Required?</td>
                    <td><?php echo ($person->is_password_change_required() ? "Yes" : "No"); ?></td>
                </tr>
                <tr>
                    <td>Diagnosis</td>
                    <td><?php echo $person->get_diagnosis(); ?></td>
                </tr>
                <tr>
                    <td>Diagnosis Date</td>
                    <td><?php echo $person->get_diagnosis_date(); ?></td>
                </tr>
                <tr>
                    <td>Hospital</td>
                    <td><?php echo $person->get_hospital(); ?></td>
                </tr>
                <tr>
                    <td>Permission to Confirm</td>
                    <td><?php echo $person->get_permission_to_confirm(); ?></td>
                </tr>
                <tr>
                    <td>Expected Treatment End Date</td>
                    <td><?php echo $person->get_expected_treatment_end_date(); ?></td>
                </tr>
                <tr>
                    <td>Allergies</td>
                    <td><?php echo $person->get_allergies(); ?></td>
                </tr>
                <tr>
                    <td>Sibling Info</td>
                    <td><?php echo $person->get_sibling_info(); ?></td>
                </tr>
                <tr>
                    <td>Can Share Contact Info?</td>
                    <td><?php echo ($person->get_can_share_contact_info() ? "Yes" : "No"); ?></td>
                </tr>
                <tr>
                    <td>Username</td>
                    <td><?php echo $person->get_username(); ?></td>
                </tr>
                <tr>
                    <td>Family Info</td>
                    <td><?php echo $person->get_familyInfo(); ?></td>
                </tr>
            <tr>
                <td>Meals</td>
                <td><?php echo ($person->get_meals() == 0) ? "Not Interested" : "Interested"; ?></td>
            </tr>
            <tr>
                <td>House Cleaning</td>
                <td><?php echo ($person->get_housecleaning() == 0) ? "Not Interested" : "Interested"; ?></td>
            </tr>
            <tr>
                <td>Lawncare</td>
                <td><?php echo ($person->get_lawncare() == 0) ? "Not Interested" : "Interested"; ?></td>
            </tr>
            <tr>
                <td>Photography</td>
                <td><?php echo ($person->get_photography() == 0) ? "Not Interested" : "Interested"; ?></td>
            </tr>
            <tr>
                <td>Gas</td>
                <td><?php echo ($person->get_gas() == 0) ? "Not Interested" : "Interested"; ?></td>
            </tr>
            <tr>
                <td>Grocery</td>
                <td><?php echo ($person->get_grocery() == 0) ? "Not Interested" : "Interested"; ?></td>
            </tr>
            <tr>
                <td>AAA Interest</td>
                <td><?php echo ($person->get_aaaInterest() == 0) ? "Not Interested" : "Interested"; ?></td>
            </tr>
            <tr>
                <td>Social Events</td>
                <td><?php echo ($person->get_socialEvents() == 0) ? "Not Interested" : "Interested"; ?></td>
            </tr>
            <tr>
                <td>House Projects</td>
                <td><?php echo ($person->get_houseProjects() == 0) ? "Not Interested" : "Interested"; ?></td>
            </tr>
            <tr>
                <td>Lead Volunteer</td>
                <td><?php echo ($person->get_aaaInterest() == 0) ? "Not Interested" : "Interested"; ?></td>
            </tr>
            <tr>
                <td>Gift Card Delivery Method</td>
                <td><?php echo $person->get_gift_card_delivery_method();?></td>
            </tr>
            <tr>
                <td>Location</td>
                <td><?php echo $person->get_location();?></td>
            </tr>
        </table>
            </div>

            <!-- javascript function to handle changing family status -->
            <!-- Redirects to the same page but with status in the get params-->
            <script>
            function updateStatus() {
                var selectedStatus = $('#status').val();
                var url = window.location.href.split('?')[0]; // Get the base URL without query parameters
                var params = new URLSearchParams(window.location.search); // Get the existing query parameters
                
                // Update the 'status' parameter with the selected status
                params.set('status', selectedStatus);
                
                // Construct the new URL with updated parameters
                var newUrl = url + '?' + params.toString();

                // Redirect to the new URL
                window.location.href = newUrl;
            }
            </script>
            <!-- Return button -->
            <a class="button cancel" style="margin-top: 30px" href="viewFamilyAccounts.php">Return to Family List</a>
        </form>
    </body>
    </html>
    