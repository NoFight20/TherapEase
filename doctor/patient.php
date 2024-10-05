<?php

//learn from w3schools.com

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


//import database
include("../connection.php");

$userrow = $database->query("select * from doctor where docemail='$useremail'");
$userfetch=$userrow->fetch_assoc();
$userid= $userfetch["docid"];
$username=$userfetch["docname"];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_case'])) {
    $pid = $_POST['pid'];
    $condition_id = $_POST['condition_id'];
    $description = $_POST['description'];

    if (empty($pid) || empty($condition_id) || empty($description)) {
        header("Location: patient.php?error=missing_fields");
        exit();
    }

    $stmt = $database->prepare("UPDATE patient SET pcase = ?, pcaseDesc = ? WHERE pid = ?");
    $stmt->bind_param("isi", $condition_id, $description, $pid);

    if ($stmt->execute()) {
        header("Location: patient.php?success=case_added");
    } else {
        header("Location: patient.php?error=case_add_failed");
    }

    $stmt->close();
}

$condition_result = $database->query("SELECT * FROM conditions");
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
        
    <title>Patients</title>
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
                    <td class="menu-btn" >
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
                    <td class="menu-btn menu-active">
                        <a href="patient.php" class="non-style-link-menu  non-style-link-menu-active"><div><p class="menu-text">My Clients</p></a></div>
                    </td>
                </tr>
				<tr class="menu-row" >
                    <td class="menu-btn">
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
        <?php       

                    $selecttype="My";
                    $current="My patients Only";
                    if($_POST){

                        if(isset($_POST["search"])){
                            $keyword=$_POST["search12"];
                            
                            $sqlmain= "select * from patient where pemail='$keyword' or pname='$keyword' or pname like '$keyword%' or pname like '%$keyword' or pname like '%$keyword%' ";
                            $selecttype="my";
                        }
                        
                        if(isset($_POST["filter"])){
                            if($_POST["showonly"]=='all'){
                                $sqlmain= "select * from patient";
                                $selecttype="All";
                                $current="All patients";
                            }else{
                                $sqlmain= "select * from appointment inner join patient on patient.pid=appointment.pid inner join schedule on schedule.scheduleid=appointment.scheduleid where schedule.docid=$userid;";
                                $selecttype="My";
                                $current="My patients Only";
                            }
                        }
                    }else{
                        $sqlmain= "select * from appointment inner join patient on patient.pid=appointment.pid inner join schedule on schedule.scheduleid=appointment.scheduleid where schedule.docid=$userid;";
                        $selecttype="My";
                    }
                ?>

        <div class="dash-body">
            <table border="0" width="100%" style=" border-spacing: 0;margin:0;padding:0;margin-top:25px; ">
                <tr >
                    <td width="13%">

                    <a href="patient.php" ><button  class="login-btn btn-primary-soft btn btn-icon-back"  style="padding-top:11px;padding-bottom:11px;margin-left:20px;width:125px"><font class="tn-in-text">Back</font></button></a>
                        
                    </td>
                    <td>
                        
                        <form action="" method="post" class="header-search">

                            <input type="search" name="search12" class="input-text header-searchbar" placeholder="Search Patient name or Email" list="patient">&nbsp;&nbsp;
                            
                            <?php
                                echo '<datalist id="patient">';
                                $list11 = $database->query($sqlmain);
                               //$list12= $database->query("select * from appointment inner join patient on patient.pid=appointment.pid inner join schedule on schedule.scheduleid=appointment.scheduleid where schedule.docid=1;");

                                for ($y=0;$y<$list11->num_rows;$y++){
                                    $row00=$list11->fetch_assoc();
                                    $d=$row00["pname"];
                                    $c=$row00["pemail"];
                                    echo "<option value='$d'><br/>";
                                    echo "<option value='$c'><br/>";
                                };

                            echo ' </datalist>';
?>
                            
                       
                            <input type="Submit" value="Search" name="search" class="login-btn btn-primary btn" style="padding-left: 25px;padding-right: 25px;padding-top: 10px;padding-bottom: 10px;">
                        
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
                        <p class="heading-main12" style="margin-left: 45px;font-size:18px;color:rgb(49, 49, 49)"><?php echo $selecttype." Patients (".$list11->num_rows.")"; ?></p>
                    </td>
                    
                </tr>
                <tr>
                    <td colspan="4" style="padding-top:0px;width: 100%;" >
                        <center>
                        <table class="filter-container" border="0" >
 
                        <form action="" method="post">
                        
                        <td  style="text-align: right;">
                        Show Details About : &nbsp;
                        </td>
                        <td width="30%">
                        <select name="showonly" id="" class="box filter-container-items" style="width:90% ;height: 37px;margin: 0;" >
                                    <option value="" disabled selected hidden><?php echo $current   ?></option><br/>
                                    <option value="my">My Clients Only</option><br/>
                                    <option value="all">All Clients</option><br/>
                                    

                        </select>
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
                        <table width="93%" class="sub-table scrolldown"  style="border-spacing:0;">
                        <thead>
                        <tr>
                                <th class="table-headin">
                                    
                                
                                Name
                                
                                </th>
                                <th class="table-headin">
                                    
                                
                                Gender
                                    
                                </th>
                                <th class="table-headin">
                                
                            
                                Telephone
                                
                                </th>
                                <th class="table-headin">
                                    Email
                                </th>
                                <th class="table-headin">
                                    
                                    Date of Birth
                                    
                                </th>
                                <th class="table-headin">
                                    
                                    Case
                                    
                                </th>
                                <th class="table-headin">
                                    
                                    Events
                                    
                                </tr>
                        </thead>
                        <tbody>
                        
                            <?php

                                
                                $result= $database->query($sqlmain);
                                //echo $sqlmain;
                                if($result->num_rows==0){
                                    echo '<tr>
                                    <td colspan="4">
                                    <br><br><br><br>
                                    <center>
                                    <img src="../img/notfound.svg" width="25%">
                                    
                                    <br>
                                    <p class="heading-main12" style="margin-left: 45px;font-size:20px;color:rgb(49, 49, 49)">We  couldnt find anything related to your keywords !</p>
                                    <a class="non-style-link" href="patient.php"><button  class="login-btn btn-primary-soft btn"  style="display: flex;justify-content: center;align-items: center;margin-left:20px;">&nbsp; Show all Patients &nbsp;</font></button>
                                    </a>
                                    </center>
                                    <br><br><br><br>
                                    </td>
                                    </tr>';
                                    
                                }
                                else{
                                for ( $x=0; $x<$result->num_rows;$x++){
                                    $row=$result->fetch_assoc();
                                    $pid=$row["pid"];
                                    $name=$row["pname"];
                                    $email=$row["pemail"];
                                    $gender=$row["pgender"];
                                    $dob=$row["pdob"];
                                    $tel=$row["ptel"];
                                    $caseId=$row["pcase"];
                                    $caseGet = $database->query("SELECT condition_name FROM conditions WHERE condition_id='$caseId'");
                                    $case = $caseGet->fetch_assoc();

                                    if ($case) {
                                        $caseName = $case["condition_name"];
                                    } else {
                                        $caseName = "No case found";
                                    }


                                    echo '<tr>
                                        <td> &nbsp;'.
                                        substr($name,0,35)
                                        .'</td>
                                        <td>
                                        '.substr($gender,0,12).'
                                        </td>
                                        <td>
                                            '.substr($tel,0,10).'
                                        </td>
                                        <td>
                                        '.substr($email,0,20).'
                                         </td>
                                        <td>
                                        '.substr($dob,0,10).'
                                        </td>
                                        <td>
                                        '.substr($caseName,0,30).'
                                        </td>
                                        <td >
                                        <div style="display:flex;justify-content: center;">
                                     
                                       <a href="?action=view&id='.$pid.'" class="non-style-link">
                                            <button class="btn-primary-soft btn button-icon btn-add-case" style="padding-left: 40px;padding-top: 12px;padding-bottom: 12px;margin-top: 10px;">
                                                <font class="tn-in-text">Add Case</font>
                                            </button>
                                        </a>
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
    $sqlmain = "SELECT * FROM patient WHERE pid='$id'";
    $result = $database->query($sqlmain);
    $row = $result->fetch_assoc();
    $name = $row["pname"];
    $email = $row["pemail"];
    $gender = $row["pgender"];
    $dob = $row["pdob"];
    $tele = $row["ptel"];
    $address = $row["paddress"];
    echo '
    <div id="popup-add-case-' . $id . '" class="overlay">
        <div class="popup">
            <center>
                <a class="close" href="patient.php">&times;</a>
                <div class="content">
                    <p style="padding: 0;margin: 0;text-align: left;font-size: 25px;font-weight: 500;">Add Case for ' . $name . '</p>
                    <br><br>
                    <form action="" method="post">
                        <input type="hidden" name="pid" value="' . $id . '">
                        <input type="hidden" name="condition_name" id="condition_name" value="">
                        <table width="80%" class="sub-table scrolldown add-doc-form-container" border="0">
                            <tr>
                                <td class="label-td">
                                    <label for="condition" class="form-label">Condition: </label>
                                </td>
                                <td>
                                    <select name="condition_id" class="box" required>
                                        <option value="" disabled selected>Select Condition</option>';
                                        while ($condition_row = $condition_result->fetch_assoc()) {
                                            $condition_id = $condition_row['condition_id'];
                                            $condition_name = $condition_row['condition_name'];
                                            echo '<option value="' . $condition_id . '" data-name="' . $condition_name . '">' . $condition_name . '</option>';
                                        }
                                    echo '
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td class="label-td">
                                    <label for="description" class="form-label">Description: </label>
                                </td>
                                <td>
                                    <textarea name="description" class="box" required></textarea>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2" style="text-align: center;">
                                    <input type="submit" name="add_case" value="Add Case" class="login-btn btn-primary-soft btn">
                                </td>
                            </tr>
                        </table>
                    </form>
                </div>
            </center>
            <br><br>
        </div>
    </div>';
}
?>
<script>
document.getElementById('condition_id').addEventListener('change', function() {
    var selectedOption = this.options[this.selectedIndex];
    document.getElementById('condition_name').value = selectedOption.getAttribute('data-name');
});

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