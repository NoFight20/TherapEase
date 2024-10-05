<?php

    session_start();

    if(isset($_SESSION["user"])){
        if(($_SESSION["user"])=="" or $_SESSION['usertype']!='a'){
            header("location: ../login.php");
        }

    }else{
        header("location: ../login.php");
    }

    //import database
    include("../connection.php");

?>

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
        
    <title>Appointments</title>
    <style>
        .popup{
            animation: transitionIn-Y-bottom 0.5s;
        }
        .sub-table{
            animation: transitionIn-Y-bottom 0.5s;
        }
		.popup .close {
            font-size: 30px;
            color: #000;
            text-decoration: none;
        }
</style>

</head>
<body>
    <!-- Mobile Header -->
    <div class="mobile-header">Appointment</div>

    <!-- Hamburger Menu -->
    <div id="hamburger-menu">
        <div class="bar"></div>
        <div class="bar"></div>
        <div class="bar"></div>
    </div>
    <div class="container">
        <div class="menu">
            <table class="menu-container" border="0">
                <tr>
                    <td style="padding:10px" colspan="2">
                        <table border="0" class="profile-container">
                            <tr>
                                <td width="30%" style="padding-left:20px" >
                                    <img src="../img/user.png" alt="" width="100%" style="border-radius:50%">
                                </td>
                                <td style="padding:0px;margin:0px;">
                                    <p class="profile-title">Administrator</p>
                                    <p class="profile-subtitle">admin@edoc.com</p>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2">
                                    <a href="../logout.php" ><input type="button" value="Log out" class="logout-btn btn-primary-soft btn"></a>
                                </td>
                            </tr>
                    </table>
                    </td>
                
                </tr>
                <tr class="menu-row" >
                    <td class="menu-btn ">
                        <a href="index.php" class="non-style-link-menu"><div><p class="menu-text">Dashboard</p></a></div></a>
                    </td>
                </tr>
                <tr class="menu-row">
                    <td class="menu-btn">
                        <a href="doctors.php" class="non-style-link-menu "><div><p class="menu-text">Doctors</p></a></div>
                    </td>
                </tr>
                <tr class="menu-row" >
                    <td class="menu-btn">
                        <a href="schedule.php" class="non-style-link-menu"><div><p class="menu-text">Schedule</p></div></a>
                    </td>
                </tr>
                <tr class="menu-row">
                    <td class="menu-btn menu-active">
                        <a href="appointment.php" class="non-style-link-menu non-style-link-menu-active"><div><p class="menu-text">Appointment</p></a></div>
                    </td>
                </tr>
                <tr class="menu-row" >
                    <td class="menu-btn">
                        <a href="patient.php" class="non-style-link-menu"><div><p class="menu-text">Clients</p></a></div>
                    </td>
                </tr>
				<tr class="menu-row" >
                    <td class="menu-btn">
                        <a href="archive.php" class="non-style-link-menu"><div><p class="menu-text">Archives</p></a></div>
					</td>
                </tr>
				<tr class="menu-row" >
                    <td class="menu-btn">
                        <a href="summary.php" class="non-style-link-menu"><div><p class="menu-text">Summary</p></a></div>
					</td>
                </tr>  
            </table>
        </div>
        <div class="dash-body">
            <table border="0" width="100%" style=" border-spacing: 0;margin:0;padding:0;margin-top:25px; ">
                <tr >
                    <td width="13%" >
                    <a href="appointment.php" ><button  class="login-btn btn-primary-soft btn btn-icon-back"  style="padding-top:11px;padding-bottom:11px;margin-left:20px;width:125px"><font class="tn-in-text">Back</font></button></a>
                    </td>
                    <td>
                        <p style="font-size: 23px;padding-left:12px;font-weight: 600;">Appointment Manager</p>
                                           
                    </td>
                    <td width="15%">
                        <p style="font-size: 14px;color: rgb(119, 119, 119);padding: 0;margin: 0;text-align: right;">
                            Today's Date
                        </p>
                        <p class="heading-sub12" style="padding: 0;margin: 0;">
                            <?php 
                                // Set the timezone
                                date_default_timezone_set('Asia/Kolkata');

                                // Get the current date
                                $today = date('Y-m-d');
                                echo $today;
                                $list110 = $database->getReference('appointments')->getValue();

                                // Check if there are any appointments and loop through them
                                if ($list110) {
                                    foreach ($list110 as $appointmentId => $appointmentData) {
                                        // You can access appointment fields like this:
                                        $patientName = $appointmentData['patientName'];
                                        $appointmentDate = $appointmentData['date'];  // Assuming you have a 'date' field

                                        // Do something with each appointment
                                        echo "Appointment ID: " . $appointmentId . "<br>";
                                        echo "Patient Name: " . $patientName . "<br>";
                                        echo "Appointment Date: " . $appointmentDate . "<br>";
                                        echo "<hr>";
                                    }
                                }
                            ?>
                        </p>
                    </td>
                    <td width="10%">
                        <button  class="btn-label"  style="display: flex;justify-content: center;align-items: center;"><img src="../img/calendar.svg" width="100%"></button>
                    </td>


                </tr>
               
                <!-- <tr>
                    <td colspan="4" >
                        <div style="display: flex;margin-top: 40px;">
                        <div class="heading-main12" style="margin-left: 45px;font-size:20px;color:rgb(49, 49, 49);margin-top: 5px;">Schedule a Session</div>
                        <a href="?action=add-session&id=none&error=0" class="non-style-link"><button  class="login-btn btn-primary btn button-icon"  style="margin-left:25px;background-image: url('../img/icons/add.svg');">Add a Session</font></button>
                        </a>
                        </div>
                    </td>
                </tr> -->
                <tr>
                    <td colspan="4" style="padding-top:10px;width: 100%;" >
                    
                        <p class="heading-main12" style="margin-left: 45px;font-size:18px;color:rgb(49, 49, 49)">All Appointments 
                            (<?php 
                                // Fetch all appointments from Firebase
                                $list110 = $database->getReference('appointments')->getValue();

                                // Check if there are any appointments
                                if ($list110) {
                                    // Count the number of appointments
                                    $appointmentCount = count($list110);
                                    echo "Total number of appointments: " . $appointmentCount . "<br><br>";

                                    // Loop through each appointment and display data
                                    foreach ($list110 as $appointmentId => $appointmentData) {
                                        $patientName = $appointmentData['patientName']; // Assuming the field exists
                                        $appointmentDate = $appointmentData['date']; // Assuming the field exists
                                    }
                                } else {
                                    echo "0";
                                }
                            ?>)
                            </p>
                    </td>
                    
                </tr>
                <tr>
                    <td colspan="4" style="padding-top:0px;width: 100%;" >
                        <center>
                        <table class="filter-container" border="0" >
                        <tr>
                           <td width="10%">

                           </td> 
                        <td width="5%" style="text-align: center;">
                        Date:
                        </td>
                        <td width="30%">
                        <form action="" method="post">
                            
                            <input type="date" name="sheduledate" id="date" class="input-text filter-container-items" style="margin: 0;width: 95%;">

                        </td>
                        <td width="5%" style="text-align: center;">
                        Doctor:
                        </td>
                        <td width="30%">
                        <select name="docid" id="" class="box filter-container-items" style="width:90% ;height: 37px;margin: 0;" >
                            <option value="" disabled selected hidden>Choose Doctor Name from the list</option><br/>
                                
                            <?php 
                             
                               
                                // Fetch all doctors from Firebase
                                $doctors = $database->getReference('doctors')->getValue();

                                // Check if there are doctors available
                                if ($doctors) {
                                    // Sort the doctors array by 'docname' (ascending order)
                                    usort($doctors, function($a, $b) {
                                        return strcmp($a['docname'], $b['docname']);
                                    });

                                    // Loop through the doctors array and generate options for the dropdown
                                    foreach ($doctors as $doctorId => $doctorData) {
                                        $docName = $doctorData['docname'];  // Assuming 'docname' field exists
                                        $docId = $doctorData['docid'];      // Assuming 'docid' field exists

                                        // Echo the option tag for the dropdown
                                        echo "<option value='" . $docId . "'>" . $docName . "</option><br/>";
                                    }
                                } else {
                                    echo "<option disabled>No doctors available</option>";
                                }


                                ?>

                        </select>
                    </td>
                    <td width="12%">
                        <input type="submit"  name="filter" value=" Filter" class=" btn-primary-soft btn button-icon btn-filter"  style="padding: 15px; margin :0;width:100%">
                        </form>
                    </td>

                    </tr>
                            </table>

                        </center>
                    </td>
                    
                </tr>
                
                <?php
                  if ($_POST) {
                    // Filters for scheduled date and doctor ID
                    $sheduledate = !empty($_POST["sheduledate"]) ? $_POST["sheduledate"] : null;
                    $docid = !empty($_POST["docid"]) ? $_POST["docid"] : null;
                
                    // Fetch all appointments and related data from Firebase
                    $appointments = $database->getReference('appointments')->getValue() ?? []; // Use ?? to default to an empty array
                    $schedules = $database->getReference('schedules')->getValue() ?? [];       // Use ?? to default to an empty array
                    $patients = $database->getReference('patients')->getValue() ?? [];         // Use ?? to default to an empty array
                    $doctors = $database->getReference('doctors')->getValue() ?? [];           // Use ?? to default to an empty array
                
                    $filteredAppointments = [];
                
                    // Loop through appointments and apply filters
                    foreach ($appointments as $appointmentId => $appointmentData) {
                        $scheduleId = $appointmentData['scheduleid'] ?? null; // Use ?? to avoid undefined index errors
                        $patientId = $appointmentData['pid'] ?? null;         // Use ?? to avoid undefined index errors
                
                        // Fetch corresponding schedule, patient, and doctor data
                        $schedule = $schedules[$scheduleId] ?? null; // Use ?? to avoid null errors
                        $patient = $patients[$patientId] ?? null;     // Use ?? to avoid null errors
                        $doctor = $schedule ? ($doctors[$schedule['docid']] ?? null) : null; // Fetch doctor only if schedule is valid
                
                        // Apply filters (schedule date and doctor ID)
                        if ($sheduledate && $schedule['scheduledate'] != $sheduledate) {
                            continue;
                        }
                
                        if ($docid && $doctor['docid'] != $docid) {
                            continue;
                        }
                
                        // Add to filtered appointments if it matches the filters
                        if ($schedule && $patient && $doctor) { // Ensure all required data is available
                            $filteredAppointments[] = [
                                'appoid' => $appointmentId,
                                'scheduleid' => $scheduleId,
                                'title' => $schedule['title'],
                                'docname' => $doctor['docname'],
                                'pname' => $patient['pname'],
                                'scheduledate' => $schedule['scheduledate'],
                                'scheduletime' => $schedule['scheduletime'],
                                'apponum' => $appointmentData['apponum'],
                                'appodate' => $appointmentData['appodate']
                            ];
                        }
                    }
                } else {
                    // If no filters are applied, fetch all appointments with related data
                    $appointments = $database->getReference('appointments')->getValue() ?? [];
                    $schedules = $database->getReference('schedules')->getValue() ?? [];
                    $patients = $database->getReference('patients')->getValue() ?? [];
                    $doctors = $database->getReference('doctors')->getValue() ?? [];
                
                    $filteredAppointments = [];
                
                    foreach ($appointments as $appointmentId => $appointmentData) {
                        $scheduleId = $appointmentData['scheduleid'] ?? null;
                        $patientId = $appointmentData['pid'] ?? null;
                
                        // Fetch corresponding schedule, patient, and doctor data
                        $schedule = $schedules[$scheduleId] ?? null;
                        $patient = $patients[$patientId] ?? null;
                        $doctor = $schedule ? ($doctors[$schedule['docid']] ?? null) : null;
                
                        // Collect all appointments if all required data is available
                        if ($schedule && $patient && $doctor) {
                            $filteredAppointments[] = [
                                'appoid' => $appointmentId,
                                'scheduleid' => $scheduleId,
                                'title' => $schedule['title'],
                                'docname' => $doctor['docname'],
                                'pname' => $patient['pname'],
                                'scheduledate' => $schedule['scheduledate'],
                                'scheduletime' => $schedule['scheduletime'],
                                'apponum' => $appointmentData['apponum'],
                                'appodate' => $appointmentData['appodate']
                            ];
                        }
                    }
                }
                ?>
                  
                <tr>
                   <td colspan="4">
                       <center>
                        <div class="abc scroll">
                        <table width="93%" class="sub-table scrolldown" border="0">
                        <thead>
                        <tr>
                                <th class="table-headin">
                                    Patient name
                                </th>
                                <th class="table-headin">
                                    
                                    Appointment number
                                    
                                </th>
                               
                                
                                <th class="table-headin">
                                    Doctor
                                </th>
                                <th class="table-headin">
                                    
                                
                                    Session Title
                                    
                                    </th>
                                
                                <th class="table-headin" style="font-size:10px">
                                    
                                    Session Date & Time
                                    
                                </th>
                                
                                <th class="table-headin">
                                    
                                    Appointment Date
                                    
                                </th>
                                
                                <th class="table-headin">
                                    
                                    Events
                                    
                                </tr>
                        </thead>
                        <tbody>
                        
                            <?php
                                // Initialize variables to empty arrays to avoid null errors
                                $appointments = $database->getReference('appointments')->getValue() ?? [];
                                $schedules = $database->getReference('schedules')->getValue() ?? [];
                                $patients = $database->getReference('patients')->getValue() ?? [];
                                $doctors = $database->getReference('doctors')->getValue() ?? [];

                                if ($_POST) {
                                    // Filters for scheduled date and doctor ID
                                    $sheduledate = !empty($_POST["sheduledate"]) ? $_POST["sheduledate"] : null;
                                    $docid = !empty($_POST["docid"]) ? $_POST["docid"] : null;

                                    $filteredAppointments = [];

                                    // Loop through appointments and apply filters
                                    foreach ($appointments as $appointmentId => $appointmentData) {
                                        $scheduleId = $appointmentData['scheduleid'];
                                        $patientId = $appointmentData['pid'];

                                        // Fetch corresponding schedule, patient, and doctor data
                                        $schedule = $schedules[$scheduleId] ?? null; // Using null coalescing to avoid errors
                                        $patient = $patients[$patientId] ?? null; // Using null coalescing to avoid errors
                                        $doctor = $doctors[$schedule['docid']] ?? null; // Using null coalescing to avoid errors

                                        // Check if the fetched data exists before proceeding
                                        if ($schedule && $patient && $doctor) {
                                            // Apply filters
                                            if ($sheduledate && $schedule['scheduledate'] != $sheduledate) {
                                                continue;
                                            }

                                            if ($docid && $doctor['docid'] != $docid) {
                                                continue;
                                            }

                                            // Add to filtered appointments if it matches the filters
                                            $filteredAppointments[] = [
                                                'appoid' => $appointmentId,
                                                'scheduleid' => $scheduleId,
                                                'title' => $schedule['title'],
                                                'docname' => $doctor['docname'],
                                                'pname' => $patient['pname'],
                                                'scheduledate' => $schedule['scheduledate'],
                                                'scheduletime' => $schedule['scheduletime'],
                                                'apponum' => $appointmentData['apponum'],
                                                'appodate' => $appointmentData['appodate']
                                            ];
                                        }
                                    }
                                } else {
                                    // Fetch all appointments if no filters are applied
                                    $filteredAppointments = [];

                                    foreach ($appointments as $appointmentId => $appointmentData) {
                                        $scheduleId = $appointmentData['scheduleid'];
                                        $patientId = $appointmentData['pid'];

                                        // Fetch corresponding schedule, patient, and doctor data
                                        $schedule = $schedules[$scheduleId] ?? null; // Using null coalescing to avoid errors
                                        $patient = $patients[$patientId] ?? null; // Using null coalescing to avoid errors
                                        $doctor = $doctors[$schedule['docid']] ?? null; // Using null coalescing to avoid errors

                                        // Check if the fetched data exists before proceeding
                                        if ($schedule && $patient && $doctor) {
                                            // Collect all appointments
                                            $filteredAppointments[] = [
                                                'appoid' => $appointmentId,
                                                'scheduleid' => $scheduleId,
                                                'title' => $schedule['title'],
                                                'docname' => $doctor['docname'],
                                                'pname' => $patient['pname'],
                                                'scheduledate' => $schedule['scheduledate'],
                                                'scheduletime' => $schedule['scheduletime'],
                                                'apponum' => $appointmentData['apponum'],
                                                'appodate' => $appointmentData['appodate']
                                            ];
                                        }
                                    }
                                }

                                // Display filtered appointments or a "not found" message
                                if (count($filteredAppointments) == 0) {
                                    echo '<tr>
                                        <td colspan="7">
                                        <br><br><br><br>
                                        <center>
                                        <img src="../img/notfound.svg" width="25%">
                                        <br>
                                        <p class="heading-main12" style="margin-left: 45px;font-size:20px;color:rgb(49, 49, 49)">We couldn\'t find anything related to your keywords!</p>
                                        <a class="non-style-link" href="appointment.php"><button class="login-btn btn-primary-soft btn" style="display: flex;justify-content: center;align-items: center;margin-left:20px;">&nbsp; Show all Appointments &nbsp;</button></a>
                                        </center>
                                        <br><br><br><br>
                                        </td>
                                        </tr>';
                                } else {
                                    foreach ($filteredAppointments as $appointment) {
                                        echo '<tr>
                                            <td style="font-weight:600;"> &nbsp;' . substr($appointment['pname'], 0, 25) . '</td>
                                            <td style="text-align:center;font-size:23px;font-weight:500; color: var(--btnnicetext);">' . $appointment['apponum'] . '</td>
                                            <td>' . substr($appointment['docname'], 0, 25) . '</td>
                                            <td>' . substr($appointment['title'], 0, 15) . '</td>
                                            <td style="text-align:center;font-size:12px;">' . substr($appointment['scheduledate'], 0, 10) . '<br>' . substr($appointment['scheduletime'], 0, 5) . '</td>
                                            <td style="text-align:center;">' . $appointment['appodate'] . '</td>
                                            <td>
                                                <div style="display:flex;justify-content: center;">
                                                <a href="?action=drop&id=' . $appointment['appoid'] . '&name=' . $appointment['pname'] . '&session=' . $appointment['title'] . '&apponum=' . $appointment['apponum'] . '" class="non-style-link"><button class="btn-primary-soft btn button-icon btn-delete" style="padding-left: 40px;padding-top: 12px;padding-bottom: 12px;margin-top: 10px;"><font class="tn-in-text">Cancel</font></button></a>
                                                &nbsp;&nbsp;&nbsp;
                                                </div>
                                            </td>
                                        </tr>';
                                    }
                                }
                                 
                            ?>
 
                            </tbody>

                        </table>
                        </div>
                        </center>
                   </td> 
                </tr>
                       
                        
                        
            </table>
        </div>
    </div>
    <?php
    // session.php - Main logic
if ($_GET) {
    $id = $_GET["id"];
    $action = $_GET["action"];

    if ($action == 'add-session') {
        echo '
        <div id="popup1" class="overlay">
            <div class="popup">
                <center>
                    <a class="close" href="schedule.php">&times;</a>
                    <div style="display: flex; justify-content: center;">
                        <div class="abc">
                            <table width="80%" class="sub-table scrolldown add-doc-form-container" border="0">
                                <tr>
                                    <td>
                                        <p style="padding: 0; margin: 0; text-align: left; font-size: 25px; font-weight: 500;">Add New Session.</p><br>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="label-td" colspan="2">
                                        <form action="schedule.php?id=' . $id . '&action=submit-session" method="POST" class="add-new-form">
                                            <label for="title" class="form-label">Session Title : </label>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="label-td" colspan="2">
                                        <input type="text" name="title" class="input-text" placeholder="Name of this Session" required><br>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="label-td" colspan="2">
                                        <label for="docid" class="form-label">Select Doctor: </label>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="label-td" colspan="2">
                                        <select name="docid" class="box">
                                            <option value="" disabled selected hidden>Choose Doctor Name from the list</option><br/>';
        // Replace with your actual doctor fetching logic
        $list11 = $database->getReference('doctors')->getValue(); // Fetch from Firebase

        foreach ($list11 as $doc) {
            echo "<option value='" . $doc['docid'] . "'>" . $doc['docname'] . "</option>";
        }
        echo '
                                        </select><br><br>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="label-td" colspan="2">
                                        <label for="nop" class="form-label">Number of Patients/Appointment Numbers : </label>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="label-td" colspan="2">
                                        <input type="number" name="nop" class="input-text" min="0" placeholder="The final appointment number for this session depends on this number" required><br>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="label-td" colspan="2">
                                        <label for="date" class="form-label">Session Date: </label>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="label-td" colspan="2">
                                        <input type="date" name="date" class="input-text" min="' . date('Y-m-d') . '" required><br>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="label-td" colspan="2">
                                        <label for="time" class="form-label">Schedule Time: </label>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="label-td" colspan="2">
                                        <input type="time" name="time" class="input-text" placeholder="Time" required><br>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="2">
                                        <input type="reset" value="Reset" class="login-btn btn-primary-soft btn">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                        <input type="submit" value="Place this Session" class="login-btn btn-primary btn" name="shedulesubmit">
                                    </td>
                                </tr>
                                </form>
                            </table>
                        </div>
                    </div>
                </center>
                <br><br>
            </div>
        </div>';
    } elseif ($action == 'submit-session') {
        // Handle session submission
        if ($_POST && isset($_POST['shedulesubmit'])) {
            // Collect data from the form
            $title = $_POST['title'];
            $docId = $_POST['docid'];
            $nop = $_POST['nop'];
            $date = $_POST['date'];
            $time = $_POST['time'];

            // Save session data to Firebase
            $newSession = [
                'title' => $title,
                'doctor_id' => $docId,
                'number_of_patients' => $nop,
                'date' => $date,
                'time' => $time,
            ];

            // Push data to Firebase
            $database->getReference('sessions')->push($newSession);

            // Redirect or display a success message
            header("Location: schedule.php?action=session-added&title=" . urlencode($title));
            exit();
        }
    } elseif ($action == 'session-added') {
        $titleget = $_GET["title"];
        echo '
        <div id="popup1" class="overlay">
            <div class="popup">
                <center>
                    <br><br>
                    <h2>Session Placed.</h2>
                    <a class="close" href="schedule.php">&times;</a>
                    <div class="content">
                        ' . substr($titleget, 0, 40) . ' was scheduled.<br><br>
                    </div>
                    <div style="display: flex; justify-content: center;">
                        <a href="schedule.php" class="non-style-link"><button class="btn-primary btn" style="display: flex; justify-content: center; align-items: center; margin:10px; padding:10px;"><font class="tn-in-text">&nbsp;&nbsp;OK&nbsp;&nbsp;</font></button></a>
                        <br><br><br><br>
                    </div>
                </center>
            </div>
        </div>';
    } elseif ($action == 'drop') {
        $nameget = $_GET["name"];
        $session = $_GET["session"];
        $apponum = $_GET["apponum"];
        echo '
        <div id="popup1" class="overlay">
            <div class="popup">
                <center>
                    <h2>Are you sure?</h2>
                    <a class="close" href="appointment.php">&times;</a>
                    <div class="content">
                        You want to delete this record<br><br>
                        Patient Name: &nbsp;<b>' . substr($nameget, 0, 40) . '</b><br>
                        Appointment number &nbsp; : <b>' . substr($apponum, 0, 40) . '</b><br><br>
                    </div>
                    <div style="display: flex; justify-content: center;">
                        <a href="delete-appointment.php?id=' . $id . '" class="non-style-link"><button class="btn-primary btn" style="display: flex; justify-content: center; align-items: center; margin:10px; padding:10px;"><font class="tn-in-text">&nbsp;Yes&nbsp;</font></button></a>&nbsp;&nbsp;&nbsp;
                        <a href="appointment.php" class="non-style-link"><button class="btn-primary btn" style="display: flex; justify-content: center; align-items: center; margin:10px; padding:10px;"><font class="tn-in-text">&nbsp;&nbsp;No&nbsp;&nbsp;</font></button></a>
                    </div>
                </center>
            </div>
        </div>';
    } elseif ($action == 'view') {
        $sqlmain = "select * from doctor where docid='$id'";
        $result = $database->getReference('doctors')->getValue(); // Fetch from Firebase
        $row = $result[$id]; // Get doctor details by ID
        $name = $row["docname"];
        $email = $row["docemail"];
        $spe = $row["specialties"];
        
        $spcil_res = $database->getReference('specialties/' . $spe)->getValue(); // Fetch specialties
        $spcil_name = $spcil_res["sname"];
        $nic = $row['docnic'];
        $tele = $row['doctel'];

        echo '
        <div id="popup1" class="overlay">
            <div class="popup">
                <center>
                    <h2></h2>
                    <a class="close" href="doctors.php">&times;</a>
                    <div class="content">
                        eDoc Web App<br>
                    </div>
                    <div style="display: flex; justify-content: center;">
                        <table width="80%" class="sub-table scrolldown add-doc-form-container" border="0">
                            <tr>
                                <td>
                                    <p style="padding: 0; margin: 0; text-align: left; font-size: 25px; font-weight: 500;">View Details.</p><br><br>
                                </td>
                            </tr>
                            <tr>
                                <td class="label-td" colspan="2">
                                    <label for="name" class="form-label">Name: </label>
                                </td>
                            </tr>
                            <tr>
                                <td class="label-td" colspan="2">' . $name . '<br><br></td>
                            </tr>
                            <tr>
                                <td class="label-td" colspan="2">
                                    <label for="Email" class="form-label">Email: </label>
                                </td>
                            </tr>
                            <tr>
                                <td class="label-td" colspan="2">' . $email . '<br><br></td>
                            </tr>
                            <tr>
                                <td class="label-td" colspan="2">
                                    <label for="nic" class="form-label">NIC: </label>
                                </td>
                            </tr>
                            <tr>
                                <td class="label-td" colspan="2">' . $nic . '<br><br></td>
                            </tr>
                            <tr>
                                <td class="label-td" colspan="2">
                                    <label for="Tele" class="form-label">Telephone: </label>
                                </td>
                            </tr>
                            <tr>
                                <td class="label-td" colspan="2">' . $tele . '<br><br></td>
                            </tr>
                            <tr>
                                <td class="label-td" colspan="2">
                                    <label for="spec" class="form-label">Specialties: </label>
                                </td>
                            </tr>
                            <tr>
                                <td class="label-td" colspan="2">' . $spcil_name . '<br><br></td>
                            </tr>
                            <tr>
                                <td colspan="2">
                                    <a href="doctors.php"><input type="button" value="OK" class="login-btn btn-primary-soft btn"></a>
                                </td>
                            </tr>
                        </table>
                    </div>
                </center>
                <br><br>
            </div>
        </div>';
    }
}
    ?>
	
	<script>
        const hamburgerMenu = document.getElementById('hamburger-menu');
        const menu = document.querySelector('.menu');
        const dashBody = document.querySelector('.dash-body');

        hamburgerMenu.addEventListener('click', () => {
            hamburgerMenu.classList.toggle('active');
            menu.classList.toggle('active');
            dashBody.classList.toggle('active');
        });
    </script>
    </div>

</body>
</html>