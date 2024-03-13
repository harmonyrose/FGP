<?php
// connect database

// Query to fetch all families from the database
$query = "SELECT * FROM dbpersons ORDER BY username";
$result = mysqli_query($connection, $query);

if (!$result) {
    die("Database query failed.");
}

// Display families and their status
echo "<table>";
echo "<tr><th>Family Name</th><th>Status</th><th>Action</th></tr>";

while ($row = mysqli_fetch_assoc($result)) {
    echo "<tr>";
    echo "<td>" . $row['family_name'] . "</td>";
    echo "<td>";
    
    // Assuming an approved column in the database indicating the status
    if ($row['approved'] == 1) {
        echo "Approved";
    } elseif ($row['approved'] == 0) {
        echo "Pending Approval";
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


mysqli_close($connection);
?>
