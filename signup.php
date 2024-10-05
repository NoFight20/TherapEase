<?php
session_start();

// Import Firebase configuration
include("connection.php");

// Unset all server-side variables
$_SESSION["user"] = "";
$_SESSION["usertype"] = "";

// Set the new timezone
date_default_timezone_set('Asia/Kolkata');
$date = date('Y-m-d');
$_SESSION["date"] = $date;

if ($_POST) {
    // Store barangay as '0' if the selected city is not Tagaytay
    $barangay = ($_POST['pcity'] === 'Tagaytay') ? $_POST['pbrgy'] : '0';

    // Prepare personal data
    $_SESSION["personal"] = [
        'fname' => $_POST['fname'],
        'lname' => $_POST['lname'],
        'dob' => $_POST['dob'],
        'pprovince' => $_POST['pprovince'],
        'pcity' => $_POST['pcity'],
        'pbrgy' => $barangay,
        'pgender' => $_POST['pgender'],
        'civil_status' => $_POST['civil'],
        'type' => $_POST['p']
    ];

    // Write data to Firebase
    $newUser = [
        'fname' => $_POST['fname'],
        'lname' => $_POST['lname'],
        'dob' => $_POST['dob'],
        'province' => $_POST['pprovince'],  // Adjusted to match JSON structure
        'city' => $_POST['pcity'],          // Adjusted to match JSON structure
        'barangay' => $barangay,
        'gender' => $_POST['pgender'],      // Adjusted to match JSON structure
        'civil_status' => $_POST['civil'],
        'type' => $_POST['p']
    ];

    // Push data to Firebase under 'users' reference
    $newPost = $database->getReference('users')->push($newUser);

    // Optionally, store in 'webuser' reference if needed
    $email = $_SESSION['personal']['email'] ?? ''; // If email is part of the session
    if (!empty($email)) {
        $database->getReference('webuser')->push([
            'email' => $email,
            'type' => 'p'  // Assuming this is a patient
        ]);
    }

    // Redirect to create account confirmation or another page
    header("Location: create-account.php");
    exit();
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
    <title>Sign Up</title>
</head>
<body>
    <center>
    <div class="container">
        <table border="0">
            <tr>
                <td colspan="2">
                    <p class="header-text">Let's Get Started</p>
                    <p class="sub-text">Add Your Personal Details to Continue</p>
                </td>
            </tr>
            <tr>
                <form action="" method="POST">
                <td class="label-td" colspan="2">
                    <label for="name" class="form-label">Name: </label>
                </td>
            </tr>
            <tr>
                <td class="label-td">
                    <input type="text" name="fname" class="input-text" placeholder="First Name" required>
                </td>
                <td class="label-td">
                    <input type="text" name="lname" class="input-text" placeholder="Last Name" required>
                </td>
            </tr>
            <tr>
                <td class="label-td" colspan="2">
                    <label for="province" class="form-label">Province: </label>
                    <select id="province" name="pprovince" class="input-text" onchange="updateCity()">
                        <option value="">Select Province</option>
                        <option value="Metro Manila">Metro Manila</option>
                        <option value="Cavite">Cavite</option>
                        <option value="Laguna">Laguna</option>
                    </select>
                    <label for="city" class="form-label">City:</label>
                    <select id="city" name="pcity" class="input-text" onchange="updateBarangay()">
                        <option value="">Select City</option>
                    </select>
                    <label for="barangay" class="form-label" id="barangayLabel" style="display: none;">Barangay:</label>
                    <select id="barangay" name="pbrgy" class="input-text">
                        <option value="">Select Barangay</option>
                        <!-- Barangay options will be appended here -->
                    </select>
                    <script>
                        function updateCity() {
                            const province = document.getElementById('province').value;
                            const city = document.getElementById('city');
                            const barangay = document.getElementById('barangay');
                            const barangayLabel = document.getElementById('barangayLabel');

                            city.innerHTML = '<option value="">Select City</option>';
                            barangay.innerHTML = '<option value="">Select Barangay</option>';
                            barangay.style.display = 'none';
                            barangayLabel.style.display = 'none';

                            let cities = [];
                            if (province === 'Metro Manila') {
                                cities = ['Makati', 'Manila', 'Las Piñas'];
                            } else if (province === 'Cavite') {
                                cities = ['Bacoor', 'Dasmariñas', 'General Trias', 'Imus', 'Silang', 'Tagaytay', 'Trece Martires'];
                            } else if (province === 'Laguna') {
                                cities = ['Biñan', 'Cabuyao', 'Calamba'];
                            }

                            cities.forEach(cityName => {
                                const option = document.createElement('option');
                                option.value = cityName; 
                                option.textContent = cityName;
                                city.appendChild(option);
                            });
                        }

                        function updateBarangay() {
                            const city = document.getElementById('city').value;
                            const barangay = document.getElementById('barangay');
                            const barangayLabel = document.getElementById('barangayLabel');

                            barangay.innerHTML = '<option value="">Select Barangay</option>';

                            if (city === 'Tagaytay') {
                                barangay.style.display = 'block';
                                barangayLabel.style.display = 'block';

                                const barangayList = [
                                    "Asisan", "Bagong Tubig", "Calabuso", "Dapdap East", "Dapdap West", "Francisco", 
                                    "Guinhawa North", "Guinhawa South", "Iruhin East", "Iruhin South", "Iruhin West", 
                                    "Kaybagal Central", "Kaybagal North", "Kaybagal South (Poblacion)", "Mag-Asawang Ilat", 
                                    "Maharlika East", "Maharlika West", "Maitim 2nd Central", "Maitim 2nd East", 
                                    "Maitim 2nd West", "Mendez Crossing East", "Mendez Crossing West", "Neogan", 
                                    "Patutong Malaki North", "Patutong Malaki South", "Sambong", "San Jose", 
                                    "Silang Junction North", "Silang Junction South", "Sungay East", "Sungay West", 
                                    "Tolentino East", "Tolentino West", "Zambal"
                                ];

                                barangayList.forEach(barangayName => {
                                    const option = document.createElement('option');
                                    option.value = barangayName;
                                    option.textContent = barangayName;
                                    barangay.appendChild(option);
                                });
                            } else {
                                barangay.style.display = 'none';
                                barangayLabel.style.display = 'none';
                            }
                        }
                    </script>
                </td>
            </tr>
            <tr>
                <td class="label-td" colspan="2">
                    <label for="civil" class="form-label">Civil Status: </label>
                </td>
            </tr>
            <tr>
                <td class="label-td" colspan="2">
                    <select id="civil" name="civil" class="input-text" required>
                        <option value="">Select your Civil Status</option>
                        <option value="Single">Single</option>
                        <option value="Married">Married</option>
                        <option value="Divorced">Divorced</option>
                    </select>
                </td>
            </tr>
            <tr>
                <td class="label-td" colspan="2">
                    <label for="pgender" class="form-label">Gender: </label>
                </td>
            </tr>
            <tr>
                <td class="label-td" colspan="2">
                    <input type="text" name="pgender" class="input-text" placeholder=" MALE / FEMALE" required>
                </td>
            </tr>
            <tr>
                <td class="label-td" colspan="2">
                    <label for="dob" class="form-label">Date of Birth: </label>
                </td>
            </tr>
            <tr>
                <td class="label-td" colspan="2">
                    <input type="date" name="dob" class="input-text" required>
                </td>
            </tr>
            <tr>
                <td>
                    <input type="reset" value="Reset" class="login-btn btn-primary-soft btn">
                </td>
                <td>
                    <input type="submit" value="Next" class="login-btn btn-primary btn">
                </td>
            </tr>
            <tr>
                <td colspan="2">
                    <br>
                    <label for="" class="sub-text" style="font-weight: 280;">Already have an account&#63; </label>
                    <a href="" class="hover-link1 non-style-link">Login</a>
                    <br><br><br>
                </td>
            </tr>
            </form>
        </table>
    </div>
</center>
</body>
</html>
