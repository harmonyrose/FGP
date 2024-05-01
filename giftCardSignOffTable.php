<?php
    // Authors: Harmony Peura and Grayson Jones
    // Displays Gift Card Sign Off table, allows families to sign to confirm
    // that they received their gift cards.
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
    if ($accessLevel < 1) {
        header('Location: index.php');
        die();
    }
    

    // Formatting for each row of the table
    function displaySearchRow($person){
        echo "
        <tr>
            <td>" . $person->getName() . "</td>
            <td>" . $person->getEmail() . "</td>
            <td>" . $person->getGiftCardPickUp() . "</td>
            <td><a href='giftCardSignOffForm.php?family_id=" . urlencode($person->getId()) . "' class='button'>Sign</a></td>";
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
        <?php if (isset($_GET['signOffSuccess'])){ ?>
                <div class="happy-toast">Sign-Off Submitted Sucessfully!</div>
        <?php } ?>
        <form id="family-list" class="general" method="get">
            <?php 
                require_once('database/dbPointsProg.php');
                // Get list of families from dbPointsProg, thus only displaying families
                // that have requested gift cards
                $people = getall_pointsProgs();
                // If there are people, create table
                if (count($people) > 0) {
                    echo '
                    <div class="table-wrapper">
                        <table class="general" id="familyTable">
                            <thead>
                                <tr>
                                    <th onclick="sortTable(0)">
                                    Name
                                    <span class="arrow-up">&#9650;</span>
                                    <span class="arrow-down">&#9660;</span>
                                    </th>
                                    <th onclick="sortTable(1)">
                                    Email
                                    <span class="arrow-up">&#9650;</span>
                                    <span class="arrow-down">&#9660;</span>
                                    </th>
                                    <th>Signed Off</th>
                                    <th>Action</th>';
                                echo '</tr>
                            </thead>
                            <tbody class="standout">';
                    // Show each person as formatted in displaySearchRow
                    foreach ($people as $person) {
                            displaySearchRow($person);
                    }
                    // End table
                    echo '
                            </tbody>
                        </table>
                    </div>';
                } else {
                    // If there are no families, print error message
                    echo '<div class="error-toast">There are no families.</div>';
                }
            ?>
            <p></p>
        </form>

        <a href="giftCardManagement.php" class="button cancel">Return to Gift Card Management</a>
        <div class="space-below-button"></div>
        <br>
        <a href="index.php" class="button cancel">Return to Dashboard</a>
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
    </body>
</html>