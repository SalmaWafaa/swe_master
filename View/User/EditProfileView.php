<?php
class EditProfileView {
    public function render($user, $error = null) {
        ?>
        <!DOCTYPE html>
        <html>
        <head>
            <title>Edit Profile</title>
            <style>
                body {
                    font-family: Arial, sans-serif;
                    background-color: #f4f4f4;
                    margin: 0;
                    padding: 20px;
                    display: flex;
                    justify-content: center;
                    align-items: center;
                    min-height: 100vh;
                }
                .profile-form {
                    width: 100%;
                    max-width: 500px;
                    padding: 30px;
                    background-color: #fff;
                    border-radius: 8px;
                    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
                }
                .profile-form h2 {
                    text-align: center;
                    color: #333;
                    margin-bottom: 30px;
                }
                .form-group {
                    margin-bottom: 20px;
                }
                .form-group label {
                    display: block;
                    margin-bottom: 5px;
                    color: #555;
                    font-weight: bold;
                }
                .form-group input {
                    width: 100%;
                    padding: 8px;
                    border: 1px solid #ddd;
                    border-radius: 4px;
                    box-sizing: border-box;
                }
                .form-group input:focus {
                    border-color: #007bff;
                    outline: none;
                    box-shadow: 0 0 5px rgba(0,123,255,0.3);
                }
                .error {
                    color: #dc3545;
                    background-color: #f8d7da;
                    border: 1px solid #f5c6cb;
                    padding: 10px;
                    border-radius: 4px;
                    margin-bottom: 20px;
                }
                .buttons {
                    display: flex;
                    gap: 10px;
                    justify-content: space-between;
                    margin-top: 30px;
                }
                button {
                    padding: 10px 20px;
                    border: none;
                    border-radius: 4px;
                    cursor: pointer;
                    font-weight: bold;
                    min-width: 120px;
                }
                button[type="submit"] {
                    background-color: #28a745;
                    color: white;
                }
                button[type="submit"]:hover {
                    background-color: #218838;
                }
                .cancel-button {
                    background-color: #6c757d;
                    color: white;
                }
                .cancel-button:hover {
                    background-color: #5a6268;
                }
                .password-requirements {
                    font-size: 12px;
                    color: #666;
                    margin-top: 5px;
                }
            </style>
        </head>
        <body>
            <div class="profile-form">
                <h2>Edit Profile</h2>
                <?php if ($error): ?>
                    <div class="error"><?= htmlspecialchars($error) ?></div>
                <?php endif; ?>
                
                <form method="POST" action="/index.php?controller=User&action=updateProfile">
                    <div class="form-group">
                        <label for="firstName">First Name:</label>
                        <input type="text" id="firstName" name="firstName" 
                               value="<?= htmlspecialchars($user->getFirstName()) ?>" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="lastName">Last Name:</label>
                        <input type="text" id="lastName" name="lastName" 
                               value="<?= htmlspecialchars($user->getLastName()) ?>" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="email">Email:</label>
                        <input type="email" id="email" name="email" 
                               value="<?= htmlspecialchars($user->getEmail()) ?>" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="password">New Password:</label>
                        <input type="password" id="password" name="password">
                        <div class="password-requirements">
                            Leave blank to keep current password. New password should be at least 8 characters.
                        </div>
                    </div>
                    
                    <div class="buttons">
                        <a href="index.php">
                            <button type="button" class="cancel-button">Cancel</button>
                        </a>
                        <button type="submit">Save Changes</button>
                    </div>
                </form>
            </div>
        </body>
        </html>
        <?php
        exit(); // Add exit to prevent any further output
    }
}
?>