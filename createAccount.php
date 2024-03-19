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
                'address', 'city', 'state', 'zip', 'first-name', 'last-name', 'birthdate',
                'diagnosis','diagnosis_date','hospital','permission_to_confirm',
                'expected_treatment_end_date','services','agreement'
                //form requries these but they cannot be confirmed by computer
            );

            $services=$args['services'];
            
            
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
            $diagnosis_date=$args['diagnosis_date'];
            /*$diagnosis_date = validateDate($args['diagnosis_date']);
            if (!$diagnosis_date) {
                $errors = true;
                echo 'bad diagnosis date';
            }*/

            $hospital=$args['hospital'];

            $cmethod=$args['cmethod'];
            if(!$cmethod="call" and !$cmethod='text'){
                $errors=true;
                echo 'bad contact method';
            }

            $expected_treatment_end_date=$args['expected_treatment_end_date'];

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
            $agreement=$args['agreement'];
            //requires signature to be same as contact parent, doesn't seem 
            //like a guarentee so cut for now
            /*if(validateAgreement($agreement, $econtactName)!=0){
                $errors=true;
                echo 'bad agreement';
            }*/

            /*$phone1 = validateAndFilterPhoneNumber($args['econtact-phone']);
            if (!$econtactPhone) {
                $errors = true;
                echo 'bad e-contact phone';
            }*/
            
            $diagnosis=$args['diagnosis'];
            $permission_to_confirm=$args['permission_to_confirm'];
           
            
            $meals=0;
            $housecleaning=0;
            $lawncare=0;
            $photography=0;
            $gas=0;
            $grocery=0;
            $aaaInterest=0;
            $socialEvents=0;
            $houseProjects=0;
            
            //$n=count($services);
            //echo $n . "number of services[] items";
            foreach ($services as $service){
                //echo $service . "<br>";
                switch ($service){
                    case 'meals':
                        $meals=1;
                        break;
                    case 'housecleaning':
                        $housecleaning=1;  
                        break;
                    case 'lawncare':
                        $lawncare=1;
                        break;
                    case 'profphotos':
                        $profphotos=1;
                        break;
                    case 'gascards':
                        $gascards=1;  
                        break;  
                    case 'grocerycards':
                        $grocerycards=1;  
                        break;
                    case 'aaaInterest':
                        $aaaInterest=1;  
                        break;
                    case 'socialevents':
                        $socialevents=1;  
                        break;
                    case 'houseprojects':
                        $houseprojects=1;  
                        break;
                }
            }
            
            // May want to enforce password requirements at this step
            $password=$dateOfBirth;
            //$password = password_hash($args['password'], PASSWORD_BCRYPT);

            if ($errors) {
                echo '<p>Your form submission contained unexpected input.</p>';
                die();
            }

            $optional=array('allergies','sibling_info','can_share_contact_info',
            'family_info','how_did_you_hear','address2');

            if($args['allergies']){
                $allergies=$args['allergies'];
            }
            else{
                $allergies="";
            }

            if($args['sibling_info']){
                $sibling_info=$args['sibling_info'];
            }
            else{
                $sibling_info="";
            }


            if($args['can_share_contact_info']){
                $can_share_contact_info=$args['can_share_contact_info'];
            }
            else{
                $can_share_contact_info="";
            }

            if($args['family_info']){
                $family_info=$args['family_info'];
            }
            else{
                $family_info="";
            }
            
            if($args['how_did_you_hear']){
                $how_did_you_hear=$args['how_did_you_hear'];
            }else{
                $how_did_you_hear="";
            }
            
            if($args['address2']){
                $address=$address.", ".$args['address2'];
            }


        
            $services_interested_in=0;


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
            null, 'family', 'pending', $cmethod, 
            //availability array, schedule array, hours array
		    '', '', '', 
            //bd=date of birth, sd=start date, notes password
            $dateOfBirth, null, null, $password,
            //all the availability variables
            null,null,null,null,
            null,null,null,null,
            null,null,null,null,
            //avail, avail, must change password, gender, diagnosis
            null,null, 0, null,$diagnosis,
            $diagnosis_date,$hospital,$permission_to_confirm, 
            $expected_treatment_end_date,$services_interested_in, 
            $allergies,$sibling_info,$can_share_contact_info,
            substr($first, 0,1).$last //username
            ,$meals, $housecleaning, $lawncare,$photography,
            $gas,$grocery,$aaaInterest,$socialEvents, $houseProjects,
            //how did they hear, general family info, lead volunteer, GC delivery method, location
            $how_did_you_hear,$family_info,null,null,null
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
