<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/animations.css">  
    <link rel="stylesheet" href="../css/main.css">  
    <link rel="stylesheet" href="../css/admin.css">
    <link rel="stylesheet" href="../css/hamburger.css">
        
    <title>Doctor</title>
    <style>
        .popup{
            animation: transitionIn-Y-bottom 0.5s;
        }
</style>
</head>
<body>
<?php

session_start();

if (isset($_SESSION["user"])) {
    if ($_SESSION["user"] == "" || $_SESSION['usertype'] != 'a') {
        header("location: ../login.php");
    }
} else {
    header("location: ../login.php");
}

// Import database
include("../connection.php");

if ($_POST) {
    // Extract POST data
    $name = $_POST['name'];
    $nic = $_POST['nic'];
    $spec = $_POST['spec'];
    $email = $_POST['email'];
    $tele = $_POST['Tele'];
    $password = $_POST['password'];
    $cpassword = $_POST['cpassword'];

    if ($password == $cpassword) {
        $error = '3';
        $result = $database->query("SELECT * FROM webuser WHERE email='$email';");
        if ($result->num_rows == 1) {
            $error = '1'; // Email already exists
        } else {
            // Hash the password
            $hashed_password = password_hash($password, PASSWORD_BCRYPT);

            // Prepare SQL queries
            $sql1 = "INSERT INTO doctor (docemail, docname, docpassword, docnic, doctel, specialties) VALUES ('$email', '$name', '$hashed_password', '$nic', '$tele', '$spec');";
            $sql2 = "INSERT INTO webuser (email, usertype) VALUES ('$email', 'd');";
            
            // Execute SQL queries
            $database->query($sql1);
            $database->query($sql2);

            $error = '4'; // Success
        }
    } else {
        $error = '2'; // Passwords do not match
    }
} else {
    $error = '3'; // No POST data
}

header("location: doctors.php?action=add&error=" . $error);
?>

   

</body>
</html>