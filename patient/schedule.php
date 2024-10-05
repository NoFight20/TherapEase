<?php
include("../connection.php");

session_start();

if (isset($_SESSION["user"])) {
    if (empty($_SESSION["user"]) || $_SESSION['usertype'] != 'p') {
        header("location: ../login.php");
        exit;
    } else {
        $useremail = $_SESSION["user"];
    }
} else {
    header("location: ../login.php");
    exit;
}

// Get user details from session
$patientRef = $database->getReference('patients')->orderByChild('email')->equalTo($useremail)->getValue();
$userfetch = reset($patientRef); // Get the first matching patient
$username = isset($userfetch["fname"]);
$userid = isset($userfetch["id"]) ? $userfetch["id"] : '';

// Get today's date
date_default_timezone_set('Asia/Kolkata');
$today = date('Y-m-d');

// Query to get all upcoming sessions
$scheduleRef = $database->getReference('schedule')->orderByChild('scheduledate')->startAt($today)->getValue();
$sessions = [];
if ($scheduleRef) {
    foreach ($scheduleRef as $scheduleId => $scheduleData) {
        // Get doctor name
        $doctorRef = $database->getReference('doctors/' . $scheduleData['docid'])->getValue();
        $scheduleData['docname'] = isset($doctorRef['docname']) ? $doctorRef['docname'] : '';
        $sessions[$scheduleId] = $scheduleData;
    }
}

// Fetch user's booked sessions
$bookedRef = $database->getReference('bookings')->orderByChild('pid')->equalTo($userid)->getValue();
$booked_sessions = [];
if ($bookedRef) {
    foreach ($bookedRef as $bookingId => $bookingData) {
        $booked_sessions[] = $bookingData['scheduleid'];
    }
}
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
        
    <title>Sessions</title>
    <style>
        .popup{
            animation: transitionIn-Y-bottom 0.5s;
        }
        .sub-table{
            animation: transitionIn-Y-bottom 0.5s;
        }
</style>
</head>
<body>

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
                                 <p class="profile-title"><?php echo substr($username,0,13)  ?></p>
                                 <p class="profile-subtitle"><?php echo substr($useremail,0,22)  ?></p>
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
                    <td class="menu-btn" >
                        <a href="index.php" class="non-style-link-menu "><div><p class="menu-text">Home</p></a></div></a>
                    </td>
                </tr>
                <tr class="menu-row">
                    <td class="menu-btn">
                        <a href="doctors.php" class="non-style-link-menu"><div><p class="menu-text">All Doctors</p></a></div>
                    </td>
                </tr>
                
                <tr class="menu-row" >
                    <td class="menu-btn menu-active">
                        <a href="schedule.php" class="non-style-link-menu non-style-link-menu-active"><div><p class="menu-text">Scheduled Sessions</p></div></a>
                    </td>
                </tr>
                <tr class="menu-row" >
                    <td class="menu-btn">
                        <a href="appointment.php" class="non-style-link-menu"><div><p class="menu-text">My Bookings</p></a></div>
                    </td>
                </tr>
                <tr class="menu-row" >
                    <td class="menu-btn">
                        <a href="settings.php" class="non-style-link-menu"><div><p class="menu-text">Settings</p></a></div>
                    </td>
                </tr>
                
            </table>
        </div>
        <?php
            // Get today's date
            date_default_timezone_set('Asia/Kolkata');
            $today = date('Y-m-d');

            $searchtype = "All";
            $insertkey = "";
            $sessions = [];

            if ($_POST) {
                if (!empty($_POST["search"])) {
                    $keyword = $_POST["search"];
                    $searchtype = "Search Result: ";
                    
                    // Fetch all schedules from Firebase
                    $scheduleRef = $database->getReference('schedule')->orderByChild('scheduledate')->startAt($today)->getValue();
                    
                    // Fetch doctors to map their IDs to names
                    $doctorRef = $database->getReference('doctors')->getValue();
                    
                    foreach ($scheduleRef as $scheduleId => $scheduleData) {
                        // Get doctor details
                        $doctorData = $doctorRef[$scheduleData['docid']] ?? null;
                        if ($doctorData) {
                            $scheduleData['docname'] = $doctorData['docname'];

                            // Check if keyword matches any criteria
                            if (
                                stripos($doctorData['docname'], $keyword) !== false ||
                                stripos($scheduleData['title'], $keyword) !== false ||
                                stripos($scheduleData['scheduledate'], $keyword) !== false
                            ) {
                                $sessions[$scheduleId] = $scheduleData;
                            }
                        }
                    }
                } else {
                    // Fetch all upcoming sessions without filtering
                    $scheduleRef = $database->getReference('schedule')->orderByChild('scheduledate')->startAt($today)->getValue();
                    
                    // Fetch doctors to map their IDs to names
                    $doctorRef = $database->getReference('doctors')->getValue();
                    
                    foreach ($scheduleRef as $scheduleId => $scheduleData) {
                        // Get doctor details
                        $doctorData = $doctorRef[$scheduleData['docid']] ?? null;
                        if ($doctorData) {
                            $scheduleData['docname'] = $doctorData['docname'];
                            $sessions[$scheduleId] = $scheduleData;
                        }
                    }
                }
            } else {
                // Fetch all upcoming sessions without filtering
                $scheduleRef = $database->getReference('schedule')->orderByChild('scheduledate')->startAt($today)->getValue();
                
                // Fetch doctors to map their IDs to names
                $doctorRef = $database->getReference('doctors')->getValue();
                
                foreach ($scheduleRef as $scheduleId => $scheduleData) {
                    // Get doctor details
                    $doctorData = $doctorRef[$scheduleData['docid']] ?? null;
                    if ($doctorData) {
                        $scheduleData['docname'] = $doctorData['docname'];
                        $sessions[$scheduleId] = $scheduleData;
                    }
                }
            }
                ?>
                  
        <div class="dash-body">
            <table border="0" width="100%" style=" border-spacing: 0;margin:0;padding:0;margin-top:25px; ">
                <tr >
                    <td width="13%" >
                    <a href="schedule.php" ><button  class="login-btn btn-primary-soft btn btn-icon-back"  style="padding-top:11px;padding-bottom:11px;margin-left:20px;width:125px"><font class="tn-in-text">Back</font></button></a>
                    </td>
                    <td >
                            <form action="" method="post" class="header-search">

                                        <input type="search" name="search" class="input-text header-searchbar" placeholder="Search Doctor name or Email or Date (YYYY-MM-DD)" list="doctors" value="<?php  echo $insertkey ?>">&nbsp;&nbsp;
                                        
                                        <?php
                                        $doctorsRef = $database->getReference('doctors')->getValue();
                                        $scheduleRef = $database->getReference('schedules')->getValue();
                                        // Ensure $doctorsRef and $scheduleRef are defined and contain valid data
                                        $doctorOptions = [];
                                        $titleOptions = [];

                                        // Check if $doctorsRef is an array or object before looping
                                        if (is_array($doctorsRef) || is_object($doctorsRef)) {
                                            foreach ($doctorsRef as $doctor) {
                                                if (isset($doctor['docname'])) {
                                                    $doctorOptions[] = $doctor['docname'];
                                                }
                                            }
                                        } 

                                        // Check if $scheduleRef is an array or object before looping
                                        if (is_array($scheduleRef) || is_object($scheduleRef)) {
                                            foreach ($scheduleRef as $schedule) {
                                                if (isset($schedule['title']) && !in_array($schedule['title'], $titleOptions)) {
                                                    $titleOptions[] = $schedule['title'];
                                                }
                                            }
                                        }

                                        // Output the datalist for doctors
                                        echo '<datalist id="doctors">';
                                        foreach ($doctorOptions as $doctorName) {
                                            echo "<option value='$doctorName'><br/>";
                                        }
                                        echo '</datalist>';

                                        // Output the datalist for titles
                                        echo '<datalist id="titles">';
                                        foreach ($titleOptions as $title) {
                                            echo "<option value='$title'><br/>";
                                        }
                                        echo '</datalist>';

                                        ?>
                                        
                                
                                        <input type="Submit" value="Search" class="login-btn btn-primary btn" style="padding-left: 25px;padding-right: 25px;padding-top: 10px;padding-bottom: 10px;">
                                        </form>
                    </td>
                    <td width="15%">
                        <p style="font-size: 14px;color: rgb(119, 119, 119);padding: 0;margin: 0;text-align: right;">
                            Today's Date
                        </p>
                        <p class="heading-sub12" style="padding: 0;margin: 0;">
                            <?php
                                echo $today;
                        ?>
                        </p>
                    </td>
                    <td width="10%">
                        <button  class="btn-label"  style="display: flex;justify-content: center;align-items: center;"><img src="../img/calendar.svg" width="100%"></button>
                    </td>


                </tr>
                
                
                <tr>
                    <td colspan="4" style="padding-top:10px;width: 100%;" >
                        <p class="heading-main12" style="margin-left: 45px;font-size:18px;color:rgb(49, 49, 49)"><?php 
                        // Fetch sessions or relevant data from Firebase
                        $sessionsRef = $database->getReference('sessions')->getValue();
                        // Initialize $numRows to 0 by default
                        $numRows = 0;
                        
                        // Check if the data is valid and count the rows
                        if (is_array($sessionsRef)) {
                            $numRows = count($sessionsRef);
                        } else {
                            $numRows = 0; // If no data, set row count to 0
                        }
                        echo $numRows; ?> </p>
                        <p class="heading-main12" style="margin-left: 45px;font-size:22px;color:rgb(49, 49, 49)"><?php $q = ""; 
                        $insertkey = ""; // Ensure $insertkey is initialized or given a value

                        // Fetch sessions from Firebase
                        $sessionsRef = $database->getReference('sessions')->getValue();

                        // Count the number of sessions
                        if (is_array($sessionsRef)) {
                            $numRows = count($sessionsRef);
                        } else {
                            $numRows = 0;
                        } ?> </p>
                    </td>
                    
                </tr>  
                <tr>
                   <td colspan="4">
                       <center>
                        <div class="abc scroll">
                        <table width="100%" class="sub-table scrolldown" border="0" style="padding: 50px;border:none">
                            
                        <tbody>
                            <?php
                             // Fetch sessions from Firebase
                            $sessionsRef = $database->getReference('sessions')->getValue(); // Adjust this path as per your structure

                            // Check if any sessions are available
                            if (empty($sessionsRef) || !is_array($sessionsRef)) {
                                // No sessions found or invalid data, show the 'not found' message
                                echo '<tr>
                                    <td colspan="4">
                                        <br><br><br><br>
                                        <center>
                                            <img src="../img/notfound.svg" width="25%">
                                            <br>
                                            <p class="heading-main12" style="margin-left: 45px;font-size:20px;color:rgb(49, 49, 49)">
                                                We couldn\'t find anything related to your keywords!
                                            </p>
                                            <a class="non-style-link" href="schedule.php">
                                                <button class="login-btn btn-primary-soft btn" style="display: flex;justify-content: center;align-items: center;margin-left:20px;">
                                                    &nbsp; Show all Sessions &nbsp;
                                                </button>
                                            </a>
                                        </center>
                                        <br><br><br><br>
                                    </td>
                                </tr>';
                            } else {
                                // Loop through the sessions and generate table rows
                                foreach ($sessionsRef as $sessionId => $session) {
                                    echo '<tr>
                                        <td style="width: 25%;">
                                            <div class="dashboard-items search-items">
                                                <div style="width:100%">
                                                    <!-- Session Title -->
                                                    <div class="h1-search">' . substr($session['title'], 0, 21) . '</div><br>

                                                    <!-- Doctor Name -->
                                                    <div class="h3-search">' . substr($session['docname'], 0, 30) . '</div>

                                                    <!-- Schedule Date and Time -->
                                                    <div class="h4-search">
                                                        ' . $session['scheduledate'] . '<br>Starts: <b>@' . substr($session['scheduletime'], 0, 5) . '</b> (24h)
                                                    </div>
                                                    <br>';

                                    // Check if the session is booked
                                    if (isset($booked_sessions) && is_array($booked_sessions) && in_array($session['scheduleid'], $booked_sessions)) {
                                        echo '<button class="login-btn btn-primary-soft btn" style="padding-top:11px;padding-bottom:11px;width:100%" disabled>Already Booked</button>';
                                    } else {
                                        echo '<a href="booking.php?id=' . $session['scheduleid'] . '">
                                            <button class="login-btn btn-primary-soft btn" style="padding-top:11px;padding-bottom:11px;width:100%">
                                                <font class="tn-in-text">Book Now</font>
                                            </button>
                                        </a>';
                                    }
                                    echo '</div>
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

    </div>

</body>
</html>
