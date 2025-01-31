<?php
// session_cache_expire(30);
// session_start();
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

// Do not require admin perms
if ($accessLevel < 1) {
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
if($_SERVER["REQUEST_METHOD"] == "POST"){
    require_once('include/input-validation.php');
    require_once('database/dbCommCare.php');
    $args = sanitize($_POST, null);
    $required = array('email', 'adultNames', 'childrenInfo', 'sportsFan',
             'sportsInfo', 'sitDinner', 'fastFood', 'sweetTreat', 
            'faveSweet', 'faveSalt', 'faveCandy', 'faveCookie', 'forFun',
            'warmAct', 'coldAct', 'notes'
                //form requries these but they cannot be confirmed by computer
            );
}


?>
<!DOCTYPE html>
<html>
    <head>
        <?php require_once('universal.inc') ?>
        <link rel="stylesheet" type="text/css" href="css/familyInfo.css">
        <title>FGP | <?php echo $person->get_first_name() . ' ' . $person->get_last_name(); ?></title>
    </head>
    <body>
        <?php require_once('header.php') ?>
        <h1><?php echo $person->get_first_name() . '\'s community carepackage form'; ?></h1>
        <main class="date">
            <h2>Community Care Package Form</h2>
            <p>Please fill out the form below. Required fields are marked with an asterisk (<span style="color: red;">*</span>)</p>
            <form id="new-event-form" method="post">
                <!--Adult names and children info -->
                <label for="email">Email </label>
                <input type="text" id="email" name="email" required placeholder="Enter email" value="<?php echo $commCare->getEmail();?>">
                <label for="adultNames">Names of adults in household </label>
                <input type="text" id="adultNames" name="adultNames" required placeholder="Enter adult names" value="<?php echo $commCare->getAdultNames();?>">
                <label for="childrenInfo">For children in the house hold: list names,ages, genders and clothing sizes</label>
                <input type="text" id="childrenInfo" name="childrenInfo" required placeholder="Enter children information" value="<?php echo $commCare->getchildrenInfo();?>">
                <!--Sports Fans? If so, what teams do you like? -->
                <label for="sportsFan">Are you sports fans? <span style="color: red;">*</span></label>
                <ul>
                <li><input type="radio" id="sportsYes" name="sportsFan" value=1 required <?php if($commCare->getSportsFan() == 1) echo 'checked'; ?>> Yes </li>
                <li><input type="radio" id="sportsNo" name="sportsFan" value=0 <?php if($commCare->getSportsFan() == 0) echo 'checked'; ?>> No </li>
                </ul>
                <label for="sportsInfo">What teams do you enjoy cheering for? </label>
                <input type="text" id="sportsInfo" name="sportsInfo" placeholder="Enter team names" value="<?php echo $commCare->getSportsInfo();?>">
                <!--Resturants and Snack Preferences -->
                <label for="sitDinner">Where do you like to eat a sit-down dinner?</label>
                <input type="text" id="sitDinner" name="sitDinner" required placeholder="Enter a restaurant name" value="<?php echo $commCare->getSitDinner();?>">
                <label for="fastFood">What are your favorite fast food restaurants?</label>
                <input type="text" id="fastFood" name="fastFood" required placeholder="Enter a restaurant name" value="<?php echo $commCare->getFastFood();?>">
                <label for="sweetTreat">Where do you like to grab a sweet treat?</label>
                <input type="text" id="sweetTreat" name="sweetTreat" required placeholder="Enter a restaurant/store name"value="<?php echo $commCare->getSweetTreat();?>">
                <label for="faveSweet">What is your favorite sweet treat?</label>
                <input type="text" id="faveSweet" name="faveSweet" required placeholder="Enter a sweet treat"value="<?php echo $commCare->getFaveSweet();?>">
                <label for="faveSalt">What is your favorite salty snack?</label>
                <input type="text" id="faveSalt" name="faveSalt" required placeholder="Enter a salty snack"value="<?php echo $commCare->getFaveSalt();?>">
                <label for="faveCandy">What is your favorite candy? </label>
                <input type="text" id="faveCandy" name="faveCandy" required placeholder="Enter a candy name"value="<?php echo $commCare->getFaveCandy();?>">
                <label for="faveCookie">What is your favorite cookie?</label>
                <input type="text" id="faveCookie" name="faveCookie" required placeholder="Enter a cookie name"value="<?php echo $commCare->getFaveCookie();?>">
                <!--Activity Preferences -->
                <label for="forFun">What does your family like to do for fun?</label>
                <input type="text" id="forFun" name="forFun" required placeholder="Enter an activity"value="<?php echo $commCare->getForFun();?>">
                <label for="warmAct">What is your favorite warm weather activity? </label>
                <input type="text" id="warmAct" name="warmAct" required placeholder="Enter an activity name"value="<?php echo $commCare->getWarmAct();?>">
                <label for="coldAct">What is your favorite cold weather activity?</label>
                <input type="text" id="coldAct" name="coldAct" required placeholder="Enter an activity name"value="<?php echo $commCare->getColdAct();?>">
                <label for="notes">What else would you like us to know about your family?</label>
                <input type="text" id="notes" name="notes" placeholder="Enter any other information"value="<?php echo $commCare->getNotes();?>">

                <input type="submit" value="Submit">
            </form>
                <a class="button cancel" href="index.php" style="margin-top: -.5rem">Return to Dashboard</a>
        </main>
    </body>
</html>
