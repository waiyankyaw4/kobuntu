// login.php
<?php
session_start();
require_once('db.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Fetch user from the database
    $sql = "SELECT * FROM users WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();

        // Verify the entered password with the hashed password
        if (password_verify($password, $user['password'])) {
            // Password is correct, set session variable and redirect to home page
            $_SESSION['username'] = $username;
            header("Location: profile.php");
            exit();
        } else {
            // Invalid password
            $_SESSION['error'] = "Invalid username or password";
            header("Location: login.html");
            exit();
        }
    } else {
        // User not found
        $_SESSION['error'] = "Invalid username or password";
        header("Location: login.html");
        exit();
    }
}
?>
