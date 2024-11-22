<?php
// Include database connection file
require 'dbconn.php';

// Include PHPMailer library
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php'; // Path to PHPMailer autoload.php

// Create a new PHPMailer instance
$mail = new PHPMailer(true);

try {
    // SMTP configuration
    $mail->isSMTP();
    $mail->Host = 'smtp.elasticemail.com';
    $mail->Port = 2525;
    $mail->SMTPAuth = true;
    $mail->Username = 'church.events001@gmail.com';
    $mail->Password = '313460A2910C647D434572FEFD750AD36B89';
    
    // Email content
    $mail->isHTML(true);
    $mail->Subject = 'Test Email via SMTP';
    $mail->Body = 'This is a test email sent via SMTP using PHPMailer.';
    
    // Query to retrieve email addresses from members table
    $stmt = $pdo->query('SELECT email FROM members');
    
    // Loop through the result set
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $recipient_email = $row['email'];
        // Add recipient email address
        $mail->addAddress($recipient_email);
        
        // Send email
        $mail->send();
        
        // Clear all addresses for next iteration
        $mail->clearAddresses();
    }
    
    echo 'Emails have been sent successfully!';
} catch (Exception $e) {
    echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
}
?>
