<?php
    // Author: Lauren Knight
    // Description: Registration page for new volunteers
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

    // if (isset($_SESSION['_id'])) {
    //     header('Location: index.php');
    // } else {
    //     $_SESSION['logged_in'] = 1;
    //     $_SESSION['access_level'] = 0;
    //     $_SESSION['venue'] = "";
    //     $_SESSION['type'] = "";
    //     $_SESSION['_id'] = "guest";
    //     header('Location: personEdit.php?id=new');
    // }


?>
<!DOCTYPE html>
<html>
<head>
    <?php require_once('universal.inc'); ?>
    <title>FGP | Register <?php if ($loggedIn) echo ' New Volunteer' ?></title>
</head>
<body>
    <?php
        require_once('header.php');
        require_once('domain/Person.php');
        require_once('database/dbPersons.php');
        require_once('domain/Volunteer.php');
        require_once('database/dbAddVolunteer.php');
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            // make every submitted field SQL-safe except for password
            $ignoreList = array('password');
            $args = sanitize($_POST, $ignoreList);

            // echo "<p>The form was submitted:</p>";
            // foreach ($args as $key => $value) {
            //     echo "<p>$key: $value</p>";
            // }

            $required = array(
                'volunteerID','first-name', 'last-name', 'email'
            );
            $errors = false;
            //$first = $args['first-name'];
            //$last = $args['last-name'];
            $email = $args['email'];
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {//validate if the email format is correct or not
                $emailErr = "Invalid email format";
                $errors = true;
            }

            if ($errors) {
                echo $emailErr;
                die();
            }
            // need to incorporate availability here
            create_volunteer($args);

        }
        require_once('registrationForm.php'); //front end management
    ?>
</body>
</html>
