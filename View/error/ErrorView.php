<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Error</title>
    <style>
        .error-container {
            max-width: 600px;
            margin: 50px auto;
            padding: 20px;
            text-align: center;
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .error-icon {
            font-size: 48px;
            color: #dc3545;
            margin-bottom: 20px;
        }
        .error-title {
            color: #333;
            font-size: 24px;
            margin-bottom: 15px;
        }
        .error-message {
            color: #666;
            margin-bottom: 25px;
        }
        .back-button {
            display: inline-block;
            padding: 10px 20px;
            background: #007bff;
            color: white;
            text-decoration: none;
            border-radius: 4px;
            transition: background 0.3s;
        }
        .back-button:hover {
            background: #0056b3;
        }
    </style>
</head>
<body>
    <div class="error-container">
        <div class="error-icon">⚠️</div>
        <h1 class="error-title"><?php echo isset($errorTitle) ? htmlspecialchars($errorTitle) : 'Error'; ?></h1>
        <p class="error-message"><?php echo isset($errorMessage) ? htmlspecialchars($errorMessage) : 'An unexpected error occurred.'; ?></p>
        <a href="/swe_master/index.php" class="back-button">Back to Home</a>
    </div>
</body>
</html> 