<?php
session_start();

if (isset($_SESSION["user"])) {
    if (empty($_SESSION["user"]) || $_SESSION['usertype'] != 'p') {
        header("location: ../login.php");
        exit;
    } else {
        $useremail = $_SESSION["users"];
    }
} else {
    header("location: ../login.php");
    exit;
}

// Import Firebase configuration
include("../connection.php");

// Fetch user data from Firebase
$userRef = $database->getReference('patients')->orderByChild('email')->equalTo($useremail);
$userData = $userRef->getValue();

if (!empty($userData)) {
    $userfetch = array_values($userData)[0]; // Get the first matching user
    $userid = $userfetch['id']; // Assuming 'id' is the unique identifier for patients
    $username = $userfetch['name']; // 'name' is where the full name is stored

    if ($_POST) {
        if (isset($_POST["booknow"])) {
            $apponum = $_POST["apponum"];
            $scheduleid = $_POST["scheduleid"];
            $date = $_POST["date"];

            // Create a new appointment in Firebase
            $newAppointment = [
                'pid' => $userid,
                'apponum' => $apponum,
                'scheduleid' => $scheduleid,
                'appodate' => $date
            ];

            // Push the appointment data to Firebase
            $database->getReference('appointments')->push($newAppointment);

            // Redirect after booking
            header("location: appointment.php?action=booking-added&id=" . $apponum . "&titleget=none");
            exit;
        }
    }
} else {
    // Handle case where user data is not found
    header("location: ../login.php"); // Redirect or show an error
    exit;
}
?>
