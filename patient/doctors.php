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

// Fetch patient data from Firebase
$patientRef = $database->getReference('patients')->orderByChild('email')->equalTo($useremail);
$patientSnapshot = $patientRef->getSnapshot();
$patientData = $patientSnapshot->getValue();

if ($patientData) {
    $userfetch = array_values($patientData)[0]; // Get the first record
    //$userid = $userfetch["pid"];
    $username = $userfetch["fname"];
} else {
    // Handle the case where no patient data is found
    echo "No patient data found.";
    exit();
}

// You can now use $userid and $username in your code.
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
        
    <title>Doctors</title>
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
                    <td class="menu-btn menu-active">
                        <a href="doctors.php" class="non-style-link-menu non-style-link-menu-active"><div><p class="menu-text">All Doctors</p></a></div>
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
        <div class="dash-body">
            <table border="0" width="100%" style=" border-spacing: 0;margin:0;padding:0;margin-top:25px; ">
                <tr >
                    <td width="13%">
                        <a href="doctors.php" ><button  class="login-btn btn-primary-soft btn btn-icon-back"  style="padding-top:11px;padding-bottom:11px;margin-left:20px;width:125px"><font class="tn-in-text">Back</font></button></a>
                    </td>
                    <td>
                        
                        <form action="" method="post" class="header-search">

                            <input type="search" name="search" class="input-text header-searchbar" placeholder="Search Doctor name or Email" list="doctors">&nbsp;&nbsp;
                            
                            <?php
                                
                            // Reference to the doctors' data in your Firebase Realtime Database
                            $reference = $database->getReference('doctors');

                            // Fetch doctors' data
                            $doctorData = $reference->getValue();

                            // Start generating the HTML
                            echo '<datalist id="doctors">';

                            // Check if the doctors' data exists and loop through it
                            if (!empty($doctorData)) {
                                foreach ($doctorData as $doctor) {
                                    $docname = $doctor['docname'];
                                    $docemail = $doctor['docemail'];
                                    
                                    echo "<option value='$docname'><br/>";
                                    echo "<option value='$docemail'><br/>";
                                }
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
                        <button  class="btn-label"  style="display: flex;justify-content: center;align-items: center;"><img src="../img/calendar.svg" width="100%"></button>
                    </td>


                </tr>
               
                
                <tr>
                    <td colspan="4" style="padding-top:10px;">
                        <p class="heading-main12" style="margin-left: 45px;font-size:18px;color:rgb(49, 49, 49)">All Doctors 
                            (<?php 
                            // Reference to the doctors node in Firebase
                            $doctorsRef = $database->getReference('doctor');

                            // Fetch doctor data
                            $doctors = $doctorsRef->getValue();

                            // Count the number of doctors (similar to num_rows)
                            $numRows = is_array($doctors) ? count($doctors) : 0;

                            // Output the count
                            echo $numRows;?>)
                        </p>
                    </td>
                    
                </tr>
                <?php
                 if($_POST){
                    $keyword=$_POST["search"];
                    
                    $sqlmain= "select * from doctor where docemail='$keyword' or docname='$keyword' or docname like '$keyword%' or docname like '%$keyword' or docname like '%$keyword%'";
                }else{
                    $sqlmain= "select * from doctor order by docid desc";

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
                                Doctor Name
                                </th>
                                <th class="table-headin">
                                    Email
                                </th>
                                <th class="table-headin">
                                    
                                    Specialties
                                    
                                </th>
                                <th class="table-headin">
                                    
                                    Events
                                    
                                </tr>
                        </thead>
                        <tbody>
                        
                            <?php
                            // Reference to the doctors node in Firebase
                            $doctorsRef = $database->getReference('doctor');

                            // Fetch doctor data
                            $result = $doctorsRef->getValue(); // Retrieve all doctors

                            if (empty($result)) {
                                // If no results, show 'not found' message
                                echo '<tr>
                                    <td colspan="4">
                                    <br><br><br><br>
                                    <center>
                                    <img src="../img/notfound.svg" width="25%">
                                    <br>
                                    <p class="heading-main12" style="margin-left: 45px;font-size:20px;color:rgb(49, 49, 49)">We couldn\'t find anything related to your keywords!</p>
                                    <a class="non-style-link" href="doctors.php"><button class="login-btn btn-primary-soft btn" style="display: flex;justify-content: center;align-items: center;margin-left:20px;">&nbsp; Show all Doctors &nbsp;</button></a>
                                    </center>
                                    <br><br><br><br>
                                    </td>
                                </tr>';
                            } else {
                                // Loop through doctors data from Firebase
                                foreach ($result as $docid => $doctor) {
                                    $name = $doctor['name'];
                                    $email = $doctor['email'];
                                    $spe = $doctor['specialty'];

                                    // Fetch specialties name based on specialties ID from Firebase
                                    $specialtiesRef = $database->getReference('specialties/' . $spe);
                                    $specialtyData = $specialtiesRef->getValue();

                                    echo '<tr>
                                        <td> &nbsp;' . substr($name, 0, 30) . '</td>
                                        <td>' . substr($email, 0, 20) . '</td>
                                        <td>' . substr($spe, 0, 20) . '</td>
                                        <td>
                                            <div style="display:flex;justify-content: center;">
                                                <a href="?action=view&id=' . $docid . '" class="non-style-link">
                                                    <button class="btn-primary-soft btn button-icon btn-view" style="padding-left: 40px;padding-top: 12px;padding-bottom: 12px;margin-top: 10px;">
                                                        <font class="tn-in-text">View</font>
                                                    </button>
                                                </a>
                                                &nbsp;&nbsp;&nbsp;
                                                <a href="?action=session&id=' . $docid . '&name=' . $name . '" class="non-style-link">
                                                    <button class="btn-primary-soft btn button-icon menu-icon-session-active" style="padding-left: 40px;padding-top: 12px;padding-bottom: 12px;margin-top: 10px;">
                                                        <font class="tn-in-text">Sessions</font>
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
    </div>
    <?php 
    if ($_GET) {
        $id = $_GET["id"];
        $action = $_GET["action"];
        if ($action == 'drop') {
            $nameget = $_GET["name"];
            echo '
            <div id="popup1" class="overlay">
                <div class="popup">
                    <center>
                        <h2>Are you sure?</h2>
                        <a class="close" href="doctors.php">&times;</a>
                        <div class="content">
                            You want to delete this record<br>(' . substr($nameget, 0, 40) . ').
                        </div>
                        <div style="display: flex; justify-content: center;">
                            <a href="delete-doctor.php?id=' . $id . '" class="non-style-link">
                                <button class="btn-primary btn" style="margin:10px;padding:10px;">Yes</button>
                            </a>
                            <a href="doctors.php" class="non-style-link">
                                <button class="btn-primary btn" style="margin:10px;padding:10px;">No</button>
                            </a>
                        </div>
                    </center>
                </div>
            </div>';
        } elseif ($action == 'view') {
            $doctorRef = $database->getReference('doctors/' . $id);
            $doctor = $doctorRef->getValue();

            if ($doctor) {
                $name = $doctor['docname'];
                $email = $doctor['docemail'];
                $nic = $doctor['docnic'];
                $tele = $doctor['doctel'];
                $spe = $doctor['specialties'];

                // Fetch specialties from a separate reference if needed
                $specialtyRef = $database->getReference('specialties/' . $spe);
                $specialty = $specialtyRef->getValue();
                $spcil_name = $specialty ? $specialty['sname'] : '';

                echo '
                <div id="popup1" class="overlay">
                    <div class="popup">
                        <center>
                            <h2></h2>
                            <a class="close" href="doctors.php">&times;</a>
                            <div class="content">eDoc Web App<br></div>
                            <div style="display: flex; justify-content: center;">
                                <table width="80%" class="sub-table scrolldown add-doc-form-container" border="0">
                                    <tr>
                                        <td><p style="font-size: 25px; font-weight: 500;">View Details.</p><br><br></td>
                                    </tr>
                                    <tr>
                                        <td class="label-td" colspan="2"><label>Name: </label></td>
                                    </tr>
                                    <tr>
                                        <td class="label-td" colspan="2">' . $name . '<br><br></td>
                                    </tr>
                                    <tr>
                                        <td class="label-td" colspan="2"><label>Email: </label></td>
                                    </tr>
                                    <tr>
                                        <td class="label-td" colspan="2">' . $email . '<br><br></td>
                                    </tr>
                                    <tr>
                                        <td class="label-td" colspan="2"><label>NIC: </label></td>
                                    </tr>
                                    <tr>
                                        <td class="label-td" colspan="2">' . $nic . '<br><br></td>
                                    </tr>
                                    <tr>
                                        <td class="label-td" colspan="2"><label>Telephone: </label></td>
                                    </tr>
                                    <tr>
                                        <td class="label-td" colspan="2">' . $tele . '<br><br></td>
                                    </tr>
                                    <tr>
                                        <td class="label-td" colspan="2"><label>Specialties: </label></td>
                                    </tr>
                                    <tr>
                                        <td class="label-td" colspan="2">' . $spcil_name . '<br><br></td>
                                    </tr>
                                    <tr>
                                        <td colspan="2"><a href="doctors.php"><input type="button" value="OK" class="login-btn btn-primary-soft btn"></a></td>
                                    </tr>
                                </table>
                            </div>
                        </center>
                        <br><br>
                    </div>
                </div>';
            }
        } elseif ($action == 'edit') {
            $doctorRef = $database->getReference('doctors/' . $id);
            $doctor = $doctorRef->getValue();

            if ($doctor) {
                $name = $doctor['docname'];
                $email = $doctor['docemail'];
                $nic = $doctor['docnic'];
                $tele = $doctor['doctel'];
                $spe = $doctor['specialties'];

                $specialtyRef = $database->getReference('specialties/' . $spe);
                $specialty = $specialtyRef->getValue();
                $spcil_name = $specialty ? $specialty['sname'] : '';

                echo '
                <div id="popup1" class="overlay">
                    <div class="popup">
                        <center>
                            <a class="close" href="doctors.php">&times;</a>
                            <div style="display: flex; justify-content: center;">
                                <table width="80%" class="sub-table scrolldown add-doc-form-container" border="0">
                                    <tr>
                                        <td class="label-td" colspan="2">
                                            <form action="edit-doc.php" method="POST" class="add-new-form">
                                                <label>Email: </label>
                                                <input type="hidden" value="' . $id . '" name="id00">
                                                <input type="email" name="email" class="input-text" placeholder="Email Address" value="' . $email . '" required><br>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="label-td" colspan="2"><label>Name: </label></td>
                                    </tr>
                                    <tr>
                                        <td class="label-td" colspan="2">
                                            <input type="text" name="name" class="input-text" placeholder="Doctor Name" value="' . $name . '" required><br>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="label-td" colspan="2"><label>NIC: </label></td>
                                    </tr>
                                    <tr>
                                        <td class="label-td" colspan="2">
                                            <input type="text" name="nic" class="input-text" placeholder="NIC Number" value="' . $nic . '" required><br>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="label-td" colspan="2"><label>Telephone: </label></td>
                                    </tr>
                                    <tr>
                                        <td class="label-td" colspan="2">
                                            <input type="tel" name="tele" class="input-text" placeholder="Telephone Number" value="' . $tele . '" required><br>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="label-td" colspan="2"><label>Choose specialties: (Current ' . $spcil_name . ')</label></td>
                                    </tr>
                                    <tr>
                                        <td class="label-td" colspan="2">
                                            <select name="spec" class="box">';
                $specialtiesRef = $database->getReference('specialties')->getValue();
                foreach ($specialtiesRef as $specialty) {
                    echo '<option value="' . $specialty['id'] . '">' . $specialty['sname'] . '</option>';
                }
                echo '       </select><br><br>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="2">
                                            <input type="submit" value="Save" class="login-btn btn-primary btn">
                                        </td>
                                    </tr>
                                    </form>
                                </table>
                            </div>
                        </center>
                        <br><br>
                    </div>
                </div>';
            }
        } else {
            echo '
            <div id="popup1" class="overlay">
                <div class="popup">
                    <center>
                        <h2>Edit Successfully!</h2>
                        <a class="close" href="doctors.php">&times;</a>
                        <div class="content"></div>
                        <div style="display: flex; justify-content: center;">
                            <a href="doctors.php" class="non-style-link">
                                <button class="btn-primary btn">OK</button>
                            </a>
                        </div>
                        <br><br>
                    </center>
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