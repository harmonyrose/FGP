<?php
    // Author: Lauren Knight
    // Description: Registration page for new volunteers
    session_cache_expire(30);
    session_start();
    
    require_once('include/input-validation.php');

    /*$loggedIn = false;
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
    */

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
    <title>FGP | Become an FGP Family </title>
</head>
<body>
    <?php
        require_once('header.php');
        require_once('domain/Person.php');
        require_once('database/dbPersons.php');
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            // make every submitted field SQL-safe except for password
            $ignoreList = array('password');
            $args = sanitize($_POST, $ignoreList);

            // echo "<p>The form was submitted:</p>";
            // foreach ($args as $key => $value) {
            //     echo "<p>$key: $value</p>";
            // }

            $required = array('econtact-name','cmethod','phone','email',
                'address', 'city', 'state', 'zip', 'first-name', 'last-name', 'birthdate'
                /*'diagnosis','diagnosis_date','hospital','permission_to_confirm',
                'expected_treatment_end_date','services_interested_in','agreement'*/
                //form requries these but they cannot be confirmed by computer
            );
            
            $errors = false;
            if (!wereRequiredFieldsSubmitted($args, $required)) {
                $errors = true;
            }
            $first = $args['first-name'];
            $last = $args['last-name'];
            $dateOfBirth = validateDate($args['birthdate']);
            if (!$dateOfBirth) {
                $errors = true;
                echo 'bad dob';
            }

            $address = $args['address'];
            $city = $args['city'];
            $state = $args['state'];
            if (!valueConstrainedTo($state, array('AK', 'AL', 'AR', 'AZ', 'CA', 'CO', 'CT', 'DC', 'DE', 'FL', 'GA',
                    'HI', 'IA', 'ID', 'IL', 'IN', 'KS', 'KY', 'LA', 'MA', 'MD', 'ME',
                    'MI', 'MN', 'MO', 'MS', 'MT', 'NC', 'ND', 'NE', 'NH', 'NJ', 'NM',
                    'NV', 'NY', 'OH', 'OK', 'OR', 'PA', 'RI', 'SC', 'SD', 'TN', 'TX',
                    'UT', 'VA', 'VT', 'WA', 'WI', 'WV', 'WY'))) {
                $errors = true;
            }
            $zipcode = $args['zip'];
            if (!validateZipcode($zipcode)) {
                $errors = true;
                echo 'bad zip';
            }
            $email = strtolower($args['email']);
            $email = validateEmail($email);
            if (!$email) {
                $errors = true;
                echo 'bad email';
            }
            $phone = validateAndFilterPhoneNumber($args['phone']);
            if (!$phone) {
                $errors = true;
                echo 'bad phone';
            }

            $econtactName = $args['econtact-name'];
            $phone1 = validateAndFilterPhoneNumber($args['econtact-phone']);
            if (!$econtactPhone) {
                $errors = true;
                echo 'bad e-contact phone';
            }
            

            // May want to enforce password requirements at this step
            $password = password_hash($args['password'], PASSWORD_BCRYPT);

            if ($errors) {
                echo '<p>Your form submission contained unexpected input.</p>';
                die();
            }
            // need to incorporate availability here
            $newperson = new Person(
//first, last venue
		$first, $last, 'portland', 
//address, city state, zip code, profile picture
                $address, $city, $state, $zipcode, "",
//phone1, phone type, phone 2, phonetype 2, email
                $phone, 'cell', null, null, $email, 
//contact name, contact number, contact relation
		$econtactName, null, null, 
//ct=contact when, type=t, status = st, ct=contact method 
                null, 'afamily', 'inactive', $cmethod, 
//availability array, schedule array, hours array
		'', '', '', 
//bd=date of birth, sd=start date, notes password
                $dateOfBirth, null, null, $dateOfBirth,
                $sundaysStart, $sundaysEnd, $mondaysStart, $mondaysEnd,
                $tuesdaysStart, $tuesdaysEnd, $wednesdaysStart, $wednesdaysEnd,
                $thursdaysStart, $thursdaysEnd, $fridaysStart, $fridaysEnd,
                $saturdaysStart, $saturdaysEnd, 0, $gender
            );
            $result = add_person($newperson);
            if (!$result) {
                echo '<p>That e-mail address is already in use.</p>';
            } else {
                if ($loggedIn) {
                    echo '<script>document.location = "index.php?registerSuccess";</script>';
                } else {
                    echo '<script>document.location = "login.php?registerSuccess";</script>';
                }
            }
        } else {
            require_once('createAccountForm.php'); 
        }
    ?>
</body>
</html>