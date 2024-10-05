<?php 
    // Import database
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
        
    <title>Sessions</title>
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
                                <td width="30%" style="padding-left:20px">
                                    <img src="../img/user.png" alt="" width="100%" style="border-radius:50%">
                                </td>
                                <td style="padding:0px;margin:0px;">
                                    <p class="profile-title"><?php echo substr($username, 0, 13); ?>..</p>
                                    <p class="profile-subtitle"><?php echo substr($useremail, 0, 22); ?></p>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2">
                                    <a href="../logout.php"><input type="button" value="Log out" class="logout-btn btn-primary-soft btn"></a>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <!-- Menu items -->
                <tr class="menu-row">
                    <td class="menu-btn">
                        <a href="index.php" class="non-style-link-menu"><div><p class="menu-text">Home</p></div></a>
                    </td>
                </tr>
                <tr class="menu-row">
                    <td class="menu-btn">
                        <a href="doctors.php" class="non-style-link-menu"><div><p class="menu-text">All Doctors</p></div></a>
                    </td>
                </tr>
                <tr class="menu-row">
                    <td class="menu-btn menu-active">
                        <a href="schedule.php" class="non-style-link-menu non-style-link-menu-active"><div><p class="menu-text">Scheduled Sessions</p></div></a>
                    </td>
                </tr>
                <tr class="menu-row">
                    <td class="menu-btn">
                        <a href="appointment.php" class="non-style-link-menu"><div><p class="menu-text">My Bookings</p></div></a>
                    </td>
                </tr>
                <tr class="menu-row">
                    <td class="menu-btn">
                        <a href="settings.php" class="non-style-link-menu"><div><p class="menu-text">Settings</p></div></a>
                    </td>
                </tr>
            </table>
        </div>
        
        <div class="dash-body">
            <table border="0" width="100%" style="border-spacing: 0; margin: 0; padding: 0; margin-top: 25px;">
                <tr>
                    <td width="13%">
                        <a href="schedule.php"><button class="login-btn btn-primary-soft btn btn-icon-back" style="padding-top: 11px; padding-bottom: 11px; margin-left: 20px; width: 125px"><font class="tn-in-text">Back</font></button></a>
                    </td>
                    <td>
                        <form action="schedule.php" method="post" class="header-search">
                            <input type="search" name="search" class="input-text header-searchbar" placeholder="Search Doctor name or Email or Date (YYYY-MM-DD)" list="doctors">&nbsp;&nbsp;
                            <?php
                            echo '<datalist id="doctors">';
                            $doctors = $database->getReference('doctors')->getValue();
                            $schedules = $database->getReference('schedules')->getValue();

                            if ($doctors) {
                                foreach ($doctors as $doc) {
                                    echo "<option value='{$doc['docname']}'><br/>";
                                }
                            }

                            if ($schedules) {
                                foreach ($schedules as $schedule) {
                                    echo "<option value='{$schedule['title']}'><br/>";
                                }
                            }

                            echo '</datalist>';
                            ?>
                            <input type="submit" value="Search" class="login-btn btn-primary btn" style="padding-left: 25px; padding-right: 25px; padding-top: 10px; padding-bottom: 10px;">
                        </form>
                    </td>
                    <td width="15%">
                        <p style="font-size: 14px; color: rgb(119, 119, 119); padding: 0; margin: 0; text-align: right;">
                            Today's Date
                        </p>
                        <p class="heading-sub12" style="padding: 0; margin: 0;">
                            <?php echo date('Y-m-d'); // Change to your desired date format ?>
                        </p>
                    </td>
                    <td width="10%">
                        <button class="btn-label" style="display: flex; justify-content: center; align-items: center;"><img src="../img/calendar.svg" width="100%"></button>
                    </td>
                </tr>
                <tr>
                    <td colspan="4" style="padding-top:10px; width: 100%;">
                        <!-- Placeholder for content -->
                    </td>
                </tr>
                <tr>
                    <td colspan="4">
                       <center>
                        <div class="abc scroll">
                            <table width="100%" class="sub-table scrolldown" border="0" style="padding: 50px; border:none">
                                <tbody>
                                <?php
if (isset($_GET["id"])) {
    $id = $_GET["id"];
    $scheduleRef = $database->getReference('schedules')->orderByChild('scheduleid')->equalTo($id)->getValue();

    if (!empty($scheduleRef)) {
        $scheduleData = array_values($scheduleRef)[0]; // Get the first matching schedule
        $scheduleid = $scheduleData["scheduleid"];
        $title = $scheduleData["title"];
        $docid = $scheduleData["docid"];

        // Fetch doctor details
        $doctorRef = $database->getReference('doctors')->orderByChild('docid')->equalTo($docid)->getValue();
        $doctorData = array_values($doctorRef)[0];
        $docname = $doctorData["docname"];
        $docemail = $doctorData["docemail"];
        $scheduledate = $scheduleData["scheduledate"];
        $scheduletime = $scheduleData["scheduletime"];

        // Fetch existing appointments
        $appointmentsRef = $database->getReference('appointments')->orderByChild('scheduleid')->equalTo($scheduleid)->getValue();
        $apponum = (count($appointmentsRef) + 1);

        echo '
            <form action="booking-complete.php" method="post">
                <input type="hidden" name="scheduleid" value="'.$scheduleid.'">
                <input type="hidden" name="apponum" value="'.$apponum.'">
                <input type="hidden" name="date" value="'.date('Y-m-d').'">
                <table width="100%" class="sub-table scrolldown" border="0" style="padding: 50px; border:none">
                    <tbody>
                        <tr>
                            <td style="width: 50%;" rowspan="2">
                                <div class="dashboard-items search-items">
                                    <div style="width:100%">
                                        <div class="h1-search" style="font-size:25px;">
                                            Session Details
                                        </div><br><br>
                                        <div class="h3-search" style="font-size:18px; line-height:30px;">
                                            Doctor name:  &nbsp;&nbsp;<b>'.$docname.'</b><br>
                                            Doctor Email:  &nbsp;&nbsp;<b>'.$docemail.'</b>
                                        </div>
                                        <div class="h3-search" style="font-size:18px;">
                                            Session Title: '.$title.'<br>
                                            Session Scheduled Date: '.$scheduledate.'<br>
                                            Session Starts: '.$scheduletime.'<br>
                                        </div>
                                        <br>
                                    </div>
                                </div>
                            </td>
                            <td style="width: 25%;">
                                <div class="dashboard-items search-items">
                                    <div style="width:100%; padding-top: 15px; padding-bottom: 15px;">
                                        <div class="h1-search" style="font-size:20px; line-height: 35px; margin-left:8px; text-align:center;">
                                            Your Appointment Number
                                        </div>
                                        <center>
                                            <div class="dashboard-icons" style="margin-left: 0px; width:90%; font-size:70px; font-weight:800; text-align:center; color:var(--btnnictext); background-color: var(--btnice);">'.$apponum.'</div>
                                        </center>
                                    </div><br><br>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <input type="submit" class="login-btn btn-primary btn btn-book" style="margin-left:10px; padding-left: 25px; padding-right: 25px; padding-top: 10px; padding-bottom: 10px; width:95%; text-align: center;" value="Book now" name="booknow">
                            </td>
                        </tr>
                    </tbody>
                </table>
            </form>
        ';
    } else {
        echo "<p>No session details found.</p>";
    }
} else {
    echo "<p>No session ID provided.</p>";
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