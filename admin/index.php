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
        
    <title>Dashboard</title>
    <style>
        .dashbord-tables{
            animation: transitionIn-Y-over 0.5s;
        }
        .filter-container{
            animation: transitionIn-Y-bottom  0.5s;
        }
    </style>
    
</head>

<body>
    <!-- Mobile Header -->
    <div class="mobile-header">Dashboard</div>

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
                    <td class="menu-btn menu-active">
                        <a href="index.php" class="non-style-link-menu non-style-link-menu-active"><div><p class="menu-text">Dashboard</p></a></div></a>
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
        <div class="dash-body" style="margin-top: 15px">
            <table border="0" width="100%" style=" border-spacing: 0;margin:0;padding:0;" >
                        
                        <tr >
                            
                            <td colspan="2" class="nav-bar" >
                                
                                <form action="doctors.php" method="post" class="header-search">
        
                                    <input type="search" name="search" class="input-text header-searchbar" placeholder="Search Doctor name or Email" list="doctors">&nbsp;&nbsp;
                                    
                                    <?php
                                    echo '<datalist id="doctors">';

                                    // Fetch the doctors from Firebase
                                    $doctors = $database->getReference('doctor')->getValue();

                                    if ($doctors) {
                                        foreach ($doctors as $doctor) {
                                            $d = htmlspecialchars($doctor['name']); // Escape output to prevent XSS
                                            $c = htmlspecialchars($doctor['email']);
                                            echo "<option value='$d'><br/>";
                                            echo "<option value='$c'><br/>";
                                        }
                                    }

                                    echo '</datalist>';
                                    ?>
                                    
                               
                                    <input type="Submit" value="Search" class="login-btn btn-primary-soft btn" style="padding-left: 25px;padding-right: 25px;padding-top: 10px;padding-bottom: 10px;">
                                
                                </form>
                                
                            </td>
                            <td width="15%">
                                <p style="font-size: 14px;color: rgb(119, 119, 119);padding: 0;margin: 0;text-align: right;">
                                    Today's Date
                                </p>
                                <p class="heading-sub12" style="padding: 0;margin: 0;">
                                    <?php 
                              
                                    // Set the timezone
                                    date_default_timezone_set('Asia/Kolkata');

                                    // Get today's date
                                    $today = date('Y-m-d');
                                    echo $today;

                                    // Fetch all patients
                                    $patients = $database->getReference('patient')->getValue();

                                    // Fetch all doctors
                                    $doctors = $database->getReference('doctor')->getValue();

                                    // Fetch appointments for today and future
                                    $appointments = $database->getReference('appointment')
                                        ->orderByChild('appodate')
                                        ->startAt($today)
                                        ->getValue();

                                    // Fetch schedules for today
                                    $schedules = $database->getReference('schedule')
                                        ->orderByChild('scheduledate')
                                        ->equalTo($today)
                                        ->getValue();
                                ?>
                                </p>
                            </td>
                            <td width="10%">
                                <button  class="btn-label"  style="display: flex;justify-content: center;align-items: center;"><img src="../img/calendar.svg" width="100%"></button>
                            </td>
        
        
                        </tr>
                <tr>
                    <td colspan="4">
                        
                        <center>
                        <table class="filter-container" style="border: none;" border="0">
                            <tr>
                                <td colspan="4">
                                    <p style="font-size: 20px;font-weight:600;padding-left: 12px;">Status</p>
                                </td>
                            </tr>
                            <tr>
                                <td style="width: 25%;">
                                    <div  class="dashboard-items"  style="padding:20px;margin:auto;width:95%;display: flex">
                                        <div>
                                                <div class="h1-dashboard">
                                                    <?php    
                                                    $doctors = $database->getReference('doctor')->getValue();

                                                    // Count the number of doctors
                                                    if ($doctors) {
                                                        $doctorCount = count($doctors);
                                                        echo $doctorCount;
                                                    } else {
                                                        echo 0; // No doctors found
                                                    } ?>
                                                </div><br>
                                                <div class="h3-dashboard">
                                                    Doctors &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                                </div>
                                        </div>
                                                <div class="btn-icon-back dashboard-icons" style="background-image: url('../img/icons/doctors-hover.svg');"></div>
                                    </div>
                                </td>
                                <td style="width: 25%;">
                                    <div  class="dashboard-items"  style="padding:20px;margin:auto;width:95%;display: flex;">
                                        <div>
                                                <div class="h1-dashboard">
                                                    <?php    
                                                    $patients = $database->getReference('patient')->getValue();
                                                    // Count the number of patients
                                                    if ($patients) {
                                                        $patientCount = count($patients);
                                                        echo "Number of patients: $patientCount";
                                                    } else {
                                                        echo "0"; // No patients found
                                                    }?>
                                                </div><br>
                                                <div class="h3-dashboard">
                                                    Patients &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                                </div>
                                        </div>
                                                <div class="btn-icon-back dashboard-icons" style="background-image: url('../img/icons/patients-hover.svg');"></div>
                                    </div>
                                </td>
                                <td style="width: 25%;">
                                    <div  class="dashboard-items"  style="padding:20px;margin:auto;width:95%;display: flex; ">
                                        <div>
                                                <div class="h1-dashboard" >
                                                    <?php   
                                                    // Fetch appointments for today and future from Firebase
                                                    $appointments = $database->getReference('appointment')
                                                        ->orderByChild('appodate')
                                                        ->startAt($today)
                                                        ->getValue();

                                                    // Count the number of appointments
                                                    if ($appointments) {
                                                        $appointmentCount = count($appointments);
                                                        echo "$appointmentCount";
                                                    } else {
                                                        echo "0"; // No appointments found
                                                    }
                                                    ?>
                                                </div><br>
                                                <div class="h3-dashboard" >
                                                    NewBooking &nbsp;&nbsp;
                                                </div>
                                        </div>
                                                <div class="btn-icon-back dashboard-icons" style="margin-left: 0px;background-image: url('../img/icons/book-hover.svg');"></div>
                                    </div>
                                </td>
                                <td style="width: 25%;">
                                    <div  class="dashboard-items"  style="padding:20px;margin:auto;width:95%;display: flex;padding-top:26px;padding-bottom:26px;">
                                        <div>
                                                <div class="h1-dashboard">
                                                    <?php    
                                                    // Fetch schedules for today from Firebase
                                                    $schedules = $database->getReference('schedule')
                                                    ->orderByChild('scheduledate')
                                                    ->equalTo($today)
                                                    ->getValue();

                                                    // Count the number of schedules
                                                    if ($schedules) {
                                                    $scheduleCount = count($schedules);
                                                    echo "$scheduleCount";
                                                    } else {
                                                    echo "0"; // No schedules found
                                                    }

                                                    ?>
                                                </div><br>
                                                <div class="h3-dashboard" style="font-size: 15px">
                                                    Today Sessions
                                                </div>
                                        </div>
                                                <div class="btn-icon-back dashboard-icons" style="background-image: url('../img/icons/session-iceblue.svg');"></div>
                                    </div>
                                </td>
                                
                            </tr>
                        </table>
                    </center>
                    </td>
                </tr>






                <tr>
                    <td colspan="4">
                        <table width="100%" border="0" class="dashbord-tables">
                            <tr>
                                <td>
                                    <p style="padding:10px;padding-left:48px;padding-bottom:0;font-size:23px;font-weight:700;color:var(--primarycolor);">
                                        Upcoming Appointments until Next <?php  
                                        echo date("l",strtotime("+1 week"));
                                        ?>
                                    </p>
                                    <p style="padding-bottom:19px;padding-left:50px;font-size:15px;font-weight:500;color:#212529e3;line-height: 20px;">
                                        Here's Quick access to Upcoming Appointments until 7 days<br>
                                        More details available in @Appointment section.
                                    </p>

                                </td>
                                <td>
                                    <p style="text-align:right;padding:10px;padding-right:48px;padding-bottom:0;font-size:23px;font-weight:700;color:var(--primarycolor);">
                                        Upcoming Sessions  until Next <?php  
                                        echo date("l",strtotime("+1 week"));
                                        ?>
                                    </p>
                                    <p style="padding-bottom:19px;text-align:right;padding-right:50px;font-size:15px;font-weight:500;color:#212529e3;line-height: 20px;">
                                        Here's Quick access to Upcoming Sessions that Scheduled until 7 days<br>
                                        Add,Remove and Many features available in @Schedule section.
                                    </p>
                                </td>
                            </tr>
                            <tr>
                                <td width="50%">
                                    <center>
                                        <div class="abc scroll" style="height: 200px;">
                                        <table width="85%" class="sub-table scrolldown" border="0">
                                        <thead>
                                        <tr>    
                                                <th class="table-headin" style="font-size: 12px;">
                                                        
                                                    Appointment number
                                                    
                                                </th>
                                                <th class="table-headin">
                                                    Patient name
                                                </th>
                                                <th class="table-headin">
                                                    
                                                
                                                    Doctor
                                                    
                                                </th>
                                                <th class="table-headin">
                                                    
                                                
                                                    Session
                                                    
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        
                                            <?php
                                            $nextweek = date('Y-m-d', strtotime("+1 week"));

                                            // Fetch all schedules within the date range
                                            $schedules = $database->getReference('schedule')
                                                ->orderByChild('scheduledate')
                                                ->startAt($today)
                                                ->endAt($nextweek)
                                                ->getValue();
                                            
                                            // Initialize an array to hold appointments
                                            $appointments = [];
                                            
                                            // Check if any schedules were found
                                            if ($schedules) {
                                                foreach ($schedules as $schedule) {
                                                    // Fetch appointments for each schedule
                                                    $scheduleId = $schedule['scheduleid'];
                                                    $appRefs = $database->getReference('appointment')
                                                        ->orderByChild('scheduleid')
                                                        ->equalTo($scheduleId)
                                                        ->getValue();
                                            
                                                    if ($appRefs) {
                                                        foreach ($appRefs as $appointment) {
                                                            $appointments[] = [
                                                                'appoid' => $appointment['appoid'],
                                                                'scheduleid' => $scheduleId,
                                                                'title' => $schedule['title'],
                                                                'docid' => $schedule['docid'], // assuming you will fetch doctor info later
                                                                'scheduledate' => $schedule['scheduledate'],
                                                                'scheduletime' => $schedule['scheduletime'],
                                                                'pid' => $appointment['pid'],
                                                                'apponum' => $appointment['apponum'],
                                                                'appodate' => $appointment['appodate'],
                                                            ];
                                                        }
                                                    }
                                                }
                                            }
                                            
                                            // Check if there are appointments to display
                                            if (empty($appointments)) {
                                                echo '<tr>
                                                    <td colspan="3">
                                                    <br><br><br><br>
                                                    <center>
                                                    <img src="../img/notfound.svg" width="25%">
                                                    <br>
                                                    <p class="heading-main12" style="margin-left: 45px;font-size:20px;color:rgb(49, 49, 49)">We couldn’t find anything related to your keywords!</p>
                                                    <a class="non-style-link" href="appointment.php"><button class="login-btn btn-primary-soft btn" style="display: flex;justify-content: center;align-items: center;margin-left:20px;">&nbsp; Show all Appointments &nbsp;</button>
                                                    </a>
                                                    </center>
                                                    <br><br><br><br>
                                                    </td>
                                                </tr>';
                                            } else {
                                                foreach ($appointments as $row) {
                                                    // Fetch doctor's name based on docid
                                                    $doctor = $database->getReference('doctor/' . $row['docid'])->getValue();
                                                    $docname = $doctor ? $doctor['docname'] : 'Unknown Doctor';
                                                    
                                                    echo '<tr>
                                                        <td style="text-align:center;font-size:23px;font-weight:500; color: var(--btnnicetext);padding:20px;">' . htmlspecialchars($row['apponum']) . '</td>
                                                        <td style="font-weight:600;">&nbsp;' . htmlspecialchars(substr($row['pid'], 0, 25)) . '</td>
                                                        <td style="font-weight:600;">&nbsp;' . htmlspecialchars(substr($docname, 0, 25)) . '</td>
                                                        <td>' . htmlspecialchars(substr($row['title'], 0, 15)) . '</td>
                                                    </tr>';
                                                }
                                            }
                                            ?>
                                            </tbody>
                                        </table>
                                        </div>
                                        </center>
                                </td>
                                <td width="50%" style="padding: 0;">
                                    <center>
                                        <div class="abc scroll" style="height: 200px;padding: 0;margin: 0;">
                                        <table width="85%" class="sub-table scrolldown" border="0" >
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
                                                </tr>
                                        </thead>
                                        <tbody>
                                        
                                            <?php
                                          // Fetch all schedules within the date range
                                            $schedules = $database->getReference('schedule')
                                            ->orderByChild('scheduledate')
                                            ->startAt($today)
                                            ->endAt($nextweek)
                                            ->getValue();

                                            // Initialize an array to hold the results
                                            $scheduleList = [];

                                            // Check if any schedules were found
                                            if ($schedules) {
                                            foreach ($schedules as $schedule) {
                                                // Fetch doctor's name based on docid
                                                $doctorRef = $database->getReference('doctor/' . $schedule['docid'])->getValue();
                                                $docname = $doctorRef ? $doctorRef['docname'] : 'Unknown Doctor';

                                                // Add to the results array
                                                $scheduleList[] = [
                                                    'scheduleid' => $schedule['scheduleid'],
                                                    'title' => $schedule['title'],
                                                    'docname' => $docname,
                                                    'scheduledate' => $schedule['scheduledate'],
                                                    'scheduletime' => $schedule['scheduletime'],
                                                    'nop' => $schedule['nop'],
                                                ];
                                            }
                                            }

                                            // Check if there are schedules to display
                                            if (empty($scheduleList)) {
                                            echo '<tr>
                                                <td colspan="4">
                                                <br><br><br><br>
                                                <center>
                                                <img src="../img/notfound.svg" width="25%">
                                                <br>
                                                <p class="heading-main12" style="margin-left: 45px;font-size:20px;color:rgb(49, 49, 49)">We couldn’t find anything related to your keywords!</p>
                                                <a class="non-style-link" href="schedule.php"><button class="login-btn btn-primary-soft btn" style="display: flex;justify-content: center;align-items: center;margin-left:20px;">&nbsp; Show all Sessions &nbsp;</button>
                                                </a>
                                                </center>
                                                <br><br><br><br>
                                                </td>
                                            </tr>';
                                            } else {
                                            foreach ($scheduleList as $row) {
                                                echo '<tr>
                                                    <td style="padding:20px;">&nbsp;' . htmlspecialchars(substr($row['title'], 0, 30)) . '</td>
                                                    <td>' . htmlspecialchars(substr($row['docname'], 0, 20)) . '</td>
                                                    <td style="text-align:center;">' . htmlspecialchars(substr($row['scheduledate'], 0, 10)) . ' ' . htmlspecialchars(substr($row['scheduletime'], 0, 5)) . '</td>
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
                            <tr>
                                <td>
                                    <center>
                                        <a href="appointment.php" class="non-style-link"><button class="btn-primary btn" style="width:85%">Show all Appointments</button></a>
                                    </center>
                                </td>
                                <td>
                                    <center>
                                        <a href="schedule.php" class="non-style-link"><button class="btn-primary btn" style="width:85%">Show all Sessions</button></a>
                                    </center>
                                </td>
                            </tr>
                        </table>
                    </td>			
            </table>	
        </div>
		
		
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