<?php
session_start();

$_SESSION["user"] = "";
$_SESSION["usertype"] = "";

// Set the new timezone
date_default_timezone_set('Asia/Kolkata');
$date = date('Y-m-d');
$_SESSION["date"] = $date;

// Initialize Firebase
include("connection.php");

if ($_POST) {
    $email = $_POST['useremail'];
    $password = $_POST['userpassword'];

    $error = '<label for="promter" class="form-label"></label>';

    // Check if the user exists in Firebase RTDB
    $userRef = $database->getReference('users')->orderByChild('email')->equalTo($email);
    $user = $userRef->getValue();

    // Debug: Log the user retrieved
    error_log('User Retrieved: ' . print_r($user, true));

    if ($user) {
        $user = array_shift($user);  // Get the first matching user

        // Ensure the 'type' key exists
        if (!isset($user['type'])) {
            $error = '<label for="promter" class="form-label" style="color:rgb(255, 62, 62);text-align:center;">User type not found.</label>';
        } else {
            $utype = $user['type'];

            // Debug output
            error_log('User type: ' . $utype);

            // Fetch user details based on user type
            if ($utype == 'p') {
                // Fetch patient details
                $patientsRef = $database->getReference('patients')->orderByChild('email')->equalTo($email);
                $patients = $patientsRef->getValue();

                // Validate the patient data
                if ($patients) {
                    $matchingPatient = array_shift($patients);
                    // Ensure the 'password' key exists
                    if (isset($matchingPatient['password']) && password_verify($password, $matchingPatient['password'])) {
                        $_SESSION['user'] = $email;
                        $_SESSION['usertype'] = 'p';
                        header('Location: patient/index.php');
                        exit();
                    } else {
                        $error = '<label for="promter" class="form-label" style="color:rgb(255, 62, 62);text-align:center;">Wrong credentials: Invalid email or password</label>';
                    }
                } else {
                    $error = '<label for="promter" class="form-label" style="color:rgb(255, 62, 62);text-align:center;">Patient data not found.</label>';
                }
            } elseif ($utype == 'a') {
                // Check admin credentials
                $adminRef = $database->getReference('admin')->orderByChild('email')->equalTo($email);
                $admin = $adminRef->getValue();

                // Ensure admin data is available
                if ($admin) {
                    $admin = array_shift($admin); // Get the first matching admin
                    // Check for password
                    if (isset($admin['password']) && password_verify($password, $admin['password'])) {
                        $_SESSION['user'] = $email;
                        $_SESSION['usertype'] = 'a';
                        header('Location: admin/index.php');
                        exit();
                    } else {
                        $error = '<label for="promter" class="form-label" style="color:rgb(255, 62, 62);text-align:center;">Wrong credentials: Invalid email or password</label>';
                    }
                } else {
                    $error = '<label for="promter" class="form-label" style="color:rgb(255, 62, 62);text-align:center;">Admin data not found.</label>';
                }
            } elseif ($utype == 'd') {
                // Check doctor credentials
                $doctorRef = $database->getReference('doctor')->orderByChild('email')->equalTo($email);
                $doctor = $doctorRef->getValue();

                // Ensure doctor data is available
                if ($doctor) {
                    $doctor = array_shift($doctor); // Get the first matching doctor
                    // Check for password
                    if (isset($doctor['password']) && password_verify($password, $doctor['password'])) {
                        $_SESSION['user'] = $email;
                        $_SESSION['usertype'] = 'd';
                        header('Location: doctor/index.php');
                        exit();
                    } else {
                        $error = '<label for="promter" class="form-label" style="color:rgb(255, 62, 62);text-align:center;">Wrong credentials: Invalid email or password</label>';
                    }
                } else {
                    $error = '<label for="promter" class="form-label" style="color:rgb(255, 62, 62);text-align:center;">Doctor data not found.</label>';
                }
            }
        }
    } else {
        $error = '<label for="promter" class="form-label" style="color:rgb(255, 62, 62);text-align:center;">We can\'t find an account for this email.</label>';
    }
} else {
    $error = '<label for="promter" class="form-label">&nbsp;</label>';
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
    <link rel="stylesheet" href="css/login.css">
    <title>Login</title>
</head>
<body>
    <center>
    <div class="container">
        <table border="0" style="margin: 0;padding: 0;width: 60%;">
            <tr>
                <td>
                    <p class="header-text">Welcome Back!</p>
                </td>
            </tr>
            <div class="form-body">
                <tr>
                    <td>
                        <p class="sub-text">Login with your details to continue</p>
                    </td>
                </tr>
                <tr>
                    <form action="" method="POST" >
                    <td class="label-td">
                        <label for="useremail" class="form-label">Email: </label>
                    </td>
                </tr>
                <tr>
                    <td class="label-td">
                        <input type="email" name="useremail" class="input-text" placeholder="Email Address" required>
                    </td>
                </tr>
                <tr>
                    <td class="label-td">
                        <label for="userpassword" class="form-label">Password: </label>
                    </td>
                </tr>
                <tr>
                    <td class="label-td">
                        <input type="Password" name="userpassword" class="input-text" placeholder="Password" required>
                    </td>
                </tr>
                <tr>
                    <td><br>
                    <?php echo $error ?>
                    </td>
                </tr>
                <tr>
                    <td>
                        <input type="submit" value="Login" class="login-btn btn-primary btn">
                    </td>
                </tr>
                </div>
                <tr>
                    <td>
                        <br>
                        <label for="" class="sub-text" style="font-weight: 280;">Don't have an account&#63; </label>
                        <a href="signup.php" class="hover-link1 non-style-link">Sign Up</a>
                        <br><br><br>
                    </td>
                </tr>          
                    </form>
            </table>
        </div>
    </center>
</body>
</html>
