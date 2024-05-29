<?php
session_start();
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirm_password'];

    // Server-side validation for matching passwords
    if ($password !== $confirmPassword) {
        echo "<p class='error'>Passwords do not match.</p>";
    } else {
        // Validate password strength
        $passwordPattern = '/^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[@#$%^&+=!]).{8,}$/';
        if (!preg_match($passwordPattern, $password)) {
            echo "<p class='error'>Password must be at least 8 characters long, include at least one uppercase letter, one lowercase letter, one number, and one special character.</p>";
        } else {
            // Hash the password
            $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

            // Check for existing username or email
            $sql = "SELECT * FROM users WHERE username = ? OR email = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ss", $username, $email);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                echo "<p class='error'>Username or email already exists.</p>";
            } else {
                // Insert user into database with hashed password using prepared statement
                $sql = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("sss", $username, $email, $hashedPassword);

                if ($stmt->execute()) {
                    $_SESSION['success'] = "Registration successful!";
                    header("Location: login.html");
                    exit();
                } else {
                    echo "<p class='error'>Error: " . $conn->error . "</p>";
                }
            }
        }
    }
}

$conn->close();
?>
