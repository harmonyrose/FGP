<?php
// <!-- addVendors.php-->
// <!-- Form admins will use to add new vendors to dbGiftCardVendors -->
// <!-- Joshua Cottrell -->


    // Make session information accessible, allowing us to associate
    // data with the logged-in user.
    session_cache_expire(30);
    session_start();

    ini_set("display_errors",1);
    error_reporting(E_ALL);

    $loggedIn = false;
    $accessLevel = 0;
    $userID = null;
    if (isset($_SESSION['_id'])) {
        $loggedIn = true;
        // 0 = not logged in, 1 = standard user, 2 = manager (Admin), 3 super admin (TBI)
        $accessLevel = $_SESSION['access_level'];
        $userID = $_SESSION['_id'];
    } 
    // Require admin privileges
    if ($accessLevel < 2) {
        header('Location: login.php');
        echo 'bad access level';
        die();
    }

    // Handles form submission
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        require_once('include/input-validation.php');
        require_once('database/dbGiftCardVendors.php');
        $args = sanitize($_POST, null);
        $required = array(
			"vendorName", "vendorType", "vendorLocation"
		);
        if (!wereRequiredFieldsSubmitted($args, $required)) {
            echo 'bad form data';
            die();
        } else {
            // Try to add vendor
            $id = create_vendor($args);
            // If null, there is a duplicate name (should probably make duplicate name an error number since there will be other errors maybe)
            if(!$id){
                header("Location: addVendors.php?duplicateName");
                die();
            }
            header("Location: listVendors.php?id=$id&createSuccess");
            die();
        }
    }


?>

<!-- The form to be filled out -->
<!DOCTYPE html>
<html>
    <head>
        <?php require_once('universal.inc') ?>
        <title>FGP | Add Vendor</title>
    </head>
    <body>
        <?php require_once('header.php') ?>
        <h1>Add Vendor</h1>
        <!-- If the user tried to submit already and had a duplicate name, display error notification -->
        <?php if (isset($_GET['duplicateName'])): ?>
            <div class="error-toast">A vendor with that name already exists. Please try again.</div>
        <?php endif ?>
        <!-- Legacy thing i dont wanna mess with -->
        <main class="date"> 
            <!-- Form -->
            <h2>New Vendor Form</h2>
            <form id="new-vendor-form" method="post">
                
                <label for="name">Vendor Name *</label>
                <input type="text" id="vendorName" name="vendorName" required placeholder="Enter vendors' name">
                <label for="name">Vendor Type *</label>
                <input type="text" id="vendorType" name="vendorType" required placeholder="Enter vendors' type"> 
                <label for="name">Vendor Location *</label>
                <input type="text" id="vendorLocation" name="vendorLocation" required placeholder="Enter vendors' location"> 
                <p></p>
                <input type="submit" value="Add New Vendor">
            </form>
            <a class="button cancel" href="listVendors.php" style="margin-top: -.5rem">Return to vendor list</a>
        </main>
    </body>
</html>