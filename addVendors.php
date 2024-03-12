<?php
$ages = [
    "0", "1", "2", "3", "4", "5", "6", "7",
    "8", "9", "10", "11", "12", "13", "14", "15", 
    "16", "17", "18", "19", "20"
];

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
            $id = create_vendor($args);
            if(!$id){
                echo "Oopsy!";
                die();
            }
            require_once('include/output.php');
            
            $name = htmlspecialchars_decode($args['name']);
            require_once('database/dbMessages.php');
            header("Location: listVendors.php?id=$id&createSuccess");
            die();
        }
    }
    $date = null;

?>
<!DOCTYPE html>
<html>
    <head>
        <?php require_once('universal.inc') ?>
        <title>FGP | Add Vendor</title>
    </head>
    <body>
        <?php require_once('header.php') ?>
        <h1>Add Vendor</h1>
        <main class="date">
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
                <?php if ($date): ?>
                    <a class="button cancel" href="calendar.php?month=<?php echo substr($date, 0, 7) ?>" style="margin-top: -.5rem">Return to Calendar</a>
                <?php else: ?>
                    <a class="button cancel" href="index.php" style="margin-top: -.5rem">Return to Dashboard</a>
                <?php endif ?>
        </main>
    </body>
</html>