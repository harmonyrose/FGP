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
    // Each parent name (get_contact_name) is hyperlinked to their respective familyInfo page so an admin can access their information easily
    function displaySearchRow($person){
        echo "
        <tr>
            <td><a href='familyInfo.php?id=" . urlencode($person->get_id()) . "'>" . $person->get_contact_name() . "</a></td>
            <td>" . $person->get_first_name() . "</td>
            <td>" . $person->get_email() . "</td>
            <td>" . $person->get_status() . "</td>
            <td><a href='modifyFamily.php?family_id=" . urlencode($person->get_id()) . "' class='button'>Modify</a></td>
            <td><a href='modifyFamilyStatus.php?family_id=" . urlencode($person->get_id()) . "' class='button'>Modify Status</a></td>";
        echo "</tr>";
    } 
?>


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
                                    <th onclick="sortTable(0)">
                                    Parent\'s Name
                                    <span class="arrow-up">&#9650;</span>
                                    <span class="arrow-down">&#9660;</span>
                                    </th>
                                    <th onclick="sortTable(1)">
                                    Child\'s Name
                                    <span class="arrow-up">&#9650;</span>
                                    <span class="arrow-down">&#9660;</span>
                                    </th>
                                    <th onclick="sortTable(3)">
                                    Email Address
                                    <span class="arrow-up">&#9650;</span>
                                    <span class="arrow-down">&#9660;</span>
                                    </th>
                                    <th onclick="sortTable(4)">
                                    Status
                                    <span class="arrow-up">&#9650;</span>
                                    <span class="arrow-down">&#9660;</span>
                                    </th>
                                    <th>Actions</th>
                                    <th></th>';
                                echo '</tr>
                            </thead>
                            <tbody class="standout">';
                    // Show each person as formatted in displaySearchRow above \\
                    foreach ($people as $person) {
                        if ($person->get_access_level() < $_SESSION['access_level']) {
                            displaySearchRow($person);
                        }
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
            <script>
            // JavaScript function to sort table by column index
            function sortTable(colIndex) {
                var table, rows, switching, i, x, y, shouldSwitch;
                table = document.getElementById("familyTable");
                switching = true;
                while (switching) {
                    switching = false;
                    rows = table.rows;
                    for (i = 1; i < (rows.length - 1); i++) {
                        shouldSwitch = false;
                        x = rows[i].getElementsByTagName("td")[colIndex];
                        y = rows[i + 1].getElementsByTagName("td")[colIndex];
                        if (x.innerHTML.toLowerCase() > y.innerHTML.toLowerCase()) {
                            shouldSwitch = true;
                            break;
                        }
                    }
                if (shouldSwitch) {
                    rows[i].parentNode.insertBefore(rows[i + 1], rows[i]);
                    switching = true;
                }
            }
        }
        </script>
        </form>
    </body>
</html>
