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
        
    <title>Schedule</title>

</head>
<body>
    <!-- Mobile Header -->
    <div class="mobile-header">Schedule</div>

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
                    <td class="menu-btn">
                        <a href="index.php" class="non-style-link-menu"><div><p class="menu-text">Dashboard</p></a></div></a>
                    </td>
                </tr>
                <tr class="menu-row">
                    <td class="menu-btn">
                        <a href="doctors.php" class="non-style-link-menu "><div><p class="menu-text">Doctors</p></a></div>
                    </td>
                </tr>
                <tr class="menu-row" >
                    <td class="menu-btn menu-active">
                        <a href="schedule.php" class="non-style-link-menu non-style-link-menu-active"><div><p class="menu-text">Schedule</p></div></a>
                    </td>
                </tr>
                <tr class="menu-row">
                    <td class="menu-btn">
                        <a href="appointment.php" class="non-style-link-menu"><div><p class="menu-text">Appointment</p></a></div>
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
                    <a href="schedule.php" ><button  class="login-btn btn-primary-soft btn btn-icon-back"  style="padding-top:11px;padding-bottom:11px;margin-left:20px;width:125px"><font class="tn-in-text">Back</font></button></a>
                    </td>
                    <td>
                        <p style="font-size: 23px;padding-left:12px;font-weight: 600;">Shedule Manager</p>
                                           
                    </td>
                    <td width="15%">
                        <p style="font-size: 14px;color: rgb(119, 119, 119);padding: 0;margin: 0;text-align: right;">
                            Today's Date
                        </p>
                        <p class="heading-sub12" style="padding: 0;margin: 0;">
                            <?php 

                            date_default_timezone_set('Asia/Kolkata');
                            $today = date('Y-m-d');
                            echo $today;
                            // Fetch schedule data from Firebase
                            $scheduleReference = $database->getReference('schedule');
                            $scheduleData = $scheduleReference->getValue();

                            // Check if data exists
                            if ($scheduleData) {
                                foreach ($scheduleData as $id => $schedule) {
                                    // Assuming $schedule is an associative array with the schedule details
                                    echo "ID: $id - Schedule: " . json_encode($schedule) . "<br>";
                                }
                            } 
                        ?>
                        </p>
                    </td>
                    <td width="10%">
                        <button  class="btn-label"  style="display: flex;justify-content: center;align-items: center;"><img src="../img/calendar.svg" width="100%"></button>
                    </td>


                </tr>
               
                <tr>
                    <td colspan="4" >
                        <div style="display: flex;margin-top: 40px;">
                        <div class="heading-main12" style="margin-left: 45px;font-size:20px;color:rgb(49, 49, 49);margin-top: 5px;">Schedule a Session</div>
                        <a href="?action=add-session&id=none&error=0" class="non-style-link"><button  class="login-btn btn-primary btn button-icon"  style="margin-left:25px;background-image: url('../img/icons/add.svg');">Add a Session</font></button>
                        </a>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td colspan="4" style="padding-top:10px;width: 100%;" >
                    
                        <p class="heading-main12" style="margin-left: 45px;font-size:18px;color:rgb(49, 49, 49)">All Sessions 
                            (<?php 
                                // Fetch schedule data from Firebase
                                $scheduleReference = $database->getReference('schedule');
                                $scheduleData = $scheduleReference->getValue();

                                // Count the number of entries
                                if ($scheduleData) {
                                    $numRows = count($scheduleData); // Count the number of schedule entries
                                    echo "Number of schedule entries: $numRows<br>";

                                    // Display schedule data
                                    foreach ($scheduleData as $id => $schedule) {
                                        // Assuming $schedule is an associative array with the schedule details
                                        echo "ID: $id - Schedule: " . json_encode($schedule) . "<br>";
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
                            
                                // Fetch doctor data from Firebase
                                $doctorReference = $database->getReference('doctor')->orderByChild('docname')->getValue();

                                // Check if any doctors are found
                                if ($doctorReference) {
                                    foreach ($doctorReference as $id00 => $doctor) {
                                        $sn = $doctor['docname'];
                                        echo "<option value=\"$id00\">$sn</option><br/>";
                                    }
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
                        // Initialize Firebase queries
                        $scheduleReference = $database->getReference('schedule');
                        $doctorReference = $database->getReference('doctor');
                    
                        $scheduledate = !empty($_POST["sheduledate"]) ? $_POST["sheduledate"] : null;
                        $docid = !empty($_POST["docid"]) ? $_POST["docid"] : null;
                    
                        // Fetch all schedules
                        $schedules = $scheduleReference->getValue();
                        $doctors = $doctorReference->getValue();
                    
                        // Filter schedules based on the provided criteria
                        $filteredSchedules = [];
                        foreach ($schedules as $scheduleId => $schedule) {
                            // Check if the schedule matches the scheduledate
                            if ($scheduledate && $schedule['scheduledate'] !== $scheduledate) {
                                continue; // Skip this schedule if the date does not match
                            }
                    
                            // Check if the schedule matches the doctor ID
                            if ($docid && $schedule['docid'] !== $docid) {
                                continue; // Skip this schedule if the doctor ID does not match
                            }
                    
                            // Add doctor name to the schedule
                            if (isset($doctors[$schedule['docid']])) {
                                $schedule['docname'] = $doctors[$schedule['docid']]['docname'];
                            }
                    
                            // Collect filtered schedules
                            $filteredSchedules[$scheduleId] = $schedule;
                        }
                    
                        // You can now use $filteredSchedules as needed for output/display
                        // Example of output:
                        foreach ($filteredSchedules as $schedule) {
                            echo "Schedule ID: " . $schedule['scheduleid'] . "<br>";
                            echo "Title: " . $schedule['title'] . "<br>";
                            echo "Doctor Name: " . $schedule['docname'] . "<br>";
                            echo "Scheduled Date: " . $schedule['scheduledate'] . "<br>";
                            echo "Scheduled Time: " . $schedule['scheduletime'] . "<br>";
                            echo "Number of Patients: " . $schedule['nop'] . "<br><br>";
                        }
                    } else {
                        // Fetch all schedules without filters
                        $schedules = $database->getReference('schedule')->orderByChild('scheduledate')->getValue();
                        $doctors = $database->getReference('doctor')->getValue();
                    
                        // Display all schedules
                        foreach ($schedules as $scheduleId => $schedule) {
                            // Add doctor name to the schedule
                            if (isset($doctors[$schedule['docid']])) {
                                $schedule['docname'] = $doctors[$schedule['docid']]['docname'];
                            }
                    
                            // Output schedule details
                            echo "Schedule ID: " . $schedule['scheduleid'] . "<br>";
                            echo "Title: " . $schedule['title'] . "<br>";
                            echo "Doctor Name: " . $schedule['docname'] . "<br>";
                            echo "Scheduled Date: " . $schedule['scheduledate'] . "<br>";
                            echo "Scheduled Time: " . $schedule['scheduletime'] . "<br>";
                            echo "Number of Patients: " . $schedule['nop'] . "<br><br>";
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
                                    
                                
                                Session Title
                                
                                </th>
                                
                                <th class="table-headin">
                                    Doctor
                                </th>
                                <th class="table-headin">
                                    
                                    Sheduled Date & Time
                                    
                                </th>
                                <th class="table-headin">
                                    
                                Max num that can be booked
                                    
                                </th>
                                
                                <th class="table-headin">
                                    
                                    Events
                                    
                                </tr>
                        </thead>
                        <tbody>
                        
                            <?php
                            // Initialize a reference to the schedule and doctor nodes
                            $scheduleReference = $database->getReference('schedule');
                            $doctorReference = $database->getReference('doctor');

                            // Fetch all schedules
                            $schedules = $scheduleReference->getValue();
                            $doctors = $doctorReference->getValue();

                            // Initialize filtered results
                            $filteredResults = [];

                            // Check if there are any filters applied (like date or doctor ID)
                            if (!empty($_POST["sheduledate"]) || !empty($_POST["docid"])) {
                                foreach ($schedules as $scheduleId => $schedule) {
                                    // Check against scheduledate and docid if provided
                                    $matchesDate = empty($_POST["sheduledate"]) || $schedule['scheduledate'] === $_POST["sheduledate"];
                                    $matchesDocId = empty($_POST["docid"]) || $schedule['docid'] === $_POST["docid"];

                                    // If both conditions are met, add to filtered results
                                    if ($matchesDate && $matchesDocId) {
                                        // Add doctor name to the schedule
                                        if (isset($doctors[$schedule['docid']])) {
                                            $schedule['docname'] = $doctors[$schedule['docid']]['docname'];
                                        }
                                        $filteredResults[$scheduleId] = $schedule;
                                    }
                                }
                            } else {
                                // If no filters, return all schedules
                                foreach ($schedules as $scheduleId => $schedule) {
                                    if (isset($doctors[$schedule['docid']])) {
                                        $schedule['docname'] = $doctors[$schedule['docid']]['docname'];
                                    }
                                    $filteredResults[$scheduleId] = $schedule;
                                }
                            }

                            // Check if there are results to display
                            if (empty($filteredResults)) {
                                echo '<tr>
                                        <td colspan="4">
                                        <br><br><br><br>
                                        <center>
                                        <img src="../img/notfound.svg" width="25%">
                                        <br>
                                        <p class="heading-main12" style="margin-left: 45px;font-size:20px;color:rgb(49, 49, 49)">We couldn\'t find anything related to your keywords!</p>
                                        <a class="non-style-link" href="schedule.php"><button class="login-btn btn-primary-soft btn" style="display: flex;justify-content: center;align-items: center;margin-left:20px;">&nbsp; Show all Sessions &nbsp;</button></a>
                                        </center>
                                        <br><br><br><br>
                                        </td>
                                        </tr>';
                            } else {
                                foreach ($filteredResults as $scheduleid => $row) {
                                    $title = $row["title"];
                                    $docname = $row["docname"];
                                    $scheduledate = $row["scheduledate"];
                                    $scheduletime = $row["scheduletime"];
                                    $nop = $row["nop"];
                                    
                                    echo '<tr>
                                            <td>&nbsp;' . substr($title, 0, 30) . '</td>
                                            <td>' . substr($docname, 0, 20) . '</td>
                                            <td style="text-align:center;">' . substr($scheduledate, 0, 10) . ' ' . substr($scheduletime, 0, 5) . '</td>
                                            <td style="text-align:center;">' . $nop . '</td>
                                            <td>
                                                <div style="display:flex;justify-content: center;">
                                                    <a href="?action=view&id=' . $scheduleid . '" class="non-style-link"><button class="btn-primary-soft btn button-icon btn-view" style="padding-left: 40px;padding-top: 12px;padding-bottom: 12px;margin-top: 10px;"><font class="tn-in-text">View</font></button></a>
                                                    &nbsp;&nbsp;&nbsp;
                                                    <a href="?action=drop&id=' . $scheduleid . '&name=' . $title . '" class="non-style-link"><button class="btn-primary-soft btn button-icon btn-delete" style="padding-left: 40px;padding-top: 12px;padding-bottom: 12px;margin-top: 10px;"><font class="tn-in-text">Remove</font></button></a>
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
   // Check if there's a GET request
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
                                            <td class="label-td" colspan="2"></td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <p style="padding: 0; margin: 0; text-align: left; font-size: 25px; font-weight: 500;">Add New Session.</p><br>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="label-td" colspan="2">
                                                <form action="add-session.php" method="POST" class="add-new-form">
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
                                                <select name="docid" id="" class="box">
                                                    <option value="" disabled selected hidden>Choose Doctor Name from the list</option><br/>';

                // Fetch doctor list from Firebase
                $doctors = $database->getReference('doctors')->getValue();
                foreach ($doctors as $docid => $doctor) {
                    $docname = $doctor['name'];
                    echo "<option value='$docid'>$docname</option><br/>";
                }

                echo '              </select><br><br>
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
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </center>
                    <br><br>
                </div>
                </div>
                ';

                // Handle session submission (POST request)
                if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                    $title = $_POST['title'];
                    $docid = $_POST['docid'];
                    $nop = $_POST['nop'];
                    $date = $_POST['date'];
                    $time = $_POST['time'];

                    // Firebase: Create a new session
                    $newSession = $database->getReference('sessions')->push([
                        'title' => $title,
                        'docid' => $docid,
                        'nop' => $nop,
                        'date' => $date,
                        'time' => $time
                    ]);

                    if ($newSession) {
                        header("Location: schedule.php?action=session-added&title=" . $title);
                        exit();
                    }
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
                                <a href="schedule.php" class="non-style-link"><button class="btn-primary btn" style="display: flex; justify-content: center; align-items: center; margin: 10px; padding: 10px;"><font class="tn-in-text">&nbsp;&nbsp;OK&nbsp;&nbsp;</font></button></a>
                                <br><br><br><br>
                            </div>
                        </center>
                    </div>
                </div>
                ';
            } elseif ($action == 'drop') {
                $nameget = $_GET["name"];
                echo '
                <div id="popup1" class="overlay">
                    <div class="popup">
                        <center>
                            <h2>Are you sure?</h2>
                            <a class="close" href="schedule.php">&times;</a>
                            <div class="content">
                                You want to delete this record<br>(' . substr($nameget, 0, 40) . ').
                            </div>
                            <div style="display: flex; justify-content: center;">
                                <a href="delete-session.php?id=' . $id . '" class="non-style-link"><button class="btn-primary btn" style="display: flex; justify-content: center; align-items: center; margin: 10px; padding: 10px;"><font class="tn-in-text">&nbsp;Yes&nbsp;</font></button></a>&nbsp;&nbsp;&nbsp;
                                <a href="schedule.php" class="non-style-link"><button class="btn-primary btn" style="display: flex; justify-content: center; align-items: center; margin: 10px; padding: 10px;"><font class="tn-in-text">&nbsp;&nbsp;No&nbsp;&nbsp;</font></button></a>
                            </div>
                        </center>
                    </div>
                </div>
                ';
            } elseif ($action == 'view') {
                $session = $database->getReference('sessions/' . $id)->getValue();
                $docname = $database->getReference('doctors/' . $session['docid'])->getValue()['name'];
                $scheduledate = $session['date'];
                $scheduletime = $session['time'];
                $title = $session['title'];
                $nop = $session['nop'];

                // Get the number of patients registered for the session
                $patients = $database->getReference('appointments')->orderByChild('sessionid')->equalTo($id)->getValue();
                $patientCount = $patients ? count($patients) : 0;

                echo '
                <div id="popup1" class="overlay">
                    <div class="popup" style="width: 70%;">
                        <center>
                            <h2></h2>
                            <a class="close" href="schedule.php">&times;</a>
                            <div class="content"></div>
                            <div class="abc scroll" style="display: flex; justify-content: center;">
                                <table width="80%" class="sub-table scrolldown add-doc-form-container" border="0">
                                    <tr>
                                        <td>
                                            <p style="font-size: 25px; font-weight: 500;">View Details.</p><br><br>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="label-td" colspan="2">
                                            <label for="title" class="form-label">Session Title: </label>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="label-td" colspan="2">' . $title . '<br><br></td>
                                    </tr>
                                    <tr>
                                        <td class="label-td" colspan="2">
                                            <label for="docid" class="form-label">Hosted Doctor: </label>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="label-td" colspan="2">' . $docname . '<br><br></td>
                                    </tr>
                                    <tr>
                                        <td class="label-td" colspan="2">
                                            <label for="nop" class="form-label">Number of Patients: </label>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="label-td" colspan="2">' . $nop . '<br><br></td>
                                    </tr>
                                    <tr>
                                        <td class="label-td" colspan="2">
                                            <label for="date" class="form-label">Session Date: </label>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="label-td" colspan="2">' . $scheduledate . '<br><br></td>
                                    </tr>
                                    <tr>
                                        <td class="label-td" colspan="2">
                                            <label for="time" class="form-label">Session Time: </label>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="label-td" colspan="2">' . $scheduletime . '<br><br></td>
                                    </tr>
                                    <tr>
                                        <td class="label-td" colspan="2">
                                            <label for="patients" class="form-label">Patients Registered: </label>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="label-td" colspan="2">' . $patientCount . '<br><br></td>
                                    </tr>
                                    <tr>
                                        <td colspan="2">
                                            <a href="schedule.php"><button class="login-btn btn-primary-soft btn">OK</button></a>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </center>
                        <br><br>
                    </div>
                </div>
                ';
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