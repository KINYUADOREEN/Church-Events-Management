<?php 
// Include PHPMailer files
require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Function to send email notification
function sendEmailNotification($subject, $message, $to) {
    // Create a PHPMailer instance
    $phpmailer = new PHPMailer(true);
    
    try {
        // Server settings
        $phpmailer->isSMTP();
        $phpmailer->Host = 'smtp.elasticemail.com'; // Elastic Email SMTP server
        $phpmailer->SMTPAuth = true;
        $phpmailer->Port = 2525;
        $phpmailer->Username = 'church.events001@gmail.com'; // Your SMTP username
        $phpmailer->Password = '313460A2910C647D434572FEFD750AD36B89'; // Your SMTP password
        
        // Sender details
        $phpmailer->setFrom('church.events001@gmail.com', 'DEkut CS Admin');
        
        // Recipients
        $phpmailer->addAddress($to);
        
        // Content
        $phpmailer->isHTML(true); // Set email format to HTML
        $phpmailer->Subject = $subject;
        $phpmailer->Body    = $message;
        
        $phpmailer->send();
    } catch (Exception $e) {
        echo "Message could not be sent. Mailer Error: {$phpmailer->ErrorInfo}";
    }
}

// Include database connection file
require 'dbconn.php';

// Process form submission for adding new event or updating existing event
if(isset($_POST['send'])){
    // Retrieve form data
    $title = $_POST['title'];
    $date = $_POST['date'];
    $start_time = $_POST['start_time'];
    $end_time = $_POST['end_time'];
    $venue = $_POST['venue'];
    $charges = $_POST['charges'];
    $content = $_POST['content'];

    // Check if the event date is in the future
    if(strtotime($date) < strtotime('today')) {
        echo "<script type=\"text/javascript\">alert('Event date cannot be in the past.');</script>";
    } else {
        // Check if the event already exists
        $check_query = "SELECT COUNT(*) AS count FROM event WHERE Title = '$title' AND Date = '$date' AND Start_Time = '$start_time' AND End_Time = '$end_time' AND Venue = '$venue' AND content = '$content' AND charges = '$charges'";
        $check_result = mysqli_query($conn, $check_query);
        $row = mysqli_fetch_assoc($check_result);
        if ($row['count'] > 0) {
            echo "<script type=\"text/javascript\">alert('This event already exists.');</script>";
        } else {
            // Insert event into the database
            $qry = "INSERT INTO event (Title, Date, Start_Time, End_Time, Venue, content, charges)
                    VALUES ('$title', '$date', '$start_time', '$end_time', '$venue', '$content', '$charges')";
            $result = mysqli_query($conn, $qry);

            if($result) {
                // Send email notification to all members
                $subject = "New Event Added: $title";
                $message = "<h2>A new event has been added:</h2>";
                $message .= "<ul>";
                $message .= "<li><strong>Title:</strong> $title</li>";
                $message .= "<li><strong>Date:</strong> $date</li>";
                $message .= "<li><strong>Start Time:</strong> $start_time</li>";
                $message .= "<li><strong>End Time:</strong> $end_time</li>";
                $message .= "<li><strong>Venue:</strong> $venue</li>";
                $message .= "<li><strong>Charges:</strong> $charges</li>";
                $message .= "<li><strong>Description:</strong> $content</li>";
                $message .= "</ul>";

                // Fetch all member emails
                $recipient_query = mysqli_query($conn, "SELECT email FROM members");
                while ($recipient_row = mysqli_fetch_array($recipient_query)) {
                    $to = $recipient_row['email'];
                    // Send email notification to each member
                    sendEmailNotification($subject, $message, $to);
                }

                // Redirect to events.php
                echo "<script type=\"text/javascript\">window.location = 'events.php';</script>";
            } else {
                echo "<script type=\"text/javascript\">alert('Message Not Sent. Try Again.');</script>";
            }
        }
    }
} elseif (isset($_POST['update'])) { // Process form submission for updating existing event
    // Retrieve form data
    $id = $_POST['id'];
    $title = $_POST['title'];
    $date = $_POST['date'];
    $start_time = $_POST['start_time'];
    $end_time = $_POST['end_time'];
    $venue = $_POST['venue'];
    $charges = $_POST['charges'];
    $content = $_POST['content'];

    // Update event in the database
    $update_query = "UPDATE event SET Title='$title', Date='$date', Start_Time='$start_time', End_Time='$end_time', Venue='$venue', content='$content', charges='$charges' WHERE id='$id'";
    $update_result = mysqli_query($conn, $update_query);

    if ($update_result) {
        // Fetch updated event details
        $updated_event_query = mysqli_query($conn, "SELECT * FROM event WHERE id='$id'");
        $updated_event_row = mysqli_fetch_assoc($updated_event_query);
        
        // Construct email notification
        $subject = "Event Updated: $title";
        $message = "<h2>An event has been updated:</h2>";
        $message .= "<ul>";
        $message .= "<li><strong>Title:</strong> $title</li>";
        $message .= "<li><strong>Date:</strong> $date</li>";
        $message .= "<li><strong>Start Time:</strong> $start_time</li>";
        $message .= "<li><strong>End Time:</strong> $end_time</li>";
        $message .= "<li><strong>Venue:</strong> $venue</li>";
        $message .= "<li><strong>Charges:</strong> $charges</li>";
        $message .= "<li><strong>Description:</strong> $content</li>";
        $message .= "</ul>";

        // Fetch all member emails
        $recipient_query = mysqli_query($conn, "SELECT email FROM members");
        while ($recipient_row = mysqli_fetch_array($recipient_query)) {
            $to = $recipient_row['email'];
            // Send email notification to each member
            sendEmailNotification($subject, $message, $to);
        }

        // Redirect to events.php or any other appropriate page
        echo "<script type=\"text/javascript\">window.location = 'events.php';</script>";
    } else {
        echo "<script type=\"text/javascript\">alert('Failed to update event.');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Events</title>
    <!-- Include any CSS or meta tags you need -->
</head>
<body>
    <?php include('header.php'); ?>
    <?php include('session.php'); ?>
    <?php include('navbar.php'); ?>
    <div class="container-fluid">
        <div class="row-fluid">
            <?php include('sidebar.php'); ?>
            <div class="span3" id="adduser">
                <?php include('add_event.php'); ?>                        
            </div>
            <div class="span6" id="">
                <div class="row-fluid">
                    <div class="empty">
                        <div class="alert alert-success alert-dismissable">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                            <i class="icon-info-sign"></i>  <strong>Note!:</strong> Select the checkbox if you want to delete?
                        </div>
                    </div>    
                    <?php   
                    $count_user=mysqli_query($conn,"select * from tithe ");
                    $count = mysqli_num_rows($count_user);
                    ?>   
                    <div id="block_bg" class="block">
                        <div class="navbar navbar-inner block-header">
                            <div class="muted pull-left"></i><i class="icon-user"></i> Giving List</div>
                            <div class="muted pull-right">
                                Number of Givings: <span class="badge badge-info"><?php echo $count; ?></span>
                            </div>
                        </div>
                        <div class="block-content collapse in">
                            <div class="span12">
                                <form action="delete_users.php" method="post">
                                    <table cellpadding="0" cellspacing="0" border="0" class="table" id="example">
                                        <?php include('modal_delete.php'); ?>
                                        <thead>
                                            <tr>
                                                <th></th>
                                                <th style="width: 100px;">Event Name</th> <!-- Adjust width as needed -->
                                                <th style="max-width: 100px;">Description</th> <!-- Adjust max-width as needed -->
                                                <th>Start</th>
                                                <th>End</th>
                                                <th>Venue</th>
                                                <th>Date</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $user_query = mysqli_query($conn,"select * from event ")or die(mysqli_error());
                                            while($row = mysqli_fetch_array($user_query)){
                                                $id = $row['id'];
                                            ?>
                                            <tr>
                                                <td width="30">
                                                    <input id="optionsCheckbox" class="uniform_on" name="selector[]" type="checkbox" value="<?php echo $id; ?>">
                                                </td>
                                                <td style="width: 100px;"><?php echo $row['Title']; ?></td>
                                                <td style="max-width: 100px; word-wrap: break-word;"><?php echo $row['content']; ?></td>
                                                <td><?php echo $row['Start_Time']; ?></td>
                                                <td><?php echo $row['End_Time']; ?></td>
                                                <td><?php echo $row['Venue']; ?></td>
                                                <td><?php echo $row['Date']; ?></td>
                                                <?php include('toolttip_edit_delete.php'); ?>
                                                <td width="120">
                                                    <a rel="tooltip" title="Edit Event" id="e<?php echo $id; ?>" href="edit_event.php<?php echo '?id='.$id; ?>" data-toggle="modal" class="btn btn-success"><i class="icon-pencil icon-large"> Edit</i></a>
                                                </td>
                                            </tr>
                                            <?php } ?>
                                        </tbody>
                                    </table>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php include('footer.php'); ?>
    <?php include('script.php'); ?>
</body>
</html>
