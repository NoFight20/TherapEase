<?php
    session_start();
    date_default_timezone_set('Asia/Kolkata');
    
    // Set today's date
    $today = date('Y-m-d');

    // Check if the user is logged in and is a patient
    if(isset($_SESSION["user"])){
        if(($_SESSION["user"]) == "" || $_SESSION['usertype'] != 'p'){
            header("location: ../login.php");
        } else {
            $useremail = $_SESSION["user"];
        }
    } else {
        header("location: ../login.php");
    }
    include("../connection.php");

    // Firebase query to get patient data based on email
    try {
        $reference = $database
            ->getReference('patients')
            ->orderByChild('email')  
            ->equalTo($useremail)
            ->getSnapshot();

        // Fetch the first result
        $userfetch = $reference->getValue();

        if ($userfetch) {
            // Since Firebase returns an associative array, we take the first item
            foreach($userfetch as $key => $value) {
                $username = $value['fname'];
            }
        } else {
            // Handle the case where no user is found
            echo "No user found.";
        }

    } catch (\Kreait\Firebase\Exception\DatabaseException $e) {
        echo "Error querying the database: " . $e->getMessage();
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
        
    <title>Dashboard</title>
    <style>
        .dashbord-tables{
            animation: transitionIn-Y-over 0.5s;
        }
        .filter-container{
            animation: transitionIn-Y-bottom  0.5s;
        }
        .sub-table,.anime{
            animation: transitionIn-Y-bottom 0.5s;
        }
		/* Mobile Header */
        .mobile-header {
            display: none;
            background-color: lightgreen; /* Set the background color for the header */
            color: white;
            padding: 15px;
            text-align: center;
            font-size: 18px;
            position: fixed;
            top: 0;
            width: 100%;
            z-index: 1002;
        }
        .popup .close {
            font-size: 30px;
            color: #000;
            text-decoration: none;
        }

        /* Hamburger Menu */
        #hamburger-menu {
            display: none;
            width: 30px;
            height: 30px;
            position: fixed;
            top: 15px;
            left: 15px;
            z-index: 1003;
            cursor: pointer;
            flex-direction: column;
            justify-content: space-between;
            align-items: center;
        }

        #hamburger-menu .bar {
            width: 100%;
            height: 3px;
            background-color: #333;
            transition: all 0.4s ease;
        }

        #hamburger-menu.active .bar:nth-child(1) {
            transform: rotate(-45deg) translate(-5px, 5px);
        }

        #hamburger-menu.active .bar:nth-child(2) {
            opacity: 0;
        }

        #hamburger-menu.active .bar:nth-child(3) {
            transform: rotate(45deg) translate(-5px, -5px);
        }

        /* Responsive Design */
        @media screen and (max-width: 768px) {
            #hamburger-menu {
                display: flex;
            }

            .mobile-header {
                display: block;
            }

            .menu {
                display: none;
                position: fixed;
                left: -250px;
                top: 50px; /* Adjust to account for mobile header */
                width: 250px;
                height: 100%;
                background: lightgreen;
                transition: left 0.3s ease;
                z-index: 1001;
            }

            .menu.active {
                display: block;
                left: 0;
            }

            .dash-body {
                margin-top: 50px; /* Adjust to account for mobile header */
                margin-left: 0;
                transition: margin-left 0.3s ease;
            }

            .menu.active + .dash-body {
                margin-left: 250px;
            }
        }
</style>
    
    
</head>
<body>
    <!-- Mobile Header -->
    <div class="mobile-header"></div>

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
                                    <p class="profile-title"><?php echo substr($username,0,13)  ?>..</p>
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
                    <td class="menu-btn menu-active" >
                        <a href="index.php" class="non-style-link-menu non-style-link-menu-active"><div><p class="menu-text">Home</p></a></div></a>
                    </td>
                </tr>
                <tr class="menu-row">
                    <td class="menu-btn">
                        <a href="doctors.php" class="non-style-link-menu"><div><p class="menu-text">All Doctors</p></a></div>
                    </td>
                </tr>
                
                <tr class="menu-row" >
                    <td class="menu-btn">
                        <a href="schedule.php" class="non-style-link-menu"><div><p class="menu-text">Scheduled Sessions</p></div></a>
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
        <div class="dash-body" style="margin-top: 15px">
            <table border="0" width="100%" style=" border-spacing: 0;margin:0;padding:0;" >
                        
                        <tr >
                            
                            <td colspan="1" class="nav-bar" >
                            <p style="font-size: 23px;padding-left:12px;font-weight: 600;margin-left:20px;">Home</p>
                          
                            </td>
                            <td width="25%">

                            </td>
                            <td width="15%">
                                <p style="font-size: 14px;color: rgb(119, 119, 119);padding: 0;margin: 0;text-align: right;">
                                    Today's Date
                                </p>
                                <p class="heading-sub12" style="padding: 0;margin: 0;">
                                    <?php 
                                try {
                                    // Query for patient data
                                    $patientReference = $database->getReference('patient'); // 'patient' is the node
                                    $patientSnapshot = $patientReference->getSnapshot();
                                    $patientData = $patientSnapshot->getValue();
                                    // $patientData will contain all patient records
                            
                                    // Query for doctor data
                                    $doctorReference = $database->getReference('doctor'); // 'doctor' is the node
                                    $doctorSnapshot = $doctorReference->getSnapshot();
                                    $doctorData = $doctorSnapshot->getValue();
                                    // $doctorData will contain all doctor records
                            
                                    // Query for appointments where the date is greater than or equal to today's date
                                    $appointmentReference = $database->getReference('appointment')
                                                                     ->orderByChild('appodate') // Assuming 'appodate' is the field for appointment date
                                                                     ->startAt($today); // Filter for appointments on or after today
                                    $appointmentSnapshot = $appointmentReference->getSnapshot();
                                    $appointmentData = $appointmentSnapshot->getValue();
                                    // $appointmentData will contain appointments from today onwards
                            
                                    // Query for schedule where the date matches today's date
                                    $scheduleReference = $database->getReference('schedule')
                                                                  ->orderByChild('scheduledate') // Assuming 'scheduledate' is the field for schedule date
                                                                  ->equalTo($today); // Filter for schedules on today's date
                                    $scheduleSnapshot = $scheduleReference->getSnapshot();
                                    $scheduleData = $scheduleSnapshot->getValue();
                                    // $scheduleData will contain today's schedule
                            
                                } catch (\Kreait\Firebase\Exception\DatabaseException $e) {
                                    echo "Error querying the database: " . $e->getMessage();
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
                        
                    <center>
                    <table class="filter-container doctor-header patient-header" style="border: none;width:95%" border="0" >
                    <tr>
                        <td >
                            <h3>Welcome!</h3>
                            <h1><?php echo $username  ?>.</h1>
                            <p>Haven't any idea about doctors? no problem let's jumping to 
                                <a href="doctors.php" class="non-style-link"><b>"All Doctors"</b></a> section or 
                                <a href="schedule.php" class="non-style-link"><b>"Sessions"</b> </a><br>
                                Track your past and future appointments history.<br>Also find out the expected arrival time of your doctor or medical consultant.<br><br>
                            </p>
                            
                            <h3>Channel a Doctor Here</h3>
                            <form action="schedule.php" method="post" style="display: flex">

                                <input type="search" name="search" class="input-text " placeholder="Search Doctor and We will Find The Session Available" list="doctors" style="width:45%;">&nbsp;&nbsp;
                                
                                <?php
                                     // Fetch doctors from Firebase
                                    $doctorsRef = $database->getReference('doctor'); // Assuming your doctors data is stored under 'doctors'
                                    $doctorsSnapshot = $doctorsRef->getSnapshot();
                                    $doctorsData = $doctorsSnapshot->getValue();

                                    echo '<datalist id="doctor">';

                                    // Check if doctors exist and loop through them
                                    if ($doctorsData) {
                                        foreach ($doctorsData as $doctor) {
                                            $docName = $doctor['name'];
                                            $docEmail = $doctor['email'];

                                            // Create options for datalist
                                            echo "<option value='$docName' data-email='$docEmail'><br/>";
                                        }
                                    }

                                    echo '</datalist>';
                                ?>
                                
                           
                                <input type="Submit" value="Search" class="login-btn btn-primary btn" style="padding-left: 25px;padding-right: 25px;padding-top: 10px;padding-bottom: 10px;">
                            
                            <br>
                            <br>
                            
                        </td>
                    </tr>
                    </table>
                    </center>
                    
                </td>
                </tr>
                <tr>
                    <td colspan="4">
                        <table border="0" width="100%"">
                            <tr>
                                <td width="50%">

                                    




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
                                                                    // Reference to the doctors path
                                                                    $doctorsRef = $database->getReference('doctors');
                                                                    $doctorSnapshot = $doctorsRef->getSnapshot();

                                                                    // Get the value and count the number of doctors
                                                                    $doctorData = $doctorSnapshot->getValue();

                                                                    if ($doctorData) {
                                                                        // Count the number of doctors
                                                                        $numDoctors = count($doctorData);
                                                                        echo $numDoctors;
                                                                    } else {
                                                                        echo "No doctors";
                                                                    } 
                                                                    ?>
                                                                </div><br>
                                                                <div class="h3-dashboard">
                                                                    All Doctors &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
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
                                                                    $patientsRef = $database->getReference('patients');
                                                                    $patientSnapshot = $patientsRef->getSnapshot();

                                                                    // Get the value and count the number of patients
                                                                    $patientData = $patientSnapshot->getValue();

                                                                    if ($patientData) {
                                                                        // Count the number of patients
                                                                        $numPatients = count($patientData);
                                                                        echo $numPatients;
                                                                    } else {
                                                                        echo "No patients";
                                                                    } 
                                                                    ?>
                                                                </div><br>
                                                                <div class="h3-dashboard">
                                                                    All Patients &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                                                </div>
                                                        </div>
                                                                <div class="btn-icon-back dashboard-icons" style="background-image: url('../img/icons/patients-hover.svg');"></div>
                                                    </div>
                                                </td>
                                                </tr>
                                                <tr>
                                                <td style="width: 25%;">
                                                    <div  class="dashboard-items"  style="padding:20px;margin:auto;width:95%;display: flex; ">
                                                        <div>
                                                                <div class="h1-dashboard" >
                                                                    <?php   
                                                                    $appointmentsRef = $database->getReference('appointments');
                                                                    $appointmentSnapshot = $appointmentsRef->getSnapshot();

                                                                    // Get the value and count the number of appointments
                                                                    $appointmentData = $appointmentSnapshot->getValue();

                                                                    if ($appointmentData) {
                                                                        // Count the number of appointments
                                                                        $numAppointments = count($appointmentData);
                                                                        echo $numAppointments;
                                                                    } else {
                                                                        echo "No appointments";
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
                                                    <div  class="dashboard-items"  style="padding:20px;margin:auto;width:95%;display: flex;padding-top:21px;padding-bottom:21px;">
                                                        <div>
                                                                <div class="h1-dashboard">
                                                                    <?php 
                                                                    // Reference to the schedule path
                                                                    $scheduleRef = $database->getReference('schedule');
                                                                    $scheduleSnapshot = $scheduleRef->getSnapshot();

                                                                    // Get the value and count the number of schedules
                                                                    $scheduleData = $scheduleSnapshot->getValue();

                                                                    if ($scheduleData) {
                                                                        // Count the number of schedules
                                                                        $numSchedules = count($scheduleData);
                                                                        echo $numSchedules;
                                                                    } else {
                                                                        echo "No schedules";
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
                                <td>


                            
                                    <p style="font-size: 20px;font-weight:600;padding-left: 40px;" class="anime">Your Upcoming Booking</p>
                                    <center>
                                        <div class="abc scroll" style="height: 250px;padding: 0;margin: 0;">
                                        <table width="85%" class="sub-table scrolldown" border="0" >
                                        <thead>
                                            
                                        <tr>
                                        <th class="table-headin">
                                                    
                                                
                                                    Appoint. Number
                                                    
                                                    </th>
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
                                            // Fetch all appointments for the user
                                                $appointmentsRef = $database->getReference('appointments')
                                                ->orderByChild('id'); // Assuming you have a 'pid' field in appointments
                                                //->equalTo($userid);
                                            $appointmentsSnapshot = $appointmentsRef->getSnapshot();
                                            $appointmentsData = $appointmentsSnapshot->getValue();

                                            // Fetch all schedules
                                            $schedulesRef = $database->getReference('schedules');
                                            $schedulesSnapshot = $schedulesRef->getSnapshot();
                                            $schedulesData = $schedulesSnapshot->getValue();

                                            // Fetch all doctors
                                            $doctorsRef = $database->getReference('doctors');
                                            $doctorsSnapshot = $doctorsRef->getSnapshot();
                                            $doctorsData = $doctorsSnapshot->getValue();

                                            // Prepare to display results
                                            $results = [];

                                            if ($appointmentsData) {
                                                foreach ($appointmentsData as $appointment) {
                                                    $scheduleId = $appointment['scheduleid'];

                                                    // Find the corresponding schedule
                                                    if (isset($schedulesData[$scheduleId])) {
                                                        $schedule = $schedulesData[$scheduleId];

                                                        // Find the corresponding doctor
                                                        $doctorId = $schedule['docid'];
                                                        $doctorName = isset($doctorsData[$doctorId]) ? $doctorsData[$doctorId]['docname'] : 'Unknown Doctor';

                                                        // Check if the schedule date is today or later
                                                        if ($schedule['scheduledate'] >= $today) {
                                                            $results[] = [
                                                                'apponum' => $appointment['apponum'],
                                                                'title' => $appointment['title'],
                                                                'docname' => $doctorName,
                                                                'scheduledate' => $schedule['scheduledate'],
                                                                'scheduletime' => $schedule['scheduletime']
                                                            ];
                                                        }
                                                    }
                                                }
                                            }

                                            if (empty($results)) {
                                                echo '<tr>
                                                        <td colspan="4">
                                                        <br><br><br><br>
                                                        <center>
                                                        <img src="../img/notfound.svg" width="25%">
                                                        <br>
                                                        <p class="heading-main12" style="margin-left: 45px;font-size:20px;color:rgb(49, 49, 49)">Nothing to show here!</p>
                                                        <a class="non-style-link" href="schedule.php"><button class="login-btn btn-primary-soft btn" style="display: flex;justify-content: center;align-items: center;margin-left:20px;">&nbsp; Channel a Doctor &nbsp;</button>
                                                        </a>
                                                        </center>
                                                        <br><br><br><br>
                                                        </td>
                                                    </tr>';
                                            } else {
                                                foreach ($results as $row) {
                                                    echo '<tr>
                                                            <td style="padding:30px;font-size:25px;font-weight:700;"> &nbsp;' . $row['apponum'] . '</td>
                                                            <td style="padding:20px;"> &nbsp;' . substr($row['title'], 0, 30) . '</td>
                                                            <td>' . substr($row['docname'], 0, 20) . '</td>
                                                            <td style="text-align:center;">' . substr($row['scheduledate'], 0, 10) . ' ' . substr($row['scheduletime'], 0, 5) . '</td>
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
                    </td>
                <tr>
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