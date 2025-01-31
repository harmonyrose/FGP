<?php
    // Authors: Harmony Peura and Grayson Jones
    // Community Care Package Form for families to fill out.

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
    


    require_once('database/dbPersons.php');
    require_once('database/dbCommCare.php');
    if (isset($_GET['id'])){

    $person = retrieve_person($_GET['id']);
    $commCare = email_retrieve_comm_care($_GET['id']);
    if(!$commCare){
        header('Location: familyInfo.php?id=' . $_GET['id'] . '&commCareError=1');
    }
}

?>
<!DOCTYPE html>
<html>
<head>
    <?php require_once('universal.inc'); ?>
    <title>FGP | Community Care Package Form </title>
</head>
    <body>
        <?php
            require_once('header.php');
            require_once('domain/CommCare.php');
            require_once('database/dbCommCare.php');
            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                // make every submitted field SQL-safe except for password
                $ignoreList = array('password');
                $args = sanitize($_POST, $ignoreList);
                $_SESSION['form_data'] = $args;

                $required = array('email', 'adultNames', 'childrenInfo', 'sportsFan',
                'sportsInfo', 'sitDinner', 'fastFood', 'sweetTreat', 
                'faveSweet', 'faveSalt', 'faveCandy', 'faveCookie', 'forFun',
                'warmAct', 'coldAct', 'notes'
                    //form requries these but they cannot be confirmed by computer
                );
                $errors = false;
                if (!wereRequiredFieldsSubmitted($args, $required)) {
                    $errors = true;
                }

                // Save all fields appropriately
                $id = find_next_id() + 1;
                $email = $args['email'];
                $con=connect();
                $query = "SELECT * FROM dbPersons WHERE email = '$email'";
                $result = mysqli_query($con, $query);
                if (mysqli_num_rows($result) == 0) {
                    $errors = true;
                    header("Location: commCare.php?emailError");

                }
                $adultNames = $args['adultNames'];
                $childrenInfo = $args['childrenInfo'];
                $sportsFan = $args['sportsFan'];
                $sportsInfo = $args['sportsInfo'];
                $sitDinner = $args['sitDinner'];
                $fastFood = $args['fastFood'];
                $sweetTreat = $args['sweetTreat'];
                $faveSweet = $args['faveSweet'];
                $faveSalt = $args['faveSalt'];
                $faveCandy = $args['faveCandy'];
                $faveCookie = $args['faveCookie'];
                $forFun = $args['forFun'];
                $warmAct = $args['warmAct'];
                $coldAct = $args['coldAct'];
                $notes = $args['notes'];
                
                if ($errors) {
                    echo '<p>Your form submission contained unexpected input.</p>';
                    die();
                }

                // Make new CommCare instance
                $newcommcare = new CommCare(
                    $id, $email, $adultNames, $childrenInfo,
                    $sportsFan, $sportsInfo, $sitDinner, $fastFood, $sweetTreat, $faveSweet, 
                    $faveSalt, $faveCandy, $faveCookie, $forFun, $warmAct, $coldAct, $notes
                );

                // Add it to the SQL table
                $result = add_comm_care($newcommcare);
                // header("Location: index.php?commCareSuccess");
                if (!$result) {
                    echo '<p>something went wrong</p>';
                } else {
                    if ($loggedIn) {
                        echo '<script>document.location = "familyInfo.php?id=' . $_GET['id'] . '&commCareSuccess";</script>';
                    } else {
                        echo '<script>document.location = "familyInfo.php?id=' . $_GET['id'] . '&commCareSuccess";</script>';
                    }
                }
            } else {
                require_once('viewFamilyCommCareForm.php'); 
            }
        ?>
    </body>
</html>
