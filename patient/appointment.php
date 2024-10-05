<?php
session_start();

if (isset($_SESSION["user"])) {
    if (empty($_SESSION["user"]) || $_SESSION['usertype'] != 'p') {
        header("location: ../login.php");
        exit();
    } else {
        $useremail = $_SESSION["user"];
    }
} else {
    header("location: ../login.php");
    exit();
}

// Import database
include("../connection.php");

// Fetch patient data
$patientRef = $database->getReference('patients')->orderByChild('email')->equalTo($useremail);
$patientSnapshot = $patientRef->getSnapshot();
$patientData = $patientSnapshot->getValue();

if ($patientData) {
    $userfetch = array_values($patientData)[0]; // Get the first patient record
    $userid = $userfetch["type"]; //BAGUHIN
    $username = $userfetch["fname"];
} else {
    // Handle the case where no patient data is found
    echo "No patient data found.";
    exit();
}

// Prepare the base query for appointments
$appointmentsRef = $database->getReference('appointments')
    ->orderByChild('id')
    ->equalTo($userid);

// Additional filters based on POST data
if ($_POST) {
    if (!empty($_POST["sheduledate"])) {
        $sheduledate = $_POST["sheduledate"];
        $appointmentsRef->orderByChild('scheduledate')->equalTo($sheduledate);
    }
}

// Fetch appointment data
$appointmentSnapshot = $appointmentsRef->getSnapshot();
$appointmentData = $appointmentSnapshot->getValue();

if ($appointmentData) {
    // Iterate through appointments and get related schedule and doctor info
    foreach ($appointmentData as $appointment) {
        // Fetch the related schedule and doctor info
        $scheduleRef = $database->getReference('schedule/' . $appointment['scheduleid']);
        $scheduleData = $scheduleRef->getSnapshot()->getValue();

        if ($scheduleData) {
            $doctorRef = $database->getReference('doctor/' . $scheduleData['docid']);
            $doctorData = $doctorRef->getSnapshot()->getValue();

            // Output appointment details
            echo "Appointment ID: " . $appointment['appoid'] . "<br/>";
            echo "Schedule Title: " . $scheduleData['title'] . "<br/>";
            echo "Doctor Name: " . $doctorData['docname'] . "<br/>";
            echo "Scheduled Date: " . $scheduleData['scheduledate'] . "<br/>";
            echo "Scheduled Time: " . $scheduleData['scheduletime'] . "<br/>";
            echo "Appointment Number: " . $appointment['apponum'] . "<br/>";
            echo "Appointment Date: " . $appointment['appodate'] . "<br/><br/>";
        }
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
        
    <title>Appointments</title>
    <style>
        .popup{
            animation: transitionIn-Y-bottom 0.5s;
        }
        .sub-table{
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
        .overlay {
            display: none;
            position: fixed;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.7);
            z-index: 1000;
        }
        .overlay .popup {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background: #fff;
            padding: 20px;
            border-radius: 8px;
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
                    <td class="menu-btn">
                        <a href="index.php" class="non-style-link-menu "><div><p class="menu-text">Home</p></a></div></a>
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
                    <td class="menu-btn menu-active">
                        <a href="appointment.php" class="non-style-link-menu non-style-link-menu-active"><div><p class="menu-text">My Bookings</p></a></div>
                    </td>
                </tr>
                <tr class="menu-row" >
                    <td class="menu-btn">
                        <a href="settings.php" class="non-style-link-menu"><div><p class="menu-text">Settings</p></a></div>
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
                        <p style="font-size: 23px;padding-left:12px;font-weight: 600;">My Bookings history</p>
                                           
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
                    
                        <p class="heading-main12" style="margin-left: 45px;font-size:18px;color:rgb(49, 49, 49)">My Bookings 
                        (<?php 
                            // Fetch appointments from Firebase
                            $appointmentsRef = $database->getReference('appointments');
                            $appointments = $appointmentsRef->getValue();

                            if ($appointments) {
                                // Count the number of appointments
                                $numRows = count($appointments);
                                
                                // Echo the number of rows
                                echo $numRows;
                            } else {
                                // If there are no appointments
                                echo "0"; // Or handle it as needed
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
                        
                    <td width="12%">
                        <input type="submit"  name="filter" value=" Filter" class=" btn-primary-soft btn button-icon btn-filter"  style="padding: 15px; margin :0;width:100%">
                        </form>
                    </td>

                    </tr>
                            </table>

                        </center>
                    </td>
                    
                </tr>
                
               
                  
                <tr>
                   <td colspan="4">
                       <center>
                        <div class="abc scroll">
                        <table width="93%" class="sub-table scrolldown" border="0" style="border:none">
                        
                        <tbody>
                        
                            <?php
                              $appointmentsRef = $database->getReference('appointments');
                              $appointments = $appointmentsRef->getValue();
                              
                              if (empty($appointments)) {
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
                                  foreach ($appointments as $appoid => $appointment) {
                                      $scheduleid = $appointment['scheduleid'] ?? '';
                                      $title = $appointment['title'] ?? '';
                                      $docname = $appointment['docname'] ?? '';
                                      $scheduledate = $appointment['scheduledate'] ?? '';
                                      $scheduletime = $appointment['scheduletime'] ?? '';
                                      $apponum = $appointment['apponum'] ?? '';
                                      $appodate = $appointment['appodate'] ?? '';
                              
                                      echo '<td style="width: 25%;">
                                              <div class="dashboard-items search-items">
                                                  <div style="width:100%;">
                                                      <div class="h3-search">
                                                          Booking Date: '.substr($appodate,0,30).'<br>
                                                          Reference Number: OC-000-'.$appoid.'
                                                      </div>
                                                      <div class="h1-search">'.substr($title,0,21).'<br></div>
                                                      <div class="h3-search">
                                                          Appointment Number:<div class="h1-search">0'.$apponum.'</div>
                                                      </div>
                                                      <div class="h3-search">'.substr($docname,0,30).'</div>
                                                      <div class="h4-search">
                                                          Scheduled Date: '.$scheduledate.'<br>Starts: <b>@'.substr($scheduletime,0,5).'</b> (24h)
                                                      </div>
                                                      <br>
                                                      <a href="?action=drop&id='.$appoid.'&title='.$title.'&doc='.$docname.'"><button class="login-btn btn-primary-soft btn" style="padding-top:11px;padding-bottom:11px;width:100%"><font class="tn-in-text">Cancel Booking</font></button></a>
                                                  </div>
                                              </div>
                                          </td>';
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
    if ($_GET) {
        $id = $_GET["id"];
        $action = $_GET["action"];
    
        if ($action == 'booking-added') {
            echo '
            <div id="popup1" class="overlay">
                <div class="popup">
                <center>
                <br><br>
                    <h2>Booking Successfully.</h2>
                    <a class="close" href="appointment.php">&times;</a>
                    <div class="content">
                        Your Appointment number is ' . $id . '.<br><br>
                    </div>
                    <div style="display: flex;justify-content: center;">
                        <a href="appointment.php" class="non-style-link">
                            <button class="btn-primary btn" style="display: flex;justify-content: center;align-items: center;margin:10px;padding:10px;">
                                <font class="tn-in-text">&nbsp;&nbsp;OK&nbsp;&nbsp;</font>
                            </button>
                        </a>
                        <br><br><br><br>
                    </div>
                </center>
            </div>
            </div>';
        } elseif ($action == 'drop') {
            $title = $_GET["title"];
            $docname = $_GET["doc"];
    
            echo '
            <div id="popup1" class="overlay">
                <div class="popup">
                <center>
                    <h2>Are you sure?</h2>
                    <a class="close" href="appointment.php">&times;</a>
                    <div class="content">
                        You want to Cancel this Appointment?<br><br>
                        Session Name: &nbsp;<b>' . substr($title, 0, 40) . '</b><br>
                        Doctor name&nbsp; : <b>' . substr($docname, 0, 40) . '</b><br><br>
                    </div>
                    <div style="display: flex;justify-content: center;">
                        <a href="delete-appointment.php?id=' . $id . '" class="non-style-link">
                            <button class="btn-primary btn" style="display: flex;justify-content: center;align-items: center;margin:10px;padding:10px;">
                                <font class="tn-in-text">&nbsp;Yes&nbsp;</font>
                            </button>
                        </a>&nbsp;&nbsp;&nbsp;
                        <a href="appointment.php" class="non-style-link">
                            <button class="btn-primary btn" style="display: flex;justify-content: center;align-items: center;margin:10px;padding:10px;">
                                <font class="tn-in-text">&nbsp;&nbsp;No&nbsp;&nbsp;</font>
                            </button>
                        </a>
                    </div>
                </center>
            </div>
            </div>';
        } elseif ($action == 'view') {
            // Fetch doctor details from Firebase
            $doctorRef = $database->getReference('doctors/' . $id);
            $doctor = $doctorRef->getValue();
    
            if ($doctor) {
                $name = $doctor["docname"];
                $email = $doctor["docemail"];
                $spe = $doctor["specialties"];
                $nic = $doctor['docnic'];
                $tele = $doctor['doctel'];
    
                // Fetch specialties name
                $specialtyRef = $database->getReference('specialties/' . $spe);
                $specialty = $specialtyRef->getValue();
                $spcil_name = $specialty ? $specialty["sname"] : 'N/A';
    
                echo '
                <div id="popup1" class="overlay">
                    <div class="popup">
                    <center>
                        <h2></h2>
                        <a class="close" href="doctors.php">&times;</a>
                        <div class="content">
                            eDoc Web App<br>
                        </div>
                        <div style="display: flex;justify-content: center;">
                        <table width="80%" class="sub-table scrolldown add-doc-form-container" border="0">
                            <tr>
                                <td>
                                    <p style="padding: 0;margin: 0;text-align: left;font-size: 25px;font-weight: 500;">View Details.</p><br><br>
                                </td>
                            </tr>
                            <tr>
                                <td class="label-td" colspan="2">
                                    <label for="name" class="form-label">Name: </label>
                                </td>
                            </tr>
                            <tr>
                                <td class="label-td" colspan="2">
                                    ' . $name . '<br><br>
                                </td>
                            </tr>
                            <tr>
                                <td class="label-td" colspan="2">
                                    <label for="Email" class="form-label">Email: </label>
                                </td>
                            </tr>
                            <tr>
                                <td class="label-td" colspan="2">
                                    ' . $email . '<br><br>
                                </td>
                            </tr>
                            <tr>
                                <td class="label-td" colspan="2">
                                    <label for="nic" class="form-label">NIC: </label>
                                </td>
                            </tr>
                            <tr>
                                <td class="label-td" colspan="2">
                                    ' . $nic . '<br><br>
                                </td>
                            </tr>
                            <tr>
                                <td class="label-td" colspan="2">
                                    <label for="Tele" class="form-label">Telephone: </label>
                                </td>
                            </tr>
                            <tr>
                                <td class="label-td" colspan="2">
                                    ' . $tele . '<br><br>
                                </td>
                            </tr>
                            <tr>
                                <td class="label-td" colspan="2">
                                    <label for="spec" class="form-label">Specialties: </label>
                                </td>
                            </tr>
                            <tr>
                                <td class="label-td" colspan="2">
                                    ' . $spcil_name . '<br><br>
                                </td>
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
            } else {
                echo "Doctor not found.";
            }
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
