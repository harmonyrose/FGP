<?php
// Authors: Harmony Peura and Grayson Jones

// Function to display each row in the Gift Card Sign Off table
function displaySearchRow($famArray){
    $family_id = $_GET['family_id'];
    foreach($famArray as $family){
        if($family->getId() == $family_id){
            echo "
            <tr>
                <td>" . $family->getName() . "</td>
                <td>" . $family->getEmail() . "</td>
                <td>" . $family->getGrocery() . "</td>
                <td>" . $family->getGas() . "</td>";
            echo "</tr>";
        }
    }
}
//get specific family name
function get_family_name($famArray){
    $contact_name = '';
    $family_id = $_GET['family_id'];
    foreach($famArray as $family){
        if($family->getId() == $family_id){
            $contact_name = $family->getName();
        }
    }
    return $contact_name;
}
//get specific family id
function get_family_id(){
    $family_id = $_GET['family_id'];
    return $family_id;
}

?>
<!DOCTYPE html>
<html>
    <head>
        <?php require_once('universal.inc') ?>
        <title>FGP | Gift Card Sign Off</title>
    </head>
    <body>
    <?php require_once('header.php') ?>
        <h1>Gift Card Sign Off</h1>
        <?php require_once('database/dbPointsProg.php');
            // Get list of families from dbPointsProg
            $families = getall_pointsProgs();
            $contact_name = get_family_name($families);

        ?>
        <p>Below is the gift card order information for <?php echo $contact_name ?> and family. </p>
        <form id="sign-off" method="post">
        <?php 
            //use display function to display family gift card info
            echo '
                <div class="table-wrapper">
                    <table class="general" id="familyTable">
                        <thead>
                            <tr>
                                <th>Contact Name</th>
                                <th>Email</th>
                                <th>Grocery Cards</th>
                                <th>Gas Cards</th>
                            </tr>
                        </thead>
                        <tbody class="standout">';
                        displaySearchRow($families);
                        
                        echo '
                        </tbody>
                    </table>
                </div>';
        ?>
        </form>
            <p>Please enter your name and today's date as confirmation of this order.</p>
            <?php $family_id = get_family_id(); ?>
            <form action="giftCardSignOff.php?family_id=<?php echo urlencode($family_id); ?>" method="POST">
            <label for="signature">Signature:</label>
            <input type="text" id="signature" name="signature" required placeholder="Enter contact name"><br><br>

            <label for="date">Date:</label>
            <input type="date" id="date" name="date" required><br><br>
            <input type="submit" value="Submit">
        </form>
    </body>
<html>