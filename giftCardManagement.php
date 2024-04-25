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
        <title>FGP | Gift Card Management</title>
    </head>
    <body>
        <?php require('header.php'); ?>
        <h1>Gift Card Management</h1>
        <main class='dashboard'>
            <!-- <?php if (isset($_GET['pcSuccess'])): ?>
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
                <div class="happy-toast">Volunteer registered successfully!</div>
            <?php endif ?> -->
            <div id="dashboard">
                <div class="dashboard-item" data-link="listVendors.php">
                    <img src="images/list.svg">
                    <span>List Vendors</span>
                </div>
                <div class="dashboard-item" data-link="giftCardOrderReport.php">
                     <img src="images/create-report.svg">
                    <span>Generate Gift Card Order Report</span>
                </div>
                <div class="dashboard-item" data-link="giftCardSignOffTable.php">
                     <img src="images/create-report.svg">
                    <span>Generate Gift Card Sign-off Form</span>
                </div>
                <div class="dashboard-item" data-link="index.php">
                    <img src="images/dashboard.svg">
                    <span>Dashboard</span>
                </div>
            </div>
        </main>
    </body>
</html>