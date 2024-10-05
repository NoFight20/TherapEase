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

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['adminName'];
    $email = $_POST['adminEmail'];
    $password = password_hash($_POST['adminPassword'], PASSWORD_DEFAULT);

    $query = "INSERT INTO admins (name, email, password) VALUES ('$name', '$email', '$password')";

    if ($database->query($query) === TRUE) {
        header("Location: doctors.php");
    } else {
        echo "Error: " . $query . "<br>" . $database->error;
    }
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
                    <td class="menu-btn">
                        <a href="logs.php" class="non-style-link-menu"><div><p class="menu-text">Logs</p></a></div></a>
                    </td>
                </tr>
                <tr class="menu-row">
                    <td class="menu-btn">
                        <a href="clients.php" class="non-style-link-menu"><div><p class="menu-text">Clients</p></a></div>
                    </td>
				</tr>
				<tr class="menu-row">
                    <td class="menu-btn menu-active">
                        <a href="secretary.php" class="non-style-link-menu non-style-link-menu-active"><div><p class="menu-text">Secretary</p></a></div>
                    </td>
				</tr>
            </table>
        </div>
		
		<div class="dash-body">
			<table border="0" width="100%" style=" border-spacing: 0;margin:0;padding:0;margin-top:25px; ">
			<tr >
				<td>
					<form action="" method="post" class="header-search">
						<input type="search" name="search" class="input-text header-searchbar" placeholder="Search Secretary name or Email" list="secretary">&nbsp;&nbsp;
						<input type="Submit" value="Search" class="login-btn btn-primary btn" style="padding-left: 25px;padding-right: 25px;padding-top: 10px;padding-bottom: 10px;">
					</form> 
				</td>
				
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
            </tr >
			
			<tr >
                <td colspan="2" style="padding-top:30px;">
                    <p class="heading-main12" style="margin-left: 45px;font-size:20px;color:rgb(49, 49, 49)">Add Secretary</p>
                </td>
                <td colspan="2">
                <a href="#" class="non-style-link" onclick="showPopup()"><button class="login-btn btn-primary btn button-icon" style="display: flex;justify-content: center;align-items: center;margin-left:75px;background-image: url('../img/icons/add.svg');">Add New</button></a>
                </a></td>
            </tr>
            <tr>
                <td colspan="4" style="padding-top:10px;">
                    <p class="heading-main12" style="margin-left: 45px;font-size:18px;color:rgb(49, 49, 49)">All Secretary (<?php // ADD SECRETARY DATABASE HERE ?>)</p>
                </td>       
            </tr>
			
			<?php
                    $query = "SELECT * FROM admin";
                    $result = $database->query($query);

                    if ($result->num_rows > 0) {
                        echo '<table width="93%" class="sub-table scrolldown" border="0">';
                        echo '<thead>
                                <tr>
                                    <th class="table-headin">Admin Name</th>
                                    <th class="table-headin">Email</th>
                                    <th class="table-headin">Password Hash</th>
                                </tr>
                            </thead>';
                        while ($row = $result->fetch_assoc()) {
                            echo "<tr>
                                    <td>{$row['aname']}</td>
                                    <td>{$row['aemail']}</td>
                                    <td>{$row['apassword']}</td>
                                </tr>";
                        }
                        echo '</table>';
                    } else {
                        echo "<p>No admin accounts found.</p>";
                    }
                    ?>                   

        <div class="overlay" id="addAdminPopup">
    <div class="popup">
        <a href="#" class="close" onclick="closePopup()">&times;</a>
        <h2>Add New Admin</h2>
        <form action="add_admin.php" method="POST">
            <label for="adminName">Admin Name:</label>
            <input type="text" id="adminName" name="adminName" required><br>
            <label for="adminEmail">Email:</label>
            <input type="email" id="adminEmail" name="adminEmail" required><br>
            <label for="adminPassword">Password:</label>
            <input type="password" id="adminPassword" name="adminPassword" required><br>
            <button type="submit" class="login-btn btn-primary btn">Add Admin</button>
        </form>
    </div>
</div>

<script>
    document.getElementById('hamburger-menu').addEventListener('click', function () {
    document.querySelector('.menu').classList.toggle('active');
    this.classList.toggle('active');
});

function closePopup() {
    document.getElementById('addAdminPopup').style.display = 'none';
}

function showPopup() {
    document.getElementById('addAdminPopup').style.display = 'block';
}

</script>
    </div>
</body>
</html>	
			