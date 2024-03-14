<?php
// Start session
session_start();

require_once('header.php');
// Connect to the database
$hostname = "localhost"; 
$database = "fgp";
$username = "fgp";
$password = "fgp";

$connection = mysqli_connect($hostname, $username, $password, $database);

// Check if the connection was successful
if (!$connection) {
    die("Connection failed: " . mysqli_connect_error());
}

// Query to fetch the logged-in user's type from the dbpersons table
// Assuming the user's username is stored in a session variable
/*$username = $_SESSION['username'];*/ // have a session variable for the username
$query = "SELECT type FROM dbpersons WHERE username = '$username'";
$result = mysqli_query($connection, $query);

if (!$result) {
    die("Database query failed: " . mysqli_error($connection));
}

// Fetch the user's type
$row = mysqli_fetch_assoc($result);
$userType = $row['type'];

// Query to fetch all families from the database
$query = "SELECT * FROM dbPersons  WHERE type = 'family' AND status='inactive' ORDER BY last_name";
$result = mysqli_query($connection, $query);

if (!$result) {
    die("Database query failed.");
}

// Display families and their status
?>

<table style="margin: auto; border-collapse: collapse;">
    <tr>
        <th style="text-align: center; padding: 10px;">Family</th>
        <th style="text-align: center; padding: 10px;">Status</th>
        <th style="text-align: center; padding: 10px;">Action</th>
    </tr>

<?php
while ($row = mysqli_fetch_assoc($result)) {
    ?>
    <tr>
        <td style="text-align: center; padding: 10px;"><?php echo $row['last_name']; ?></td>
        <td style="text-align: center; padding: 10px;">
    <?php
    // approved column in the database indicating the status
    if ($row['status'] == 1) {
        echo "Approved";
    } elseif ($row['status'] == 0) {
        echo "Pending Approval";
        // Notify the admin/superadmin
        // implement the notification logic
        if ($userType == "admin" || $userType == "superadmin") {
            $_SESSION['notification'] = "A new family is pending approval.";
        }
    } else {
        echo "Pending";
    }
    ?>
        </td>
        <td style="text-align: center; padding: 10px;">
            <button onclick="handleApproval(<?php echo $row['id']; ?>)">Approve</button>
            <button onclick="handleRejection(<?php echo $row['id']; ?>)">Reject</button>
        </td>
    </tr>
    <?php
}
?>

</table>

<?php
// Close the database connection
mysqli_close($connection);

require_once('universal.inc');
?>
