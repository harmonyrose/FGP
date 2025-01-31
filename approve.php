<?php
//page for admins to approve families for assistance

// Start session
session_start();

require_once('header.php');
require_once('database/dbPersons.php');

// Check if the "Approve" or "Reject" button is clicked
if (isset($_POST['approve'])) {
    $id = $_POST['id']; // Family ID
    update_status($id, 'Active'); // Set status to 'Active'
    //header("Location: familyServiceDoc.php?id=$id"); // Redirect to familyServiceDoc.php after approving
    echo "<script>document.location = 'familyServiceDoc.php?id=".$id."';</script>";
    exit; // Stop further execution
} elseif (isset($_POST['reject'])) {
    $id = $_POST['id']; // Family ID
    update_status($id, 'Rejected'); // Set status to 'Rejected'
}
// Query to fetch all families from the database
$con=connect();
$query = "SELECT * FROM dbPersons WHERE type = 'family' AND status = 'pending' ORDER BY last_name";
$result = mysqli_query($con, $query);

if (!$result) {
    die("Database query failed.");
}

// Display families and their status
?>

<table style="margin: auto; border-collapse: collapse;">
    <tr>
        <th style="text-align: center; padding: 10px;">Family</th>
        <th style="text-align: center; padding: 10px;">Email</th>
        <th style="text-align: center; padding: 10px;">Status</th>
        <th style="text-align: center; padding: 10px;">Action</th>
    </tr>

<?php
while ($row = mysqli_fetch_assoc($result)) {
    ?>
    <tr>
        <td style="text-align: center; padding: 10px;"><?php echo "<a href=\"familyInfo.php?id=" . $row['id'] ."\">"?><?php echo $row['last_name']; ?></td>
        <td style="text-align: center; padding: 10px;"><?php echo $row['email']; ?></td>
        <td style="text-align: center; padding: 10px;">
    <?php
    // Display status
    if ($row['status'] == 'Active') {
        echo "Approved";
    } elseif ($row['status'] == 'pending' or $row['status'] == 'Pending') {
        echo "Pending Approval";
    }
    ?>
        </td>
        <td style="text-align: center; padding: 10px;">
            <form method="post">
                <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                <button type="submit" name="approve" style="background-color: green; color: white;">Approve</button>
                <button type="submit" name="reject" style="background-color: red; color: white;">Reject</button>
            </form>
        </td>
    </tr>
    <?php
}
?>

</table>

<?php
// Close the database connection
require_once('universal.inc');
?>
