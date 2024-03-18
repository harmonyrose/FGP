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
?>

<h1>New Family Sign Up</h1>
<main class="family-account-form">
    <form class="family-account-form" method="post">
        <h2>Become an FGP Family</h2>
        <p>We provide support to families with a child in treatment for pediatric cancer. We require a confirmation of treatment filled out by an oncologist to confirm the
             diagnosis which will be sent to you within the next 24 hours. Please fill out this form to give us an idea of what type of support your family needs. 
             If we provide support in your area, we will let you know next steps. We are accepting applications for families in Planning District 16 (Stafford, 
             Spotsylvania, Caroline, King George Counties and Fredericksburg City) and the Northern Neck. This application does not guarantee services!</p>
        <p>An asterisk (<em>*</em>) indicates a required field.</p>
        <fieldset>
            <!--<legend>Personal Information</legend>-->
            <label for="econtact-name"><em>* </em>Who is the best person to contact to set up services?</label>
            <input type="text" id="econtact-name" name="econtact-name" required placeholder="Your answer">

            <label for="cmethod"><em>* </em>What is your prefered means of contact?</label>
            <select id="cmethod" name="cmethod" required>
                <option value="">Choose an option</option>
                <option value="text">Text Message</option>
                <option value="call">Phone Call</option>
            </select>

            <label for="phone"><em>* </em>Phone Number</label>
            <input type="tel" id="phone" name="phone" pattern="\([0-9]{3}\) [0-9]{3}-[0-9]{4}" required placeholder="Ex. (555) 555-5555">
           
            <label for="email"><em>* </em>Email</label>
            <input type="text" id="email" name="email" required placeholder="">

            <label for="address"><em>* </em>Address</label>
            <input type="text" id="address" name="address" required placeholder="">

            <label for="address2">Address Line 2</label>
            <input type="text" id="address2" name="address2" placeholder="">

            <label for="city"><em>* </em>City</label>
            <input type="text" id="city" name="city" required placeholder="Enter your city">

            <label for="state"><em>* </em>State</label>
            
            <select id="state" name="state" required>
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
            <input type="text" id="zip" name="zip" pattern="[0-9]{5}" title="5-digit zip code" required placeholder="Enter your 5-digit zip code">
        
            <label for="first-name"><em>* </em>Child's First Name</label>
            <input type="text" id="first-name" name="first-name" required placeholder="">

            <label for="last-name"><em>* </em>Child's Last Name</label>
            <input type="text" id="last-name" name="last-name" required placeholder="">

            <label for="birthdate"><em>* </em>Child's Birthdate</label>
            <input type="date" id="birthdate" name="birthdate" required placeholder="Choose your birthday" max="<?php echo date('Y-m-d'); ?>">

            <label for="diagnosis"><em>* </em>Child's Diagnosis</label>
            <input type="text" id="diagnosis" name="diagnosis" required placeholder="Your answer">

            <label for="diagnosis_date"><em>* </em>Date of Diagnosis</label>
            <input type="date" id="diagnosis_date" name="diagnosis_date" required placeholder="Your answer">

            <label for="hospital"><em>* </em>Where is your child receiving treatment? please include name of oncologist or contact at hospital</label>
            <input type="text" id="hospital" name="hospital" required placeholder="Your answer">

            <label><em>* </em>Do we have permission to reach out to the above mentioned on your behave to confirm treament?</label>
            <div class="radio-group">
                <input type="radio" id="permission-yes" name="permission_to_confirm" value="Yes" required><label for="permission-yes">Yes</label>
                <input type="radio" id="permission-no" name="permission_to_confirm" value="No" required><label for="permission-no">No</label>
            </div>

            <label for="expected_treatment_end_date"><em>* </em>What is the expected date of treatment completion?</label>
            <input type="date" id="expected_treatment_end_date" name="expected_treatment_end_date" required placeholder="Your answer">

            <label><em>* </em>What services are you interested in?</label>
            <p> This is not a guarentee of services. Service is dependent upon volunteer availability. </p>
            
            <input type="checkbox" id="meals" name="services[]" value='meals'>
            <label> Meals **Not available in Northern Neck</label> 
            
            <input type="checkbox" id="lawncare" name="services[]" value="lawncare">
            <label> Lawn Care **Not available in Northern Neck</label> 
            
            <input type="checkbox" id="housecleaning" name="services[]" value="housecleaning">
            <label> Professional House Cleaning **Not available in Northern Neck</label>

            <input type="checkbox" id="gascards" name="services[]" name="gascards">
            <label for="gascards"> Gas Cards</label>

            <div><input type="checkbox" id="socialevents" name="socialevents">
            <label for="socialevents"> Social Events</label> </div>

            <div><input type="checkbox" id="houseprojects" name="houseprojects">
            <label for="houseprojects"> House Projects **Not available in Northern Neck</label> 

            <div><input type="checkbox" id="profphotos" name="profphotos">
            <label for="profphotos"> Professional Photography</label> </div>

            <div><input type="checkbox" id="grocerycards" name="grocerycards">
            <label for="grocerycards"> Grocery Cards</label> </div>

            <div><input type="checkbox" id="AAA" name="AAA">
            <label for="AAA"> AAA membership</label> </div>

            <label for="allergies">If you are interested in meals, are there any allergies or dietary restrictions?</label>
            <input type="text" id="allergies" name="allergies" placeholder="Your answer">

            <label for="sibling_info">With our Adopt-A-Family program, volunteers send out monthly cards and gifts. 
            Please provide names and ages of siblings, along with any interests or hobbies your family may have.</label>
            <input type="text" id="sibling_info" name="sibling_info" placeholder="Your answer">

            <label><em>* </em>Do we have permission to share your name and contact information with our service providers and partners?</label>
            <p> We will share your information with lawn service, Moms in Motion (provide assistance with the medicaid waiver which serves as a secondary insurance),
                 cleaners, and YMCA (you receive a membership through them). </p>
            <div class="radio-group">
                <input type="radio" id="permission-yes" name="can_share_contact_info" value="Yes"><label for="permission-yes">Yes</label>
                <input type="radio" id="permission-no" name="can_share_contact_info" value="No" ><label for="permission-no">No</label>
                <!--<p> need to add pop up question if other is selected </p>-->
            </div>

            <label for="family_info">Please tell us about your family.</label>
            <input type="text" id="family_info" name="family_info" placeholder="Your answer">

            <label for="how_did_you_hear">How did you hear about FGP?</label>
            <input type="text" id="how_did_you_hear" name="how_did_you_hear" placeholder="Your answer">

            <label for="agreement"><em>* </em>In consideration of Fairy Godmother Project allowing Volunteer to act as an official “Fairy Godmother Project” volunteer,
            Family voluntarily waives any and all rights to recovery from Fairy Godmother Project, its representatives, agents, officers, directors, shareholders, employees,
            insurers, attorneys, successors and assigns, for all claims, demands, damages, actions, costs, expenses, actions, causes of action, suits at law or in equity for injuries,
            damage, or other loss to person or property that Family and any other individual or entity claiming by or through Family, may sustain while receiving services as part 
            of the Fairy Godmother Project. The parties agree that electronic signatures will be permitted under the provisions of the Virginia Uniform
            Electronic Transactions Act, Va. Code § 59.1-479 et seq.</label>
            <input type="text" id="agreement" name="agreement" required placeholder="Name (First and Last)">
        
        </fieldset>
        
        <input type="submit" name="registration-form" value="Submit">
    </form>
    <?php if ($loggedIn): ?>
        <a class="button cancel" href="index.php" style="margin-top: .5rem">Cancel</a>
    <?php endif ?>
</main>