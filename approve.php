<?php
// Start session
session_start();

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
$username = $_SESSION['username']; // have a session variable for the username
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
echo "<table>";
echo "<tr><th>Family</th><th>Status</th><th>Action</th></tr>";

while ($row = mysqli_fetch_assoc($result)) {
    echo "<tr>";
    echo "<td>" . $row['last_name'] . "</td>";
    echo "<td>";
    
    // approved column in the database indicating the status
    if ($row['approved'] == 1) {
        echo "Approved";
    } elseif ($row['approved'] == 0) {
        echo "Pending Approval";
        // Notify the admin/superadmin
        // implement the notification logic
        if ($userType == "admin" || $userType == "superadmin") {
            $_SESSION['notification'] = "A new family is pending approval.";
        }
    } else {
        echo "Rejected";
    }
    
    echo "</td>";
    echo "<td>";
    
    // Display approval and rejection buttons
    echo "<button onclick=\"handleApproval(" . $row['id'] . ")\">Approve</button>";
    echo "<button onclick=\"handleRejection(" . $row['id'] . ")\">Reject</button>";
    
    echo "</td>";
    echo "</tr>";
}

echo "</table>";

// Close the database connection
mysqli_close($connection);

require_once('universal.inc');
?>
