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

// Fetch mass booking details based on the ID
$get_mass_id = isset($_GET['id']) ? $_GET['id'] : null;

if ($get_mass_id) {
    $get_mass_query = mysqli_query($conn, "SELECT * FROM mass_bookings WHERE id = '$get_mass_id'");
    $mass_booking = mysqli_fetch_assoc($get_mass_query);
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
                        <div class="muted pull-left"><i class="icon-plus-sign icon-large"> BOOK MASS</i></div>
                    </div>
                    <div class="block-content collapse in">
                        <div class="span12">
                            <form method="post">
                                <table>
                                    <tr>
                                        <td style="color: #003300; font-weight: bold; font-size: 16px">Book Mass Here:</td>
                                    </tr>
                                    <tr>
                                        <td>&nbsp;</td>
                                    </tr>
                                    <tr>
                                        <td><input type="text" name="title" placeholder="Title" value="<?php echo isset($mass_booking['title']) ? $mass_booking['title'] : ''; ?>"></td>
                                    </tr>
                                    <tr>
                                        <td><input type="text" name="name" placeholder="Name" value="<?php echo isset($mass_booking['name']) ? $mass_booking['name'] : ''; ?>"></td>
                                    </tr>
                                    <tr>
                                        <td><input type="text" name="venue" placeholder="Venue" value="<?php echo isset($mass_booking['venue']) ? $mass_booking['venue'] : ''; ?>"></td>
                                    </tr>
                                    <tr>
                                        <td><input type="date" name="date" placeholder="Date" min="<?php echo date('Y-m-d'); ?>" value="<?php echo isset($mass_booking['date']) ? $mass_booking['date'] : ''; ?>"></td>
                                    </tr>
                                    <tr>
                                        <td><input type="text" name="start_time" placeholder="Start Time" value="<?php echo isset($mass_booking['start_time']) ? $mass_booking['start_time'] : ''; ?>"></td>
                                    </tr>
                                    <tr>
                                        <td><input type="text" name="end_time" placeholder="End Time" value="<?php echo isset($mass_booking['end_time']) ? $mass_booking['end_time'] : ''; ?>"></td>
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
             $count_masses=mysqli_query($conn,"select * from mass_bookings");
             $count = mysqli_num_rows($count_masses);
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
                            <div class="muted pull-left"><i class="icon-user"></i>  Mass(s) List</div>
                            <div class="muted pull-right">
                                Number of masses <span class="badge badge-info"><?php  echo $count; ?></span>
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
                                                <th>Title</th>
                                                <th>Name</th>
                                                <th>Venue</th>
                                                <th>Date</th>
                                                <th>Start Time</th>
                                                <th>End Time</th>
                                                <th>Action</th>
                                           </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $mass_query = mysqli_query($conn,"select * from mass_bookings ")or die(mysqli_error());
                                            while($row = mysqli_fetch_array($mass_query)){
                                                $id = $row['id'];
                                            ?>
                                            <tr>
                                                <td width="30">
                                                    <input id="optionsCheckbox" class="uniform_on" name="selector[]" type="checkbox" value="<?php echo $id; ?>">
                                                </td>
                                                <td><?php echo $row['title']; ?></td>
                                                <td><?php echo $row['name']; ?></td>
                                                <td><?php echo $row['venue']; ?></td>
                                                <td><?php echo $row['date']; ?></td>
                                                <td><?php echo $row['start_time']; ?></td>
                                                <td><?php echo $row['end_time']; ?></td>
                                                <?php include('toolttip_edit_delete.php'); ?>                                                            
                                                <td width="120">
                                                    <a rel="tooltip"  title="Edit Mass Booking" id="e<?php echo $id; ?>" href="edit_mass_booking.php<?php echo '?id='.$id; ?>"  data-toggle="modal" class="btn btn-success"><i class="icon-pencil icon-large"> Edit</i></a>
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
        // Your existing code to save mass booking details
        $title = $_POST['title'];
        $name = $_POST['name'];
        $venue = $_POST['venue'];
        $date = $_POST['date'];
        $start_time = $_POST['start_time'];
        $end_time = $_POST['end_time'];

        $qry = "INSERT INTO mass_bookings (title, name, venue, date, start_time, end_time)
                VALUES ('$title', '$name', '$venue', '$date', '$start_time', '$end_time')
                ON DUPLICATE KEY UPDATE 
                title = VALUES(title), name = VALUES(name), venue = VALUES(venue), date = VALUES(date), start_time = VALUES(start_time), end_time = VALUES(end_time)";
        
        $result = mysqli_query($conn, $qry);
        
        if($result){
            echo "<script type = \"text/javascript\"> window.location = 'mass_bookings.php'; </script>";
        } else{
            echo "<script type = \"text/javascript\"> alert(\"Failed to save mass booking. Please try again.\"); </script>";
        }
    }
    ?>
</body>
</html>
