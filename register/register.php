<?php
$servername = "localhost";
$username = "root"; // Default XAMPP username
$password = ""; // Default XAMPP password is empty
$dbname = "user_management";

// Connect to the database
$conn = new mysqli($servername, $username, $password, $dbname);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Create user function
function createUser($conn, $user_name, $password, $phone_no, $age, $gender, $repeatpassword, $fullname) 
{
    $errors = array(); // Initialize error array

    // Validation checks
    if (empty($fullname) || empty($age) || empty($gender) || empty($user_name) || empty($phone_no) || empty($repeatpassword) || empty($password)) {
        array_push($errors, "All fields are required.");
    }

    if (strlen($user_name) < 6) {
        array_push($errors, "Username must be at least 6 characters.");
    }

    if (strlen($phone_no) != 10) {
        array_push($errors, "Phone number must be 10 digits.");
    }

    if (strlen($password) < 8) {
        array_push($errors, "Password must be at least 8 characters.");
    }

    if ($password !== $repeatpassword) {
        array_push($errors, "Passwords do not match.");
    }

    // If no errors, insert the data into the database
    if (count($errors) == 0) {
        $hashed_password = password_hash($password, PASSWORD_BCRYPT); // Hash the password

        // Correctly format the SQL query
        $sql = "INSERT INTO users (fullname, gender, age, phone_no, user_name, password) 
                VALUES ('$fullname', '$gender', '$age', '$phone_no', '$user_name', '$hashed_password')";

        if ($conn->query($sql) === TRUE) {
            echo "Your data is inserted !";
        } else {
            echo "Error: " . $conn->error;
        }
    } else {
        foreach ($errors as $error) {
            echo "<p style='color:red;'>$error</p>";
        }
    }
}

// Check if form is submitted
if (isset($_POST['submit'])) {
    $fullname = $_POST['fullname'];
    $gender = $_POST['gender'];
    $age = $_POST['age'];
    $phone_no = $_POST['phone_no'];
    $user_name = $_POST['username'];
    $password = $_POST['password'];
    $repeatpassword = $_POST['repeatpassword'];

    // Call the function to create the user
    createUser($conn, $user_name, $password, $phone_no, $age, $gender, $repeatpassword, $fullname);
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration Form</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous">
    <link rel="stylesheet" href="signup-style.css">
</head>
<body>
    <div class="container">
        <h2>Welcome to Work Fixer</h2>
        <p>Fill Following Details to Join Work Fixer</p>
        <form method="post" action="register.php">
            <div class="form-group">
                <input type="text" class="form-control" name="fullname" placeholder="Full Name:" required>
            </div>
            <div class="radio-group">
                <input type="radio" id="male" name="gender" value="Male" required>
                <label for="male">Male</label><br><br>
                <input type="radio" id="female" name="gender" value="Female" required>
                <label for="female">Female</label><br><br>
                <input type="radio" id="other" name="gender" value="Other" required>
                <label for="other">Other</label><br><br>
            </div>
            <div class="form-group">
                <input type="number" class="form-control" name="age" placeholder="Age" required>
            </div>
            <div class="form-group">
                <input type="tel" class="form-control" name="phone_no" placeholder="Phone No." required>
            </div>
            <div class="form-group">
                <input type="text" id="username" class="form-control" name="username" placeholder="Username" required>
            </div>
            <div class="form-group">
                <input type="password" id="password" class="form-control" name="password" placeholder="Password" required>
            </div>
            <div class="form-group">
                <input type="password" class="form-control" name="repeatpassword" placeholder="Repeat Password" required>
            </div>
            <div class="form-btn">
                <input type="submit" class="btn btn-primary" value="Register" name="submit">
            </div>
        </form>
        <div>
            <a href="login.php"><p>Already Registered? Login Here</a></p>
        </div>
    </div>
</body>
</html>
