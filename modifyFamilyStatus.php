<?php
session_cache_expire(30);
session_start();

require_once('include/input-validation.php');


?>
<!DOCTYPE html>
<html>
<head>
    <?php require_once('universal.inc'); ?>
    <title>FGP|Modify Family Status <?php if ($loggedIn) echo ' New Volunteer' ?></title>
</head>
<body>
    <?php
        require_once('header.php');
        require_once('domain/Person.php');
        require_once('database/dbPersons.php');

    // Retrieve family information
    if (isset($_GET['family_id'])) {
        $id = $_GET['family_id'];
        $person=retrieve_person($id);
    }
    if (!$person) {
        die("Database query failed.");
    }
    

    // Check if the form is submitted
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $args = sanitize($_POST);
        
        if($args['status']){
            $status=$args['status'];
            update_status($person->get_id(),$status);
        }

        if(isset($args['remission_trans_date'])){
            $remission_trans_date=$args['remission_trans_date'];
            update_remission_trans_date($person->get_id(), $remission_trans_date);
        }

        if(isset($args['remembrance_date'])){
            $remembrance_date=$args['remembrance_date'];
            update_remembrance_date($person->get_id(), $remembrance_date);
        }

        if(isset($args['remembrance_wishes'])){
            $remembrance_wishes=$args['remembrance_wishes'];
            update_notes($person->get_id(), $remembrance_wishes);
        }
          
    //echo '<script>document.location = "viewFamilyAccounts.php?modifySuccess";</script>';
    exit();
    }


?>
<h1>Modify Family Status</h1>
<main class="modify-status-form">
    <form class="modify-status-form" method="post">
        <h2>Modify Status</h2>
        <p> Child name is: <?php echo $person->get_first_name() . " ". $person->get_last_name()?></p>
        <p> Current family status is <?php echo $person->get_status()?> </p>
        
        <label name="status"> Select the family status you want to change to (required) </label>
        <select name="status" id="status" required>
            <option value="select"> Select a Status </option>
            <option value="Active"> Active </option>
            <option value="Remission"> Remission </option>
            <option value="Survivor"> Survivor </option>
            <option value="Stargazer"> Stargazer </option>
        </select>
        <h3> Please fill out the relevent fields for the new status where applicable.</h3>
        <fieldset>
            <legend> Remission Modification Fields </legend>
            <label for="remission_trans_date"> Remission Transition Date</label>
            <input type="date" name="remission_trans_date">

        </fieldset>
        
        <fieldset>
            <legend> Stargazer Modification Fields </legend>
            <label for="remembrance_date"> Remembrance Date</label>
            <input type="date" name="remembrance_date" >

            <label for="remembrance_wishes"> Remembrance Wishes</label>
            <input type="text" name="remembrance_wishes" placeholder="Enter wishes">
        </fieldset>

    <button type="submit">Submit</button>
</form>

<?php require_once('universal.inc'); ?>
</body>
</html>