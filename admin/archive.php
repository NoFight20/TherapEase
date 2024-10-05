<?php
session_start();

if (!isset($_SESSION["user"]) || $_SESSION['usertype'] != 'a') {
    header("location: ../login.php");
    exit();
}

include("../connection.php");

if (isset($_GET['action']) && $_GET['action'] == 'retrieve' && isset($_GET['id'])) {
    $pid = intval($_GET['id']);

    // Validate the patient ID
    if ($pid > 0) {
        // Update the archived status to 0 (retrieve the patient)
        $sql = "UPDATE patient SET archived = 0 WHERE pid = ?";
        if ($stmt = $database->prepare($sql)) {
            $stmt->bind_param("i", $pid);
            if ($stmt->execute()) {
                // Redirect to the archive page with a success message
                header("Location: archive.php?message=Patient retrieved successfully");
                exit();
            } else {
                echo "Error: " . $stmt->error;
            }
        } else {
            echo "Error: " . $database->error;
        }
    } else {
        echo "Invalid patient ID.";
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
        
    <title>Dashboard</title>
    <style>
        .dashbord-tables{
            animation: transitionIn-Y-over 0.5s;
        }
        .filter-container{
            animation: transitionIn-Y-bottom  0.5s;
        }
        .sub-table{
            animation: transitionIn-Y-bottom 0.5s;
        }
    </style>
</head>

<body>
    <!-- Mobile Header -->
    <div class="mobile-header">Archive</div>

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
                    <td class="menu-btn menu-active">
                        <a href="archive.php" class="non-style-link-menu non-style-link-menu-active"><div><p class="menu-text">Archives</p></a></div>
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
            <table border="0" width="100%" style="border-spacing: 0;margin:0;padding:0;margin-top:25px;">
                <tr>
                    <td width="13%">
                        <a href="archive.php"><button class="login-btn btn-primary-soft btn btn-icon-back" style="padding-top:11px;padding-bottom:11px;margin-left:20px;width:125px"><font class="tn-in-text">Back</font></button></a>
                    </td>
                    <td>
                        <form action="" method="post" class="header-search">
                            <input type="search" name="search" class="input-text header-searchbar" placeholder="Search Patient name or Email" list="patient">&nbsp;&nbsp;
                            <?php
                                echo '<datalist id="archive">';
                                $list11 = $database->query("SELECT pname, pemail FROM patient;");
                                while ($row00 = $list11->fetch_assoc()) {
                                    $d = $row00["pname"];
                                    $c = $row00["pemail"];
                                    echo "<option value='$d'><br/>";
                                    echo "<option value='$c'><br/>";
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
                                date_default_timezone_set('Asia/Kolkata');
                                $date = date('Y-m-d');
                                echo $date;
                            ?>
                        </p>
                    </td>
                    <td width="10%">
                        <button class="btn-label" style="display: flex;justify-content: center;align-items: center;"><img src="../img/calendar.svg" width="100%"></button>
                    </td>
                </tr>
                <tr>
                    <td colspan="4" style="padding-top:10px;">
                        <p class="heading-main12" style="margin-left: 45px;font-size:18px;color:rgb(49, 49, 49)">All Archived (<?php echo $list11->num_rows; ?>)</p>
                    </td>
                </tr>
                <?php
                    if ($_POST) {
                        $keyword = $_POST["search"];
                        $sqlmain = "SELECT * FROM patient WHERE (pemail='$keyword' OR pname='$keyword' OR pname LIKE '$keyword%' OR pname LIKE '%$keyword' OR pname LIKE '%$keyword%') AND archived = 1";
                    } else {
                        $sqlmain = "SELECT * FROM patient WHERE archived = 1 ORDER BY pid DESC";
                    }
                ?>
                <tr>
                    <td colspan="4">
                        <center>
                            <div class="abc scroll">
                                <table width="93%" class="sub-table scrolldown" style="border-spacing:0;">
                                    <thead>
                                        <tr>
                                            <th class="table-headin">Name</th>
                                            <th class="table-headin">Age</th>
                                            <th class="table-headin">Address</th>
                                            <th class="table-headin">Phone No.</th>
                                            <th class="table-headin">Email</th>
                                            <th class="table-headin">Date of Birth</th>
                                            <th class="table-headin">Gender</th>
                                            <th class="table-headin">Civil Status</th>
                                            <th class="table-headin">Events</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                            $result = $database->query($sqlmain);
                                            if ($result->num_rows == 0) {
                                                echo '<tr>
                                                    <td colspan="4">
                                                    <br><br><br><br>
                                                    <center>
                                                    <img src="../img/notfound.svg" width="25%">
                                                    <br>
                                                    <p class="heading-main12" style="margin-left: 45px;font-size:20px;color:rgb(49, 49, 49)">We couldn\'t find anything related to your keywords!</p>
                                                    <a class="non-style-link" href="patient.php"><button class="login-btn btn-primary-soft btn" style="display: flex;justify-content: center;align-items: center;margin-left:20px;">&nbsp; Show all Patients &nbsp;</button></a>
                                                    </center>
                                                    <br><br><br><br>
                                                    </td>
                                                    </tr>';
                                            } else {
                                                while ($row = $result->fetch_assoc()) {
                                                    $pid = $row["pid"];
                                                    $name = $row["pname"];
                                                    $email = $row["pemail"];
                                                    $address = $row["pcity"];
                                                    $gender = $row["pgender"];
                                                    $age = $row["page"];
                                                    $dob = $row["pdob"];
                                                    $tel = $row["ptel"];
                                                    $civil = $row["pcivil"];
                                                    echo '<tr>
                                                        <td>' . substr($name, 0, 35) . '</td>
                                                        <td>' . substr($age, 0, 12) . '</td>
                                                        <td>' . substr($address, 0, 50) . '</td>
                                                        <td>' . substr($tel, 0, 10) . '</td>
                                                        <td>' . substr($email, 0, 20) . '</td>
                                                        <td>' . substr($dob, 0, 10) . '</td>
                                                        <td>' . substr($gender, 0, 12) . '</td>
                                                        <td>' . substr($civil, 0, 20) . '</td>
                                                        <td>
                                                            <div style="display:flex;justify-content: center;">
                                                                <a href="archive.php?action=retrieve&id=' . $pid . '" class="non-style-link">
                                                                    <button class="btn-primary-soft btn button-icon btn-retrieve" style="padding-left: 40px;padding-top: 12px;padding-bottom: 12px;margin-top: 10px;">
                                                                        <font class="tn-in-text">Retrieve</font>
                                                                    </button>
                                                                </a>
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