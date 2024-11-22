<div class="row-fluid">
    <!-- block -->
    <div class="block">
        <div class="navbar navbar-inner block-header">
            <div class="muted pull-left"><i class="icon-plus-sign icon-large"> ADD EVENT</i></div>
        </div>
        <div class="block-content collapse in">
            <div class="span12">
                <form method="post">
                    <table>
                        <tr>
                            <td style="color: #003300; font-weight: bold; font-size: 16px">Add Event Here:</td>
                        </tr>
                        <tr>
                            <td>&nbsp;</td>
                        </tr>
                        <tr>
                            <td><input type="text" name="title" placeholder="Title"></td>
                        </tr>
                        <tr>
                            <td><input type="date" name="date" placeholder="Date"></td>
                        </tr>
                        <tr>
                            <td><input type="text" name="start_time" placeholder="Start Time"></td>
                        </tr>
                        <tr>
                            <td><input type="text" name="end_time" placeholder="End Time"></td>
                        </tr>
                        <tr>
                            <td><input type="text" name="venue" placeholder="Venue"></td>
                        </tr>
                        <tr>
                            <td><input type="text" name="charges" placeholder="Charges"></td>
                        </tr>
                        <tr>
                            <td>
                                <textarea name="content" placeholder="Description" class="text"></textarea>
                            </td>
                        </tr>
                        <tr>
                            <td><input type="submit" name="send" value="SAVE"></td>
                        </tr>
                    </table>
                </form>
            </div>
        </div>
    </div>
    <!-- /block -->
</div>

<?php
if(isset($_POST['send'])) {
    $title = $_POST['title'];
    $date = $_POST['date'];
    $start_time = $_POST['start_time'];
    $end_time = $_POST['end_time'];
    $venue = $_POST['venue'];
    $charges = $_POST['charges'];
    $content = $_POST['content'];
    
    // Check if the event already exists in the database
    $check_query = mysqli_query($conn, "SELECT * FROM event WHERE Title = '$title' AND Date = '$date' AND Start_Time = '$start_time' AND End_Time = '$end_time' AND Venue = '$venue' AND Charges = '$charges' AND Content = '$content'");
    
    if(mysqli_num_rows($check_query) > 0) {
        // Event already exists
        echo "<script type = 'text/javascript'>
                    alert('Event already exists in the database.');
                </script>";
    } else {
        // Insert the event into the database
        $qry = "INSERT INTO event (Title, Date, Start_Time, End_Time, Venue, Charges, Content)
                VALUES ('$title', '$date', '$start_time', '$end_time', '$venue', '$charges', '$content')";
        $result = mysqli_query($conn, $qry) or die(mysqli_error());
        
        if($result == TRUE) {
            echo "<script type = 'text/javascript'>
                        window.location = 'events.php';
                    </script>";
        } else {
            echo "<script type = 'text/javascript'>
                        alert('Failed to add event. Try again.');
                    </script>";
        }
    }
}
?>
