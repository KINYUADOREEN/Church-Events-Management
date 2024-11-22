<?php
// Include PHPMailer library
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php'; // Path to PHPMailer autoload.php

// Function to send email
function sendEmail($subject, $body) {
    // Create a new PHPMailer instance
    $mail = new PHPMailer(true);

    try {
        // SMTP configuration
        $mail->isSMTP();
        $mail->Host = 'smtp.elasticemail.com'; // Elastic Email SMTP server
        $mail->Port = 2525; // Use port 2525 for Elastic Email
        $mail->SMTPAuth = true;
        $mail->Username = 'church.events001@gmail.com'; // Your Elastic Email username
        $mail->Password = '313460A2910C647D434572FEFD750AD36B89'; // Your Elastic Email password
        
        // Sender details
        $mail->setFrom('church.events001@gmail.com', 'DEkut CS Admin'); // Your email and name
        
        // Email content
        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body = $body;
        
        // Include database connection file
        require 'dbconn.php';
        
        // Query to retrieve email addresses from members table
        $stmt = $conn->query('SELECT email FROM members');
        
        // Loop through the result set
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $recipient_email = $row['email'];
            // Add recipient email address
            $mail->addAddress($recipient_email);
        }
        
        // Send email
        $mail->send();
        
        echo 'Emails have been sent successfully!';
    } catch (Exception $e) {
        echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }
}
?>
