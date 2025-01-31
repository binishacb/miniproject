<?php
session_start();
include('dbconnection.php');
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];
    $hashed_password = md5($password);
    $sql_user = "SELECT * FROM login WHERE email='$email' AND password='$hashed_password'";
    $result_user = $con->query($sql_user);
    if (!$result_user) {
        die("SQL query failed: " . $con->error);
    }

    if ($result_user->num_rows > 0) {
        $user_row = $result_user->fetch_assoc();
        $role_id = $user_row['role_id'];
        $verification_status = $user_row['verify_status'];

        if ($role_id == 2) {
            if ($verification_status == 1) {
                // Farmer login
                $_SESSION['useremail'] = $email;
                $_SESSION['usertype'] = 'farmer';
                echo "<script>alert('Farmer login successful.')</script>";
                header('Location: splashpage.php');
                exit();
            } else {
                echo "<script>alert('Email not verified for farmers.')</script>";
            }
        } elseif ($role_id == 1) {
            // Admin login
            $_SESSION['useremail'] = $email;
            $_SESSION['usertype'] = 'admin';
            echo "<script>alert('Admin login successful.')</script>";
            header('Location: dashboard_admin.php');
            exit();
        } elseif ($role_id == 3) {
            // Officer login

            $officerquery = "SELECT officer.designation_id FROM officer JOIN login ON officer.log_id = login.log_id WHERE login.email = '$email'";
            $result = mysqli_query($con, $officerquery);
            $row = mysqli_fetch_assoc($result);
            $desig_id = $row['designation_id'];
            if ($desig_id == 1) {
                $_SESSION['useremail'] = $email;
                $_SESSION['usertype'] = 'agricultural_officer';
                header('Location:dashboard_ao.php');
                exit();
            } elseif ($desig_id == 2) {
                $_SESSION['useremail'] = $email;
                $_SESSION['usertype'] = 'assistant_officer';
                header('Location:dashboard_officer.php');
                exit();
            }
        } elseif ($role_id == 4) {
            // Vendor login
            $sql_vendor = "SELECT v.status, l.* FROM vendor AS v INNER JOIN login AS l ON l.log_id = v.log_id WHERE l.email = '$email' AND l.password = '$hashed_password'";
            $result_vendor = $con->query($sql_vendor);

            if (!$result_vendor) {
                die("SQL query failed: " . $con->error);
            }

            if ($result_vendor->num_rows > 0) {
                $vendor_row = $result_vendor->fetch_assoc();
                $approval_status = $vendor_row['status'];

                if ($approval_status == 1) {
                    // Vendor login successful
                    $_SESSION['useremail'] = $email;
                    $_SESSION['usertype'] = 'vendor';
                    echo "<script>alert('Vendor login successful.')</script>";
                    header('Location: ./vendor_details/dashboard_vendor.php');
                    exit();
                } else {
                    // Application not approved
                    echo "<script>alert('Application is not approved. Check your mail...')</script>";
                    // header('Location: login.php');
                    // exit();
                }
            } else {
                // Incorrect email or password
                echo "<script>alert('Incorrect email or password.')</script>";
            }
        }
    } else {
        // If no user is found, display an error message
        echo "<script>alert('Invalid email id or password..')</script>";
    }
}
$con->close();
?>

<!DOCTYPE html>
<!--
<div id="google_element">
<script src="http://translate.google.com/translate_a/element.js?cb=loadGoogleTranslate"></script>
                            <script >
                                function loadGoogleTranslate(){
                                   new google.translate.TranslateElement("google_element");
                                }
                            </script>
  </div>
-->

<head>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        /* Additional CSS styles */
        .card {
            max-width: 400px;
            margin: 0 auto;
            /* Center the card horizontally */
            box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2);
            background-color: rgba(255, 255, 255, 0.8);

            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.3);
            /* Drop shadow for the form container */
        }

        .card-body {
            padding: 20px;
        }

        .form-group {
            margin-bottom: 15px;
        }

        .btn-primary {
            width: 100%;

        }

        body {
            /* background-color: #4CAF50;*/
            /* Green background color */
            /* background: linear-gradient(45deg, #4CAF50, #FFC107); */
            background: url("./images/farmer.jpg") no-repeat;
            background-size: cover;
            /* Gradient from green to orange */
        }
    </style>
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
</head>

<body>
    <title>Login</title>
    </head>

    <body>
        <?php
        include('navbar/public_navbar.php')
        ?>
        <div class="container mt-5">
            <div class="row justify-content-center">
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title text-center">Login</h5>
                            <form class="needs-validation" method="post" action="#" onsubmit="return validateForm()" novalidate>
                                <div class="form-group">
                                    <label for="email">Email:</label>
                                    <input type="text" class="form-control" id="email" name="email" oninput="validateEmail(this.value)" required>
                                    <div id="email-warning" class="invalid-feedback"></div>
                                    <div id="email-error"></div>
                                </div>

                                <div class="form-group">
                                    <label for="password">Password:</label>
                                    <input type="password" class="form-control" id="password" name="password" oninput="validatePassword(this.value)" required>
                                    <div id="password-warning" class="invalid-feedback"></div>
                                    <div id="password-error"></div>
                                </div>

                                <div class="form-group">
                                    <button type="submit" id="submit" name="submit" class="btn btn-primary">Login</button>
                                </div>
                            </form>
                            <p class="text-center">Don't have an account? <a href="registration.php">Register</a></p>
                            <center> <a href="forgot_password.php">Forgot Password ? </a></center>
                        </div>
                    </div>
                </div>
            </div>
        </div>




        <!-- Include Bootstrap JS (optional) -->
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
        <!-- Include JavaScript for form validation (assuming you have this script) -->
        <script src="your-validation-script.js"></script>

        <script>
            function validateEmail(email) {
                const emailRegex = /^[A-Za-z0-9._%+-]+@[A-Za-z0-9.-]+\.[A-Za-z]{2,}$/;
                const emailInput = document.getElementById('email');
                const emailWarning = document.getElementById('email-warning');
                const emailError = document.getElementById('email-error');

                if (email === '') {
                    emailWarning.textContent = 'Warning: Email field is empty.';
                    emailInput.style.border = '2px solid red';
                    emailWarning.style.color = 'red';
                    emailError.textContent = '';
                    return false; // Return false to prevent form submission
                }

                if (emailRegex.test(email)) {
                    emailInput.style.border = '2px solid green';
                    emailWarning.textContent = '';
                    emailError.textContent = '';
                    return true; // Return true if validation is successful
                } else {
                    emailInput.style.border = '2px solid red';
                    emailWarning.textContent = '';
                    emailError.style.color = 'red';
                    emailError.textContent = 'Error: Invalid email address';
                    return false; // Return false to prevent form submission
                }
            }

            function validatePassword(password) {
                const passwordInput = document.getElementById('password');
                const passwordWarning = document.getElementById('password-warning');
                const passwordError = document.getElementById('password-error');

                if (password === '') {
                    passwordWarning.textContent = 'Warning: Password field is empty.';
                    passwordInput.style.border = '2px solid red';
                    passwordWarning.style.color = 'red';
                    passwordError.textContent = '';
                    return false; // Return false to prevent form submission
                } else {
                    passwordInput.style.border = '2px solid green';
                    passwordWarning.textContent = '';
                    passwordError.textContent = '';
                    return true; // Return true if validation is successful
                }
            }

            function validateForm() {
                // Check both email and password validation results
                const isEmailValid = validateEmail(document.getElementById('email').value);
                const isPasswordValid = validatePassword(document.getElementById('password').value);

                // Only allow form submission if both validations are true
                return isEmailValid && isPasswordValid;
            }
        </script>
        <br><br> <br>
        <br>
        <br><br><br> <br>
        <br>
        <br><br><br> <br>
        <br>
        <br>

        <?php
        include('footer/footer.php')
        ?>
    </body>

    </html>