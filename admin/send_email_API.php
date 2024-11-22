<?php
// Elastic Email API details
$apiKey = 'YOUR_API_KEY'; // Replace 'YOUR_API_KEY' with your actual API key

// Email details
$from = 'church.events001@gmail.com'; // Sender email address
$fromName = 'Your Name'; // Sender name
$subject = 'Subject of the Email'; // Email subject
$body = 'Content of the Email'; // Email body
$to = 'recipient@example.com'; // Recipient email address

// Prepare the request data
$requestData = array(
    'from' => $from,
    'fromName' => $fromName,
    'subject' => $subject,
    'body' => $body,
    'to' => $to
);

// Send the email using Elastic Email API
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, 'https://api.elasticemail.com/v2/email/send');
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query(array_merge($requestData, array('apikey' => $apiKey))));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$response = curl_exec($ch);
curl_close($ch);

// Check the response
if ($response === 'Email sent') {
    echo 'Email sent successfully!';
} else {
    echo 'Failed to send email. Error: ' . $response;
}
?>
