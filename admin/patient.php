<?php
session_start();

if (!isset($_SESSION["user"]) || $_SESSION["user"] == "" || $_SESSION['usertype'] != 'a') {
    header("location: ../login.php");
    exit();
}

// Import database
include("../connection.php");

// Archive patient logic
if (isset($_GET["action"]) && isset($_GET["pid"]) && $_GET["action"] === 'archive') {
    $id = intval($_GET["pid"]);

    // Check if patient exists in Firebase
    $reference = $database->getReference('patients/' . $id);
    $snapshot = $reference->getSnapshot();

    if (!$snapshot->exists()) {
        echo '<script>alert("Patient ID does not exist."); window.location.href="patient.php";</script>';
        exit();
    }

    // Get patient data from Firebase
    $patientData = $snapshot->getValue();

    // Archive patient (add to 'archive' node)
    $archiveReference = $database->getReference('archive/' . $id);
    $archiveReference->set($patientData);

    // Mark patient as archived (you can create a flag in Firebase)
    $reference->update(['archived' => true]);

    echo '<script>alert("Patient archived successfully!"); window.location.href="patient.php";</script>';
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
    <title>Patients</title>
    <style>
        .sub-table{
            animation: transitionIn-Y-bottom 0.5s;
        }
          /* Add this CSS to your existing styles */
          .overlay {
            display: none; /* Hide popup by default */
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.8); /* Semi-transparent background */
            justify-content: center;
            align-items: center;
        }
        .popup {
            background: white;
            padding: 20px;
            border-radius: 5px;
            width: 80%;
            max-width: 500px; /* Adjust as needed */
            animation: transitionIn-Y-bottom 0.5s;
        }
    </style>

</head>
<body>
    <!-- Mobile Header -->
    <div class="mobile-header">Clients</div>

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
                    <td class="menu-btn menu-active">
                        <a href="patient.php" class="non-style-link-menu  non-style-link-menu-active"><div><p class="menu-text">Clients</p></a></div>
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
            <table border="0" width="100%" style="border-spacing: 0; margin: 0; padding: 0; margin-top: 25px;">
                <tr>
                    <td width="13%">
                        <a href="patient.php"><button class="login-btn btn-primary-soft btn btn-icon-back" style="padding-top:11px; padding-bottom:11px; margin-left:20px; width:125px"><font class="tn-in-text">Back</font></button></a>
                    </td>
                    <td>
                        <form action="" method="post" class="header-search">
                            <input type="search" name="search" class="input-text header-searchbar" placeholder="Search Patient name or Email" list="patient">&nbsp;&nbsp;
                            <?php
                               // Retrieve patient data from Firebase
                                $patientsReference = $database->getReference('patients'); // Assuming 'patients' node holds the data
                                $patientsSnapshot = $patientsReference->getSnapshot();

                                if (!$patientsSnapshot->exists()) {
                                    echo "No patients found.";
                                } else {
                                    $patientsData = $patientsSnapshot->getValue(); // Array of patient data

                                    echo '<datalist id="patient">';
                                    
                                    // Loop through the patient data and populate the datalist
                                    foreach ($patientsData as $patient) {
                                        $pname = $patient['name']; // Assuming 'pname' is the field for patient name
                                        $pemail = $patient['email']; // Assuming 'pemail' is the field for patient email

                                        echo "<option value='$pname'><br/>";
                                        echo "<option value='$pemail'><br/>";
                                    }
                                    
                                    echo '</datalist>';
                                }
                            ?>
                            <input type="submit" value="Search" class="login-btn btn-primary btn" style="padding-left: 25px; padding-right: 25px; padding-top: 10px; padding-bottom: 10px;">
                        </form>
                    </td>
                    <td width="15%">
                        <p style="font-size: 14px;color: rgb(119, 119, 119);padding: 0;margin: 0;text-align: right;">
                            Today's Date
                        </p>
                        <p class="heading-sub12" style="padding: 0; margin: 0;">
                            <?php
                                 date_default_timezone_set('Asia/Kolkata');
                                 $date = date('Y-m-d');
                                 echo $date;
                            ?>
                        </p>
                    </td>
                    <td width="10%">
                        <button class="btn-label" style="display: flex; justify-content: center; align-items: center;"><img src="../img/calendar.svg" width="100%"></button>
                    </td>
                </tr>
                <tr>
                    <td colspan="4" style="padding-top:10px;">
                        <p class="heading-main12" style="margin-left: 45px; font-size:18px; color:rgb(49, 49, 49)">All Patients 
                            (<?php
                                // Fetch all patients
                                $patientsReference = $database->getReference('patients');
                                $patientsSnapshot = $patientsReference->getSnapshot();
                                $patientsData = $patientsSnapshot->getValue();

                                // Count records
                                if ($patientsData) {
                                    $count = count($patientsData);
                                    echo $count; // Output the number of patient records
                                } else {
                                    echo 0; // No records found
                                } 
                            ?>)
                        </p>
                    </td>
                </tr>
                <?php
                    // Get the search keyword from POST
                    $keyword = $_POST["search"] ?? '';

                    // Reference to the patients node in Firebase
                    $patientsReference = $database->getReference('patients');
                    $patientsSnapshot = $patientsReference->getSnapshot();

                    // Get all patient data
                    $patientsData = $patientsSnapshot->getValue();

                    // Filter the patient data based on the search keyword
                    $filteredPatients = [];
                    if ($patientsData) {
                        foreach ($patientsData as $pid => $patient) {
                            // Check if the patient is not archived
                            if (isset($patient['archived']) && $patient['archived'] == 0) {
                                // Check if the keyword matches email, name, or similar patterns
                                if ($keyword === '' ||
                                    stripos($patient['email'], $keyword) !== false ||
                                    stripos($patient['name'], $keyword) !== false ||
                                    stripos($patient['name'], "$keyword%") !== false ||
                                    stripos($patient['name'], "%$keyword") !== false ||
                                    stripos($patient['name'], "%$keyword%") !== false) {
                                    // Add matched patient to the filtered results
                                    $filteredPatients[$pid] = $patient;
                                }
                            }
                        }
                    }

                    // Display filtered patients (adjust display logic to your needs)
                    if (!empty($filteredPatients)) {
                        foreach ($filteredPatients as $pid => $patient) {
                            echo "Patient ID: $pid, Name: " . $patient['pname'] . ", Email: " . $patient['pemail'] . "<br/>";
                        }
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
                                            <th class="table-headin">Phone No.</th>
                                            <th class="table-headin">Email</th>
                                            <th class="table-headin">Date of Birth</th>
                                            <th class="table-headin">Gender</th>
                                            <th class="table-headin">Case</th>
                                            <th class="table-headin">Civil Status</th>
                                            <th class="table-headin">Events</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                           // Retrieve patient data from Firebase
                                            $patientsReference = $database->getReference('patients');
                                            $patientsSnapshot = $patientsReference->getSnapshot();
                                            $patientsData = $patientsSnapshot->getValue();

                                            if (!$patientsData) {
                                                // No results found
                                                echo '<tr><td colspan="4"><br><br><br><br>
                                                <center><img src="../img/notfound.svg" width="25%">
                                                <br><br><br><br>No results found.</center>
                                                </td></tr>';
                                            } else {
                                                // Loop through each patient and display the details
                                                foreach ($patientsData as $pid => $patient) {
                                                    // Ensure patient is not archived
                                                    if (isset($patient['archived']) && $patient['archived'] == 0) {
                                                        // Sanitize data before displaying
                                                        $pname = htmlspecialchars($patient['name']);
                                                        $age = htmlspecialchars($patient['page']);
                                                        $ptel = htmlspecialchars($patient['tel']);
                                                        $pemail = htmlspecialchars($patient['email']);
                                                        $pdob = htmlspecialchars($patient['dob']);
                                                        $pgender = htmlspecialchars($patient['gender']);
                                                        $pcase = htmlspecialchars($patient['case']);
                                                        $pcivil = htmlspecialchars($patient['civil']);

                                                        echo "<tr>
                                                            <td>{$pname}</td>
                                                            <td>{$age}</td>
                                                            <td>{$ptel}</td>
                                                            <td>{$pemail}</td>
                                                            <td>{$pdob}</td>
                                                            <td>{$pgender}</td>
                                                            <td>{$pcase}</td>
                                                            <td>{$pcivil}</td>
                                                            <td>
                                                                <a href='patient.php?action=archive&pid={$pid}' onclick='return confirm(\"Are you sure you want to archive this patient?\")'>
                                                                    <button class='login-btn btn-primary-soft btn btn-icon'>
                                                                        <img src='../img/view-gray.svg' width='15'> Archive
                                                                    </button>
                                                                </a>
                                                            </td>
                                                        </tr>";
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
<div id="popup1" class="overlay">
                <div class="popup">
                <center>
                    <a class="close" href="patient.php">&times;</a>
                    <div class="content">
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
                                <label for="name" class="form-label">Client ID: </label>
                            </td>
                        </tr>
                        <tr>
                            <td class="label-td" colspan="2">
                                P-'.$id.'<br><br>
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
                                <label for="nic" class="form-label">Age: </label>
                            </td>
                        </tr>
                        <tr>
                            <td class="label-td" colspan="2">
                                '.$age.'<br><br>
                            </td>
                        </tr>
                        <tr>
                            <td class="label-td" colspan="2">
                                <label for="spec" class="form-label">Gender: </label>
                            </td>
                        </tr>
                        <tr>
                            <td class="label-td" colspan="2">
                                '.$gender.'<br><br>
                            </td>
                        </tr>
                        <tr>
                            <td class="label-td" colspan="2">
                                <label for="spec" class="form-label">Civil Status: </label>
                            </td>
                        </tr>
                        <tr>
                            <td class="label-td" colspan="2">
                                '.$civil.'<br><br>
                            </td>
                        </tr>
                        <tr>
                            <td class="label-td" colspan="2">
                                <label for="Tele" class="form-label">Phone No.: </label>
                            </td>
                        </tr>
                        <tr>
                            <td class="label-td" colspan="2">
                                '.$tele.'<br><br>
                            </td>
                        </tr>
                        <tr>
                            <td class="label-td" colspan="2">
                                <label for="spec" class="form-label">Address: </label>
                            </td>
                        </tr>
                        <tr>
                            <td class="label-td" colspan="2">
                                '.$address.'<br><br>
                            </td>
                        </tr>
                        <tr>
                            <td class="label-td" colspan="2">
                                <label for="name" class="form-label">Date of Birth: </label>
                            </td>
                        </tr>
                        <tr>
                            <td class="label-td" colspan="2">
                                '.$dob.'<br><br>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2">
                                <a href="patient.php"><input type="button" value="BACK" class="login-btn btn-primary-soft btn"></a>
                                <a href="?action=archive&id=<?php echo $pid; ?>"><input type="button" value="ARCHIVE" class="login-btn btn-primary-soft btn"></a>
                                <a href="patient.php"><input type="button" value="PRINT" class="login-btn btn-primary-soft btn"></a>
                            </td>
                        </tr>
                    </table>
                    </div>
                </center>
                <br><br>
            </div>
        </div>

 <!-- Floating button -->
 <div class="floating-btn-container">
    <button id="floating-btn2" class="floating-btn">
        <img src="../img/icons/camera.svg" alt="Camera Icon">
    </button>
	</div>
    <script src="../js/main.js"></script>
    <script>
	   document.getElementById('floating-btn2').addEventListener('click', function() {
		// Create an invisible file input element
		let fileInput = document.createElement('input');
		fileInput.type = 'file';
		fileInput.accept = 'image/*'; // Accept images or camera photos
		fileInput.style.display = 'none'; // Hide the file input

		fileInput.onchange = function(event) {
			let file = event.target.files[0];
			if (file) {
				console.log('File selected:', file);
				// Display a preview for debugging
				let reader = new FileReader();
				reader.onload = function(e) {
					let img = new Image();
					img.src = e.target.result;
					document.body.appendChild(img);
				};
				reader.readAsDataURL(file);
			}
		};

		// Append the file input to the body
		document.body.appendChild(fileInput);

		// Trigger a click event on the file input
		fileInput.click();

		// Remove the file input from the DOM after usage
		fileInput.remove();
	});
        // Show popup
        document.getElementById('floating-btn').addEventListener('click', function() {
                document.getElementById('popup1').style.display = 'block';
            });

            // Close popup
            function closePopup() {
                document.getElementById('popup1').style.display = 'none';
            }

	const hamburgerMenu = document.getElementById('hamburger-menu');
			const menu = document.querySelector('.menu');
			const dashBody = document.querySelector('.dash-body');

			hamburgerMenu.addEventListener('click', () => {
				hamburgerMenu.classList.toggle('active');
				menu.classList.toggle('active');
				dashBody.classList.toggle('active');
			});

    </script>
</body>
</html>
