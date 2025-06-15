<?php
class ContactView {
    public function render($contactInfo) {
        ?>
        <!DOCTYPE html>
        <html>
        <head>
            <title>Contact Us - SYS</title>
            <style>
                body {
                    font-family: Arial, sans-serif;
                    margin: 0;
                    padding: 0;
                    background-color: #f4f4f4;
                }

                .header {
                    background-color: #007bff;
                    padding: 15px 30px;
                    display: flex;
                    justify-content: space-between;
                    align-items: center;
                }

                .brand {
                    display: flex;
                    flex-direction: column;
                    text-decoration: none;
                }

                .brand h1 {
                    color: white;
                    margin: 0;
                    font-size: 32px;
                    font-weight: bold;
                }

                .brand .slogan {
                    color: rgba(255,255,255,0.9);
                    font-size: 14px;
                }

                .auth-buttons a {
                    color: white;
                    text-decoration: none;
                    padding: 8px 16px;
                    border-radius: 6px;
                    background-color: rgba(255, 255, 255, 0.2);
                    margin-left: 10px;
                }

                .auth-buttons a:hover {
                    background-color: rgba(255, 255, 255, 0.3);
                }

                .contact-container {
                    max-width: 800px;
                    margin: 40px auto;
                    padding: 20px;
                    background: white;
                    border-radius: 10px;
                    box-shadow: 0 0 10px rgba(0,0,0,0.1);
                }

                .contact-header {
                    text-align: center;
                    margin-bottom: 40px;
                }

                .contact-header h1 {
                    color: #333;
                    margin-bottom: 10px;
                    font-family: Arial, sans-serif;
                    font-size: 32px;
                }

                .contact-header p {
                    color: #666;
                    font-family: Arial, sans-serif;
                }

                .contact-info {
                    display: grid;
                    grid-template-columns: repeat(3, 1fr);
                    gap: 30px;
                    margin-top: 40px;
                }

                .contact-card {
                    text-align: center;
                    padding: 20px;
                    border-radius: 8px;
                    background: #f8f9fa;
                    transition: transform 0.3s ease;
                }

                .contact-card:hover {
                    transform: translateY(-5px);
                }

                .contact-card i {
                    font-size: 24px;
                    color: #007bff;
                    margin-bottom: 15px;
                }

                .contact-card h3 {
                    color: #333;
                    margin-bottom: 10px;
                    font-family: Arial, sans-serif;
                    font-size: 18px;
                }

                .contact-card p, .contact-card a {
                    color: #666;
                    text-decoration: none;
                    font-family: Arial, sans-serif;
                }

                .contact-card a:hover {
                    color: #007bff;
                }
            </style>
            <!-- Add Font Awesome for icons -->
            <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
        </head>
        <body>
            <div class="header">
                <a href="../index.php" class="brand">
                    <h1>SYS</h1>
                    <span class="slogan">Style Your Success</span>
                </a>
                <div class="auth-buttons">
                    <a href="../index.php">Home</a>
                </div>
            </div>

            <div class="contact-container">
                <div class="contact-header">
                    <h1>Contact Us</h1>
                    <p>We're here to help! Reach out to us through any of these channels.</p>
                </div>

                <div class="contact-info">
                    <div class="contact-card">
                        <i>📞</i>
                        <h3>Hotline</h3>
                        <p><?php echo htmlspecialchars($contactInfo->getHotline()); ?></p>
                    </div>

                    <div class="contact-card">
                        <i>✉️</i>
                        <h3>Email</h3>
                        <a href="mailto:<?php echo htmlspecialchars($contactInfo->getEmail()); ?>">
                            <?php echo htmlspecialchars($contactInfo->getEmail()); ?>
                        </a>
                    </div>

                    <div class="contact-card">
                        <i>📸</i>
                        <h3>Instagram</h3>
                        <a href="<?php echo htmlspecialchars($contactInfo->getInstagram()); ?>" target="_blank">
                            @sys_fashion
                        </a>
                    </div>
                </div>
            </div>
        </body>
        </html>
        <?php
    }
}
?> 