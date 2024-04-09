<?php
    session_cache_expire(30);
    session_start();

    date_default_timezone_set("America/New_York");
    
    if (!isset($_SESSION['access_level']) || $_SESSION['access_level'] < 1) {
        if (isset($_SESSION['change-password'])) {
            header('Location: changePassword.php');
        } else {
            header('Location: login.php');
        }
        die();
    }
        
    include_once('database/dbPersons.php');
    include_once('domain/Person.php');
    // Get date?
    if (isset($_SESSION['_id'])) {
        $person = retrieve_person($_SESSION['_id']);
    }
    $notRoot = $person->get_id() != 'vmsroot';
?>
<!DOCTYPE html>
<html>
    <head>
        <?php require('universal.inc'); ?>
        <title>FGP | Dashboard</title>
    </head>
    <body>
        <?php require('header.php'); ?>
        <h1>Dashboard</h1>
        <main class='dashboard'>
            <?php if (isset($_GET['pcSuccess'])): ?>
                <div class="happy-toast">Password changed successfully!</div>
            <?php elseif (isset($_GET['deleteService'])): ?>
                <div class="happy-toast">Service successfully removed!</div>
            <?php elseif (isset($_GET['serviceAdded'])): ?>
                <div class="happy-toast">Service successfully added!</div>
            <?php elseif (isset($_GET['animalRemoved'])): ?>
                <div class="happy-toast">Animal successfully removed!</div>
            <?php elseif (isset($_GET['locationAdded'])): ?>
                <div class="happy-toast">Location successfully added!</div>
            <?php elseif (isset($_GET['deleteLocation'])): ?>
                <div class="happy-toast">Location successfully removed!</div>
            <?php elseif (isset($_GET['registerSuccess'])): ?>
                <div class="happy-toast">Family registered successfully!</div>
            <?php elseif (isset($_GET['registerAdminSuccess'])): ?>
                <div class="happy-toast">Admin registered successfully!</div>
            <?php elseif (isset($_GET['modifyAdminSuccess'])): ?>
                <div class="happy-toast">Admin modified successfully!</div>
            <?php endif ?>
            <p>Welcome back, <?php echo $person->get_first_name() ?>!</p>
            <p>Today is <?php echo date('l, F j, Y'); ?>.</p>
            <div id="dashboard">
                <?php
                    require_once('database/dbMessages.php');
                    $unreadMessageCount = get_user_unread_count($person->get_id());
                    $inboxIcon = 'inbox.svg';
                    if ($unreadMessageCount) {
                        $inboxIcon = 'inbox-unread.svg';
                    }
                ?>
                <div class="dashboard-item" data-link="inbox.php">
                    <img src="images/<?php echo $inboxIcon ?>">
                    <span>Notifications<?php 
                        if ($unreadMessageCount > 0) {
                            echo ' (' . $unreadMessageCount . ')';
                        }
                    ?></span>
                </div>
                <div class="dashboard-item" data-link="pointsProg.php">
                     <img src="images/create-report.svg">
                    <span>Points Program Form</span>
                </div>
                <div class="dashboard-item" data-link="commCare.php">
                     <img src="images/create-report.svg">
                    <span>Community Care Package Form</span>
                </div>
				<div class="dashboard-item" data-link="addAnimal.php">
                    <img src="images/settings.png">
                    <span>Add Animal</span>
                </div>
                <!-- Commenting out because volunteers won't be searching events
                <div class="dashboard-item" data-link="eventSearch.php">
                    <img src="images/search.svg">
                    <span>Find Event</span>
                </div>
                -->
                <?php if ($_SESSION['access_level'] >= 2): ?>
                <div class="dashboard-item" data-link="personSearch.php">
                    <img src="images/person-search.svg">
                    <span>Find Volunteer</span>
                </div>
                <div class="dashboard-item" data-link="register.php">
                    <img src="images/create-report.svg">
                    <span>Add Volunteer</span>
                </div>
                <div class="dashboard-item" data-link="viewArchived.php">
                    <img src="images/person-search.svg">
                    <span>Archived Animals</span>
                </div>
                <div class="dashboard-item" data-link="report.php">
                    <img src="images/create-report.svg">
                    <span>Create Report</span>
                </div>
                
                <?php endif ?>
                <?php if ($notRoot) : ?>
                    <div class="dashboard-item" data-link="viewProfile.php">
                        <img src="images/view-profile.svg">
                        <span>View Profile</span>
                    </div>
                    <div class="dashboard-item" data-link="editProfile.php">
                        <img src="images/manage-account.svg">
                        <span>Edit Profile</span>
                    </div>
                <?php endif ?>
                <?php if ($notRoot) : ?>
                    <div class="dashboard-item" data-link="volunteerReport.php">
                        <img src="images/volunteer-history.svg">
                        <span>View My Hours</span>
                    </div>
                <?php endif ?>
                <div class="dashboard-item" data-link="changePassword.php">
                    <img src="images/change-password.svg">
                    <span>Change Password</span>
                </div>
                <div class="dashboard-item" data-link="giftCardManagement.php">
                    <img src="images/giftcard.svg">
                    <span>Gift Card Management</span>
                </div>

                <div class="dashboard-item" data-link="approve.php">
                    <img src="images/settings.png">
                    <span>Approve Acounts</span>
                </div>
                <div class="dashboard-item" data-link="addAdmin.php">
                    <img src="images/settings.png">
                    <span>Add Admin</span>
                </div>
                <div class="dashboard-item" data-link="viewAdmin.php">
                    <img src="images/settings.png">
                    <span>View Admin</span>
                </div>
                <div class="dashboard-item" data-link="viewFamilyAccounts.php">
                    <img src="images/person-search.svg">
                    <span>View Family Accounts</span>
                </div>
                <div class="dashboard-item" data-link="logout.php">
                    <img src="images/logout.svg">
                    <span>Log out</span>
                </div>
            </div>
        </main>
    </body>
</html>