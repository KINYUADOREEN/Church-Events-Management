<?php error_reporting(0);?>
<?php $get_event_id= $_GET['id']; ?>		  
<a href="events.php" class="btn btn-info" id="add" data-placement="right" title="Click to Add New"><i class="icon-plus-sign icon-large"></i> Add New Event</a>
<script type="text/javascript">
    $(document).ready(function(){
        $('#add').tooltip('show');
        $('#add').tooltip('hide');
    });
</script>
<div class="navbar navbar-inner block-header">
    <div class="muted pull-left"><i class="icon-pencil icon-large"></i> Edit member Info.</div>
</div>
<?php
$query = mysqli_query($conn,"select * from event where id = '$get_event_id'")or die(mysqli_error());
$row = mysqli_fetch_array($query);
?>
<div class="row-fluid">
    <!-- block -->
    <div class="block">
        <div class="navbar navbar-inner block-header">
            <div class="muted pull-left"><i class="icon-plus-sign icon-large"> EDIT EVENT</i></div>
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
                            <td><input type="text" name="title" value="<?php echo $row['Title']; ?>"></td>
                        </tr>
                        <tr>
                            <td><input type="date" name="date" value="<?php echo $row['Date']; ?>"></td>
                        </tr>
                        <tr>
                            <td><input type="text" name="start_time" placeholder="Start Time" value="<?php echo $row['Start_Time']; ?>"></td>
                        </tr>
                        <tr>
                            <td><input type="text" name="end_time" placeholder="End Time" value="<?php echo $row['End_Time']; ?>"></td>
                        </tr>
                        <tr>
                            <td><input type="text" name="venue" placeholder="Venue" value="<?php echo $row['Venue']; ?>"></td>
                        </tr>
                        <tr>
                            <td>
                                <textarea name="content" rows="" cols="" placeholder="Description"><?php echo $row['content']; ?></textarea>
                            </td>
                        </tr>
                        <tr>
                            <td><input type="submit" name="update" value="SAVE"></td>
                        </tr>
                    </table>
                </form>
            </div>
        </div>
    </div>
    <!-- /block -->
</div>
								
<?php

if(isset($_POST['update'])){
    $title = $_POST['title'];
    $date = $_POST['date'];
    $start_time = $_POST['start_time'];
    $end_time = $_POST['end_time'];
    $venue = $_POST['venue'];
    $content = $_POST['content'];
    $qry = "UPDATE event  
            SET Title='$title',
                Date='$date',
                Start_Time='$start_time',
                End_Time='$end_time',
                Venue='$venue',
                content='$content' 
            WHERE id='$get_event_id'";
    $result = mysqli_query($conn,$qry)or die(mysqli_error());
    if($result == TRUE){
        echo "<script type = \"text/javascript\">
                    window.location = (\"events.php\")
                    </script>";
    } else{
        echo "<script type = \"text/javascript\">
                    alert(\"Not Updated. Try Again\");
                    </script>";
    }
}
?>
