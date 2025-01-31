
<!-- Reused Josh code from vendors it was too clean-->


<!DOCTYPE html>
<html>
    <head>
        <?php require_once('universal.inc') ?>
        <title>FGP | Add Volunteer</title>
    </head>
    <body>
        <?php require_once('header.php') ?>
        <h1>Add Volunteer</h1>
        <!-- Legacy thing i dont wanna mess with -->
        <main class="date"> 
            <!-- Form -->
            <h2>New Volunteer Form</h2>
            <form id="new-volunteer-form" method="post">
                
                <label for="name">Volunteer First Name </label>
                <input type="text" id="first-name" name="first-name" required placeholder="Enter volunteers' first name">
                <label for="name">Volunteer Last Name </label>
                <input type="text" id="last-name" name="last-name" required placeholder="Enter volunteers' last name"> 
                <label for="name">Volunteer Email </label>
                <input type="text" id="email" name="email" required placeholder="Enter volunteers' email"> 
                <label for="name">Volunteer Address </label>
                <input type="text" id="addres" name="addres" required placeholder="Enter volunteers' address"> 
                <label for="name">Volunteer Phone Number </label>
                <input type="text" id="phoneNumber" name="phoneNumber" required placeholder="Enter volunteers' phone number"> 
                <p></p>
                <input type="submit" value="Add new volunteer">
                <br></br>
            <a class="button cancel" href="viewVolunteer.php" style="background-color: red">View and delete volunteer</a>
            <br></br>
            <a class="button cancel" href="index.php" style="margin-top: -.5rem">Return to main menu</a>
        </main>
    </body>
</html>