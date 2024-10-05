<?php
session_start();

if (!isset($_SESSION["user"]) || $_SESSION["user"] == "" || $_SESSION['usertype'] != 'a') {
    header("location: ../login.php");
    exit();
}

// Import database
include("../connection.php");

/// Fetch all patients from Firebase
$patientsReference = $database->getReference('patients');
$patientsSnapshot = $patientsReference->getSnapshot();
$patientsData = $patientsSnapshot->getValue() ?? []; // Use null coalescing operator to avoid undefined variable

// Initialize data structures
$tagaytayData = ['labels' => [], 'data' => []];
$barangayData = ['labels' => [], 'data' => []];
$withinCaviteData = ['labels' => [], 'data' => []];
$outsideCaviteData = ['labels' => [], 'data' => []];
$caseData = ['labels' => [], 'data' => []];
$ageData = ['labels' => [], 'data' => []];
$genderData = ['labels' => [], 'data' => []];

// Count Patients from Tagaytay
$numTagaytayClients = 0;
if (!empty($patientsData)) {
    foreach ($patientsData as $patient) {
        if (isset($patient['pcity']) && $patient['pcity'] === 'Tagaytay') {
            $numTagaytayClients++;
        }
    }
    $tagaytayData['labels'][] = 'Tagaytay';
    $tagaytayData['data'][] = $numTagaytayClients;
}

// Count Patients by Barangay within Tagaytay
$barangayCount = [];
foreach ($patientsData as $patient) {
    if (isset($patient['pcity']) && $patient['pcity'] === 'Tagaytay') {
        $barangay = $patient['pbrgy'] ?? 'Unknown';
        $barangayCount[$barangay] = ($barangayCount[$barangay] ?? 0) + 1;
    }
}

foreach ($barangayCount as $brgy => $count) {
    $barangayData['labels'][] = $brgy;
    $barangayData['data'][] = $count;
}

// Count Patients within Cavite but Excluding Tagaytay
$withinCaviteCount = [];
foreach ($patientsData as $patient) {
    if (isset($patient['pprovince']) && $patient['pprovince'] === 'Cavite' && $patient['pcity'] !== 'Tagaytay') {
        $city = $patient['pcity'] ?? 'Unknown';
        $withinCaviteCount[$city] = ($withinCaviteCount[$city] ?? 0) + 1;
    }
}

foreach ($withinCaviteCount as $city => $count) {
    $withinCaviteData['labels'][] = $city;
    $withinCaviteData['data'][] = $count;
}

// Count Patients Outside Cavite
$outsideCaviteCount = [];
foreach ($patientsData as $patient) {
    if (isset($patient['pprovince']) && $patient['pprovince'] !== 'Cavite') {
        $city = $patient['pcity'] ?? 'Unknown';
        $outsideCaviteCount[$city] = ($outsideCaviteCount[$city] ?? 0) + 1;
    }
}

foreach ($outsideCaviteCount as $city => $count) {
    $outsideCaviteData['labels'][] = $city;
    $outsideCaviteData['data'][] = $count;
}

// Count Patients by Case (Condition)
$caseCount = [];
$conditionsReference = $database->getReference('conditions');
$conditionsSnapshot = $conditionsReference->getSnapshot();
$conditionsData = $conditionsSnapshot->getValue() ?? []; 

foreach ($patientsData as $patient) {
    if (isset($patient['pcase'])) {
        $conditionName = $conditionsData[$patient['pcase']]['condition_name'] ?? 'Unknown';
        $caseCount[$conditionName] = ($caseCount[$conditionName] ?? 0) + 1;
    }
}

foreach ($caseCount as $condition => $count) {
    $caseData['labels'][] = $condition;
    $caseData['data'][] = $count;
}

// Fetch Age Data
$ageCount = ['Under 17' => 0, '17 and above' => 0];
foreach ($patientsData as $patient) {
    $dob = new DateTime($patient['dob']);
    $age = $dob->diff(new DateTime())->y;
    if ($age < 17) {
        $ageCount['Under 17']++;
    } else {
        $ageCount['17 and above']++;
    }
}

foreach ($ageCount as $group => $count) {
    $ageData['labels'][] = $group;
    $ageData['data'][] = $count;
}

// Fetch Gender Data
$genderCount = [];
foreach ($patientsData as $patient) {
    $gender = $patient['gender'] ?? 'Unknown';
    $genderCount[$gender] = ($genderCount[$gender] ?? 0) + 1;
}

foreach ($genderCount as $gender => $count) {
    $genderData['labels'][] = $gender;
    $genderData['data'][] = $count;
}

// Prepare JSON for JavaScript
$tagaytayLabels = json_encode($tagaytayData['labels']);
$tagaytayCounts = json_encode($tagaytayData['data']);
$barangayLabels = json_encode($barangayData['labels']);
$barangayCounts = json_encode($barangayData['data']);
$withinCaviteLabels = json_encode($withinCaviteData['labels']);
$withinCaviteCounts = json_encode($withinCaviteData['data']);
$outsideCaviteLabels = json_encode($outsideCaviteData['labels']);
$outsideCaviteCounts = json_encode($outsideCaviteData['data']);
$caseLabels = json_encode($caseData['labels']);
$caseCounts = json_encode($caseData['data']);
$ageLabels = json_encode($ageData['labels']);
$ageCounts = json_encode($ageData['data']);
$genderLabels = json_encode($genderData['labels']);
$genderCounts = json_encode($genderData['data']);

// Prepare HTML Table Rows for Display
$tagaytayTableRows = "<tr><td>{$tagaytayData['labels'][0]}</td><td>{$tagaytayData['data'][0]}</td></tr>";

$barangayTableRows = '';
foreach ($barangayData['labels'] as $index => $barangay) {
    $barangayTableRows .= "<tr><td>{$barangay}</td><td>{$barangayData['data'][$index]}</td></tr>";
}

$withinCaviteTableRows = '';
foreach ($withinCaviteData['labels'] as $index => $city) {
    $withinCaviteTableRows .= "<tr><td>{$city}</td><td>{$withinCaviteData['data'][$index]}</td></tr>";
}

$outsideCaviteTableRows = '';
foreach ($outsideCaviteData['labels'] as $index => $city) {
    $outsideCaviteTableRows .= "<tr><td>{$city}</td><td>{$outsideCaviteData['data'][$index]}</td></tr>";
}

$caseTableRows = '';
foreach ($caseData['labels'] as $index => $condition) {
    $caseTableRows .= "<tr><td>{$condition}</td><td>{$caseData['data'][$index]}</td></tr>";
}

$ageTableRows = '';
foreach ($ageData['labels'] as $index => $ageGroup) {
    $ageTableRows .= "<tr><td>{$ageGroup}</td><td>{$ageData['data'][$index]}</td></tr>";
}

$genderTableRows = '';
foreach ($genderData['labels'] as $index => $gender) {
    $genderTableRows .= "<tr><td>{$gender}</td><td>{$genderData['data'][$index]}</td></tr>";
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
        
    <title>Dashboard</title>
    <style>
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
        .dashbord-tables{
            animation: transitionIn-Y-over 0.5s;
        }
        .filter-container{
            animation: transitionIn-Y-bottom  0.5s;
        }
        .sub-table{
            animation: transitionIn-Y-bottom 0.5s;
        }
        .bar-charts {
            display: flex;
            justify-content: space-around;
            margin-bottom: 20px;
        }
        .bar-chart {
            text-align: center;
            width: 30%;
        }
        .bar-chart canvas {
            display: block;
            margin: 0 auto;
			margin-top: 30px;
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
    <div class="mobile-header">Summary</div>

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
                    <td class="menu-btn">
                        <a href="archive.php" class="non-style-link-menu"><div><p class="menu-text">Archives</p></a></div>
					</td>
                </tr>
				<tr class="menu-row" >
                    <td class="menu-btn menu-active">
                        <a href="summary.php" class="non-style-link-menu non-style-link-menu-active"><div><p class="menu-text">Summary</p></a></div>
					</td>
                </tr> 
            </table>
        </div>
		
		<div class="dash-body" style="margin-top: 15px">
            <table border="0" width="100%" style=" border-spacing: 0;margin:0;padding:0;margin-top:25px; ">
                <tr >
                    <td width="13%" >
                    <a href="schedule.php" ><button  class="login-btn btn-primary-soft btn btn-icon-back"  style="padding-top:11px;padding-bottom:11px;margin-left:20px;width:125px"><font class="tn-in-text">Back</font></button></a>
                    </td>
                    <td>
                        <p style="font-size: 23px;padding-left:12px;font-weight: 600;">Summary</p>
                                           
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
				
				<!-- Content of Summary -->
			<tr >
				<td colspan="4" >
					<div class="bar-charts">
						<div class="bar-chart" id="chart1">
							<canvas id="canvas1"></canvas>
							<p>Within Tagaytay [City]</p>
						</div>	
					</div>
					
					<center>
                        <div class="abc scroll">
                        <table width="50%" class="sub-table scrolldown" border="0">
							<thead>
								<tr>
									<th class="table-headin">
										Within Tagaytay [City]
									</th>
									<!-- <th class="table-headin">
										Case
									</th> -->
									<th class="table-headin">      
										Number of Clients   
									</th>
								</tr>
							</thead>
							
							<tbody>
							<?php echo $tagaytayTableRows; ?>
							</tbody>
						</table>
						</div>
					
					<div class="bar-charts">
						<div class="bar-chart" id="chart2">
							<canvas id="canvas2"></canvas>
							<p>Within Tagaytay [Barangay]</p>
						</div>	
					</div>	
					
						<center>
                        <div class="abc scroll">
                        <table width="50%" class="sub-table scrolldown" border="0">
							<thead>
								<tr>
									<th class="table-headin">
										Within Tagaytay [Barangay]
									</th>
									<th class="table-headin">      
										Number of Clients   
									</th>
								</tr>
							</thead>
							
							<tbody>
							<?php echo $barangayTableRows; ?>
							</tbody>
						</table>
						</div>
							
				
					<div class="bar-charts">
						<div class="bar-chart" id="chart3">
							<canvas id="canvas3"></canvas>
							<p>Within Cavite excluding Tagaytay</p>
						</div>
					</div>
					
						<center>
                        <div class="abc scroll">
                        <table width="50%" class="sub-table scrolldown" border="0">
							<thead>
								<tr>
									<th class="table-headin">
										Within Cavite excluding Tagaytay
									</th>
									<!-- <th class="table-headin">
										Case
									</th> -->
									<th class="table-headin">      
										Number of Clients   
									</th>
								</tr>
							</thead>
							
							<tbody>
								<!-- Database for table here -->
								<?php echo $withinCaviteTableRows; ?>
							</tbody>
						</table>
						</div>
					
					
					
					<div class="bar-charts">
						<div class="bar-chart" id="chart4">
							<canvas id="canvas4"></canvas>
							<p>Outside Cavite</p>
						</div>
					</div>
					
						<center>
                        <div class="abc scroll">
                        <table width="50%" class="sub-table scrolldown" border="0">
							<thead>
								<tr>
									<th class="table-headin">
										Outside Cavite
									</th>
									<!-- <th class="table-headin">
										Case
									</th> -->
									<th class="table-headin">      
										Number of Clients   
									</th>
								</tr>
							</thead>
							
							<tbody>
							<?php echo $outsideCaviteTableRows; ?>
							</tbody>
						</table>
						</div>
					
					
					
					<div class="bar-charts">
						<div class="bar-chart" id="chart5">
							<canvas id="canvas5"></canvas>
							<p>Cases</p>
						</div>	
					</div>
					
						<center>
                        <div class="abc scroll">
                        <table width="50%" class="sub-table scrolldown" border="0">
							<thead>
								<tr>
									<th class="table-headin">
										Cases
									</th>
									<th class="table-headin">      
										Number of Clients   
									</th>
								</tr>
							</thead>
							
							<tbody>
							<?php echo $caseTableRows; ?>
							</tbody>
						</table>
						</div>
					
					
					<div class="bar-charts">
						<div class="bar-chart" id="chart6">
							<canvas id="canvas6"></canvas>
							<p>Clients Under 17</p>
						</div>	
					</div>
					
						<center>
                        <div class="abc scroll">
                        <table width="50%" class="sub-table scrolldown" border="0">
							<thead>
								<tr>
									<th class="table-headin">
										Client's Age Under 17
									</th>
									<th class="table-headin">      
										Number of Clients   
									</th>
								</tr>
							</thead>
							
							<tbody>
								<!-- Database for table here -->
								<?php echo $ageTableRows; ?>
							</tbody>
						</table>
						</div>
					
					
					<div class="bar-charts">
						<div class="bar-chart" id="chart7">
							<canvas id="canvas7"></canvas>
							<p>Clients based on Gender</p>
						</div>	
					</div>
					
						<center>
                        <div class="abc scroll">
                        <table width="50%" class="sub-table scrolldown" border="0">
							<thead>
								<tr>
									<th class="table-headin">
										Client's Gender
									</th>
									<th class="table-headin">      
										Number of Clients   
									</th>
								</tr>
							</thead>
							
							<tbody>
								<!-- Database for table here -->
								<?php echo $genderTableRows; ?>
							</tbody>
						</table>
						</div>
			</tr >
		</div>

			<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
			<script>
    const genderLabels = <?php echo $genderLabels; ?>;
    const genderCounts = <?php echo $genderCounts; ?>;
    const ageLabels = <?php echo $ageLabels; ?>;
    const ageData = <?php echo $ageCounts; ?>;
    const tagaytayLabels = <?php echo $tagaytayLabels; ?>;
    const tagaytayCounts = <?php echo $tagaytayCounts; ?>;
    const barangayLabels = <?php echo $barangayLabels; ?>;
    const barangayCounts = <?php echo $barangayCounts; ?>;
    const withinCaviteLabels = <?php echo $withinCaviteLabels; ?>;
    const withinCaviteCounts = <?php echo $withinCaviteCounts; ?>;
    const outsideCaviteLabels = <?php echo $outsideCaviteLabels; ?>;
    const outsideCaviteCounts = <?php echo $outsideCaviteCounts; ?>;
    const caseLabels = <?php echo $caseLabels; ?>;
    const caseCounts = <?php echo $caseCounts; ?>;

    const data1 = {
        labels: tagaytayLabels,
        datasets: [{
            label: 'Patients from Tagaytay',
            data: tagaytayCounts,
            backgroundColor: '#4CAF50'
        }]
    };

    const data2 = {
        labels: barangayLabels,
        datasets: [{
            label: 'Within Tagaytay [Barangay]',
            data: barangayCounts,
            backgroundColor: '#FF9800'
        }]
    };

    const data3 = {
        labels: withinCaviteLabels,
        datasets: [{
            label: 'Within Cavite excluding Tagaytay',
            data: withinCaviteCounts,
            backgroundColor: '#F44336'
        }]
    };

    const data4 = {
        labels: outsideCaviteLabels,
        datasets: [{
            label: 'Outside Cavite',
            data: outsideCaviteCounts,
            backgroundColor: '#E91E63'
        }]
    };

    const data5 = {
        labels: caseLabels,
        datasets: [{
            label: 'Cases',
            data: caseCounts,
            backgroundColor: '#9C27B0'
        }]
    };

    const data6 = {
        labels: ageLabels,
        datasets: [{
            label: 'Clients by Age Group',
            data: ageData,
            backgroundColor: '#F44336'
        }]
    };

    const data7 = {
        labels: genderLabels,
        datasets: [{
            label: 'Clients based on Gender',
            data: genderCounts,
            backgroundColor: ['#4CAF50', '#2196F3']
        }]
    };

    const config1 = {
        type: 'bar',
        data: data1,
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    };

    const config2 = {
        type: 'bar',
        data: data2,
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    };

    const config3 = {
        type: 'bar',
        data: data3,
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    };

    const config4 = {
        type: 'bar',
        data: data4,
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    };

    const config5 = {
        type: 'bar',
        data: data5,
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    };

    const config6 = {
        type: 'bar',
        data: data6,
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    };

    const config7 = {
        type: 'bar',
        data: data7,
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    };

    window.onload = function() {
        new Chart(document.getElementById('canvas1'), config1);
        new Chart(document.getElementById('canvas2'), config2);
        new Chart(document.getElementById('canvas3'), config3);
        new Chart(document.getElementById('canvas4'), config4);
        new Chart(document.getElementById('canvas5'), config5);
        new Chart(document.getElementById('canvas6'), config6);
        new Chart(document.getElementById('canvas7'), config7);
    };

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