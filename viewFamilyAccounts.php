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
    // Connect to the database
$hostname = "localhost"; 
$database = "fgp";
$username = "fgp";
$password = "fgp";

$connection = mysqli_connect($hostname, $username, $password, $database);

// Check if the connection was successful
if (!$connection) {
    die("Connection failed: " . mysqli_connect_error());
}

// Function to delete a family
function deleteFamily($id) {
    global $connection; // Access the global $connection variable
    $query = "DELETE FROM dbPersons WHERE id = '$id' AND (type = 'family' OR type='Family')";
    $result = mysqli_query($connection, $query);
    if (!$result) {
        die("Delete failed: " . mysqli_error($connection));
    }
}



// Handle delete action
if (isset($_POST['delete'])) {
    $id = $_POST['id']; // Family ID
    deleteFamily($id);
    // Redirect to prevent form resubmission
    header("Location: ".$_SERVER['PHP_SELF']);
    exit();
}

// Formatting for each row of the table
// Each parent name (get_contact_name) is hyperlinked to their respective familyInfo page so an admin can access their information easily
function displaySearchRow($person){
    echo "<tr>
        <td><a href='familyInfo.php?contact_id=" . urlencode($person->get_id()) . "'>" . $person->get_contact_name() . "</a></td>
        <td>" . $person->get_first_name() . "</td>
        <td>" . $person->get_email() . "</td>
        <td><a href='modifyFamily.php?family_id=" . urlencode($person->get_id()) . "' class='button'>Modify</a></td>
        <td><a href='modifyFamilyStatus.php?family_id=" . urlencode($person->get_id()) . "' class='button'>Modify Status</a></td>
        <td>
            <form method='post'>
                <input type='hidden' name='id' value='" . $person->get_id() . "'>
                <button type='submit' name='delete' class='button delete' onclick='return confirmDelete();'>Delete</button>
            </form>
        </td>
    </tr>";
}

?>

<script>
function confirmDelete() {
    return confirm('Are you sure you want to delete this family?');
}
</script>

<!DOCTYPE html>
<html>
    <head>
        <?php require_once('universal.inc') ?>
        <title>FGP | Family List</title>
    </head>
    <body>
        <?php require_once('header.php') ?>
        <h1>Family List</h1>
        <form id="family-list" class="general" method="get">
            <!-- The actual table -->
            <!-- Takes all the families from dbPersons and displays them following the displaySearchRow function above to create a list of families-->
            <?php 
                require_once('database/dbPersons.php');
                // Get list of families from dbPersons database \\
                $people = getall_families();
                // If there are people, create table \\
                if (count($people) > 0) {
                    echo '
                    <div class="table-wrapper">
                        <table class="general" id="familyTable">
                            <thead>
                                <tr>
                                    <th>Parent\'s Name</th>
                                    <th>Child\'s Name</th>
                                    <th>Email Address</th>
                                    <th>Actions</th>
                                    <th></th>';
                                echo '</tr>
                            </thead>
                            <tbody class="standout">';
                    // Show each person as formatted in displaySearchRow above \\
                    foreach ($people as $person) {
                        displaySearchRow($person);
                    }
                    // End table \\
                    echo '
                            </tbody>
                        </table>
                    </div>';
                } else {
                    // If there are no families, print error message \\
                    echo '<div class="error-toast">There are no families.</div>';
                }
            ?>
            <p></p>
            <!-- Return button -->
            <a class="button cancel" href="index.php">Return to Dashboard</a>
        </form>
    </body>
</html>
