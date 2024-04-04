<?php
session_cache_expire(30);
session_start();

require_once('include/input-validation.php');

// Connect to the database
/*$hostname = "localhost"; 
$database = "fgp";
$username = "fgp";
$password = "fgp";

$connection = mysqli_connect($hostname, $username, $password, $database);

// Check if the connection was successful
if (!$connection) {
    die("Connection failed: " . mysqli_connect_error());
}*/

?>
<!DOCTYPE html>
<html>
<head>
    <?php require_once('universal.inc'); ?>
    <title>ODHS Medicine Tracker | Register <?php if ($loggedIn) echo ' New Volunteer' ?></title>
</head>
<body>
    <?php
        require_once('header.php');
        require_once('domain/Person.php');
        require_once('database/dbPersons.php');

    // Retrieve family information
    if (isset($_GET['id'])) {
        $id = $_GET['id'];
        $person=retrieve_person($id);
    }
    if (!$person) {
        die("Database query failed.");
    }
    

    // Check if the form is submitted
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $args = sanitize($_POST);

        if(isset($_POST['first-name'])){
            $firstname=$_POST['first-name'];
            update_first_name($id,$firstname);
        }
        if(isset($_POST['last_name'])){
            $lastname=$_POST['last_name'];
            update_last_name($id,$lastname);
        }
        if(isset($_POST['address'])&&isset($_POST['city'])){
            $street=$_POST['address'];
            $city=$_POST['city'];
            $state='VA';
            $zip=00000;
            //update_address($id,$street,$city,$state,$zip);
        }
    echo "Update successful.";
    }


?>
<h1>Modify Admin Information</h1>
<main class="signup-form">
    <form class="signup-form" method="post">
        <h2>Modify Form</h2>
        <p> Fill in fields you wish to update.
        <fieldset>
            <legend>Personal Information</legend>
            <label for="first-name">First Name</label>
            <input type="text" id="first-name" name="first-name" placeholder="Enter first name">

            <label for="last-name">Last Name</label>
            <input type="text" id="last-name" name="last-name" placeholder="Enter last name">

            <label for="address">Street Address</label>
            <input type="text" id="address" name="address" placeholder="Enter street address">

            <label for="city">City</label>
            <input type="text" id="city" name="city" placeholder="Enter city">

            <label for="state">State</label>
            
            <select id="state" name="state" >
                <option value="AL">Alabama</option>
                <option value="AK">Alaska</option>
                <option value="AZ">Arizona</option>
                <option value="AR">Arkansas</option>
                <option value="CA">California</option>
                <option value="CO">Colorado</option>
                <option value="CT">Connecticut</option>
                <option value="DE">Delaware</option>
                <option value="DC">District Of Columbia</option>
                <option value="FL">Florida</option>
                <option value="GA">Georgia</option>
                <option value="HI">Hawaii</option>
                <option value="ID">Idaho</option>
                <option value="IL">Illinois</option>
                <option value="IN">Indiana</option>
                <option value="IA">Iowa</option>
                <option value="KS">Kansas</option>
                <option value="KY">Kentucky</option>
                <option value="LA">Louisiana</option>
                <option value="ME">Maine</option>
                <option value="MD">Maryland</option>
                <option value="MA">Massachusetts</option>
                <option value="MI">Michigan</option>
                <option value="MN">Minnesota</option>
                <option value="MS">Mississippi</option>
                <option value="MO">Missouri</option>
                <option value="MT">Montana</option>
                <option value="NE">Nebraska</option>
                <option value="NV">Nevada</option>
                <option value="NH">New Hampshire</option>
                <option value="NJ">New Jersey</option>
                <option value="NM">New Mexico</option>
                <option value="NY">New York</option>
                <option value="NC">North Carolina</option>
                <option value="ND">North Dakota</option>
                <option value="OH">Ohio</option>
                <option value="OK">Oklahoma</option>
                <option value="OR">Oregon</option>
                <option value="PA">Pennsylvania</option>
                <option value="RI">Rhode Island</option>
                <option value="SC">South Carolina</option>
                <option value="SD">South Dakota</option>
                <option value="TN">Tennessee</option>
                <option value="TX">Texas</option>
                <option value="UT">Utah</option>
                <option value="VT">Vermont</option>
                <option value="VA" selected>Virginia</option>
                <option value="WA">Washington</option>
                <option value="WV">West Virginia</option>
                <option value="WI">Wisconsin</option>
                <option value="WY">Wyoming</option>
            </select>

            <label for="zip"><em>* </em>Zip Code</label>
            <input type="text" id="zip" name="zip" pattern="[0-9]{5}" title="5-digit zip code" placeholder="Enter your 5-digit zip code">
        </fieldset>
        <fieldset>
            <legend>Contact Information</legend>
            <!--<p>The following information will help us determine the best way to contact you.</p>-->
            <label for="email"><em>* </em>E-mail</label>
            <!--<p>This will also serve as your username when logging in.</p>-->
            <input type="email" id="email" name="email"  placeholder="Enter your e-mail address">

            <label for="phone"><em>* </em>Phone Number</label>
            <input type="tel" id="phone" name="phone" pattern="\([0-9]{3}\) [0-9]{3}-[0-9]{4}" placeholder="Ex. (555) 555-5555">

            <label><em>* </em>Phone Type</label>
            <div class="radio-group">
                <input type="radio" id="phone-type-cellphone" name="phone-type" value="cellphone" ><label for="phone-type-cellphone">Cell</label>
                <input type="radio" id="phone-type-home" name="phone-type" value="home" ><label for="phone-type-home">Home</label>
                <input type="radio" id="phone-type-work" name="phone-type" value="work" ><label for="phone-type-work">Work</label>
            </div>

            <label><em>* </em>Preferred Contact Method</label>
            <div class="radio-group">
                <input type="radio" id="method-phone" name="contact-method" value="phone" ><label for="method-phone">Phone call</label>
                <input type="radio" id="method-text" name="contact-method" value="text" ><label for="method-text">Text</label>
                <input type="radio" id="method-email" name="contact-method" value="email" ><label for="method-email">E-mail</label>
            </div>
        </fieldset>
       

    <button type="submit">Submit</button>
</form>

<?php require_once('universal.inc'); ?>
</body>
</html>