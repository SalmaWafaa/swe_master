<?php
// // Start the session
// session_start();

// // Include the Customer class and Database configuration
// require_once 'C:\xampp\htdocs\ecommerce_master\Model\Customer.php'; // Adjust the path as needed
// require_once 'C:\xampp\htdocs\ecommerce_master\config\Database.php'; // Adjust the path as needed

// // Check if the customer is logged in
// if (!isset($_SESSION['customer_id'])) {
//     header("Location: login.php");
//     exit();
// }

// // Create a Customer object with the session data
// $database = new Database();
// $db = $database->getConnection();
// $customer = new Customer($db);

// // Fetch the customer's details from the database
// $customer->setId($_SESSION['customer_id']);
// $customerDetails = $customer->getCustomerById(); // Fetch customer details

// // Check if the form is submitted
// if ($_SERVER['REQUEST_METHOD'] == 'POST') {
//     $firstName = $_POST['first_name'];
//     $lastName = $_POST['last_name'];
//     $email = $_POST['email'];
//     $password = $_POST['password'];

//     // Update the customer details
//     $customer->setFirstName($firstName);
//     $customer->setLastName($lastName);
//     $customer->setEmail($email);

//     // If a new password is provided, hash it
//     if (!empty($password)) {
//         $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
//         $customer->setPassword($hashedPassword);
//     }

//     // Save the changes to the database
//     if ($customer->editAccount()) {
//         // Update session data
//         $_SESSION['first_name'] = $firstName;
//         $_SESSION['last_name'] = $lastName;
//         $_SESSION['email'] = $email;

//         // Redirect to home.php or show a success message
//         header("Location: home.php");
//         exit();
//     } else {
//         $error = "Failed to update account.";
//     }
//}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Account</title>
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
        .edit-account-form {
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 300px;
        }
        .edit-account-form h2 {
            margin-bottom: 20px;
            text-align: center;
        }
        .edit-account-form input {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        .edit-account-form button {
            width: 100%;
            padding: 10px;
            background-color: #28a745;
            color: #fff;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        .edit-account-form button:hover {
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
    <div class="edit-account-form">
        <h2>Edit Account</h2>
        <?php if (isset($error)): ?>
            <div class="error"><?php echo $error; ?></div>
        <?php endif; ?>
        <form method="POST" action="">
            <input type="text" name="first_name" placeholder="First Name" value="<?php echo $customerDetails['first_name']; ?>" required><br>
            <input type="text" name="last_name" placeholder="Last Name" value="<?php echo $customerDetails['last_name']; ?>" required><br>
            <input type="email" name="email" placeholder="Email" value="<?php echo $customerDetails['email']; ?>" required><br>
            <input type="password" name="password" placeholder="New Password (leave blank to keep current)"><br>

            <button type="submit">Update Account</button>
        </form>
        <p style="text-align: center; margin-top: 10px;">
            <a href="home.php">Back to Home</a>
        </p>
    </div>
</body>
</html>