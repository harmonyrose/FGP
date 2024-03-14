<?php




// Formatting for each row of the table
function displaySearchRow($vendor){
    echo "
    <tr>
        <td>" . $vendor->get_name() . "</td>
        <td>" . $vendor->get_type() . "</td>
        <td>" . $vendor->get_location() . "</td>
        <td><input class=\"vendorsCheckbox\" type=\"checkbox\" name=" . $vendor->get_id() . "/>&nbsp;</td>";
    echo "</tr>";
}

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
        <!-- Add vendors button -->
        <a class="button cancel" href="addVendors.php" style="background-color: green">Add vendors</a>
        <!-- Delete vendors button, can probably do this cleaner so maybe fix that -->
        <a class="button cancel" href="#" style="background-color: red">Delete vendors</a>
        <script>
            document.getElementById('deleteVendorsBtn').addEventListener('click', function() {
                var selectedIds = [];
                // Assuming your checkboxes have a class name 'vendorCheckbox'
                var checkboxes = document.querySelectorAll('.vendorCheckbox:checked');
                checkboxes.forEach(function(checkbox) {
                    selectedIds.push(checkbox.name); // Might not work
                });

                if (selectedIds.length > 0) {
                    remove_vendors(selectedIds);
                    window.location.href = "listVendors.php";
                } else {
                    <div class="error-toast">Please select at least one vendor.</div>
                }
            });
        </script>
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
                            <table class="general">
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
            <a class="button cancel" href="index.php">Return to Dashboard</a>
        </form>
    </body>
</html>