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
        
    <title>Doctors</title>
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
    <div class="mobile-header">Doctors</div>

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
                    <td class="menu-btn menu-active">
                        <a href="doctors.php" class="non-style-link-menu non-style-link-menu-active"><div><p class="menu-text">Doctors</p></a></div>
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
                              // Retrieve doctor data from Firebase
                            $doctorsReference = $database->getReference('doctors');
                            $doctorsSnapshot = $doctorsReference->getSnapshot();
                            $doctorsData = $doctorsSnapshot->getValue();

                            echo '<datalist id="doctors">';

                            // Check if data is available
                            if ($doctorsData) {
                                foreach ($doctorsData as $doctor) {
                                    $docName = htmlspecialchars($doctor['docname']);
                                    $docEmail = htmlspecialchars($doctor['docemail']);

                                    // Display name and email as options in datalist
                                    echo "<option value='$docName'><br/>";
                                    echo "<option value='$docEmail'><br/>";
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
               
                <tr >
                    <td colspan="2" style="padding-top:30px;">
                        <p class="heading-main12" style="margin-left: 45px;font-size:20px;color:rgb(49, 49, 49)">Add New Doctor</p>
                    </td>
                    <td colspan="2">
                        <a href="?action=add&id=none&error=0" class="non-style-link"><button  class="login-btn btn-primary btn button-icon"  style="display: flex;justify-content: center;align-items: center;margin-left:75px;background-image: url('../img/icons/add.svg');">Add New</font></button>
                            </a></td>
                </tr>
                <tr>
                    <td colspan="4" style="padding-top:10px;">
                        <p class="heading-main12" style="margin-left: 45px;font-size:18px;color:rgb(49, 49, 49)">All Doctors 
                            (<?php 
                            $numDoctors = $doctorsData ? count($doctorsData) : 0;echo $numDoctors; 
                            ?>)
                        </p>
                    </td>
                    
                </tr>
                <?php
                    if ($_POST) {
                        $keyword = $_POST["search"];
                    
                        // Prepare a reference to the 'doctors' node in Firebase
                        $doctorsReference = $database->getReference('doctors');
                    
                        // Fetch all doctors
                        $doctorsSnapshot = $doctorsReference->getSnapshot();
                        $doctorsData = $doctorsSnapshot->getValue();
                    
                        // Filter doctors based on the keyword (docemail or docname)
                        $filteredDoctors = [];
                        if ($doctorsData) {
                            foreach ($doctorsData as $doctorId => $doctor) {
                                $docEmail = $doctor['docemail'];
                                $docName = $doctor['docname'];
                    
                                // Check if the email or name matches the keyword
                                if (
                                    $docEmail === $keyword ||
                                    $docName === $keyword ||
                                    stripos($docName, $keyword) === 0 || // Starts with
                                    stripos($docName, $keyword) !== false // Contains
                                ) {
                                    // Add to filtered results
                                    $filteredDoctors[$doctorId] = $doctor;
                                }
                            }
                        }
                    } else {
                        // Fetch all doctors in descending order of some identifier (you can sort later if needed)
                        $doctorsReference = $database->getReference('doctors');
                        $doctorsSnapshot = $doctorsReference->getSnapshot();
                        $filteredDoctors = $doctorsSnapshot->getValue();
                    }
                    
                    // Display the filtered doctors (either from search or default order)
                    if ($filteredDoctors) {
                        foreach ($filteredDoctors as $doctor) {
                            $docName = htmlspecialchars($doctor['docname']);
                            $docEmail = htmlspecialchars($doctor['docemail']);
                    
                            echo "<tr>
                                    <td>{$docName}</td>
                                    <td>{$docEmail}</td>
                                  </tr>";
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
                            if ($_POST) {
                                $keyword = $_POST["search"];

                                // Prepare a reference to the 'doctors' node in Firebase
                                $doctorsReference = $database->getReference('doctors');
                                $doctorsSnapshot = $doctorsReference->getSnapshot();
                                $doctorsData = $doctorsSnapshot->getValue();

                                // Initialize filtered results array
                                $filteredDoctors = [];
                                if ($doctorsData) {
                                    foreach ($doctorsData as $doctorId => $doctor) {
                                        $docName = $doctor['docname'];
                                        $docEmail = $doctor['docemail'];

                                        // Filter based on the keyword (matches name or email, or partially matches name)
                                        if (
                                            $docEmail === $keyword ||
                                            $docName === $keyword ||
                                            stripos($docName, $keyword) === 0 ||  // Starts with keyword
                                            stripos($docName, $keyword) !== false // Contains keyword
                                        ) {
                                            $filteredDoctors[$doctorId] = $doctor;
                                        }
                                    }
                                }

                                // If no results were found
                                if (empty($filteredDoctors)) {
                                    echo '<tr>
                                            <td colspan="4">
                                            <br><br><br><br>
                                            <center>
                                            <img src="../img/notfound.svg" width="25%">
                                            
                                            <br>
                                            <p class="heading-main12" style="margin-left: 45px;font-size:20px;color:rgb(49, 49, 49)">We couldn\'t find anything related to your keywords!</p>
                                            <a class="non-style-link" href="doctors.php"><button  class="login-btn btn-primary-soft btn" style="display: flex;justify-content: center;align-items: center;margin-left:20px;">&nbsp; Show all Doctors &nbsp;</button></a>
                                            </center>
                                            <br><br><br><br>
                                            </td>
                                        </tr>';
                                } else {
                                    // Display filtered results
                                    foreach ($filteredDoctors as $doctorId => $doctor) {
                                        $docName = htmlspecialchars($doctor['docname']);
                                        $docEmail = htmlspecialchars($doctor['docemail']);
                                        $specialtyId = $doctor['specialties'];

                                        // Fetch the specialty name from Firebase
                                        $specialtiesReference = $database->getReference('specialties/' . $specialtyId);
                                        $specialtySnapshot = $specialtiesReference->getSnapshot();
                                        $specialtyData = $specialtySnapshot->getValue();
                                        $specialtyName = $specialtyData ? htmlspecialchars($specialtyData['sname']) : 'Unknown';

                                        echo '<tr>
                                                <td>&nbsp;' . substr($docName, 0, 30) . '</td>
                                                <td>' . substr($docEmail, 0, 20) . '</td>
                                                <td>' . substr($specialtyName, 0, 20) . '</td>
                                                <td>
                                                    <div style="display:flex;justify-content: center;">
                                                        <a href="?action=edit&id=' . $doctorId . '&error=0" class="non-style-link">
                                                            <button class="btn-primary-soft btn button-icon btn-edit" style="padding-left: 40px;padding-top: 12px;padding-bottom: 12px;margin-top: 10px;">
                                                                <font class="tn-in-text">Edit</font>
                                                            </button>
                                                        </a>
                                                        &nbsp;&nbsp;&nbsp;
                                                        <a href="?action=view&id=' . $doctorId . '" class="non-style-link">
                                                            <button class="btn-primary-soft btn button-icon btn-view" style="padding-left: 40px;padding-top: 12px;padding-bottom: 12px;margin-top: 10px;">
                                                                <font class="tn-in-text">View</font>
                                                            </button>
                                                        </a>
                                                        &nbsp;&nbsp;&nbsp;
                                                        <a href="?action=drop&id=' . $doctorId . '&name=' . $docName . '" class="non-style-link">
                                                            <button class="btn-primary-soft btn button-icon btn-delete" style="padding-left: 40px;padding-top: 12px;padding-bottom: 12px;margin-top: 10px;">
                                                                <font class="tn-in-text">Remove</font>
                                                            </button>
                                                        </a>
                                                    </div>
                                                </td>
                                            </tr>';
                                    }
                                }
                            } else {
                                // If no search is performed, display all doctors (descending order logic can be implemented based on Firebase key order)
                                $doctorsReference = $database->getReference('doctors');
                                $doctorsSnapshot = $doctorsReference->getSnapshot();
                                $doctorsData = $doctorsSnapshot->getValue();

                                if ($doctorsData) {
                                    foreach ($doctorsData as $doctorId => $doctor) {
                                        $docName = htmlspecialchars($doctor['docname']);
                                        $docEmail = htmlspecialchars($doctor['docemail']);
                                        $specialtyId = $doctor['specialties'];

                                        // Fetch the specialty name from Firebase
                                        $specialtiesReference = $database->getReference('specialties/' . $specialtyId);
                                        $specialtySnapshot = $specialtiesReference->getSnapshot();
                                        $specialtyData = $specialtySnapshot->getValue();
                                        $specialtyName = $specialtyData ? htmlspecialchars($specialtyData['sname']) : 'Unknown';

                                        echo '<tr>
                                                <td>&nbsp;' . substr($docName, 0, 30) . '</td>
                                                <td>' . substr($docEmail, 0, 20) . '</td>
                                                <td>' . substr($specialtyName, 0, 20) . '</td>
                                                <td>
                                                    <div style="display:flex;justify-content: center;">
                                                        <a href="?action=edit&id=' . $doctorId . '&error=0" class="non-style-link">
                                                            <button class="btn-primary-soft btn button-icon btn-edit" style="padding-left: 40px;padding-top: 12px;padding-bottom: 12px;margin-top: 10px;">
                                                                <font class="tn-in-text">Edit</font>
                                                            </button>
                                                        </a>
                                                        &nbsp;&nbsp;&nbsp;
                                                        <a href="?action=view&id=' . $doctorId . '" class="non-style-link">
                                                            <button class="btn-primary-soft btn button-icon btn-view" style="padding-left: 40px;padding-top: 12px;padding-bottom: 12px;margin-top: 10px;">
                                                                <font class="tn-in-text">View</font>
                                                            </button>
                                                        </a>
                                                        &nbsp;&nbsp;&nbsp;
                                                        <a href="?action=drop&id=' . $doctorId . '&name=' . $docName . '" class="non-style-link">
                                                            <button class="btn-primary-soft btn button-icon btn-delete" style="padding-left: 40px;padding-top: 12px;padding-bottom: 12px;margin-top: 10px;">
                                                                <font class="tn-in-text">Remove</font>
                                                            </button>
                                                        </a>
                                                    </div>
                                                </td>
                                            </tr>';
                                    }
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
                            <button class="btn-primary btn" style="display: flex; justify-content: center; align-items: center; margin: 10px; padding: 10px;">
                                <font class="tn-in-text">&nbsp;Yes&nbsp;</font>
                            </button>
                        </a>&nbsp;&nbsp;&nbsp;
                        <a href="doctors.php" class="non-style-link">
                            <button class="btn-primary btn" style="display: flex; justify-content: center; align-items: center; margin: 10px; padding: 10px;">
                                <font class="tn-in-text">&nbsp;&nbsp;No&nbsp;&nbsp;</font>
                            </button>
                        </a>
                    </div>
                </center>
            </div>
        </div>';
        }    elseif($action == 'view') {
            $doctorRef = $database->getReference('doctors/' . $id);
            $doctor = $doctorRef->getValue();
    
            if ($doctor) {
                $name = $doctor['docname'];
                $email = $doctor['docemail'];
                $spe = $doctor['specialties'];
                $nic = $doctor['docnic'];
                $tele = $doctor['doctel'];
    
                // Fetch the specialty name
                $specialtyRef = $database->getReference('specialties/' . $spe);
                $specialty = $specialtyRef->getValue();
                $spcil_name = $specialty ? $specialty['sname'] : 'Unknown';
    
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
                                        '.$name.'<br><br>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="label-td" colspan="2">
                                        <label for="Email" class="form-label">Email: </label>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="label-td" colspan="2">
                                    '.$email.'<br><br>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="label-td" colspan="2">
                                        <label for="nic" class="form-label">NIC: </label>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="label-td" colspan="2">
                                    '.$nic.'<br><br>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="label-td" colspan="2">
                                        <label for="Tele" class="form-label">Telephone: </label>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="label-td" colspan="2">
                                    '.$tele.'<br><br>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="label-td" colspan="2">
                                        <label for="spec" class="form-label">Specialties: </label>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="label-td" colspan="2">
                                    '.$spcil_name.'<br><br>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="2">
                                        <a href="doctors.php"><input type="button" value="OK" class="login-btn btn-primary-soft btn" ></a>
                                    </td>
                                </tr>
                            </table>
                            </div>
                        </center>
                        <br><br>
                    </div>
                </div>';    
        }elseif ($action == 'add') {
            $error_1 = $_GET["error"];
            $errorlist = array(
                '1' => '<label for="promter" class="form-label" style="color:rgb(255, 62, 62);text-align:center;">Already have an account for this Email address.</label>',
                '2' => '<label for="promter" class="form-label" style="color:rgb(255, 62, 62);text-align:center;">Password Confirmation Error! Reconfirm Password</label>',
                '3' => '<label for="promter" class="form-label" style="color:rgb(255, 62, 62);text-align:center;"></label>',
                '4' => "",
                '0' => '',
            );
        
            if ($error_1 != '4') {
                echo '
                <div id="popup1" class="overlay">
                    <div class="popup">
                        <center>
                            <a class="close" href="doctors.php">&times;</a>
                            <div style="display: flex;justify-content: center;">
                            <div class="abc">
                            <table width="80%" class="sub-table scrolldown add-doc-form-container" border="0">
                                <tr>
                                    <td class="label-td" colspan="2">' . $errorlist[$error_1] . '</td>
                                </tr>
                                <tr>
                                    <td>
                                        <p style="padding: 0;margin: 0;text-align: left;font-size: 25px;font-weight: 500;">Add New Doctor.</p><br><br>
                                    </td>
                                </tr>
                                <tr>
                                    <form action="add-new.php" method="POST" class="add-new-form">
                                        <td class="label-td" colspan="2">
                                            <label for="name" class="form-label">Name: </label>
                                        </td>
                                </tr>
                                <tr>
                                    <td class="label-td" colspan="2">
                                        <input type="text" name="name" class="input-text" placeholder="Doctor Name" required><br>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="label-td" colspan="2">
                                        <label for="Email" class="form-label">Email: </label>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="label-td" colspan="2">
                                        <input type="email" name="email" class="input-text" placeholder="Email Address" required><br>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="label-td" colspan="2">
                                        <label for="nic" class="form-label">NIC: </label>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="label-td" colspan="2">
                                        <input type="text" name="nic" class="input-text" placeholder="NIC Number" required><br>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="label-td" colspan="2">
                                        <label for="Tele" class="form-label">Telephone: </label>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="label-td" colspan="2">
                                        <input type="tel" name="Tele" class="input-text" placeholder="Telephone Number" required><br>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="label-td" colspan="2">
                                        <label for="spec" class="form-label">Choose specialties: </label>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="label-td" colspan="2">
                                        <select name="spec" id="" class="box">';
        
                // Fetching specialties from Firebase Realtime Database
                $specialties = $database->getReference('specialties')->getValue();
                if ($specialties) {
                    foreach ($specialties as $id => $specialty) {
                        $sname = $specialty['sname'];
                        echo "<option value=\"$id\">$sname</option>";
                    }
                }
        
                echo '</select><br>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="label-td" colspan="2">
                                        <label for="password" class="form-label">Password: </label>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="label-td" colspan="2">
                                        <input type="password" name="password" class="input-text" placeholder="Define a Password" required><br>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="label-td" colspan="2">
                                        <label for="cpassword" class="form-label">Confirm Password: </label>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="label-td" colspan="2">
                                        <input type="password" name="cpassword" class="input-text" placeholder="Confirm Password" required><br>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="2">
                                        <input type="reset" value="Reset" class="login-btn btn-primary-soft btn">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                        <input type="submit" value="Add" class="login-btn btn-primary btn">
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
        </div>';
            }
             } elseif ($action == 'edit') {
            $doc_id = $id;
            $doctor = $database->getReference("doctors/$doc_id")->getValue();
            $name = $doctor["name"];
            $email = $doctor["email"];
            $nic = $doctor["nic"];
            $tele = $doctor["tele"];
            $specialty_id = $doctor["spec"];
            $specialty_name = $database->getReference("specialties/$specialty_id/sname")->getValue();
        
            echo '
            <div id="popup1" class="overlay">
                <div class="popup">
                    <center>
                        <a class="close" href="doctors.php">&times;</a>
                        <div style="display: flex;justify-content: center;">
                        <div class="abc">
                        <table width="80%" class="sub-table scrolldown add-doc-form-container" border="0">
                            <tr>
                                <td class="label-td" colspan="2">
                                    <form action="edit-doc.php" method="POST" class="add-new-form">
                                        <label for="Email" class="form-label">Email: </label>
                                        <input type="hidden" value="' . $doc_id . '" name="id00">
                                        <input type="hidden" name="oldemail" value="' . $email . '" >
                                </td>
                            </tr>
                            <tr>
                                <td class="label-td" colspan="2">
                                    <input type="email" name="email" class="input-text" placeholder="Email Address" value="' . $email . '" required><br>
                                </td>
                            </tr>
                            <tr>
                                <td class="label-td" colspan="2">
                                    <label for="name" class="form-label">Name: </label>
                                </td>
                            </tr>
                            <tr>
                                <td class="label-td" colspan="2">
                                    <input type="text" name="name" class="input-text" placeholder="Doctor Name" value="' . $name . '" required><br>
                                </td>
                            </tr>
                            <tr>
                                <td class="label-td" colspan="2">
                                    <label for="nic" class="form-label">NIC: </label>
                                </td>
                            </tr>
                            <tr>
                                <td class="label-td" colspan="2">
                                    <input type="text" name="nic" class="input-text" placeholder="NIC Number" value="' . $nic . '" required><br>
                                </td>
                            </tr>
                            <tr>
                                <td class="label-td" colspan="2">
                                    <label for="Tele" class="form-label">Telephone: </label>
                                </td>
                            </tr>
                            <tr>
                                <td class="label-td" colspan="2">
                                    <input type="tel" name="Tele" class="input-text" placeholder="Telephone Number" value="' . $tele . '" required><br>
                                </td>
                            </tr>
                            <tr>
                                <td class="label-td" colspan="2">
                                    <label for="spec" class="form-label">Choose specialties: (Current: ' . $specialty_name . ')</label>
                                </td>
                            </tr>
                            <tr>
                                <td class="label-td" colspan="2">
                                    <select name="spec" id="" class="box">';
        
            // Fetching specialties from Firebase Realtime Database
            $specialties = $database->getReference('specialties')->getValue();
            if ($specialties) {
                foreach ($specialties as $id => $specialty) {
                    $sname = $specialty['sname'];
                    echo "<option value=\"$id\">$sname</option>";
                }
            }
        
            echo '</select><br>
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
                </div>
            </center>
            <br><br>
        </div>
        </div>';
        }; };
    };

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