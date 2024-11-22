<!DOCTYPE html>
<html>
<head>
    <title>Upcoming Events</title>
    <style>
        /* Hide the report generation options by default */
        .report-options {
            display: none;
        }
    </style>
</head>
<body>
    <?php include('header.php'); ?>
    <?php include('session.php'); ?>
    <?php include('navbar.php'); ?>
    <div class="container-fluid">
        <div class="row-fluid">
            <?php include('sidebar.php'); ?>
            <div class="span9" id="content">
                <div class="row-fluid">
                    <div id="sc" align="center"><img src="images/sclogo.png" width="45%" height="45%"/></div>
                    <?php  
                        $count_members = mysqli_query($conn, "SELECT * FROM event WHERE DATE_ADD(STR_TO_DATE(Date, '%Y-%m-%d'), INTERVAL YEAR(CURDATE())-YEAR(STR_TO_DATE(Date, '%Y-%m-%d')) YEAR) BETWEEN CURDATE() AND DATE_ADD(CURDATE(), INTERVAL 7 DAY)");
                        $count = mysqli_num_rows($count_members);
                    ?>    
                    <div id="block_bg" class="block">
                        <div class="navbar navbar-inner block-header">
                            <div class="muted pull-left"><i class="icon-reorder icon-large"></i>Upcoming Events</div>
                            <div class="muted pull-right">
                                Upcoming Events <span class="badge badge-info"><?php echo $count; ?></span>
                            </div>
                        </div>
                        <h4 id="sc">Events List 
                            <div align="right" id="sc">Date:
                                <?php
                                    $date = new DateTime();
                                    echo $date->format('l, F jS, Y');
                                ?>
                            </div>
                        </h4>
                        <div class="container-fluid">
                            <div class="row-fluid"> 
                                <div class="empty">
                                    <div class="pull-right">
                                        <a href="print_events.php" class="btn btn-info" id="print" data-placement="left" title="Click to Print"><i class="icon-print icon-large"></i> Print List</a>
                                    </div>
                                </div>
                            </div> 
                        </div>
                        <div class="block-content collapse in">
                            <div class="span12">
                                <div style="overflow-x:auto;">
                                    <table cellpadding="0" cellspacing="0" border="0" class="table" id="example">
                                        <thead>      
                                            <tr>                    
                                                <th>TITLE</th>
                                                <th>DATE</th>
                                                <th>DESCRIPTION</th>
                                                <th>START TIME</th> <!-- New column header for Start Time -->
                                                <th>END TIME</th> <!-- New column header for End Time -->
                                                <th>VENUE</th> <!-- New column header for Venue -->
                                                <th>CHARGES</th> <!-- New column header for the charges -->
                                                <th>VIEW DETAILS</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                                $members_query = mysqli_query($conn,"SELECT * FROM event WHERE DATE_ADD(STR_TO_DATE(Date, '%Y-%m-%d'), INTERVAL YEAR(CURDATE())-YEAR(STR_TO_DATE(Date, '%Y-%m-%d')) YEAR) BETWEEN CURDATE() AND DATE_ADD(CURDATE(), INTERVAL 7 DAY)") or die(mysqli_error($conn));
                                                while($row = mysqli_fetch_array($members_query)) {
                                                    $username = $row['id'];
                                            ?>
                                            <tr>        
                                                <td style="max-width:150px; overflow:hidden; text-overflow:ellipsis;"><?php echo $row['Title']; ?></td>
                                                <td><?php echo $row['Date']; ?></td>
                                                <td style="max-width:200px; overflow:hidden; text-overflow:ellipsis;"><?php echo $row['content']; ?></td>
                                                <td><?php echo $row['Start_Time']; ?></td> <!-- Display Start Time for each event -->
                                                <td><?php echo $row['End_Time']; ?></td> <!-- Display End Time for each event -->
                                                <td style="max-width:150px; overflow:hidden; text-overflow:ellipsis;"><?php echo $row['Venue']; ?></td> <!-- Display Venue for each event -->
                                                <td><?php echo $row['charges']; ?></td> <!-- Display charges for each event -->
                                                <!-- Button to view details -->
                                                <td>
                                                    <a href="events_details.php?id=<?php echo $row['id']; ?>" class="btn btn-info">View Details</a>
                                                </td>
                                            </tr>
                                            <?php } ?>   
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                 <!-- Toggle button for report options -->
                <button id="toggleOptions" class="btn btn-info">Toggle Report Options</button>
                <!-- End of toggle button -->

                <!-- Form for generating report with venue, date, and month options -->
                <form id="reportForm" action="generate_pdf.php" method="post" class="report-options">
                    <label for="filter_venue">Select Venue:</label>
                    <select name="filter_venue" id="filter_venue">
                        <option value="all">All Venues</option>
                        <option value="Freedom Hall">Freedom Hall</option>
                        <option value="Auditorium">Auditorium</option>
                        <option value="Zatima Hall">Zatima Hall</option>
                        <option value="Quo Vadis">Quo Vadis</option>
                        <option value="St Irene Shrine">St Irene Shrine</option>
                        <option value="Nyeri Cathedral">Nyeri Cathedral</option>
                        <!-- Add more options as needed -->
                    </select>
                    <label for="filter_start_date">Start Date:</label>
                    <input type="date" name="filter_start_date" id="filter_start_date">
                    <label for="filter_end_date">End Date:</label>
                    <input type="date" name="filter_end_date" id="filter_end_date">
                    <label for="filter_month">Select Month:</label>
                    <input type="month" name="filter_month" id="filter_month">
                    <button type="submit" class="btn btn-primary">Generate Report</button>
                </form>
                <!-- End of form for generating report -->
            </div>
        </div>
    </div>
    <?php include('footer.php'); ?>
    <?php include('script.php'); ?>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        // JavaScript to toggle visibility of report options
        $(document).ready(function(){
            $('#toggleOptions').click(function(){
                $('.report-options').toggle();
            });
        });
    </script>
</body>
</html>
