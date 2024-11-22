<!DOCTYPE html>
<html>
<head>
    <title>Register for Event and Registered Members</title>
</head>
<body>
    <?php 
    // Include necessary files
    include('header.php'); 
    include('session.php'); 
    include('navbar.php'); 
    ?>
    <?php
    // Turn off error reporting
    error_reporting(0);

    // Your PHP code here

    // Turn on error reporting if needed
    error_reporting(E_ALL);
    ?>


    <div class="container-fluid">
        <div class="row-fluid">
            <div class="span6">
                <div class="block">
                    <div class="navbar-inner block-header">
                        <div class="muted pull-left"><i class="icon-list icon-large"> REGISTER FOR EVENT</i></div>
                    </div>
                    <div class="block-content collapse in">
                        <div class="span12">
                            <!-- Form to register for event -->
                            <form method="post">
                                <table>
                                    <tr>
                                        <td style="color: #003300; font-weight: bold; font-size: 16px">Register for Event:</td>
                                    </tr>
                                    <tr>
                                        <td>&nbsp;</td>
                                    </tr>
                                    <tr>
                                        <td><input type="text" name="name" placeholder="Name"></td>
                                    </tr>
                                    <tr>
                                        <td><input type="text" name="Phone" placeholder="Phone Number"></td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <input type="hidden" name="event_id" value="<?php echo $event_id; ?>">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <select name="paid" id="paid">
                                                <option value="yes">Paid</option>
                                                <option value="no">Not Paid</option>
                                            </select>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><input type="submit" name="register" value="Register"></td>
                                    </tr>
                                </table>
                            </form>
                            <!-- End of registration form -->
                        </div>
                    </div>
                </div>
            </div>
            <div class="span6">
                <div class="block">
                    <div class="navbar-inner block-header">
                        <div class="muted pull-left"><i class="icon-group icon-large"> REGISTERED MEMBERS</i></div>
                    </div>
                    <div class="block-content collapse in">
                        <div class="span12">
                            <!-- Print button -->
                            <div class="pull-right">
                                <a href="print_registrations.php" class="btn btn-info" id="print" data-placement="left" title="Click to Print"><i class="icon-print icon-large"></i> Print List</a>
                            </div>
                            <!-- Display registered members -->
                            <h2>Registered Members</h2>
                            <table class="table table-bordered table-responsive">
                                <tr>
                                    <th>Name</th>
                                    <th>Phone Number</th>
                                    <th>Paid</th>
                                </tr>
                                <?php
                                // Retrieve registered members for the selected event from the database
                                if(isset($_POST['event_id'])){
                                    $event_id = $_POST['event_id'];
                                    $query = mysqli_query($conn, "SELECT * FROM event_registrations WHERE event_id = '$event_id'") or die(mysqli_error());
                                    while($row = mysqli_fetch_assoc($query)){
                                        echo "<tr>";
                                        echo "<td>".(isset($row['Name']) ? $row['Name'] : '')."</td>";
                                        echo "<td>".(isset($row['Phone']) ? $row['Phone'] : '')."</td>";
                                        echo "<td>".(isset($row['Paid']) ? $row['Paid'] : '')."</td>";
                                        echo "</tr>";
                                    }
                                }
                                ?>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php 
    // Process registration
    if(isset($_POST['register'])){
        $name = $_POST['name'];
        $phone = $_POST['Phone'];
        $event_id = $_POST['event_id'];
        $paid = $_POST['paid'];

        // Check if the user is already registered for the event
        $check_query = mysqli_query($conn, "SELECT * FROM event_registrations WHERE Name = '$name' AND Phone = '$phone' AND event_id = '$event_id'") or die(mysqli_error());
        $existing_registration = mysqli_fetch_assoc($check_query);

        if(empty($existing_registration)){
            // Insert registration details into the database
            $qry = "INSERT INTO event_registrations (Name, Phone, event_id, Paid) VALUES ('$name', '$phone', '$event_id', '$paid')";
            $result = mysqli_query($conn, $qry) or die(mysqli_error());

            // Display success message
            if($result == TRUE){
                echo "<script type='text/javascript'>alert('Registration Successful!')</script>";
            } else{
                echo "<script type='text/javascript'>alert('Registration Failed! Please try again.')</script>";
            }
        } else {
            echo "<script type='text/javascript'>alert('You are already registered for this event!')</script>";
        }
    }
    ?>

    <?php 
    // Fetch the event ID from the event table
    $event_query = mysqli_query($conn, "SELECT id FROM event LIMIT 1") or die(mysqli_error());
    $event_row = mysqli_fetch_assoc($event_query);
    $event_id = $event_row['id'];
    ?>

    <?php include('footer.php'); ?>
    <?php include('script.php'); ?>
</body>
</html>
