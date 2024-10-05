<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/animations.css">  
    <link rel="stylesheet" href="../css/main.css">  
    <link rel="stylesheet" href="../css/admin.css">
        
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
    <div class="mobile-header">Doctors</div>

    <!-- Hamburger Menu -->
    <div id="hamburger-menu">
        <div class="bar"></div>
        <div class="bar"></div>
        <div class="bar"></div>
    </div>

    <?php

    //learn from w3schools.com

    session_start();

    if(isset($_SESSION["user"])){
        if(($_SESSION["user"])=="" or $_SESSION['usertype']!='i'){
            header("location: ../login.php");
        }

    }else{
        header("location: ../login.php");
    }
    
    

    //import database
    include("../connection.php");

    $loginAttempts = $database->query("SELECT * FROM login_attempts ORDER BY attempt_time");

    // Generate table rows
    $loginAttemptRows = "";
    while ($row = $loginAttempts->fetch_assoc()) {
        $timestamp = $row['attempt_time'];
        $description = $row['aemail'];
        $loginAttemptRows .= "<tr>
            <td>$timestamp</td>
            <td>$description</td>
        </tr>";
    }

    ?>
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
                                    <p class="profile-title">IT</p>
                                    <p class="profile-subtitle">it@edoc.com</p>
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
                        <a href="logs.php" class="non-style-link-menu non-style-link-menu-active"><div><p class="menu-text">Logs</p></a></div></a>
                    </td>
                </tr>
                <tr class="menu-row">
                    <td class="menu-btn">
                        <a href="clients.php" class="non-style-link-menu"><div><p class="menu-text">Clients</p></a></div>
                    </td>
				</tr>
				<tr class="menu-row">
                    <td class="menu-btn">
                        <a href="secretary.php" class="non-style-link-menu"><div><p class="menu-text">Secretary</p></a></div>
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
                        <p style="font-size: 23px;padding-left:12px;font-weight: 600;">Logs</p>
                                           
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
				
				<!-- Content of Logs -->
				<tr >
					<td colspan="4" >
					<center>
					
					<div class="abc scroll">
                        <table width="50%" class="sub-table scrolldown" border="0">
							<thead>
								<tr>
									<th class="table-headin">
										Time
									</th>
									<th class="table-headin">      
										Login Attempts
									</th>
								</tr>
							</thead>
							
							<tbody>
							<?php echo $loginAttemptRows; ?>
							</tbody>
						</table>
					</div>
				</tr >				
	</body>
</html>