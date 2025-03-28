<?php
session_start();
define('TITLE', "Business On the GO");

function strip_bad_chars($input)
{
    $output = preg_replace("/[^a-zA-Z0-9_-]/", "", $input);
    return $output;
}

if (isset($_SESSION['userId'])) {
    header("Location: home.php");
    exit();
}

include 'includes/HTML-head.php';
?>
 <link
      rel="stylesheet"
      href="https://cdn.jsdelivr.net/npm/boxicons@latest/css/boxicons.min.css"/>
</head>

<body>
    <?php include 'includes/navbarlogin.php'; ?>

    <section class="form-container">
        <form method="POST" action="includes/login.inc.php">
            <h3>Login now</h3>
            <?php
    if (isset($_GET['error'])) {
        if ($_GET['error'] == 'emptyfields') {
            echo '<div class="alert alert-danger" role="alert">
                        <strong>Error: </strong>Fill In All The Fields
                      </div>';
        } else if ($_GET['error'] == 'nouser') {
            echo '<div class="alert alert-danger" role="alert">
                        <strong>Error: </strong>Username does not exist
                      </div>';
        } else if ($_GET['error'] == 'wrongpwd') {
            echo '<div class="alert alert-danger" role="alert">
                        Wrong password  
                         <a href="forgot-password.php" class="alert-link">Forgot Password?</a>
                      </div>';
        } else if ($_GET['error'] == 'sqlerror') {
            echo '<div class="alert alert-danger" role="alert">
                        <strong>Error: </strong>Website error. Contact admin to have it fixed
                      </div>';
        }
    } else if (isset($_GET['newpwd'])) {
        if ($_GET['newpwd'] == 'passwordupdated') {
            echo '<div class="alert alert-success" role="alert">
                        <strong>Password Updated </strong>Login with your new password
                      </div>';
        }
    }
    ?>
            <div class="input-control">
                <input type="text" id="name" name="mailuid" placeholder="Username">
            </div>
            <div class="input-control">
                <input type="password" id="password" name="pwd" placeholder="Password">
            </div>
            <p style="color: dark blue;">Don't have an account? <a href="#" style="color:#2A6F97" data-toggle="modal" onclick="showRegistrationModal()">Register</a></p>
            <input type="submit" value="Login Now" class="button" name="login-submit">
        </form>
    </section>

    <!-- Registration Modal -->
    <div class="modal fade" id="registrationModal" tabindex="-1" role="dialog" aria-labelledby="registrationModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="registrationModalLabel">Registration Form</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="stepper">
                            <div class="step active" id="step1">1</div>
                            <div class="step" id="step2">2</div>
                            <div class="step" id="step3">3</div>
                        </div>
                        <form action="./endpoint/add-user.php" method="POST">
                            <!-- Step 1: Personal Information -->
                            <div class="step-content" id="stepContent1">
                            <p style="color: #013A63; font-size: 19px;">Personal Information</p>
                            <br>
                                <div class="form-group">
                                <label for="middleName">First Name:</label>
                                    <input type="text" class="form-control" id="firstName" name="firstname" required>
                                    <div class="error"></div>
                                </div>
                                <div class="form-group">
                                    <label for="middleName">Middle Initial:</label>
                                    <input type="text" class="form-control" id="middleName" name="middlename" maxlength="2">
                                    <div class="error"></div>
                                </div>
                                <div class="form-group">
                                    <label for="lastName">Last Name:</label>
                                    <input type="text" class="form-control" id="lastName" name="lastname" required>
                                    <div class="error"></div>
                                </div>
                                <div class="form-group">
                                    <label for="suffix">Suffix:</label>
                                    <input type="text" class="form-control" id="suffix" name="suffix">
                                </div>
                                <div class="form-group">
                                    <label for="birthdate">Birthdate:</label>
                                    <input type="date" class="form-control" id="birthdate" name="birthdate" onchange="calculateAge()" required>
                                    <div class="error" id="birthdate-error"></div> 
                                </div>
                                <div class="form-group">
                                    <label for="age">Age:</label>
                                    <input type="number" class="form-control" id="age" name="age" readonly required>
                                    <div class="error"></div>
                                </div>
                                <div class="form-group">
                                    <label for="contactNumber">Contact Number:</label>
                                    <input type="tel" class="form-control" id="contactNumber" name="contactnumber" maxlength="13" onclick="formatContactNumber()" required>
                                    <div class="error"></div>
                                </div>
                                <!-- Add Next button -->
                                <button type="button" class="btn btn-primary float-right" onclick="nextStep(1)" style="background-color: #014F86; font-size: 20px;">Next</button>
                            </div>
                            <!-- Step 2: Business Information -->
                            
                            <div class="step-content" id="stepContent2" style="display: none;">
                            <p style="color: #013A63; font-size: 19px;">Business Details</p>
                            <br>
                                <div class="form-group">
                                    <label for="businessName">Business Name:</label>
                                    <input type="text" class="form-control" id="businessName" name="business_name" required>
                                    <div class="error"></div>
                                </div>
                                <div class="form-group">
    <label for="businessType">Business Type:</label>
    <select class="form-control" id="businessType" name="business_type" required>
        <option value="">Select Business Type</option>
        <option value="Financial Services">Financial Services</option>
        <option value="Real Estate">Real Estate</option>
        <option value="Cleaning Services">Cleaning Services</option>
        <option value="Energy and Utilities">Energy and Utilities</option>
        <option value="Restaurant">Restaurant</option>
        <option value="Retail">Retail</option>
        <option value="Technology">Technology</option>
        <option value="Healthcare">Healthcare</option>

    </select>
    <div class="error"></div>
</div>

                                <div class="form-group">
                                    <label for="address">Address:</label>
                                    <input type="text" class="form-control" id="address" name="address" required>
                                    <div class="error"></div>
                                </div>
                                <!-- Add Back and Next buttons side by side -->
                                <div class="form-group d-flex justify-content-end">
                                    <button type="button" class="btn btn-primary mr-2" onclick="prevStep(2)" style="background-color: #014F86; font-size: 20px;">Back</button>
                                    <button type="button" class="btn btn-primary" onclick="nextStep(2)" style="background-color: #2C7DA0; font-size: 20px;">Next</button>
                                </div>
                            </div>
                            <!-- Step 3: Account Information -->
                            <div class="step-content" id="stepContent3" style="display: none;">
                            <p style="color: #013A63; font-size: 19px;">Create An Accout</p>
                            <br>
                                <div class="form-group">
                                    <label for="username">Username:</label>
                                    <input type="text" class="form-control" id="usernameInput" name="username">
                                </div>
                                <div class="form-group">
                                    <label for="email">Email:</label>
                                    <input type="email" class="form-control" id="email" name="email" required>
                                </div>
                                <div class="form-group">
                                    <label for="password">Password:</label>
                                    <input type="password" class="form-control" id="passwordInput" name="password" required>
                                    <div class="error"></div>
                                </div>
                                <div class="form-group">
                                    <label for="password">Confirm Password:</label>
                                    <input type="password" class="form-control" id="conpass" name="conpass" required>
                                    <div class="error"></div>
                                </div>
                                
                                
                                <!-- Add Back and Submit buttons -->
                                <div class="form-group d-flex justify-content-end">
                                    <button type="button" class="btn btn-primary mr-2" onclick="prevStep(3)" style="background-color: #014F86; font-size: 20px;">Back</button>
                                    <button type="submit" class="btn btn-dark" name="register" style="background-color: #2C7DA0; font-size: 20px;">Submit</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap Js -->
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.min.js"></script>

    <!-- JavaScript for handling form steps -->
    <script>
    function validateStep(step) {
    var inputs = document.querySelectorAll('#stepContent' + step + ' input[required], #stepContent' + step + ' select[required]');
    var isValid = true;

    inputs.forEach(function(input) {
        if (!input.value.trim()) {
            isValid = false;
            input.style.border = "1px solid red";
            input.nextElementSibling.textContent = 'This field is required.';
        } else {
            input.style.border = ""; // Reset border if field is filled
            input.nextElementSibling.textContent = '';
        }
    });

    return isValid;
}

function nextStep(step) {
    if (validateStep(step)) {
        document.getElementById('stepContent' + step).style.display = 'none';
        document.getElementById('step' + step).classList.remove('active');
        step++;
        document.getElementById('stepContent' + step).style.display = 'block';
        document.getElementById('step' + step).classList.add('active');
    }
}

function prevStep(step) {
    document.getElementById('stepContent' + step).style.display = 'none';
    document.getElementById('step' + step).classList.remove('active');
    step--;
    document.getElementById('stepContent' + step).style.display = 'block';
    document.getElementById('step' + step).classList.add('active');
}

document.querySelectorAll('select[required]').forEach(function(select) {
    select.addEventListener('change', function() {
        if (this.value.trim() === '') {
            this.style.border = "1px solid red";
            this.nextElementSibling.textContent = 'Please select an option.';
        } else {
            this.style.border = ""; // Reset border if field is filled
            this.nextElementSibling.textContent = '';
        }
    });
});

</script>

<script>
    function validateBirthdate() {
        var birthdateInput = document.getElementById("birthdate");
        var ageInput = document.getElementById("age");
        var errorDiv = document.getElementById("birthdate-error");
        var birthdate = new Date(birthdateInput.value);
        var currentDate = new Date();
        var maxDate = new Date("2024-01-01");

        errorDiv.textContent = "";
        errorDiv.style.display = "none";

        if (birthdate > maxDate) {
            errorDiv.textContent = "Birthdate should not exceed 2024.";
            errorDiv.style.display = "block";
            return;
        }

        var age = currentDate.getFullYear() - birthdate.getFullYear();
        var monthDiff = currentDate.getMonth() - birthdate.getMonth();
        var dayDiff = currentDate.getDate() - birthdate.getDate();

        if (monthDiff < 0 || (monthDiff === 0 && dayDiff < 0)) {
            age--;
        }

        ageInput.value = isNaN(age) ? "" : age;

        if (age < 18) {
            errorDiv.textContent = "You must be 18 years or above.";
            errorDiv.style.display = "block";
            // Disable next button if age is below 18
            document.getElementById('stepContent1').querySelector('.btn-primary').disabled = true;
        } else {
            errorDiv.textContent = "";
            errorDiv.style.display = "none";
            // Enable next button if age is 18 or above
            document.getElementById('stepContent1').querySelector('.btn-primary').disabled = false;
        }
    }

    document.getElementById("birthdate").addEventListener("change", validateBirthdate);
    window.addEventListener("load", validateBirthdate);
</script>

<script>
document.getElementById('firstName').addEventListener('input', function() {
    var firstNameInput = document.getElementById('firstName');
    var firstNameValue = firstNameInput.value.trim();
    var errorDiv = firstNameInput.parentElement.querySelector('.error');

    // Regular expression to match letters and spaces
    var pattern = /^[A-Za-z ]+$/;

    if (!pattern.test(firstNameValue)) {
        errorDiv.textContent = 'First name should contain letters only.';
        firstNameInput.value = firstNameValue.replace(/[^A-Za-z ]/g, ''); // Remove non-letter and non-space characters
    } else {
        errorDiv.textContent = '';
    }
});

document.getElementById('lastName').addEventListener('input', function() {
    var lastNameInput = document.getElementById('lastName');
    var lastNameValue = lastNameInput.value.trim();
    var errorDiv = lastNameInput.parentElement.querySelector('.error');

    // Regular expression to match letters and spaces
    var pattern = /^[A-Za-z ]+$/;

    if (!pattern.test(lastNameValue)) {
        errorDiv.textContent = 'Last name should contain letters only.';
        lastNameInput.value = lastNameValue.replace(/[^A-Za-z ]/g, ''); // Remove non-letter and non-space characters
    } else {
        errorDiv.textContent = '';
    }
});

document.getElementById('middleName').addEventListener('input', function() {
    var middleNameInput = document.getElementById('middleName');
    var middleNameValue = middleNameInput.value.trim();
    var errorDiv = middleNameInput.parentElement.querySelector('.error');

    // Regular expression to match letters and spaces
    var pattern = /^[A-Za-z]*$/;

    if (!pattern.test(middleNameValue)) {
        errorDiv.textContent = 'Middle initial should contain letters only.';
        middleNameInput.value = middleNameValue.replace(/[^A-Za-z]/g, ''); // Remove non-letter characters
    } else {
        errorDiv.textContent = '';
    }
});
</script>

<script>
document.getElementById('contactNumber').addEventListener('input', function(event) {
    var inputValue = this.value.trim();
    var numericValue = inputValue.replace(/[^\d+]/g, '');

    // Add the '+' sign and country code if not already included
    if (!numericValue.startsWith('+63')) {
        numericValue = '+63' + numericValue.slice(1);
    }

    // Limit the length to 18 characters (including the '+', country code, and separators)
    if (numericValue.length > 18) {
        numericValue = numericValue.slice(0, 18);
    }

    // Display error if the number of digits is less than 13
    var errorDiv = this.parentElement.querySelector('.error');
    if (numericValue.length < 13) {
        errorDiv.textContent = 'Contact number should contain at least 13 digits.';
    } else {
        errorDiv.textContent = ''; // Clear error message if valid
    }

    this.value = numericValue;

    // Enable or disable the "Next" button based on the length of the contact number
    var nextButton = document.getElementById('stepContent1').querySelector('.btn-primary');
    if (numericValue.length >= 13) {
        nextButton.disabled = false;
    } else {
        nextButton.disabled = true;
    }
});
</script>


<script> //PARA NAMAN SA EMAIL NA AUTOMATIC YUNG @GMAIL.COM
  const emailInput = document.getElementById('email');

emailInput.addEventListener('input', function(event) {
    const inputValue = this.value.toLowerCase();
    const lastCharIndex = inputValue.length - 1;

    if (inputValue[lastCharIndex] === '@' && !inputValue.endsWith('@gmail.com')) {
        this.value = inputValue.slice(0, -1) + '@gmail.com'; // Corrected line
    }
});
</script>

<script>
document.getElementById('conpass').addEventListener('input', function() {
    var passwordInput = document.getElementById('passwordInput');
    var confirmPasswordInput = this;
    var passwordErrorDiv = passwordInput.parentElement.querySelector('.error');
    var confirmPasswordErrorDiv = confirmPasswordInput.parentElement.querySelector('.error');
    var submitButton = document.querySelector('button[name="register"]');

    var password = passwordInput.value;
    var confirmPassword = confirmPasswordInput.value;

    // Check if passwords match
    if (password !== confirmPassword) {
        confirmPasswordErrorDiv.textContent = 'Passwords do not match.';
        submitButton.disabled = true; // Disable submit button if passwords don't match
    } else {
        confirmPasswordErrorDiv.textContent = '';
        submitButton.disabled = false; // Enable submit button if passwords match
    }

    // Check password complexity (letters, numbers, special characters)
    var passwordRegex = /^(?=.*[a-zA-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/;
    if (!passwordRegex.test(password)) {
        passwordErrorDiv.textContent = 'Password must contain at least 8 characters, including letters, numbers, and special characters.';
        submitButton.disabled = true; // Disable submit button if password complexity requirements are not met
    } else {
        passwordErrorDiv.textContent = '';
        if (password === confirmPassword) {
            submitButton.disabled = false; // Enable submit button if passwords match and complexity requirements are met
        }
    }
});
</script>


    <!-- JavaScript for showing registration modal -->
    <script>
        function showRegistrationModal() {
            $('#registrationModal').modal('show');
        }
    </script>
    <script src="js/jquery.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <section class="contact" id="contact">
      <div class="social">
        <a href="#"><i class="bx bxl-facebook"></i></a>
        <a href="#"><i class="bx bxl-twitter"></i></a>
        <a href="#"><i class="bx bxl-instagram"></i></a>
        <a href="#"><i class="bx bxl-youtube"></i></a>
      </div>
      <div class="links">
        <a href="#">Privacy Policy</a>
        <a href="#">Terms Of Use</a>
        <a href="#">Our Company</a>
      </div>
      <p>&#169; BusinessOnTheGo - All Right Reserved.</p>
    </section>
    <!--- Link To Custom Js -->
    <script src="js/res.js"></script>
</body>
</html>