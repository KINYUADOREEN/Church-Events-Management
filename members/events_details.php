<?php 
// Start session at the very beginning of the file, before any output
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
error_reporting(E_ERROR | E_PARSE); // Suppress warnings and notices
?>
<!DOCTYPE html>
<html>
<head>
    <title>Event Details</title>
    <style>
        body {
            background-color: rgba(0, 0, 0, 0.4);
            font-size: 16px; /* Adjust font size as needed */
            color: #333; /* Font color */
            margin: 0; /* Remove default margin */
            padding: 0; /* Remove default padding */
        }

        #eventContainer {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 80%;
            max-width: 600px; /* Max width of the container */
            height: 80%; /* Set height to 80% of the viewport height */
            max-height: 80vh; /* Limit height to 80% of the viewport height */
            overflow-y: auto; /* Add vertical scrollbar if content exceeds container height */
            padding: 20px;
            background-color: #fefefe; /* Background color of the container */
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.3);
        }

        h2 {
            margin-top: 0; /* Remove default margin for heading */
        }

        p {
            margin: 10px 0; /* Adjust paragraph margin */
        }

        img {
            width: 100%; /* Make the image fill the container width */
            border-top-left-radius: 10px; /* Rounded corners for the image */
            border-top-right-radius: 10px;
        }

    </style>
</head>
<body>
    <?php 
    // Include necessary files
    include('header.php');
    include('session.php');
    include('navbar.php');

    // Retrieve event details based on the event ID passed in the URL
    $event_id = isset($_GET['id']) ? $_GET['id'] : null; // Check if 'id' is set
    if ($event_id) {
        $event_query = mysqli_query($conn, "SELECT * FROM event WHERE id = $event_id");
        $event_details = mysqli_fetch_assoc($event_query);
    }

    ?>
    <!-- Event Container -->
    <div id="eventContainer">
        <!-- Image -->
        <img src="img/cheers.jpeg" alt="Event Image">
        
        <!-- Event Details -->
        <?php if ($event_details): ?> <!-- Check if event details exist -->
            <h2><?php echo $event_details['Title']; ?></h2>
            <p><strong>Date:</strong> <?php echo $event_details['Date']; ?></p>
            <p><strong>Description:</strong> <?php echo $event_details['content']; ?></p>
            <p><strong>Start Time:</strong> <?php echo $event_details['Start_Time']; ?></p>
            <p><strong>End Time:</strong> <?php echo $event_details['End_Time']; ?></p>
            <p><strong>Venue:</strong> <?php echo $event_details['Venue']; ?></p>
            <p><strong>Charges:</strong> <?php echo $event_details['charges']; ?></p>

            <!-- Button Container -->
            <div class="button-container">
                <!-- Payment button -->
                <?php if ($event_details['charges'] > 0): ?>
                    <a href="MPESA-STK-PUSH/checkout.php?amount=<?php echo $event_details['charges']; ?>" class="btn btn-success">Pay with M-Pesa</a>
                <?php endif; ?>
                <!-- Register button -->
                <a href="register.php?event_id=<?php echo $event_id; ?>" class="btn btn-primary">Register</a>
            </div>
            <!-- End of Button Container -->
        <?php else: ?>
            <p>Event details not found.</p>
        <?php endif; ?>

    </div>

    <!-- Your other HTML content -->

</body>
</html>
