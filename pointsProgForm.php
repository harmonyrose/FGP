<?php
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
        require_once('database/dbEvents.php');
        $args = sanitize($_POST, null);
        $required = array(
            "name", "abbrev-name", "date", "start-time", "description", "location", "service", "animal"
        );
        if (!wereRequiredFieldsSubmitted($args, $required)) {
            echo 'bad form data';
            die();
        } else {
            $validated = validate12hTimeRangeAndConvertTo24h($args["start-time"], "11:59 PM");
            if (!$validated) {
                echo 'bad time range';
                die();
            }
            $startTime = $args['start-time'] = $validated[0];
            $date = $args['date'] = validateDate($args["date"]);
            //$capacity = intval($args["capacity"]);
            $abbrevLength = strlen($args['abbrev-name']);
            if (!$startTime || !$date || $abbrevLength > 11){
                echo 'bad args';
                die();
            }
            $id = create_event($args);
            if(!$id){
                echo "Oopsy!";
                die();
            }
            require_once('include/output.php');
            
            $name = htmlspecialchars_decode($args['name']);
            $startTime = time24hto12h($startTime);
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
    // Get all the animals from animal table
    $sql = "SELECT * FROM `dbAnimals`";
    $all_animals = mysqli_query($con,$sql);
    $sql = "SELECT * FROM `dbLocations`";
    $all_locations = mysqli_query($con,$sql);
    $sql = "SELECT * FROM `dbServices`";
    $all_services = mysqli_query($con,$sql);

?>
<!DOCTYPE html>
<html>
    <head>
        <?php require_once('universal.inc') ?>
        <title>FGP | Points Program</title>
    </head>
    <body>
        <?php require_once('header.php') ?>
        <h1>Points Program</h1>
        <main class="date">
            <h2>Points Program Form</h2>
            <form id="new-event-form" method="post">
                <label for="name">* Name </label>
                <input type="text" id="name" name="name" required placeholder="Enter contact name">
                <label for="name">* Address </label>
                <input type="text" id="address" name="address" required placeholder="Enter address">
                <label for="name">Freezer Meals & Snacks </label>
                    <p><b>We offer two freezer meals and snacks per month at NO CHARGE to your points.</b> 
                    Freezer meals will be delivered on the third <b>Tuesday</b> of even months
                    (February, April, June, August, October and December) and snacks will
                     be delivered on the third <b>Tuesday</b> of the odd months 
                     (January, March, May, July, September and November)  
                      On freezer meal months, we ask that you leave a cooler on your doorstep.  
                      A volunteer will leave the meals in the cooler without ringing the doorbell
                       and there's no need for you to be home. 
                </p>
                <label for="name">* How many freezer meals would you like? </label>
                <ul>
                <li><input type="radio" id="freezer_meals" name="freezer_meals" value="2 Meals per month (Free)"> 2 Meals per month (Free)</li>
                <li><input type="radio" id="freezer_meals" name="freezer_meals" value="2 Meals per month (Free)"> 4 meals per month (2 points)</li>
                <li><input type="radio" id="freezer_meals" name="freezer_meals" value="2 Meals per month (Free)"> 6 meals per month (3 points)</li>
                <li><input type="radio" id="freezer_meals" name="freezer_meals" value="2 Meals per month (Free)"> 8 meals per month (4 points)</li>
                <li><input type="radio" id="freezer_meals" name="freezer_meals" value="2 Meals per month (Free)"> We do not want ANY freezer meals</li>
                </ul>
                <label for="name">* Are there any food allergies that we need to be aware of? </label>
                <ul>
                <li><input type="checkbox" id="allergies" name="allergies" value="Peanuts"> Peanuts</li>
                <li><input type="checkbox" id="allergies" name="allergies" value="Tree Nuts"> Tree Nuts</li>
                <li><input type="checkbox" id="allergies" name="allergies" value="Gluten"> Gluten</li>
                <li><input type="checkbox" id="allergies" name="allergies" value="Soy"> Soy</li>
                <li><input type="checkbox" id="allergies" name="allergies" value="Egg"> Egg</li>
                <li><input type="checkbox" id="allergies" name="allergies" value="Dairy"> Dairy</li>
                <li><input type="checkbox" id="allergies" name="allergies" value="No Known Allergies"> No Known Allergies</li>
                <li><input type="checkbox" id="allergies" name="allergies" value="Other:"> Other: <input type= "text" id="name2" name="name2" /></li>
                </ul>
                <label for="name">* What types of snacks do you prefer?  We will do our best to accommodate.  Please note that these are examples and not an all inclusive list. </label>
                <ul>
                <li><input type="checkbox" id="snacks" name="snacks" value="Crackers"> Crackers</li>
                <li><input type="checkbox" id="snacks" name="snacks" value="Cookies"> Cookies</li>
                <li><input type="checkbox" id="snacks" name="snacks" value="Chips"> Chips</li>
                <li><input type="checkbox" id="snacks" name="snacks" value="Granola Bars"> Granola Bars</li>
                <li><input type="checkbox" id="snacks" name="snacks" value="Cereal"> Cereal</li>
                <li><input type="checkbox" id="snacks" name="snacks" value="Nuts"> Nuts</li>
                <li><input type="checkbox" id="snacks" name="snacks" value="Fruit Snacks"> Fruit Snacks</li>
                <li><input type="checkbox" id="snacks" name="snacks" value="Other:"> Other: <input type= "text" id="name2" name="name2" /></li>
                </ul>
                <label for="name">* Are there any snacks that your child/children do not prefer or will not eat? Is there anything else we should know when considering snacks for your family? </label>
                <input type="text" id="snack_notes" name="snack_notes" required placeholder="Your answer">
                <label for="name">Grocery Store Gift Cards </label>
                <p>We only offer gift cards from stores that allow us to 
                    purchase the cards online.  Shoppers Food Warehouse 
                    and Aldi do not currently have that service. 
                    Please note that Walmart does not allow shipments to
                    PO Boxes. 
                </p>
                <label for="name"> Store Selection </label>
                <style>
                    th, td {
                        padding: 8px;
                        text-align: center;
                    }
                </style>
                <table>
                    <thead>
                        <tr>
                            <th></th>
                            <th>$25 Gift Card<br>(1 point)</th>
                            <th>$50 Gift Card<br>(2 points)</th>
                            <th>$75 Gift Card<br>(3 points)</th>
                            <th>$100 Gift Card<br>(4 points)</th>
                            <th>$200 Gift Card<br>(8 points)</th>
                            <th>$300 Gift Card<br>(12 points)</th>
                            <th>$400 Gift Card<br>(16 points)</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Food Lion</td>
                            <td><input type="checkbox" name="foodlion_25"></td>
                            <td><input type="checkbox" name="foodlion_50"></td>
                            <td><input type="checkbox" name="foodlion_75"></td>
                            <td><input type="checkbox" name="foodlion_100"></td>
                            <td><input type="checkbox" name="foodlion_200"></td>
                            <td><input type="checkbox" name="foodlion_300"></td>
                            <td><input type="checkbox" name="foodlion_400"></td>
                        </tr>
                        <tr>
                            <td>Giant</td>
                            <td><input type="checkbox" name="giant_25"></td>
                            <td><input type="checkbox" name="giant_50"></td>
                            <td><input type="checkbox" name="giant_75"></td>
                            <td><input type="checkbox" name="giant_100"></td>
                            <td><input type="checkbox" name="giant_200"></td>
                            <td><input type="checkbox" name="giant_300"></td>
                            <td><input type="checkbox" name="giant_400"></td>
                        </tr>
                        <tr>
                            <td>Walmart</td>
                            <td><input type="checkbox" name="walmart_25"></td>
                            <td><input type="checkbox" name="walmart_50"></td>
                            <td><input type="checkbox" name="walmart_75"></td>
                            <td><input type="checkbox" name="walmart_100"></td>
                            <td><input type="checkbox" name="walmart_200"></td>
                            <td><input type="checkbox" name="walmart_300"></td>
                            <td><input type="checkbox" name="walmart_400"></td>
                        </tr>
                        <tr>
                            <td>Wegmans</td>
                            <td><input type="checkbox" name="wegmans_25"></td>
                            <td><input type="checkbox" name="wegmans_50"></td>
                            <td><input type="checkbox" name="wegmans_75"></td>
                            <td><input type="checkbox" name="wegmans_100"></td>
                            <td><input type="checkbox" name="wegmans_200"></td>
                            <td><input type="checkbox" name="wegmans_300"></td>
                            <td><input type="checkbox" name="wegmans_400"></td>
                        </tr>
                    </tbody>
                </table>
                <label for="name">Gas Gift Cards</label>
                <p> We are currently offering gas cards from Sheetz and Wawa.</p>
                <label for="name">Gas Card Selection</label>
                <style>
                    th, td {
                        padding: 8px;
                        text-align: center;
                    }
                </style>
                <table>
                    <thead>
                        <tr>
                            <th></th>
                            <th>$25 Gift Card<br>(1 point)</th>
                            <th>$50 Gift Card<br>(2 points)</th>
                            <th>$75 Gift Card<br>(3 points)</th>
                            <th>$100 Gift Card<br>(4 points)</th>
                            <th>$200 Gift Card<br>(8 points)</th>
                            <th>$300 Gift Card<br>(12 points)</th>
                            <th>$400 Gift Card<br>(16 points)</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Sheetz</td>
                            <td><input type="checkbox" name="sheetz_25"></td>
                            <td><input type="checkbox" name="sheetz_50"></td>
                            <td><input type="checkbox" name="sheetz_75"></td>
                            <td><input type="checkbox" name="sheetz_100"></td>
                            <td><input type="checkbox" name="sheetz_200"></td>
                            <td><input type="checkbox" name="sheetz_300"></td>
                            <td><input type="checkbox" name="sheetz_400"></td>
                        </tr>
                        <tr>
                            <td>Wawa</td>
                            <td><input type="checkbox" name="wawa_25"></td>
                            <td><input type="checkbox" name="wawa_50"></td>
                            <td><input type="checkbox" name="wawa_75"></td>
                            <td><input type="checkbox" name="wawa_100"></td>
                            <td><input type="checkbox" name="wawa_200"></td>
                            <td><input type="checkbox" name="wawa_300"></td>
                            <td><input type="checkbox" name="wawa_400"></td>
                        </tr>
                    </tbody>
                </table>
                <label for="name">* Would you like house cleaning? </label>
                    <?php 
                        echo '<ul>';
                        echo '<li><input class="radio" type="radio" name="house_cleaning" value="' . "once" . '" required/> ' . "Once a month (7 points)" . '</li>';
                        echo '<li><input class="radio" type="radio" name="house_cleaning" value="' . "twice" . '" required/> ' . "Twice a month (14 points)" . '</li>';
                        echo '<li><input class="radio" type="radio" name="house_cleaning" value="' . "no" . '" required/> ' . "We do not want house cleaning" . '</li>';
                        echo '</ul>';
                    ?>
                <label for="name">* Would you like lawn care? </label>
                    <?php 
                        echo '<ul>';
                        echo '<li><input class="radio" type="radio" name="lawn_care" value="' . "yes" . '" required/> ' . "Yes (3 points per month)" . '</li>';
                        echo '<li><input class="radio" type="radio" name="lawn_care" value="' . "no" . '" required/> ' . "We do not want lawn care" . '</li>';
                        echo '</ul>';
                    ?>
                <label for="name">* Would you like a AAA Plus Membership? </label>
                    <?php 
                        echo '<ul>';
                        echo '<li><input class="radio" type="radio" name="AAA_membership" value="' . "yes" . '" required/> ' . "Yes" . '</li>';
                        echo '<li><input class="radio" type="radio" name="AAA_membership" value="' . "no" . '" required/> ' . "No" . '</li>';
                        echo '</ul>';
                    ?>
                <p> If yes to AAA Membership please provide the responsible party's name and date of birth. </p>
                <p>Responsible Party's Name </p>
                <input type="text" id="AAA_membership_name" name="AAA_membership_name" required placeholder="Enter name">
                <p> Responsible Party's Date of Birth </p>
                <input type="date" id="AAA_membership_DOB" name="AAA_membership_DOB" <?php if ($date) echo 'value="' . $date . '"'; ?> min="<?php echo date('Y-m-d'); ?>" required>
                <label for="name"> Photography </label>
                    <p> 
                    We offer your family two sessions of photography.  
                    We will do one during treatment and again after treatment has finished. 
                    There is no charge to your points for this service. 
                    </p> 
                <label for="name">* Are you interested in a photography session? </label>
                    <?php 
                        echo '<ul>';
                        echo '<li><input class="radio" type="radio" name="photography" value="' . "yes" . '" required/> ' . "Yes" . '</li>';
                        echo '<li><input class="radio" type="radio" name="photography" value="' . "no" . '" required/> ' . "No" . '</li>';
                        echo '</ul>';
                    ?>
                <label for="name"> Additional Services </label>
                    <p>
                    We currently offer to help with house projects and financial relief twice a year.  
                    If you are interested we will contact you when these services become available
                    </p>   
                <label for="name">* House Projects </label>
                    <?php 
                        echo '<ul>';
                        echo '<li><input class="radio" type="radio" name="house_projects" value="' . "more_info" . '" required/> ' . "We would like more information when available" . '</li>';
                        echo '<li><input class="radio" type="radio" name="house_projects" value="' . "not_interested" . '" required/> ' . "We are not interested in house projects" . '</li>';
                        echo '</ul>';
                    ?>
                <label for="name">* Financial Relief </label>
                    <?php 
                        echo '<ul>';
                        echo '<li><input class="radio" type="radio" name="financiel_relief" value="' . "more_info" . '" required/> ' . "We would like more information when available" . '</li>';
                        echo '<li><input class="radio" type="radio" name="financiel_relief" value="' . "not_interested" . '" required/> ' . "We are not interested in financial relief" . '</li>';
                        echo '</ul>';
                    ?>
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