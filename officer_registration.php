<?php

if (isset($_GET['designation_name'])) {
    $designation_name = $_GET['designation_name'];
    //echo  $designation_name;
} 

session_start();
include('dbconnection.php');
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;
require './vendor/autoload.php';
$k_id = $_GET['k_id'];

function sendemail_verify($email,$rpassword)
{
    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->SMTPAuth = true;
        $mail->Host = 'smtp.gmail.com';
        $mail->Username = 'agrocompanion2023@gmail.com'; // Your Gmail email address
        $mail->Password = 'wwme uijt eygq xqas'; // Your Gmail password or App Password
        $mail->SMTPSecure = 'tls';
        $mail->Port = 587;

        $mail->setFrom('agrocompanion2023@gmail.com', 'Agrocompanion');
        $mail->addAddress($email);

        $mail->isHTML(true);
        $mail->Subject = 'Email from Agrocompanion';
        $officerFirstName = $_POST['firstname'];
        $officerLastName = $_POST['lastname'];
        $officerName = $officerFirstName . ' ' . $officerLastName;
        
        $officerEmail = $_POST['email'];
        $autoGeneratedPassword = $rpassword;
       // $hashed_password = hashPassword($autoGeneratedPassword);
        $email_template = "
        <h2>Dear $officerName</h2>
            
        <h5>Welcome to the team! Your login details are:
        Username: $officerEmail
        Password: $autoGeneratedPassword</h5>
        <br><br>
        ";
        $mail->Body = $email_template;
        $mail->send();
     
        return true;
    } catch (Exception $e) {
        return false;
    }
}

function generateRandomPassword($length = 10) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ@#$%&*';
    $password = '';
    for ($i = 0; $i < $length; $i++) {
        $password .= $characters[rand(0, strlen($characters) - 1)];
    }
    return $password;
}

function hashPassword($password) {
    // Use a secure hashing algorithm, such as bcrypt, for production use
    // For this example, I'm using MD5, but it's not recommended for actual use
    return md5($password);
}

?>

<?php

if (isset($_POST['submit'])) {
    $firstname = $_POST['firstname'];
    $lastname = $_POST['lastname'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];

   
    $rpassword = generateRandomPassword();
    $hashed_password = hashPassword($rpassword);
    
    if (empty($firstname) || empty($lastname) || empty($email) || empty($phone) ) {
        echo "Please fill out all the fields.";
    }  else {
    // Query the 'designation' table to get the ID for the selected designation
    $designation_query = "SELECT designation_id FROM designation WHERE designation_name = '$designation_name'";
    $designation_result = mysqli_query($con, $designation_query);

    if ($designation_row = mysqli_fetch_assoc($designation_result)) {
        $designation_id = $designation_row['designation_id'];


            // Insert data into the 'login' table
            $insert_login_query = "INSERT INTO login(email, password, role_id) VALUES ('$email', '$hashed_password', 3)";
            $insert_login_query_run = mysqli_query($con, $insert_login_query);

            if ($insert_login_query_run) {
                // Get the 'log_id' of the newly inserted 'login' record
                $log_id = mysqli_insert_id($con);

                // Now, insert the officer data with the obtained 'log_id'
                $query_officer = "INSERT INTO officer(firstname, lastname, phone_no, log_id, designation_id, krishibhavan_id) 
                VALUES ('$firstname', '$lastname', '$phone', '$log_id', '$designation_id', '$k_id')";
                $query_officer_run = mysqli_query($con, $query_officer);

                if ($query_officer_run) {
                    if (sendemail_verify($email, $rpassword)) {
                        echo "<script>alert('Officer registered successfully and email is sent'); window.location = 'admin_add_officer.php?krishibhavan_id=" . base64_encode($k_id) . "';</script>";

                    }
                } else {
                    echo "<script>alert('Email cannot be sent'); window.location = 'admin_add_officer.php';</script>";
                }
            } else {
                echo "<script>alert('Registration failed'); window.location = 'admin_add_officer.php';</script>";
            }
         
    } else {
        echo "<script>alert('Designation not found'); window.location = 'admin_add_officer.php';</script>";
    }
}
}
// else{
//     echo "<script>alert('Please fill the required fields'); window.location = 'admin_add_officer.php';</script>";
// }
?>


<html>

<head>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
    body {
        background-color: #4CAF50;
        /* Green background color */
        background: linear-gradient(45deg, #4CAF50, #FFC107);
        /* Gradient from green to orange */
    }

    /*
.registration-container {
    background-color: rgba(255, 255, 255, 0.8); /* Semi-transparent white background for the form container 
    padding: 20px;
    border-radius: 10px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.3); /* Drop shadow for the form container 
}*/
    </style>
    <title>officer Registration</title>
</head>

<body>
    <?php
    include('navbar/navbar_admin.php');
    
?>
    <div class="container mt-5">

        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title text-center">Add <?php echo  $designation_name ?></h5>
                        <form class="needs-validation" method="post" action="#" onsubmit= "return validateForm()">

                            <div class="form-group">
                                <label for="name">First Name:</label>
                                <input type="text" class="form-control" id="firstname" name="firstname"
                                    placeholder="Please fill officer first name "
                                    oninput="validateFirstName(this.value)" required>

                                <div id="firstname-warning" class="invalid-feedback"></div>
                                <div id="firstname-error" class="invalid-feedback"></div>
                            </div>

                            <div class="form-group">
                                <label for="name">Last Name:</label>
                                <input type="text" class="form-control" id="lastname" name="lastname"
                                    placeholder="Please fill officer last name " oninput="validateLastName(this.value)"
                                    required>

                                <div id="lastname-warning" class="invalid-feedback"></div>
                                <div id="lastname-error" class="invalid-feedback"></div>
                            </div>


                            <div class="form-group">
                                <label for="email">Email:</label>
                                <input type="text" class="form-control" id="email" name="email"
                                    placeholder="Enter officer email id" oninput="validateEmail(this.value)" required>

                                <div id="email-warning" class="invalid-feedback"></div>
                                <div id="email-error"></div>
                            </div>


                            <div class="form-group">
                                <label for="phone">Phone Number:</label>
                                <input type="text" class="form-control" id="phone" name="phone"
                                    placeholder="Enter phone number" oninput="validatePhone(this.value)" required>
                                <div id="phone-warning" class="invalid-feedback"></div>
                                <div id="phone-error"></div>
                            </div>

                         
                           
                           
                              </div>



                            <div class="form-group">
                                <center> <button type="submit" name="submit"  class="btn btn-primary">Submit</button>
                                </center>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>
    <br>
    <br>
    <?php
    include('footer/footer.php');
    
?>

    <!-- Include Bootstrap JS (optional) -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <!-- Include JavaScript for form validation (assuming you have this script) -->
    <script src="your-validation-script.js"></script>

    <script>
    function validateFirstName(firstName) {
        const firstNameInput = document.getElementById('firstname');
        const firstNameWarning = document.getElementById('firstname-warning');
        const firstNameError = document.getElementById('firstname-error');
        firstName = firstName.trim();
        firstName = firstName.toUpperCase();
        firstNameInput.value = firstName;

        if (firstName === '') {
            firstNameWarning.textContent = 'Warning: First Name field is empty.';
            firstNameInput.classList.add('is-invalid');
            firstNameError.textContent = '';
            return false;
        } else if (firstName.length < 3) {
            firstNameInput.classList.add('is-invalid');
            firstNameWarning.textContent = '';
            firstNameError.textContent = 'Error: First Name should contain at least 3 letters.';
            return false;
        } else if (!/^[a-zA-Z]+$/.test(firstName)) {
            firstNameInput.classList.add('is-invalid');
            firstNameWarning.textContent = '';
            firstNameError.textContent = 'Error: First Name should not contain numbers or special characters.';
            return false;
        } else if (firstName.length > 30) {
            firstNameInput.classList.add('is-invalid');
            firstNameWarning.textContent = '';
            firstNameError.textContent = 'Error: Name exceeds the maximum character limit of 30.';
            return false;
        } else if (/^(.)\1+$/i.test(firstName)) {
            firstNameInput.classList.add('is-invalid');
            firstNameWarning.textContent = '';
            firstNameError.textContent = 'Error: Name should be meaningful and not consist of repeating characters.';
            return false;
        } else {
            firstNameInput.classList.remove('is-invalid');
            firstNameInput.classList.add('is-valid');
            firstNameInput.style.border = '2px solid green';
            firstNameWarning.textContent = '';
            firstNameError.textContent = '';
            return true;
        }
    }

    function validateLastName(lastName) {
        // Get elements for last name validation
        const lastNameInput = document.getElementById('lastname');
        const lastNameWarning = document.getElementById('lastname-warning');
        const lastNameError = document.getElementById('lastname-error');

        // Remove leading and trailing whitespace
        lastName = lastName.trim();

        // Convert to capital letters
        lastName = lastName.toUpperCase();

        // Update the input field with the capitalized last name
        lastNameInput.value = lastName;
        // Ensure at least two alphabets and convert to capital letters
        if (lastName === '') {
            lastNameWarning.textContent = 'Warning: First Name field is empty.';
            lastNameInput.classList.add('is-invalid');
            lastNameError.textContent = '';
            return false;
        } else if (lastName.length < 1) {
            lastNameInput.classList.add('is-invalid');
            lastNameWarning.textContent = '';
            lastNameError.textContent = 'Error: Last Name should contain at least one alphabets.';
            return false; // Return false to prevent form submission
        } else if (/(.)\1{2,}/i.test(lastName)) {
            lastNameInput.classList.add('is-invalid');
            lastNameWarning.textContent = '';
            lastNameError.textContent = 'Error: Name should be meaningful and not consist of repeating characters.';
            return false;
        } else if (lastName.length > 20) {
            lastNameInput.classList.add('is-invalid');
            lastNameWarning.textContent = '';
            lastNameError.textContent = 'Error: Name exceeds the maximum character limit of 20.';
            return false; // Return false to prevent form submission
        }
        //     else if (lastName.length === 2 && !/^[a-zA-Z ]+$/.test(lastName)) {
        //     lastNameInput.classList.add('is-invalid');
        //     lastNameWarning.textContent = '';
        //     lastNameError.textContent = 'Error: Last Name should contain exactly two alphabets or alphabets with spaces.';
        //     return false;
        // } 
        else if (!/^[a-zA-Z]+$/.test(lastName)) {
            lastNameInput.classList.add('is-invalid');
            lastNameWarning.textContent = '';
            lastNameError.textContent =
                'Error: No numbers and special characters are allowed';
            return false;
        } else {
            // Clear any previous validation messages
            lastNameInput.classList.remove('is-invalid');
            lastNameInput.style.border = '2px solid green';
            lastNameWarning.textContent = '';
            lastNameError.textContent = '';
            return true; // Return true if validation is successful
        }
    }

    function validateEmail(email) {
        const emailInput = document.getElementById('email');
        const emailWarning = document.getElementById('email-warning');
        const emailError = document.getElementById('email-error');

        if (email === '') {
            emailWarning.textContent = 'Warning: Email field is empty.';
            emailInput.classList.add('is-invalid');
            emailError.textContent = '';
            return false; // Return false to prevent form submission
        } else {
            const emailRegex = /^[A-Za-z][A-Za-z0-9._%+-]*@[A-Za-z0-9.-]+\.[A-Za-z]{2,4}$/;
            if (emailRegex.test(email)) {
                emailInput.classList.remove('is-invalid');
                emailInput.classList.add('is-valid');
                emailWarning.textContent = '';
                emailError.textContent = '';
                return true; // Return true if validation is successful
            } else {
                emailInput.classList.add('is-invalid');
                emailWarning.textContent = '';
                emailError.style.color = 'red';
                emailError.textContent = 'Error: Invalid email address';
                return false; // Return false to prevent form submission
            }
        }
    }

    function hasRepeatingDigits(phone) {
        const repeatingDigitsRegex = /(.)\1{5}/; // Matches any digit repeated 6 or more times

        return repeatingDigitsRegex.test(phone);
    }

    function validatePhone(phone) {
        const phoneRegex = /^[0-9]{10}$/;
        const phoneInput = document.getElementById('phone');
        const phoneWarning = document.getElementById('phone-warning');
        const phoneError = document.getElementById('phone-error');

        if (phone === '') {
            phoneWarning.textContent = 'Warning: Phone number field is empty.';
            phoneInput.classList.add('is-invalid');
            phoneError.textContent = '';
            return false; // Return false to prevent form submission
        }

        if (phoneRegex.test(phone)) {
            if (hasRepeatingDigits(phone)) {
                phoneInput.classList.add('is-invalid');
                phoneWarning.textContent = '';
                phoneError.style.color = 'red';
                phoneError.textContent = 'Error: Phone number contains repeating digits.';
                return false; // Return false to prevent form submission
            } else {
                phoneInput.classList.remove('is-invalid');
                phoneInput.classList.add('is-valid');
                phoneWarning.textContent = '';
                phoneError.textContent = '';
                return true; // Return true if validation is successful
            }
        } else {
            phoneInput.classList.add('is-invalid');
            phoneWarning.textContent = '';
            phoneError.style.color = 'red';
            phoneError.textContent = 'Error: Invalid phone number. Please enter a 10-digit number.';
            return false; // Return false to prevent form submission
        }
    }

    function validateSelect(selectId) {
        const select = document.getElementById(selectId);
        const selectWarning = document.getElementById(selectId + '-warning');

        if (select.value === '' || select.value === null) {
            selectWarning.textContent = 'Warning: Please select a valid option.';
            select.classList.add('is-invalid');
            return false;
        } else {
            selectWarning.textContent = '';
            select.classList.remove('is-invalid');
            return true;
        }
    }

    function validateForm() {
        const isfirstNameValid = validateFirstName(document.getElementById('firstname').value);
        const islastNameValid = validateLastName(document.getElementById('lastname').value);
        const isEmailValid = validateEmail(document.getElementById('email').value);
        const isPhoneValid = validatePhone(document.getElementById('phone').value);
        const isKrishibhavanValid = validateSelect('krishibhavan_name');
        // const isDesignationValid = validateSelect('designation');

        // return isFirstNameValid && isLastNameValid && isEmailValid && isPhoneValid && isKrishibhavanValid && isDesignationValid;

        if (!isfirstNameValid || !islastNameValid || !isEmailValid || !isPhoneValid || !isKrishibhavanValid ) {

        return false; // Prevent form submission
        }
        
        
    }
    </script>
</body>

</html>