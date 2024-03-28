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


// Formatting for each row of the table
function displaySearchRow($vendor){
    echo "
    <tr>
        <td>" . $vendor->get_name() . "</td>
        <td>" . $vendor->get_type() . "</td>
        <td>" . $vendor->get_location() . "</td>
        <td><input class=\"vendorsCheckbox\" type=\"checkbox\" name=" . $vendor->get_id() . ">&nbsp;</td>";
    echo "</tr>";
}

?>

<!DOCTYPE html>
<html>
    <head>
        <?php require_once('universal.inc') ?>
        <title>FGP | Vendor List</title>
    </head>
    <body>
        <?php require_once('header.php') ?>
        <h1>Vendor List</h1>
        <form id="vendor-search" class="general" method="get">
        <!-- If the addVendors form submits successfully, display a confirmation notification -->
        <?php if (isset($_GET['createSuccess'])): ?>
            <div class="happy-toast">Vendor created successfully!</div>
        <?php endif ?>
        <!-- If the delete vendors button is clicked, delete the selected vendors -->
        <?php
        require_once('database/dbGiftCardVendors.php');
        // Check if the 'checkedNames' parameter is present in the GET request
        if(isset($_GET['checkedNames'])) {
            // Retrieve the 'checkedNames' parameter value from the GET request
            $checkedNamesString = $_GET['checkedNames'];
            
            // Explode the string into an array using comma as delimiter
            $checkedNamesArray = explode(",", $checkedNamesString);
            
            // Remove vendor from database
            remove_vendor($checkedNamesArray);
            // Display confirmation notification
            echo '<div class="happy-toast">Vendors deleted successfully!</div>';
        }
        ?>

        <?php if (isset($_GET['deletedVendors'])): ?>
            <div class="error-toast">Vendor deleted successfully!</div>
        <?php endif ?>
        <!-- Add vendors button -->
        <a class="button cancel" href="addVendors.php" style="background-color: green">Add vendors</a>

        <!-- Delete vendors button -->
        <a class="button cancel" id="deleteButton" style="background-color: red">Delete vendors</a>

        <!-- JavaScript code to handle deleting vendors -->
        <script>
            // Get the button element by its ID
            var button = document.getElementById('deleteButton');

            // Add an event listener to the button for the 'click' event
            button.addEventListener('click', function() {
                // Array to store the names of checked checkboxes
                var checkedNames = [];

                // Get all checkboxes in the table
                var checkboxes = document.querySelectorAll('#vendorTable input[type="checkbox"]');

                // Iterate over each checkbox
                checkboxes.forEach(function(checkbox) {
                    // If checkbox is checked, add its name to the array
                    if (checkbox.checked) {
                        checkedNames.push(checkbox.name);
                    }
                });

                // If there are checked checkboxes, construct the URL with the parameters
                if (checkedNames.length > 0) {
                    var url = "listVendors.php?checkedNames=" + checkedNames.join(",");
                    // Perform the GET request using the constructed URL
                    window.location.href = url;
                } else {
                    alert("No checkboxes are checked.");
                }
            });
        </script>


    <style>   /* Apply alternating background colors */
        tr:nth-child(even) {
            background-color: #f2f2f2; /* Lighter color */
        }
            
        tr:nth-child(odd) {
            background-color: #e6e6e6; /* Slightly darker color */
        }
    </style>



            <!-- The actual table -->
            <?php 
                require_once('include/input-validation.php');
                require_once('database/dbGiftCardVendors.php');
                // Get list of vendors from gift card database \\
                    $vendors = find_vendors();
                    require_once('include/output.php');
                    // If there are vendors, create table \\
                    if (count($vendors) > 0) {
                        echo '
                        <div class="table-wrapper">
                            <table class="general" id="vendorTable">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Type</th>
                                        <th>Location</th>
                                        <th></th>';
                                    echo '</tr>
                                </thead>
                                <tbody class="standout">';
                        // Show each vendor as formatted in displaySearchRow above \\

                        foreach ($vendors as $vendor) {
                            displaySearchRow($vendor);
                        }
                        // End table \\
                        echo '
                                </tbody>
                            </table>
                        </div>';
                    } else {
                        // If there are not vendors, print error message \\
                        echo '<div class="error-toast">There are no vendors.</div>';
                    }
            ?>
            <p></p>
            <!-- Return button -->
            <a class="button cancel" href="giftCardManagement.php">Return to Gift Card Management</a>
        </form>
    </body>
</html>