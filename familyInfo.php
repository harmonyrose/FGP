<?php
    // Make session information accessible, allowing us to associate
    // data with the logged-in user.
    session_cache_expire(30);
    session_start();

    $loggedIn = false;
    $accessLevel = 0;
    $userID = null;
    $isAdmin = false;
    if (!isset($_SESSION['access_level']) || $_SESSION['access_level'] < 1) {
        header('Location: login.php');
        die();
    }
    if (isset($_SESSION['_id'])) {
        $loggedIn = true;
        // 0 = not logged in, 1 = standard user, 2 = manager (Admin), 3 super admin (TBI)
        $accessLevel = $_SESSION['access_level'];
        $isAdmin = $accessLevel >= 2;
        $userID = $_SESSION['_id'];
    } 
    else {
        header('Location: login.php');
        die();
    }
    if ($isAdmin && isset($_GET['id'])) {
        require_once('include/input-validation.php');
        $args = sanitize($_GET);
        $id = strtolower($args['id']);
    } else {
        $id = $userID; // Use session ID if no ID provided in GET parameters
    }

    

    require_once('database/dbPersons.php');
    // Check if status and contact_id are provided
    if (isset($_GET['status'])) {
        // Call the update_status() function
        $status = $_GET['status'];
        $contact_id = $id;
        update_status($contact_id, $status);
        // Redirect to the current page without the 'status' parameter but with the 'contact_id' parameter
        // This is not only done to make the url look good but also because it won't update the default option in the table if I don't
        $redirect_url = strtok($_SERVER["REQUEST_URI"], '?'); // Get the current URL without query parameters
        if (isset($_GET['id'])) {
            $contact_id_param = "id=" . urlencode($_GET['id']); // Get contact_id
            header("Location: $redirect_url?$contact_id_param"); // Combine to get full URL and then redirect there
        }
        header("Location: $redirect_url"); // Redirect to same url without status
        exit();
    }

    // Get the dbPersons information of the family in the get request so we can display all their information
    $person = retrieve_person($id);

    if (isset($_GET['removePic'])) {
      if ($_GET['removePic'] === 'true') {
        remove_profile_picture($id);
      }
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
      if (isset($_POST['url'])) {
        if (!update_profile_pic($id, $_POST['url'])) {
          header('Location: familyInfo.php?id='.$id.'&picsuccess=False');
        } else {
          header('Location: familyInfo.php?id='.$id.'&picsuccess=True');
        }
      }
    }
    // echo '<script>alert("Inside the if statement!");</script>';
?>
<!DOCTYPE html>
<html>
<head>
    <?php require_once('universal.inc') ?>
    <link rel="stylesheet" type="text/css" href="css/familyInfo.css">
    <title>FGP | <?php echo $person->get_first_name() . ' ' . $person->get_last_name(); ?></title>
</head>
<body>
    <?php require_once('header.php'); ?>
    <h1><?php echo $person->get_first_name() . '\'s Information'; ?></h1>
    <main class="general">
        <?php
            if (isset($_GET['picsuccess'])) {
              $picsuccess = $_GET['picsuccess'];
              if ($picsuccess === 'True') {
                echo '<div class="happy-toast">Profile Picture Updated Successfully!</div>';
              } else if ($picsuccess === 'False') {
                echo '<div class="error-toast">There was an error updating the Profile Picture!</div>';
              }
            }
        ?>
        <fieldset>
            <legend>General Information</legend>
            <label>Name</label>
            <p><?php echo $person->get_first_name() . ' ' . $person->get_last_name(); ?></p>
            <label>ID</label>
            <p><?php echo $person->get_id(); ?></p>
            <label>Profile Picture</label>
            <img class="profile-pic" src="<?php
                $profile_pic = $person -> get_profile_pic();
                if ($profile_pic) {
                    echo $profile_pic;
                } else {
                    echo 'images/default-profile-picture.svg';
                }
              ?>"width="140" height="140">
            <form class="media-form hidden" method="post" id="edit-profile-picture-form">
                <label>Edit Photo</label>
                <label for="url">URL</label>
                <input type="text" id="url" name="url" placeholder="Paste link to media" required>
                <p class="error hidden" id="url-error">Please enter a valid URL.</p>
                <input type="hidden" name="id" value="<?php echo $id ?>">
                <input type="submit" name="edit-profile-picture-submit" value="Attach">
            </form>
            <a id="edit-profile-picture" class="link-like">Edit Photo</a>
            <?php
              echo '<a href="familyInfo.php?id='.$id.'&removePic=true" style="color:inherit">Remove Photo</a>'
            ?>
            <label>Address</label>
            <p><?php echo $person->get_address() . ', ' . $person->get_city() . ', ' . $person->get_state() . ' ' . $person->get_zip() ?></p>
            <label>Phone 1</label>
            <p><?php echo $person->get_phone1(); ?></p>
            <label>Birthday</label>
            <p><?php echo $person->get_birthday(); ?></p>
            <label>Email</label>
            <p><?php echo $person->get_email(); ?></p>
            <label>Parent Name</label>
            <p><?php echo $person->get_contact_name(); ?></p>
            <label>Contact Method</label>
            <p><?php echo $person->get_cMethod(); ?></p>
            <label>How Did You Hear</label>
            <p><?php echo $person->get_how_did_you_hear(); ?></p>
            <label>Type</label>
            <p><?php echo $person->get_type(); ?></p>
            <label>Status</label>
            <?php if (isset($_GET['id'])): ?>
                <p>
                    <form id="status-form" style="width: 200px">
                        <select name="status" id="status" onchange="updateStatus()">
                            <option value="pending" <?php if (strtolower($person->get_status()) == 'pending') echo 'selected="selected"'; ?>>Pending</option>
                            <option value="active" <?php if (strtolower($person->get_status()) == 'active') echo 'selected="selected"'; ?>>Active</option>
                            <option value="remission" <?php if (strtolower($person->get_status()) == 'remission') echo 'selected="selected"'; ?>>Remission</option>
                        </select>
                    </form>
                </p>
            <?php else: ?>
              <?php echo "<p>" . $person->get_status() . "</p>"; ?>
            <?php endif; ?>
            
            <label>Notes</label>
            <p><?php echo $person->get_notes(); ?></p>
            <label>Password</label>
            <p><?php echo $person->get_password(); ?></p>
            <label>Is Password Change Required?</label>
            <p><?php echo ($person->is_password_change_required() ? "Yes" : "No"); ?></p>
            <label>Diagnosis</label>
            <p><?php echo $person->get_diagnosis(); ?></p>
            <label>Diagnosis Date</label>
            <p><?php echo $person->get_diagnosis_date(); ?></p>
            <label>Hospital</label>
            <p><?php echo $person->get_hospital(); ?></p>
            <label>Permission to Confirm</label>
            <p><?php echo $person->get_permission_to_confirm(); ?></p>
            <label>Expected Treatment End Date</label>
            <p><?php echo $person->get_expected_treatment_end_date(); ?></p>
            <label>Allergies</label>
            <p><?php echo $person->get_allergies(); ?></p>
            <label>Sibling Info</label>
            <p><?php echo $person->get_sibling_info(); ?></p>
            <label>Can Share Contact Info?</label>
            <p><?php echo ($person->get_can_share_contact_info() ? "Yes" : "No"); ?></p>
            <label>Username</label>
            <p><?php echo $person->get_username(); ?></p>
            <label>Family Info</label>
            <p><?php echo $person->get_familyInfo(); ?></p>
            <label>Meals</label>
            <p><?php echo ($person->get_meals() == 0) ? "Not Interested" : "Interested"; ?></p>
            <label>House Cleaning</label>
            <p><?php echo ($person->get_housecleaning() == 0) ? "Not Interested" : "Interested"; ?></p>
            <label>Lawncare</label>
            <p><?php echo ($person->get_lawncare() == 0) ? "Not Interested" : "Interested"; ?></p>
            <label>Photography</label>
            <p><?php echo ($person->get_photography() == 0) ? "Not Interested" : "Interested"; ?></p>
            <label>Gas</label>
            <p><?php echo ($person->get_gas() == 0) ? "Not Interested" : "Interested"; ?></p>
            <label>Grocery</label>
            <p><?php echo ($person->get_grocery() == 0) ? "Not Interested" : "Interested"; ?></p>
            <label>AAA Interest</label>
            <p><?php echo ($person->get_aaaInterest() == 0) ? "Not Interested" : "Interested"; ?></p>
            <label>Social Events</label>
            <p><?php echo ($person->get_socialEvents() == 0) ? "Not Interested" : "Interested"; ?></p>
            <label>House Projects</label>
            <p><?php echo ($person->get_houseProjects() == 0) ? "Not Interested" : "Interested"; ?></p>
            <label>Lead Volunteer</label>
            <p><?php echo ($person->get_aaaInterest() == 0) ? "Not Interested" : "Interested"; ?></p>
            <label>Gift Card Delivery Method</label>
            <p><?php echo $person->get_gift_card_delivery_method();?></p>
            <label>Location</label>
            <p><?php echo $person->get_location();?></p>
        </fieldset>
        <!-- Return button -->
        <a class="button cancel" style="margin-top: 30px" href="viewFamilyAccounts.php">Return to Family List</a>
    </main>

    <!-- JavaScript function to handle changing family status -->
    <!-- Redirects to the same page but with status in the GET parameters -->
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

        document.getElementById('edit-profile-picture').addEventListener('click', function() {
          document.querySelector('.media-form').classList.toggle('hidden');
        });
    </script>
</body>
</html>
