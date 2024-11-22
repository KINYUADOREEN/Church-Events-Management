<?php
ob_start(); // Start output buffering to prevent header issues

// Include necessary files and namespaces
require_once "MPESA-STK-PUSH/callback.php";
require_once "MPESA-STK-PUSH/checkout.php";
require_once "MPESA-STK-PUSH/express-stk.php";

// Validate input data
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['id']) && isset($_POST['mpesa_number']) && isset($_POST['amount'])) {
    try {
        // Retrieve event details based on the event ID
        $event_id = $_POST['id'];
        // Fetch event details from the database based on the event ID
        // (This step may vary depending on your database setup)
        $event_details = ['id' => $event_id]; // Simulated event details
        
        // Simulate generating MerchantRequestID
        $MerchantRequestID = '12345'; // Simulated MerchantRequestID

        // Redirect to express_stk.php with checkoutRequestID for PIN request
        header("Location: MPESA-STK-PUSH/express-stk.php?checkoutRequestID=$MerchantRequestID");
        exit;
        
    } catch (\Exception $e) {
        // Handle exceptions, redirect to error-log file with error message
        header("Location: MPESA-STK-PUSH/error_log");
        exit;
    }
} else {
    // Invalid request, redirect to error-log file with error message
    header("Location: MPESA-STK-PUSH/error_log");
    exit;
}
?>
