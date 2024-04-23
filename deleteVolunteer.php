<?php
function connect() {
    $host = "localhost"; 
    $database = "fgp";
    $user = "fgp";
    $pass = "fgp";
    if ($_SERVER['SERVER_NAME'] == 'jenniferp122.sg-host.com') {
        $user = 'uc1op8sb8zdqp';
        $database = 'dbjyzu1z500h5e';
        $pass = "7f8r0d57ltxn";
    } else if ($_SERVER['SERVER_NAME'] == 'gwynethsgiftvms.org') {
        $user = "uybhc603shfl5";
        $pass = "f11kwvhy4yic";
        $database = "dbwgyuabseaoih";
    }
    $con = mysqli_connect($host,$user,$pass,$database);
    if (!$con) { echo "not connected to server"; return mysqli_error($con);}
    $selected = mysqli_select_db($con,$database);
    if (!$selected) { echo "database not selected"; return mysqli_error($con); }
    else return $con;
    
}


$con = connect();
if(isset($_GET['volunteerID'])){
    $id =$_GET['volunteerID'];

    $query ="delete from dbvolunteer where volunteerID=$id";
    $result =mysqli_query($con, $query);
    if($result){
        header('location:viewVolunteer.php');
    }else{
        die(mysqli_error($con));

    }

}

?>
