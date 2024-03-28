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
function displaySearchRow($person){
    echo "
    <tr>
        <td><a href='familyInfo.php?contact_id=" . urlencode($person->get_id()) . "'>" . $person->get_contact_name() . "</a></td>
        <td>" . $person->get_first_name() . "</td>
        <td>" . $person->get_email() . "</td>";
    echo "</tr>";
} 




?>

<style>   /* Apply alternating background colors */
        tr:nth-child(even) {
            background-color: #f2f2f2; /* Lighter color */
        }
            
        tr:nth-child(odd) {
            background-color: #e6e6e6; /* Slightly darker color */
        }
</style>

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
                                        <th>Email Address</th>';
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
                        // If there are not vendors, print error message \\
                        echo '<div class="error-toast">There are no families.</div>';
                    }
            ?>
            <p></p>
            <!-- Return button -->
            <a class="button cancel" href="index.php">Return to Dashboard</a>
        </form>
    </body>
</html>