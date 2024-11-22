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
                        <div class="muted pull-left"><i class="icon-plus-sign icon-large"> BOOK VENUE</i></div>
                    </div>
                    <div class="block-content collapse in">
                        <div class="span12">
                            <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                                <table>
                                    <tr>
                                        <td style="color: #003300; font-weight: bold; font-size: 16px">Book Venue Here:</td>
                                    </tr>
                                    <tr>
                                        <td>&nbsp;</td>
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
                                        <td><input type="text" name="occasion" placeholder="Occasion"></td>
                                    </tr>
                                    <tr>
                                        <td><input type="submit" name="book" value="Book"></td>
                                    </tr>
                                </table>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <?php   
             $count_venues=mysqli_query($conn,"select * from venue_bookings");
             $count = mysqli_num_rows($count_venues);
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
                            <div class="muted pull-left"><i class="icon-user"></i>  Venue(s) List</div>
                            <div class="muted pull-right">
                                Number of venues booked: <span class="badge badge-info"><?php  echo $count; ?></span>
                            </div>
                        </div>
                        <div class="block-content collapse in">
                            <div class="span12">
                                <table cellpadding="0" cellspacing="0" border="0" class="table" id="example">
                                    <thead>
                                        <tr>
                                            <th>Venue</th>
                                            <th>Date</th>
                                            <th>Start Time</th>
                                            <th>End Time</th>
                                            <th>Occasion</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $venue_query = mysqli_query($conn,"SELECT * FROM venue_bookings") or die(mysqli_error($conn));
                                        while($row = mysqli_fetch_array($venue_query)){
                                        ?>
                                        <tr>
                                            <td><?php echo $row['venue']; ?></td>
                                            <td><?php echo $row['date']; ?></td>
                                            <td><?php echo $row['start_time']; ?></td>
                                            <td><?php echo $row['end_time']; ?></td>
                                            <td><?php echo $row['occasion']; ?></td>
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
    if(isset($_POST['book'])){
        $venue = $_POST['venue'];
        $date = $_POST['date'];
        $start_time = $_POST['start_time'];
        $end_time = $_POST['end_time'];
        $occasion = $_POST['occasion'];
        
        // Check if the date is not in the past
        if(strtotime($date) < strtotime(date('Y-m-d'))) {
            echo "<script type = \"text/javascript\">
                        alert(\"Please select a date in the future.\");
                    </script>";
        } else {
            // Check if the venue is available
            $is_available = checkVenueAvailability($venue, $date, $start_time, $end_time);
            
            if($is_available) {
                // Venue is available, insert the booking into the database
                $qry = "INSERT INTO venue_bookings (venue, date, start_time, end_time, occasion)
                        VALUES('$venue', '$date', '$start_time', '$end_time', '$occasion')";
                $result = mysqli_query($conn, $qry) or die(mysqli_error($conn));
                if($result == TRUE){
                    echo "<script type = \"text/javascript\">
                                alert(\"Venue booked successfully!\");
                                window.location = 'Venuebooking.php'; // Redirect to the same page to refresh the table
                            </script>";
                } else {
                    echo "<script type = \"text/javascript\">
                                alert(\"Failed to book venue. Try again.\");
                            </script>";
                }
            } else {
                // Venue is not available at the specified time
                echo "<script type = \"text/javascript\">
                            alert(\"Venue is currently booked for an event at the specified time. Please choose another time or venue.\");
                        </script>";
            }
        }
    }
    
    // Function to check venue availability
    function checkVenueAvailability($venue, $date, $start_time, $end_time) {
        global $conn;
        // Query the event table to check if the venue is scheduled for an event at the specified time
        $query = "SELECT * FROM venue_bookings WHERE venue = '$venue' AND date = '$date' AND ((start_time BETWEEN '$start_time' AND '$end_time') OR (end_time BETWEEN '$start_time' AND '$end_time'))";
        $result = mysqli_query($conn, $query);
        if(mysqli_num_rows($result) > 0) {
            // Venue is already booked for an event
            return false;
        } else {
            // Venue is available
            return true;
        }
    }
    ?>
</body>
</html>
