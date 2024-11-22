<?php
// Include necessary files
include('header.php');
include('session.php');
include('dbconn.php');
?>

<!DOCTYPE html>
<html>
<head>
    <title>Upcoming Events</title>
</head>
<body>
    <?php include('navbar.php'); ?>
    <div class="container-fluid">
        <div class="row-fluid">
            <?php include('sidebar.php'); ?>
            <div class="span9" id="content">
                <div class="row-fluid">
                    <?php
                    // Count upcoming events
                    $count_members_query = mysqli_query($conn, "SELECT * FROM event WHERE DATE_ADD(STR_TO_DATE(Date, '%Y-%m-%d'), INTERVAL YEAR(CURDATE())-YEAR(STR_TO_DATE(Date, '%Y-%m-%d')) YEAR) BETWEEN CURDATE() AND DATE_ADD(CURDATE(), INTERVAL 7 DAY)");
                    $count = mysqli_num_rows($count_members_query);
                    ?>
                    <div id="block_bg" class="block">
                        <div class="navbar navbar-inner block-header">
                            <div class="muted pull-left"><i class="icon-reorder icon-large"></i>Upcoming Events</div>
                            <div class="muted pull-right">
                                Upcoming Events <span class="badge badge-info"><?php echo $count; ?></span>
                            </div>
                        </div>
                        <h4 id="sc">Members List 
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
                                        <a href="#" class="btn btn-info" id="print" data-placement="left" title="Click to Print"><i class="icon-print icon-large"></i> Print List</a>               
                                        <script type="text/javascript">
                                            $(document).ready(function(){
                                                $('#print').tooltip('show');
                                                $('#print').tooltip('hide');
                                            });
                                        </script>                      
                                    </div>
                                </div>
                            </div> 
                        </div>
                        <div class="block-content collapse in">
                            <div class="span12">
                                <form action="" method="post">
                                    <table cellpadding="0" cellspacing="0" border="0" class="table" id="example">
                                        <thead>        
                                            <tr>                    
                                                <th>TITLE</th>
                                                <th>DATE</th>
                                                <th>START TIME</th>
                                                <th>END TIME</th>
                                                <th>VENUE</th>
                                                <th>DESCRIPTION</th>
                                                <th>Action</th> <!-- New column for action buttons -->
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $events_query = mysqli_query($conn, "SELECT * FROM event WHERE DATE_ADD(STR_TO_DATE(Date, '%Y-%m-%d'), INTERVAL YEAR(CURDATE())-YEAR(STR_TO_DATE(Date, '%Y-%m-%d')) YEAR) BETWEEN CURDATE() AND DATE_ADD(CURDATE(), INTERVAL 7 DAY)") or die(mysqli_error());
                                            while($row = mysqli_fetch_array($events_query)){
                                                ?>
                                                <tr>
                                                    <td><?php echo $row['Title']; ?></td>
                                                    <td><?php echo $row['Date']; ?></td>
                                                    <td><?php echo $row['Start_Time']; ?></td>
                                                    <td><?php echo $row['End_Time']; ?></td>
                                                    <td><?php echo $row['Venue']; ?></td>
                                                    <td style="max-width: 200px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;"><?php echo $row['content']; ?></td>
                                                    <td>
                                                        <a href="view_registrations.php?event_id=<?php echo $row['id']; ?>" class="btn btn-info">Registrations</a>
                                                    </td>
                                                </tr>
                                                <?php
                                            }
                                            ?>   
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
