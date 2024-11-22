<?php 
include('header.php'); 
include('session.php'); 

// Include PHPMailer Autoload file
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
        // Update with your email and name
        $phpmailer->addAddress($to);

        // Content
        $phpmailer->isHTML(true);
        $phpmailer->Subject = $subject;
        $phpmailer->Body = $message;

        // Send email
        if ($phpmailer->send()) {
            return true;
        } else {
            return false;
        }
    } catch (Exception $e) {
        echo "Message could not be sent. Mailer Error: {$phpmailer->ErrorInfo}";
    }
}

// Fetch event details based on the ID
$get_event_id = isset($_GET['id']) ? $_GET['id'] : null;

if ($get_event_id) {
    $get_event_query = mysqli_query($conn, "SELECT * FROM event WHERE id = '$get_event_id'");
    $event = mysqli_fetch_assoc($get_event_query);
}

?>
<body>
    <?php include('navbar.php'); ?>
    <div class="container-fluid">
        <div class="row-fluid">
            <?php include('sidebar.php'); ?>
            <div class="span3" id="adduser">
                <div class="block">
                    <div class="navbar navbar-inner block-header">
                        <div class="muted pull-left"><i class="icon-plus-sign icon-large"> ADD/EDIT EVENT</i></div>
                    </div>
                    <div class="block-content collapse in">
                        <div class="span12">
                            <form method="post">
                                <table>
                                    <tr>
                                        <td style="color: #003300; font-weight: bold; font-size: 16px">Edit Event Here:</td>
                                    </tr>
                                    <tr>
                                        <td>&nbsp;</td>
                                    </tr>
                                    <tr>
                                        <td><input type="text" name="title" placeholder="Title" value="<?php echo isset($event['Title']) ? $event['Title'] : ''; ?>"></td>
                                    </tr>
                                    <tr>
                                        <td><input type="date" name="date" placeholder="Date" value="<?php echo isset($event['Date']) ? $event['Date'] : ''; ?>"></td>
                                    </tr>
                                    <tr>
                                        <td><textarea name="content" placeholder="Description" class="text"><?php echo isset($event['content']) ? $event['content'] : ''; ?></textarea></td>
                                    </tr>
                                    <tr>
                                        <td><input type="text" name="venue" placeholder="Venue" value="<?php echo isset($event['Venue']) ? $event['Venue'] : ''; ?>"></td>
                                    </tr>
                                    <tr>
                                        <td><input type="text" name="charges" placeholder="Charges" value="<?php echo isset($event['charges']) ? $event['charges'] : ''; ?>"></td>
                                    </tr>
                                    <tr>
                                        <td><input type="time" name="start_time" placeholder="Start Time" value="<?php echo isset($event['Start_Time']) ? $event['Start_Time'] : ''; ?>"></td>
                                    </tr>
                                    <tr>
                                        <td><input type="time" name="end_time" placeholder="End Time" value="<?php echo isset($event['End_Time']) ? $event['End_Time'] : ''; ?>"></td>
                                    </tr>
                                    <tr>
                                        <td><input type="submit" name="save" value="SAVE"></td>
                                    </tr>
                                </table>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <?php   
             $count_events=mysqli_query($conn,"select * from event");
             $count = mysqli_num_rows($count_events);
             ?>  
            <div class="span6" id="">
                <div class="row-fluid">
                    <!-- block -->
                    <div class="empty">
                        <div class="alert alert-success alert-dismissable">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                            <i class="icon-info-sign"></i>  <strong>Note!:</strong> Select the checkbox if you want to delete?
                        </div>
                    </div>
                    <div id="block_bg" class="block">
                        <div class="navbar navbar-inner block-header">
                            <div class="muted pull-left"><i class="icon-user"></i>  event(s) List</div>
                            <div class="muted pull-right">
                                Number of events <span class="badge badge-info"><?php  echo $count; ?></span>
                            </div>
                        </div>
                        <div class="block-content collapse in">
                            <div class="span12">
                                <form action="delete_members.php" method="post">
                                    <table cellpadding="0" cellspacing="0" border="0" class="table" id="example">
                                        <a data-placement="right" title="Click to Delete check item"  data-toggle="modal" href="#client_delete" id="delete"  class="btn btn-danger" name=""><i class="icon-trash icon-large"> Delete</i></a>
                                        <script type="text/javascript">
                                            $(document).ready(function(){
                                                $('#delete').tooltip('show');
                                                $('#delete').tooltip('hide');
                                            });
                                        </script>
                                        <?php include('modal_delete.php'); ?>
                                        <thead>
                                            <tr>
                                                <th></th>
                                                <th>Event Name</th>
                                                <th>Description</th>
                                                <th>Start Time</th>
                                                <th>End Time</th>
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
                                                <td><?php echo $row['Title']; ?></td>
                                                <td style="max-width: 150px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;"><?php echo $row['content']; ?></td>
                                                <td><?php echo $row['Start_Time']; ?></td>
                                                <td><?php echo $row['End_Time']; ?></td>
                                                <td><?php echo $row['Venue']; ?></td>
                                                <td><?php echo $row['Date']; ?></td>
                                                <?php include('toolttip_edit_delete.php'); ?>                                                            
                                                <td width="120">
                                                    <a rel="tooltip"  title="Edit Event" id="e<?php echo $id; ?>" href="edit_event.php<?php echo '?id='.$id; ?>"  data-toggle="modal" class="btn btn-success"><i class="icon-pencil icon-large"> Edit</i></a>
                                                </td>
                                            </tr>
                                            <?php } ?>
                                        </tbody>
                                    </table>
                                </form>
                            </div>
                        </div>
                    </div>
                    <!-- /block -->
                </div>
            </div>
        </div>
        <?php include('footer.php'); ?>
    </div>
    <?php include('script.php'); ?>

    <?php
    if(isset($_POST['save'])){
        // Your existing code to save event details
        $title = $_POST['title'];
        $date = $_POST['date'];
        $content = $_POST['content'];
        $venue = $_POST['venue'];
        $charges = $_POST['charges'];
        $start_time = $_POST['start_time'];
        $end_time = $_POST['end_time'];

        $qry = "INSERT INTO event (Title, Date, content, Venue, charges, Start_Time, End_Time)
                VALUES ('$title', '$date', '$content', '$venue', '$charges', '$start_time', '$end_time')
                ON DUPLICATE KEY UPDATE 
                Title = VALUES(Title), Date = VALUES(Date), content = VALUES(content), Venue = VALUES(Venue), charges = VALUES(charges), Start_Time = VALUES(Start_Time), End_Time = VALUES(End_Time)";
        
        $result = mysqli_query($conn, $qry);
        
        if($result){
            // Fetch all member emails
            $recipient_query = mysqli_query($conn, "SELECT email FROM members");
            while ($recipient_row = mysqli_fetch_array($recipient_query)) {
                $to = $recipient_row['email'];
                // Send email notification to each member
                $subject = "Event Details Updated: $title";
                $message = "The details of the event '$title' have been updated. Please check the latest details.";
                sendEmailNotification($subject, $message, $to);
            }

            echo "<script type = \"text/javascript\"> window.location = 'events.php'; </script>";
        } else{
            echo "<script type = \"text/javascript\"> alert(\"Failed to save event. Please try again.\"); </script>";
        }
    }
    ?>
</body>
</html>
