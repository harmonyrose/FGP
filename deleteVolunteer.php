<?php
include_once('database/dbinfo.php');

$con = connect();
if(isset($_GET['volunteerID'])){
    $id =$_GET['volunteerID'];

    $query ='DELETE from dbVolunteer WHERE volunteerID='.$id;
    $result =mysqli_query($con, $query);
    if($result){
        header('location:viewVolunteer.php');
    }else{
        die(mysqli_error($con));

    }

}

?>
