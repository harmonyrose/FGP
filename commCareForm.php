<?php
    // Make session information accessible, allowing us to associate
    // data with the logged-in user.
    session_cache_expire(30);
    session_start();

    ini_set("display_errors",1);
    error_reporting(E_ALL);
    /*
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
    */
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        require_once('include/input-validation.php');
        require_once('database/dbCommCare.php'); //changed from dbPointsProg to dbCommCare
        $args = sanitize($_POST, null);
        $required = array(
            "adultNames", "childrenInfo", "sportsFan", "sitDinner", "fastFood", "sweetTreat", "faveSweet", "faveSalt",
            "faveCandy", "faveCookie", "forFun", "warmAct", "coldAct"
        );
        if (!wereRequiredFieldsSubmitted($args, $required)) {
            echo 'bad form data';
            die();
        } else {
            $date = $args['AAA_membership_DOB'] = validateDate($args["AAA_membership_DOB"]);
            $id = ($args);
            if(!$id){
                echo "Oopsy!";
                die();
            }
            require_once('include/output.php');
            
            $name = htmlspecialchars_decode($args['name']);
            $date = date('l, F j, Y', strtotime($date));
            require_once('database/dbMessages.php');
            system_message_all_users_except($userID, "A new event was created!", "Exciting news!\r\n\r\nThe [$name](event: $id) event at $startTime on $date was added!\r\nSign up today!");
            header("Location: event.php?id=$id&createSuccess");
            die();
        }
    }
    $date = null;
    if (isset($_GET['date'])) {
        $date = $_GET['date'];
        $datePattern = '/[0-9]{4}-[0-9]{2}-[0-9]{2}/';
        $timeStamp = strtotime($date);
        if (!preg_match($datePattern, $date) || !$timeStamp) {
            header('Location: calendar.php');
            die();
        }
    }

    // get animal data from database for form
    // Connect to database
    include_once('database/dbinfo.php'); 
    $con=connect();  
    // Got rid of animal dbs
?>
<!DOCTYPE html>
<html>
    <head>
        <?php require_once('universal.inc') ?>
        <title>FGP | Community Care</title>
    </head>
    <body>
        <?php require_once('header.php') ?>
        <h1>Community Care</h1>
        <main class="date">
            <h2>Community Care Package Form</h2>
            <form id="new-event-form" method="post">
                <!--Adult names and children info -->
                <label for="adultNames">* Names of adults in household </label>
                <input type="text" id="adultNames" name="adultNames" required placeholder="Enter adult names">
                <label for="childrenInfo">* For children in the house hold: list names,ages, genders and clothing sizes</label>
                <input type="text" id="childrenInfo" name="childrenInfo" required placeholder="Enter children information">
                <!--Sports Fans? If so, what teams do you like? -->
                <label for="sportsFan">* Are you sports fans? </label>
                    <?php 
                        echo '<ul>';
                        echo '<li><input class="radio" type="radio" name="sportsFan" value="' . "yes" . '" required/> ' . "Yes" . '</li>';
                        echo '<li><input class="radio" type="radio" name="sportsFan" value="' . "no" . '" required/> ' . "No" . '</li>';
                        echo '</ul>';
                    ?>
                <label for="sportsInfo">What teams do you enjoy cheering for? </label>
                <input type="text" id="sportsInfo" name="sportsInfo" required placeholder="Enter team names">
                <!--Resturants and Snack Preferences -->
                <label for="sitDinner">* Where do you like to eat a sit-down dinner?</label>
                <input type="text" id="sitDinner" name="sitDinner" required placeholder="Enter a restaurant name">
                <label for="fastFood">* What are your favorite fast food restaurants?</label>
                <input type="text" id="fastFood" name="fastFood" required placeholder="Enter a restaurant name">
                <label for="sweetTreat">* Where do you like to grab a sweet treat?</label>
                <input type="text" id="sweetTreat" name="sweetTreat" required placeholder="Enter a restaurant/store name">
                <label for="faveSweet">* What is your favorite sweet treat?</label>
                <input type="text" id="faveSweet" name="faveSweet" required placeholder="Enter a sweet treat">
                <label for="faveSalt">* What is your favorite salty snack?</label>
                <input type="text" id="faveSalt" name="faveSalt" required placeholder="Enter a salty snack">
                <label for="faveCandy">* What is your favorite candy? </label>
                <input type="text" id="faveCandy" name="faveCandy" required placeholder="Enter a candy name">
                <label for="faveCookie">* What is your favorite cookie?</label>
                <input type="text" id="faveCookie" name="faveCookie" required placeholder="Enter a cookie name">
                <!--Activity Preferences -->
                <label for="forFun">* What does your family like to do for fun?</label>
                <input type="text" id="forFun" name="forFun" required placeholder="Enter an activity">
                <label for="warmAct">* What is your favorite warm weather activity? </label>
                <input type="text" id="warmAct" name="warmAct" required placeholder="Enter an activity name">
                <label for="coldAct">* What is your favorite cold weather activity?</label>
                <input type="text" id="coldAct" name="coldAct" required placeholder="Enter an activity name">
                <label for="notes">What else would you like us to know about your family?</label>
                <input type="text" id="notes" name="notes" required placeholder="Enter any other information">




                <input type="submit" value="Submit">
                                <!--Commented out original ODHS code-->
                <!--<label for="name">* Appointment Name </label>
                <input type="text" id="name" name="name" required placeholder="Enter name">
                <label for="name">* Abbreviated Name</label>
                <input type="text" id="abbrev-name" name="abbrev-name" maxlength="11" required placeholder="Enter name that will appear on calendar">
                <label for="name">* Date </label>
                <input type="date" id="date" name="date" <?php if ($date) echo 'value="' . $date . '"'; ?> min="<?php echo date('Y-m-d'); ?>" required>
                <label for="name">* Start Time </label>
                <input type="text" id="start-time" name="start-time" pattern="([1-9]|10|11|12):[0-5][0-9] ?([aApP][mM])" required placeholder="Enter start time. Ex. 12:00 PM">
                <label for="name">* Description </label>
                <input type="text" id="description" name="description" required placeholder="Enter description">
                <fieldset>
                    <label for="name">* Service </label>
                    <?php 
                        // fetch data from the $all_services variable
                        // and individually display as an option
                        echo '<ul>';
                        while ($service = mysqli_fetch_array(
                                $all_services, MYSQLI_ASSOC)):; 
                            echo '<li><input class="checkboxes" type="checkbox" name="service[]" value="' . $service['id'] . '" required/> ' . $service['name'] . '</li>';
                        endwhile;
                        echo '</ul>';
                    ?>
                </fieldset> 
                <label for="name">* Location </label>
                <select for="name" id="location" name="location" required>
                    <option value="">--</option>
                    <?php 
                        // fetch data from the $all_locations variable
                        // and individually display as an option
                        while ($location = mysqli_fetch_array(
                                $all_locations, MYSQLI_ASSOC)):; 
                    ?>
                    <option value="<?php echo $location['id'];?>">
                        <?php echo $location['name'];?>
                    </option>
                    <?php 
                        endwhile; 
                        // terminate while loop
                    ?>
                </select><p></p>
  
                <label for="name">* Animal</label>
                <select for="name" id="animal" name="animal" required>
                    <?php 
                        // fetch data from the $all_animals variable
                        // and individually display as an option
                        while ($animal = mysqli_fetch_array(
                                $all_animals, MYSQLI_ASSOC)):; 
                    ?>
                    <option value="<?php echo $animal['id'];?>">
                        <?php echo $animal['name'];?>
                    </option>
                    <?php 
                        endwhile; 
                        // terminate while loop
                    ?>
                </select><br/>
                <p></p>
                -->
            </form>
                <?php if ($date): ?>
                    <a class="button cancel" href="calendar.php?month=<?php echo substr($date, 0, 7) ?>" style="margin-top: -.5rem">Return to Calendar</a>
                <?php else: ?>
                    <a class="button cancel" href="index.php" style="margin-top: -.5rem">Return to Dashboard</a>
                <?php endif ?>
                <!-- Require at least one checkbox be checked -->
                <script type="text/javascript">
                    $(document).ready(function(){
                        var checkboxes = $('.checkboxes');
                        checkboxes.change(function(){
                            if($('.checkboxes:checked').length>0) {
                                checkboxes.removeAttr('required');
                            } else {
                                checkboxes.attr('required', 'required');
                            }
                        });
                    });
                </script>
        </main>
    </body>
</html>