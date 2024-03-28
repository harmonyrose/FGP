<?php
$times = [
    '12:00 AM', '1:00 AM', '2:00 AM', '3:00 AM', '4:00 AM', '5:00 AM',
    '6:00 AM', '7:00 AM', '8:00 AM', '9:00 AM', '10:00 AM', '11:00 AM',
    '12:00 PM', '1:00 PM', '2:00 PM', '3:00 PM', '4:00 PM', '5:00 PM',
    '6:00 PM', '7:00 PM', '8:00 PM', '9:00 PM', '10:00 PM', '11:00 PM',
    '11:59 PM'
];
$values = [
    "00:00", "01:00", "02:00", "03:00", "04:00", "05:00", 
    "06:00", "07:00", "08:00", "09:00", "10:00", "11:00", 
    "12:00", "13:00", "14:00", "15:00", "16:00", "17:00", 
    "18:00", "19:00", "20:00", "21:00", "22:00", "23:00",
    "23:59"
];

function buildSelect($name, $disabled=false, $selected=null) {
    global $times;
    global $values;
    if ($disabled) {
        $select = '
            <select id="' . $name . '" name="' . $name . '" disabled>';
    } else {
        $select = '
            <select id="' . $name . '" name="' . $name . '">';
    }
    if (!$selected) {
        $select .= '<option disabled selected value>Select a time</option>';
    }
    $n = count($times);
    for ($i = 0; $i < $n; $i++) {
        $value = $values[$i];
        if ($selected == $value) {
            $select .= '
                <option value="' . $values[$i] . '" selected>' . $times[$i] . '</option>';
        } else {
            $select .= '
                <option value="' . $values[$i] . '">' . $times[$i] . '</option>';
        }
    }
    $select .= '</select>';
    return $select;
}

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
            <form id="new-points-prog-form" method="post">

                <label for="name">* Name </label>
                <input type="text" id="name" name="name" required placeholder="Enter contact name">

                <label for="email">* Email </label>
                <input type="text" id="email" name="email" required placeholder="Enter email">

                <label for="address">* Address </label>
                <input type="text" id="address" name="address" required placeholder="Enter address">

                <label for="freezer_meals_and_snacks">Freezer Meals & Snacks </label>
                    <p><b>We offer two freezer meals and snacks per month at NO CHARGE to your points.</b> 
                    Freezer meals will be delivered on the third <b>Tuesday</b> of even months
                    (February, April, June, August, October and December) and snacks will
                     be delivered on the third <b>Tuesday</b> of the odd months 
                     (January, March, May, July, September and November)  
                      On freezer meal months, we ask that you leave a cooler on your doorstep.  
                      A volunteer will leave the meals in the cooler without ringing the doorbell
                       and there's no need for you to be home. 
                    </p>

                <label for="freezer_meals_and_snacks">* How many freezer meals would you like? </label>
                <ul>
                <li><input type="radio" id="freezer_2" name="freezer_meals" value=2> 2 Meals per month (Free)</li>
                <li><input type="radio" id="freezer_4" name="freezer_meals" value=4> 4 meals per month (2 points)</li>
                <li><input type="radio" id="freezer_6" name="freezer_meals" value=6> 6 meals per month (3 points)</li>
                <li><input type="radio" id="freezer_8" name="freezer_meals" value=8> 8 meals per month (4 points)</li>
                <li><input type="radio" id="freezer_0" name="freezer_meals" value=0> We do not want ANY freezer meals</li>
                </ul>

                <label for="allergies">* Are there any food allergies that we need to be aware of? </label>
                <ul>
                <li><input type="checkbox" id="peanuts" name="allergies[]" value="Peanuts"> Peanuts</li>
                <li><input type="checkbox" id="treenuts" name="allergies[]" value="Tree Nuts"> Tree Nuts</li>
                <li><input type="checkbox" id="gluten" name="allergies[]" value="Gluten"> Gluten</li>
                <li><input type="checkbox" id="soy" name="allergies[]" value="Soy"> Soy</li>
                <li><input type="checkbox" id="egg" name="allergies[]" value="Egg"> Egg</li>
                <li><input type="checkbox" id="dairy" name="allergies[]" value="Dairy"> Dairy</li>
                <li><input type="checkbox" id="none" name="allergies[]" value="No Known Allergies"> No Known Allergies</li>
                <li><input type="checkbox" id="other" name="allergies[]" value="other"> Other: <input type= "text" id="otherallergy" name="otherallergy" /></li>
                </ul>

                <label for="snacks">* What types of snacks do you prefer?  We will do our best to accommodate.  Please note that these are examples and not an all inclusive list. </label>
                <ul>
                <li><input type="checkbox" id="crackers" name="snacks[]" value="Crackers"> Crackers</li>
                <li><input type="checkbox" id="cookies" name="snacks[]" value="Cookies"> Cookies</li>
                <li><input type="checkbox" id="chips" name="snacks[]" value="Chips"> Chips</li>
                <li><input type="checkbox" id="granolabars" name="snacks[]" value="Granola Bars"> Granola Bars</li>
                <li><input type="checkbox" id="cereal" name="snacks[]" value="Cereal"> Cereal</li>
                <li><input type="checkbox" id="nuts" name="snacks[]" value="Nuts"> Nuts</li>
                <li><input type="checkbox" id="fruitsnacks" name="snacks[]" value="Fruit Snacks"> Fruit Snacks</li>
                <li><input type="checkbox" id="other" name="snacks[]" value="Other:"> Other: <input type= "text" id="name2" name="name2" /></li>
                </ul>

                <label for="snack_notes">* Are there any snacks that your child/children do not prefer or will not eat? Is there anything else we should know when considering snacks for your family? </label>
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

                <label for="house_cleaning">* Would you like house cleaning? </label>
                <ul>
                <li><input type="radio" id="house_cleaning_1" name="house_cleaning" value="Once a month"> Once a month (7 points)</li>
                <li><input type="radio" id="house_cleaning_2" name="house_cleaning" value="Twice a month"> Twice a month (14 points)</li>
                <li><input type="radio" id="house_cleaning_0" name="house_cleaning" value="No house cleaning"> We do not want house cleaning</li>
                </ul>

                <label for="lawn_care">* Would you like lawn care? </label>
                <ul>
                <li><input type="radio" id="lawn_care_yes" name="lawn_care" value="Yes"> Yes (3 points per month)</li>
                <li><input type="radio" id="lawn_care_no" name="lawn_care" value="No"> We do not want lawn care</li>
                </ul>

                <label for="aaa_membership">* Would you like a AAA Plus Membership? </label>
                <ul>
                <li><input type="radio" id="aaa_yes" name="aaa_membership" value="Yes"> Yes</li>
                <li><input type="radio" id="aaa_no" name="aaa_membership" value="No"> No</li>
                </ul>

                <p> If yes to AAA Membership please provide the responsible party's name and date of birth. </p>

                <p>Responsible Party's Name </p>
                <input type="text" id="aaa_membership_name" name="aaa_membership_name" required placeholder="Enter name">

                <p> Responsible Party's Date of Birth </p>
                <input type="date" id="aaa_membership_dob" name="aaa_membership_dob" required placeholder="Date of birth"  max="<?php echo date('Y-m-d'); ?>">
                
                <label for="photography"> Photography </label>
                    <p> 
                    We offer your family two sessions of photography.  
                    We will do one during treatment and again after treatment has finished. 
                    There is no charge to your points for this service. 
                    </p>

                <label for="photography">* Are you interested in a photography session? </label>
                <ul>
                <li><input type="radio" id="photo_yes" name="photography" value="Yes"> Yes</li>
                <li><input type="radio" id="photo_no" name="photography" value="No"> No</li>

                <label for="add_services"> Additional Services </label>
                    <p>
                    We currently offer to help with house projects and financial relief twice a year.  
                    If you are interested we will contact you when these services become available
                    </p>   
                <label for="house_projects">* House Projects </label>
                <ul>
                <li><input type="radio" id="house_more_info" name="house_projects" value="More info requested"> We would like more information when available</li>
                <li><input type="radio" id="house_not_interested" name="house_projects" value="Not interested"> We are not interested in house projects</li>

                <label for="financial_relief">* Financial Relief </label>
                <ul>
                <li><input type="radio" id="relief_more_info" name="financial_relief" value="More info requested"> We would like more information when available</li>
                <li><input type="radio" id="relief_not_interested" name="financial_relief" value="Not interested"> We are not interested in financial relief</li>

                <input type="submit" name="points_form" value="Submit">
            </form>

        </main>
    </body>
</html>