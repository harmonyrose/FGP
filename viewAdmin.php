<?php
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

// Function to delete an admin
function delete_admin($id) {
    global $connection; // Access the global $connection variable
    $query = "DELETE FROM dbPersons WHERE id = '$id' AND type = 'admin'";
    $result = mysqli_query($connection, $query);
    if (!$result) {
        die("Delete failed: " . mysqli_error($connection));
    }
}

// Query to fetch all admins from the database
$query = "SELECT * FROM dbPersons WHERE type = 'admin'";
$result = mysqli_query($connection, $query);

if (!$result) {
    die("Database query failed.");
}

// Display admins and their details
?>

<table style="margin: auto; border-collapse: collapse;">
    <tr>
        <th style="text-align: center; padding: 10px;">Admin</th>
        <th style="text-align: center; padding: 10px;">Email</th>
        <th style="text-align: center; padding: 10px;">Action</th>
    </tr>

<?php
while ($row = mysqli_fetch_assoc($result)) {
    ?>
    <tr>
        <td style="text-align: center; padding: 10px;"><?php echo $row['last_name']; ?></td>
        <td style="text-align: center; padding: 10px;"><?php echo $row['email']; ?></td>
        <td style="text-align: center; padding: 10px;">
            <form method="post">
                <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                <button type="submit" name="delete" style="background-color: red; color: white;">Delete</button>
            </form>
        </td>
    </tr>
    <?php
}
?>

</table>

<?php
// Handle delete action
if (isset($_POST['delete'])) {
    $id = $_POST['id']; // Admin ID
    delete_admin($id);
}
?>

<?php
// Close the database connection
mysqli_close($connection);
require_once('universal.inc');
?>
