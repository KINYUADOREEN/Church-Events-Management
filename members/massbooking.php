<?php 
include('header.php'); 
include('session.php'); 

// Suppress warnings
error_reporting(E_ERROR | E_PARSE);

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
                            <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                                <table>
                                    <tr>
                                        <td style="color: #003300; font-weight: bold; font-size: 16px">Book Mass Here:</td>
                                    </tr>
                                    <tr>
                                        <td>&nbsp;</td>
                                    </tr>
                                    <tr>
                                        <td><input type="text" name="title" placeholder="Title"></td>
                                    </tr>
                                    <tr>
                                        <td><input type="text" name="name" placeholder="Name"></td>
                                    </tr>
                                    <tr>
                                        <td><input type="text" name="venue" placeholder="Venue"></td>
                                    </tr>
                                    <tr>
                                        <td><input type="date" name="date" placeholder="Date" min="<?php echo date('Y-m-d'); ?>"></td>
                                    </tr>
                                    <tr>
                                        <td><input type="text" name="start_time" placeholder="Start Time"></td>
                                    </tr>
                                    <tr>
                                        <td><input type="text" name="end_time" placeholder="End Time"></td>
                                    </tr>
                                    <tr>
                                        <td><input type="text" name="phone_number" placeholder="Phone number"></td>
                                    </tr>
                                    <tr>
                                        <td><input type="submit" name="send" value="Book"></td>
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
                                <table cellpadding="0" cellspacing="0" border="0" class="table" id="example">
                                    <thead>
                                        <tr>
                                            <th>Title</th>
                                            <th>Name</th>
                                            <th>Venue</th>
                                            <th>Date</th>
                                            <th>Start Time</th>
                                            <th>End Time</th>
                                            <th>Phone Number</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $mass_query = mysqli_query($conn,"select * from mass_bookings ")or die(mysqli_error());
                                        while($row = mysqli_fetch_array($mass_query)){
                                        ?>
                                        <tr>
                                            <td><?php echo $row['title']; ?></td>
                                            <td><?php echo $row['name']; ?></td>
                                            <td><?php echo $row['venue']; ?></td>
                                            <td><?php echo $row['date']; ?></td>
                                            <td><?php echo $row['start_time']; ?></td>
                                            <td><?php echo $row['end_time']; ?></td>
                                            <td><?php echo $row['phone_number']; ?></td>
                                        </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>
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
    if(isset($_POST['send'])){
        $title = $_POST['title'];
        $name = $_POST['name'];
        $venue = $_POST['venue'];
        $date = $_POST['date'];
        $start_time = $_POST['start_time'];
        $end_time = $_POST['end_time'];
        $phone_number = $_POST['phone_number'];
        
        // Check if the mass date is in the past
        if(strtotime($date) < strtotime(date('Y-m-d'))) {
            echo "<script type = \"text/javascript\">
                        alert(\"Cannot book mass for past dates.\");
                    </script>";
        } else {
            // Check if the phone number exists in the members table
            $check_member_query = mysqli_query($conn, "SELECT * FROM members WHERE mobile = '$phone_number'");
            
            if(mysqli_num_rows($check_member_query) > 0) {
                // Phone number exists, insert the mass into the database
                $qry = "INSERT INTO mass_bookings (title, name, venue, date, start_time, end_time, phone_number)
                        VALUES('$title', '$name', '$venue', '$date', '$start_time', '$end_time', '$phone_number')";
                $result = mysqli_query($conn, $qry) or die(mysqli_error());
                if($result == TRUE){
                    // Show Pay Now or Later pop-up
                    echo "<script type = \"text/javascript\">
                                var payNow = confirm('Event added successfully! Would you like to pay now?');
                                if(payNow) {
                                    // Redirect to payment page
                                    window.location = ' MPESA-STK-PUSH/checkout.php';
                                } else {
                                    // Redirect to home page or any other appropriate action
                                    window.location = '".$_SERVER['PHP_SELF']."';
                                }
                            </script>";
                } else {
                    echo "<script type = \"text/javascript\">
                                alert(\"Failed to book mass. Try again.\");
                            </script>";
                          
                }
            } else {
                // Phone number does not exist in the members table
                echo "<script type = \"text/javascript\">
                            alert(\"Phone number does not correspond to any member. Please visit the church offices for assistance.\");
                        </script>";
            }
        }
    }
    ?>
</body>
</html>
