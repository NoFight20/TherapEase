<?php
session_start();

if(isset($_SESSION["user"])){
    if(($_SESSION["user"])=="" or $_SESSION['usertype']!='d'){
        header("location: ../login.php");
    }else{
        $useremail=$_SESSION["user"];
    }

}else{
    header("location: ../login.php");
}
include("../connection.php");
$sqlmain= "select * from doctor where docemail=?";
$stmt = $database->prepare($sqlmain);
$stmt->bind_param("s",$useremail);
$stmt->execute();
$userrow = $stmt->get_result();
$userfetch=$userrow->fetch_assoc();

$userid= $userfetch["docid"];
$username=$userfetch["docname"];

//import database

$tagaytayQuery = "
    SELECT
        pcity,
        COUNT(*) AS num_clients 
    FROM patient
    WHERE pcity = 'Tagaytay'
    GROUP BY pcity;
";

$resultTagaytay = $database->query($tagaytayQuery);

$tagaytayData = [
    'labels' => [],
    'data' => []
];

$tagaytayTableRows = '';
while ($row = $resultTagaytay->fetch_assoc()) {
    $tagaytayData['labels'][] = $row['pcity'];
    $tagaytayData['data'][] = $row['num_clients'];

    // Prepare rows for HTML table
    $tagaytayTableRows .= "<tr>
        <td>{$row['pcity']}</td>
        <td>{$row['num_clients']}</td>
    </tr>";
}
$tagaytayLabels = json_encode($tagaytayData['labels']);
$tagaytayCounts = json_encode($tagaytayData['data']);


// Query to count patients by barangay within Tagaytay
$barangayQuery = "
    SELECT pbrgy, COUNT(*) as num_clients
    FROM patient
    WHERE pcity = 'Tagaytay'
    GROUP BY pbrgy;
";

$resultBarangay = $database->query($barangayQuery);

$barangayTableRows = '';

while ($row = $resultBarangay->fetch_assoc()) {
	$barangayData['labels'][] = $row['pbrgy'];
    $barangayData['data'][] = $row['num_clients'];

    $barangayTableRows .= "<tr>
        <td>{$row['pbrgy']}</td>
        <td>{$row['num_clients']}</td>
    </tr>";
}
$barangayLabels = json_encode($barangayData['labels']);
$barangayCounts = json_encode($barangayData['data']);

$withinCaviteData = [
    'labels' => [],
    'data' => []
];
// Query to count patients within Cavite but excluding Tagaytay city
$withinCaviteQuery = "
    SELECT pcity, COUNT(*) as num_clients
    FROM patient
    WHERE pprovince = 'Cavite' AND pcity != 'Tagaytay'
    GROUP BY pcity;
";

$resultWithinCavite = $database->query($withinCaviteQuery);

$withinCaviteTableRows = '';

while ($row = $resultWithinCavite->fetch_assoc()) {
    $withinCaviteData['labels'][] = $row['pcity'];
    $withinCaviteData['data'][] = $row['num_clients'];

    $withinCaviteTableRows .= "<tr>
        <td>{$row['pcity']}</td>
        <td>{$row['num_clients']}</td>
    </tr>";
}

$withinCaviteLabels = json_encode($withinCaviteData['labels']);
$withinCaviteCounts = json_encode($withinCaviteData['data']);

$outsideCavite = "
SELECT pcity, COUNT(*) as num_clients
FROM patient
WHERE pprovince != 'Cavite'
GROUP BY pcity;"
;
$resultOutsideCavite = $database->query($outsideCavite);

$outsideCaviteTableRows = '';

while ($row = $resultOutsideCavite->fetch_assoc()) {
	$outsideCaviteData['labels'][] = $row['pcity'];
    $outsideCaviteData['data'][] = $row['num_clients'];

    $outsideCaviteTableRows .= "<tr>
        <td>{$row['pcity']}</td>
        <td>{$row['num_clients']}</td>
    </tr>";
}

$outsideCaviteLabels = json_encode($outsideCaviteData['labels']);
$outsideCaviteCounts = json_encode($outsideCaviteData['data']);

$caseQuery = "
    SELECT conditions.condition_name, COUNT(*) as num_clients
    FROM patient
    JOIN conditions ON patient.pcase = conditions.condition_id
    GROUP BY conditions.condition_name
    ORDER BY conditions.condition_name; 
";

// Execute the query
$result = $database->query($caseQuery);

$caseData = [
    'labels' => [],
    'data' => []
];
$caseTableRows = '';

while ($row = $result->fetch_assoc()) {
    $caseData['labels'][] = $row['condition_name'];  // Use condition_name instead of pcase
    $caseData['data'][] = $row['num_clients'];

    // Prepare rows for HTML table
    $caseTableRows .= "<tr>
        <td>{$row['condition_name']}</td>
        <td>{$row['num_clients']}</td>
    </tr>";
}

$caseLabels = json_encode($caseData['labels']);
$caseCounts = json_encode($caseData['data']);


// Fetch age data from the patient table
$ageQuery = "
    SELECT
        CASE
            WHEN TIMESTAMPDIFF(YEAR, pdob, CURDATE()) < 17 THEN 'Under 17'
            ELSE '17 and above'
        END AS age_group,
        COUNT(*) AS num_clients
    FROM patient
    GROUP BY age_group
    ORDER BY age_group;
";

$result = $database->query($ageQuery);

$ageData = [
    'labels' => [],
    'data' => []
];

$ageTableRows = '';

while ($row = $result->fetch_assoc()) {
    $ageData['labels'][] = $row['age_group'];
    $ageData['data'][] = $row['num_clients'];

    // Prepare rows for HTML table
    $ageTableRows .= "<tr>
        <td>{$row['age_group']}</td>
        <td>{$row['num_clients']}</td>
    </tr>";
}
$ageLabels = json_encode($ageData['labels']);
$ageData = json_encode($ageData['data']);


$genderQuery = "
    SELECT
        pgender,
        COUNT(*) AS num_clients
    FROM patient
    GROUP BY pgender
    ORDER BY pgender;
";

$resultGender = $database->query($genderQuery);

$genderData = [
    'labels' => [],
    'data' => []
];

$genderTableRows = '';

while ($row = $resultGender->fetch_assoc()) {
    $genderData['labels'][] = $row['pgender'];
    $genderData['data'][] = $row['num_clients'];

    // Prepare rows for HTML table
    $genderTableRows .= "<tr>
        <td>{$row['pgender']}</td>
        <td>{$row['num_clients']}</td>
    </tr>";
}

$genderLabels = json_encode($genderData['labels']);
$genderCounts = json_encode($genderData['data']);
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
                        <a href="index.php" class="non-style-link-menu "><div><p class="menu-text">Dashboard</p></a></div></a>
                    </td>
                </tr>
                <tr class="menu-row">
                    <td class="menu-btn">
                        <a href="appointment.php" class="non-style-link-menu"><div><p class="menu-text">My Appointments</p></a></div>
                    </td>
                </tr>
                
                <tr class="menu-row" >
                    <td class="menu-btn">
                        <a href="schedule.php" class="non-style-link-menu"><div><p class="menu-text">My Sessions</p></div></a>
                    </td>
                </tr>
                <tr class="menu-row" >
                    <td class="menu-btn">
                        <a href="patient.php" class="non-style-link-menu  non-style-link-menu-active"><div><p class="menu-text">My Clients</p></a></div>
                    </td>
                </tr>
				<tr class="menu-row" >
                    <td class="menu-btn menu-active">
                        <a href="summary.php" class="non-style-link-menu"><div><p class="menu-text">Summary</p></a></div>
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

                        $list110 = $database->query("select  * from  schedule;");

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
            	const ageData = <?php echo $ageData; ?>;
				const tagaytayLabels = <?php echo $tagaytayLabels; ?>;
				const tagaytayCounts = <?php echo $tagaytayCounts; ?>;
				const outsideCaviteLabels = <?php echo $tagaytayLabels; ?>

				const data1 = {
					labels: <?php echo $tagaytayLabels; ?>, // ['Tagaytay']
					datasets: [{
						label: 'Within Tagaytay [City]',
						data: <?php echo $tagaytayCounts; ?>, // [30]
						backgroundColor: ['#4CAF50', '#2196F3', '#FFC107']
					}]
				};


				const data2 = {
					labels: <?php echo $barangayLabels;?>,
					datasets: [{
						label: 'Within Tagaytay [Barangay]',
						data: <?php echo $barangayCounts;?>,
						backgroundColor: ['#FF9800', '#8BC34A', '#FF5722']
					}]
				};
				const data3 = {
					labels: <?php echo $withinCaviteLabels; ?>,
					datasets: [{
						label: 'Within Cavite excluding Tagaytay',
						data: <?php echo $withinCaviteCounts; ?>,
						backgroundColor: ['#F44336', '#E91E63', '#9C27B0']
					}]
				};

				
				const data4 = {
					labels: <?php echo $outsideCaviteLabels; ?>,
					datasets: [{
						label: 'Outside Cavite ',
						data: <?php echo $outsideCaviteCounts; ?>,
						backgroundColor: ['#F44336', '#E91E63', '#9C27B0']
					}]
				};
				
				const data5 = {
					labels: <?php echo $caseLabels; ?>,
					datasets: [{
						label: 'Cases',
						data: <?php echo $caseCounts; ?>,
						backgroundColor: ['#F44336', '#E91E63', '#9C27B0']
					}]
				};
				
				const data6 = {
					labels: ['G', 'H', 'I'],
					datasets: [{
						label: 'Clients under 17',
						data: <?php echo $ageData; ?>,
						backgroundColor: ['#F44336', '#E91E63', '#9C27B0']
					}]
				};
				
				const data7 = {
				labels: genderLabels,
				datasets: [{
					label: 'Clients based on Gender',
					data: <?php echo $genderCounts; ?>,
					backgroundColor: ['#4CAF50', '#2196F3'] // Adjust colors as needed
				}]
			};

				const config1 = {
					type: 'bar',
						data: {
							labels: tagaytayLabels,
							datasets: [{
								label: 'Patients from Tagaytay',
								data: tagaytayCounts,
								backgroundColor: '#4CAF50'
							}]
						},
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
                data: {
                    labels: ageLabels,
                    datasets: [{
                        label: 'Clients under 17',
                        data: ageData,
                        backgroundColor: '#F44336'
                    }]
                },
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