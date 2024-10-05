<?php
session_start();

// Unset all server-side variables
$_SESSION["user"] = "";
$_SESSION["usertype"] = "";

// Set the new timezone
date_default_timezone_set('Asia/Kolkata');
$date = date('Y-m-d');
$_SESSION["date"] = $date;

// Import Firebase configuration
include("connection.php");

if ($_POST) {
    // Gather user input from session and POST request
    $fname = $_SESSION['personal']['fname'];
    $lname = $_SESSION['personal']['lname'];
    $name = $fname . " " . $lname; // Combined name
    $province = $_SESSION['personal']['pprovince'];
    $city = $_SESSION['personal']['pcity'];
    $barangay = ($city === 'Tagaytay') ? $_SESSION['personal']['pbrgy'] : '0';
    $gender = $_SESSION['personal']['pgender'];
    $dob = $_SESSION['personal']['dob'];
    $email = trim($_POST['newemail']);
    $tele = trim($_POST['tele']);
    $civil = $_SESSION['personal']['civil_status'];
    $newpassword = $_POST['newpassword'];
    $cpassword = $_POST['cpassword'];

    // Calculate age
    $currentDate = new DateTime($date);
    $birthDate = new DateTime($dob);
    $age = $currentDate->diff($birthDate)->y;

    $passwordPattern = "/^(?=.*[A-Z])(?=.*[a-z])(?=.*\d)(?=.*[\W_]).{8,}$/";

    if ($newpassword === $cpassword) {
        if (preg_match($passwordPattern, $newpassword)) {
            // Check if email already exists in Firebase
            $userRef = $database->getReference('users')->orderByChild('email')->equalTo($email);
            $existingUser = $userRef->getValue();

            if (!empty($existingUser)) {
                $error = '<label for="promter" class="form-label" style="color:rgb(255, 62, 62);text-align:center;">Already have an account for this Email address.</label>';
            } else {
                // Hash the password for security
                $hashedPassword = password_hash($newpassword, PASSWORD_DEFAULT);

                // Create a new user in Firebase
                $newUser = [
                    'name' => $name,  // Store combined name
                    'email' => $email,
                    'gender' => $gender,
                    'password' => $hashedPassword,  // Store hashed password
                    'province' => $province,
                    'city' => $city,
                    'barangay' => $barangay,
                    'dob' => $dob,
                    'tele' => $tele,
                    'age' => $age,
                    'civil_status' => $civil,
                    'type' => 'p'  // Set user type
                ];

                // Store in users reference
                $database->getReference('users')->push($newUser);

                // Store in patients reference
                $newPatient = [
                    'name' => $name,  // Store combined name
                    'email' => $email,
                    'age' => $age,
                    'gender' => $gender,
                    'dob' => $dob,
                    'province' => $province,
                    'city' => $city,
                    'barangay' => $barangay,
                    'tele' => $tele,
                    'civil_status' => $civil,
                    'password' => $hashedPassword,  // Store hashed password
                    'type' => 'p'  // Set user type
                ];

                // Push patient data to Firebase
                $database->getReference('patients')->push($newPatient);

                // Create a record in webuser
                $database->getReference('webuser')->push([
                    'email' => $email,
                    'type' => 'p'
                ]);

                // Set session variables and redirect
                $_SESSION["user"] = $email;
                $_SESSION["usertype"] = "p";
                $_SESSION["username"] = $name;  // Store full name in session
                header('Location: login.php');
                exit;
            }
        } else {
            $error = '<label for="promter" class="form-label" style="color:rgb(255, 62, 62);text-align:center;">Password must be at least 8 characters long, with one capital letter, one special character, and one number.</label>';
        }
    } else {
        $error = '<label for="promter" class="form-label" style="color:rgb(255, 62, 62);text-align:center;">Password Confirmation Error! Reconfirm Password.</label>';
    }
} else {
    $error = '<label for="promter" class="form-label"></label>';
}
?>




<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/animations.css">  
    <link rel="stylesheet" href="css/main.css">  
    <link rel="stylesheet" href="css/signup.css">
        
    <title>Create Account</title>
    <style>
        .container{
            animation: transitionIn-X 0.5s;
        }
    </style>
</head>
<body>
    <center>
    <div class="container">
        <table border="0" style="width: 69%;">
            <tr>
                <td colspan="2">
                    <p class="header-text">Let's Get Started</p>
                    <p class="sub-text">It's Okay, Now Create User Account.</p>
                </td>
            </tr>
            <tr>
                <form action="" method="POST" >
                <td class="label-td" colspan="2">
                    <label for="newemail" class="form-label">Email: </label>
                </td>
            </tr>
            <tr>
                <td class="label-td" colspan="2">
                    <input type="email" name="newemail" class="input-text" placeholder="Email Address" required>
                </td>
                
            </tr>
            <tr>
                <td class="label-td" colspan="2">
                    <label for="tele" class="form-label">Mobile Number: </label>
                </td>
            </tr>
            <tr>
                <td class="label-td" colspan="2">
                    <input type="tel" name="tele" class="input-text"  placeholder="ex: 09123456789" pattern="[0]{1}[0-9]{10}" >
                </td>
            </tr>
            <tr>
                <td class="label-td" colspan="2">
                    <label for="newpassword" class="form-label">Create New Password: </label>
                </td>
            </tr>
            <tr>
                <td class="label-td" colspan="2">
                    <input type="password" name="newpassword" class="input-text" placeholder="New Password" required>
                </td>
            </tr>
            <tr>
                <td class="label-td" colspan="2">
                    <label for="cpassword" class="form-label">Confirm Password: </label>
                </td>
            </tr>
            <tr>
                <td class="label-td" colspan="2">
                    <input type="password" name="cpassword" class="input-text" placeholder="Conform Password" required>
                </td>
            </tr>
     
            <tr>
                
                <td colspan="2">
                    <?php echo $error ?>

                </td>
            </tr>
            
            <tr>
                <td>
                    <input type="reset" value="Reset" class="login-btn btn-primary-soft btn" >
                </td>
                <td>
                    <input type="submit" value="Sign Up" class="login-btn btn-primary btn">
                </td>

            </tr>
            <tr>
                <td colspan="2">
                    <br>
                    <label for="" class="sub-text" style="font-weight: 280;">Already have an account&#63; </label>
                    <a href="login.php" class="hover-link1 non-style-link">Login</a>
                    <br><br><br>
                </td>
            </tr>

                    </form>
            </tr>
        </table>

    </div>
</center>
</body>
</html>