<?php

    session_start();

    if(isset($_SESSION["user"])){
        if(($_SESSION["user"])=="" or $_SESSION['usertype']!='a'){
            header("location: ../login.php");
        }

    }else{
        header("location: ../login.php");
    }
    
    include("../connection.php");

    if ($_GET) {
        $id = $_GET["id"];
    
        // Reference to the doctor to delete
        $doctorReference = $database->getReference('doctors/' . $id);
    
        // Delete the doctor record
        $doctorReference->remove();
    
        // Redirect back to the doctors page with a success message
        header("location: doctors.php?action=delete&status=success");
        exit();
    }


?>