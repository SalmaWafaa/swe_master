<?php 
// Start session at the very beginning to access session variables
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Register</title>
    <link rel="stylesheet" href="../assets/css/register_styles.css"> 
    <style>
        /* You can keep styles here or move all to the external CSS file */
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .register-form {
            background-color: #fff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 400px;
        }
        .register-form h2 {
            margin-bottom: 30px; /* Increased space */
            text-align: center;
            color: #333;
            font-size: 24px;
        }
        .form-group {
            margin-bottom: 20px;
        }
        .form-group label {
            display: block;
            margin-bottom: 8px;
            color: #555;
            font-weight: 500;
        }
        .form-group input, .form-group select {
            width: 100%;
            padding: 12px;
            border: 2px solid #e1e1e1;
            border-radius: 6px;
            box-sizing: border-box;
            font-size: 16px;
            transition: all 0.3s ease;
            background-color: white;
        }
        .form-group input:focus, .form-group select:focus {
            border-color: #007bff;
            outline: none;
            box-shadow: 0 0 0 3px rgba(0, 123, 255, 0.1);
        }
        .register-form button[type="submit"] { /* Specificity for submit button */
            width: 100%;
            padding: 12px;
            background-color: #28a745;
            color: #fff;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-size: 16px;
            font-weight: 600;
            transition: background-color 0.3s ease;
            margin-top: 10px;
        }
        .register-form button[type="submit"]:hover {
            background-color: #218838;
        }
        .login-link {
            text-align: center;
            margin-top: 20px;
            color: #666;
        }
        .login-link a {
            color: #007bff;
            text-decoration: none;
            font-weight: 500;
        }
        .login-link a:hover {
            text-decoration: underline;
        }
        /* Error Message Styling */
        .error {
            color: #dc3545; /* Dark red text */
            text-align: center;
            margin-bottom: 20px; /* Space below error */
            background-color: #f8d7da; /* Light pink background */
            border: 1px solid #f5c6cb; /* Pink border */
            padding: 10px 15px; /* Padding inside */
            border-radius: 6px; /* Rounded corners */
            font-size: 14px; /* Slightly smaller font */
            line-height: 1.4; /* Improve readability for multi-line errors */
        }
        /* Custom select styling */
        .form-group select {
            appearance: none;
            -webkit-appearance: none;
            -moz-appearance: none;
            background-image: url("data:image/svg+xml;charset=UTF-8,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='%236c757d' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3e%3cpolyline points='6 9 12 15 18 9'%3e%3c/polyline%3e%3c/svg%3e"); /* Adjusted stroke color */
            background-repeat: no-repeat;
            background-position: right 1rem center;
            background-size: 1em;
            padding-right: 2.5rem; /* Ensure space for arrow */
            cursor: pointer;
        }
        .password-requirements {
            font-size: 12px;
            color: #666;
            margin-top: 5px;
        }
    </style>
</head>
<body>
    <div class="register-form">

        <?php if (isset($_SESSION['register_error'])): ?>
            <div class="error">
                <?php 
                // Use nl2br if your error message might contain line breaks (\n)
                // otherwise htmlspecialchars is sufficient
                echo nl2br(htmlspecialchars($_SESSION['register_error'])); 
                unset($_SESSION['register_error']); // Important: Clear the error after displaying
                ?>
            </div>
        <?php endif; ?> 
        <h2>Register</h2>
        
        <form action="../../Controller/UserController.php?action=register" method="POST">
            
            <div class="form-group">
                <label for="userType">Account Type</label>
                <select id="userType" name="type" required>
                    <option value="customer" selected>Customer</option> 
                    <option value="admin">Admin</option> 
                </select>
            </div>

            <div class="form-group">
                <label for="firstName">First Name</label>
                <input type="text" id="firstName" name="firstName" placeholder="Enter your first name" required>
            </div>

            <div class="form-group">
                <label for="lastName">Last Name</label>
                <input type="text" id="lastName" name="lastName" placeholder="Enter your last name" required>
            </div>

            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" placeholder="Enter your email" required>
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" 
                       placeholder="Enter your password" 
                       pattern=".{8,}" 
                       title="Password must be at least 8 characters long"
                       required>
                <div class="password-requirements">
                    Password should be at least 8 characters long
                </div>
            </div>
            
            <button type="submit">Register</button>

        </form>

        <div class="login-link">
            Already have an account? <a href="login.php">Login</a> </div>

    </div>
</body>
</html>