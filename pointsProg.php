<?php
    // Author: Lauren Knight
    // Description: Registration page for new volunteers
    // session_cache_expire(30);
    // session_start();

    require_once('include/input-validation.php');

    // $loggedIn = false;
    // if (isset($_SESSION['change-password'])) {
    //     header('Location: changePassword.php');
    //     die();
    // }
    // if (isset($_SESSION['_id'])) {
    //     $loggedIn = true;
    //     $accessLevel = $_SESSION['access_level'];
    //     $userID = $_SESSION['_id'];
    // }

    // Require admin privileges
    /*if ($accessLevel < 2)
    {
        header('Location: login.php');
        echo 'bad access level';
        die();
    }*/
    

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
    <title>FGP | Points Program </title>
</head>
<body>
    <?php
        require_once('header.php');
        require_once('domain/PointsProg.php');
        require_once('database/dbPointsProg.php');
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            // make every submitted field SQL-safe except for password
            $ignoreList = array('password');
            $args = sanitize($_POST, $ignoreList);
            $_SESSION['form_data'] = $args;
            // echo "<p>The form was submitted:</p>";
            // foreach ($args as $key => $value) {
            //     echo "<p>$key: $value</p>";
            // }


            $required = array('name', 'email', 'address', 'freezer_meals', 'snack_notes',
             'house_cleaning', 'lawn_care', 'aaa_membership', 'photography', 
            'house_projects', 'financial_relief'
                //form requries these but they cannot be confirmed by computer
            );
            
            $points_used = 0;
            
            $errors = false;
            if (!wereRequiredFieldsSubmitted($args, $required)) {
                $errors = true;
            }

            $id = find_next_id() + 1;
            $name = $args['name'];
            $email = $args['email'];
            $address = $args['address'];
            $freezer_meals = $args['freezer_meals'];
            if ($freezer_meals != 2){
                $points_used += ($freezer_meals / 2);
            }
            $snack_notes = $args['snack_notes'];
            
            $house_cleaning = $args['house_cleaning'];
            $points_used += ($house_cleaning * 7);

            $lawn_care = $args['lawn_care'];
            $points_used += ($lawn_care * 3);
            $AAA_membership = $args['aaa_membership'];
            $photography = $args['photography'];
            $house_projects = $args['house_projects'];
            $financial_relief = $args['financial_relief'];
            //checkbox fields
            //Collect allergies selected
            if(isset($_POST["allergies"])){
                $allergies = implode(",", $_POST["allergies"]);
            }
            // Check if "other" checkbox was selected and text box is not empty
            if (isset($_POST["otherAllergy"]) && isset($_POST["otherAllergyText"]) && !empty($_POST["otherAllergyText"])) {
                // Add the other allergy to the allergies array
                $otherAllergy =  "," . $_POST["otherAllergyText"];
                $allergies .= $otherAllergy;
            }
            //Collect snacks selected
            if(isset($_POST["snacks"])){
                $snacks = implode(",", $_POST["snacks"]);
            }
            // Check if "other" checkbox was selected and text box is not empty
            if (isset($_POST["otherSnack"]) && isset($_POST["otherSnackText"]) && !empty($_POST["otherSnackText"])) {
                // Add the other snack to the snacks array
                $otherSnack = "," . $_POST["otherSnackText"];
                $snacks .= $otherSnack;
            }
            //grocery fields
            //Collect grocery input
            if(isset($_POST["grocery"])){
                $grocery = implode(",", $_POST["grocery"]);
            }
            //gas fields
            //Collect gas selected
            if(isset($_POST["gas"])){
                $gas = implode(",", $_POST["gas"]);
            }


            // Regular expression pattern to match integers inside parentheses
            $pattern = '/\((\d+)\)/';

            // Use preg_match_all to find all matches of the pattern in the string
            preg_match_all($pattern, $grocery, $matches);

            // $matches[1] contains all the integers found within parentheses
            foreach ($matches[1] as $match) {
                // Convert the matched string to integer and add to the total
                $points_used += intval($match);
            }

            // Use preg_match_all to find all matches of the pattern in the string
            preg_match_all($pattern, $gas, $matches);

            // $matches[1] contains all the integers found within parentheses
            foreach ($matches[1] as $match) {
                // Convert the matched string to integer and add to the total
                $points_used += intval($match);
            }

            if ($errors) {
                echo '<p>Your form submission contained unexpected input.</p>';
                die();
            }



            $optional=array('aaa_membership_name', 'aaa_membership_dob');

            if($args['aaa_membership_name']){
                $AAA_membership_name=$args['aaa_membership_name'];
            }
            else{
                $AAA_membership_name="";
            }

            if($args['aaa_membership_dob']){
                $AAA_membership_DOB = validateDate($args['aaa_membership_dob']);
                if (!$AAA_membership_DOB) {
                    $errors = true;
                    echo 'bad dob';
                }
            }
            else{
                $AAA_membership_DOB="";
            }

            
            if($points_used > 19){
                header("Location: pointsProg.php?pointsError");
                die();
            }
            

            // need to incorporate availability here
            $newpointsprog = new PointsProg(
                $id, $name, $email, $address, $freezer_meals, 
                $allergies, $snacks, $snack_notes, 
                $grocery, $gas, 
                $house_cleaning, $lawn_care, 
                $AAA_membership, $AAA_membership_name, $AAA_membership_DOB, 
                $photography, $house_projects, $financial_relief, $points_used
            );

            $result = add_points_prog($newpointsprog);
            header("Location: index.php?pointsProgSuccess");
            if (!$result) {
                echo '<p>something went wrong</p>';
            } else {
                if ($loggedIn) {
                    echo '<script>document.location = "index.php?pointsProgSuccess";</script>';
                } else {
                    echo '<script>document.location = "login.php?pointsProgSuccess";</script>';
                }
            }
        } else {
            require_once('pointsProgForm.php'); 
        }
    ?>

</body>
</html>
