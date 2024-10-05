<?php
// patients.php

// Learn from w3schools.com

session_start();

if (isset($_SESSION["user"])) {
    if (($_SESSION["user"]) == "" or $_SESSION['usertype'] != 'd') {
        header("location: ../login.php");
    } else {
        $useremail = $_SESSION["user"];
    }
} else {
    header("location: ../login.php");
}

// Import database
include("../connection.php");

$userrow = $database->query("select * from doctor where docemail='$useremail'");
$userfetch = $userrow->fetch_assoc();
$userid = $userfetch["docid"];
$username = $userfetch["docname"];

// Handle POST request
$selecttype = "My";
$current = "My patients Only";
if ($_POST) {
    if (isset($_POST["search"])) {
        $keyword = $_POST["search12"];
        $sqlmain = "select * from patient where pemail='$keyword' or pname='$keyword' or pname like '$keyword%' or pname like '%$keyword' or pname like '%$keyword%' ";
        $selecttype = "my";
    }

    if (isset($_POST["filter"])) {
        if ($_POST["showonly"] == 'all') {
            $sqlmain = "select * from patient";
            $selecttype = "All";
            $current = "All patients";
        } else {
            $sqlmain = "select * from appointment inner join patient on patient.pid=appointment.pid inner join schedule on schedule.scheduleid=appointment.scheduleid where schedule.docid=$userid;";
            $selecttype = "My";
            $current = "My patients Only";
        }
    }
} else {
    $sqlmain = "select * from appointment inner join patient on patient.pid=appointment.pid inner join schedule on schedule.scheduleid=appointment.scheduleid where schedule.docid=$userid;";
    $selecttype = "My";
}

$list11 = $database->query($sqlmain);

// Handle GET request
if ($_GET) {
    $id = $_GET["id"];
    $action = $_GET["action"];
    $sqlmain = "select * from patient where pid='$id'";
    $result = $database->query($sqlmain);
    $row = $result->fetch_assoc();
    $name = $row["pname"];
    $email = $row["pemail"];
    $nic = $row["pnic"];
    $dob = $row["pdob"];
    $tele = $row["ptel"];
    $address = $row["paddress"];
}
?>
