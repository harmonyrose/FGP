<?php




function displaySearchRow($vendor){
    echo "
    <tr>
        <td>" . $vendor->get_name() . "</td>
        <td>" . $vendor->get_type() . "</td>
        <td>" . $vendor->get_location() . "</td>";
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
        <?php if (isset($_GET['createSuccess'])): ?>
            <div class="happy-toast">Vendor created successfully!</div>
        <?php endif ?>
        <a class="button cancel" href="addVendors.php">Add vendors</a>
            <?php 
                require_once('include/input-validation.php');
                require_once('database/dbGiftCardVendors.php');
                    $vendors = find_vendors();
                    require_once('include/output.php');
                    if (count($vendors) > 0) {
                        echo '
                        <div class="table-wrapper">
                            <table class="general">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Type</th>
                                        <th>Location</th>';
                                    echo '</tr>
                                </thead>
                                <tbody class="standout">';
                        $mailingList = '';
                        $notFirst = false;
                        foreach ($vendors as $vendor) {
                            displaySearchRow($vendor);
                        }
                        echo '
                                </tbody>
                            </table>
                        </div>';
                    } else {
                        echo '<div class="error-toast">There are no vendors.</div>';
                    }
            ?>
            <p></p>
            <a class="button cancel" href="index.php">Return to Dashboard</a>
        </form>
    </body>
</html>