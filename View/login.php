<?php
// Start the session
session_start();

// Include the UserController class
// require_once __DIR__ . '/../../Controller/UserController.php';
require_once 'C:\xampp\htdocs\ecommerce_master\Controller\UserController.php';

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Create a UserController object
    $userController = new UserController();

    // Attempt to log in
    $result = $userController->login($email, $password);

    if ($result === true) {
        // Login successful, redirect to home.php
        header("index.php");
        exit();
    } else {
        // Display the error message
        echo "Login failed: " . $result;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .login-form {
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 300px;
        }
        .login-form h2 {
            margin-bottom: 20px;
            text-align: center;
        }
        .login-form input {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        .login-form button {
            width: 100%;
            padding: 10px;
            background-color: #28a745;
            color: #fff;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        .login-form button:hover {
            background-color: #218838;
        }
        .error {
            color: red;
            text-align: center;
            margin-bottom: 15px;
        }
    </style>
</head>
<body>
    <div class="login-form">
        <h2>Login</h2>
        <?php if (isset($result) && $result !== true): ?>
            <div class="error"><?php echo $result; ?></div>
        <?php endif; ?>
        <form method="POST" action="">
            <input type="email" name="email" placeholder="Email" required><br>
            <input type="password" name="password" placeholder="Password" required><br>

            <button type="submit">Login</button>
        </form>
        <p style="text-align: center; margin-top: 10px;">
            Don't have an account? <a href="register.php">Register here</a>
        </p>
    </div>
</body>
</html>