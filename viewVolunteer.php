<?php
    session_cache_expire(30);
    session_start();
    
    require_once('include/input-validation.php');

    $loggedIn = false;
    if (isset($_SESSION['change-password'])) {
        header('Location: changePassword.php');
        die();
    }
    if (isset($_SESSION['_id'])) {
        $loggedIn = true;
        $accessLevel = $_SESSION['access_level'];
        $userID = $_SESSION['_id'];
    }

    // Require admin privileges
    if ($accessLevel < 2)
    {
        header('Location: login.php');
        echo 'bad access level';
        die();
    }


?>
<!DOCTYPE html>
<html>
    <head>
        <?php require_once('universal.inc') ?>
        <title>FGP | View Volunteer</title>
    </head>
    <body>
        <?php require_once('header.php') ?>
        <h1>View Volunteer</h1>
        <!-- Legacy thing i dont wanna mess with -->
        <main class="date"> 
        <!-- Form -->
        <h2>Volunteer List</h2>
            <table class="table">
                <thead class="thead-dark">
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">First Name</th>
                        <th scope="col">Last Name</th>
                        <th scope="col">Email</th>
                        <th scope="col">Operation</th>
                    </tr>
                </thead>
                <tbody>
                <?php require_once("database/dbAddVolunteer.php");
                    $volunteers = display_volunteer();
                ?>
                
                </tbody>
        </table>
    </body>
    <a class="button cancel" href="register.php" style="margin-top: -.5rem">Return to add volunteer page</a>
</html>
