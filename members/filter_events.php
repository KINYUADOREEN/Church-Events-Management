<?php
include('session.php');
include('config.php');
?>
<!DOCTYPE html>
<html>
<head>
    <title>Filtered Events</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
<div class="container">
    <h2>Filtered Events</h2>
    <table class="table table-bordered">
        <thead>
        <tr>
            <th>Title</th>
            <th>Date</th>
            <th>Content</th>
            <th>Venue</th>
            <th>Start Time</th>
            <th>End Time</th>
            <th>Charges</th>
        </tr>
        </thead>
        <tbody>
        <?php
        // Check if filter criteria are set
        if(isset($_POST['filterDate']) || isset($_POST['filterVenue'])){
            // Construct query based on filter criteria
            $filter_query = "SELECT * FROM event WHERE 1=1";
            if(isset($_POST['filterDate']) && !empty($_POST['filterDate'])){
                $filter_query .= " AND Date = '" . $_POST['filterDate'] . "'";
            }
            if(isset($_POST['filterVenue']) && !empty($_POST['filterVenue'])){
                $filter_query .= " AND Venue = '" . $_POST['filterVenue'] . "'";
            }

            // Execute the query
            $result = mysqli_query($conn, $filter_query);

            // Display filtered events
            if(mysqli_num_rows($result) > 0){
                while($row = mysqli_fetch_assoc($result)){
                    echo "<tr>";
                    echo "<td>" . $row['Title'] . "</td>";
                    echo "<td>" . $row['Date'] . "</td>";
                    echo "<td>" . $row['content'] . "</td>";
                    echo "<td>" . $row['Venue'] . "</td>";
                    echo "<td>" . $row['Start_Time'] . "</td>";
                    echo "<td>" . $row['End_Time'] . "</td>";
                    echo "<td>" . $row['charges'] . "</td>";
                    echo "</tr>";
                }
            } else{
                echo "<tr><td colspan='7'>No events found</td></tr>";
            }
        } else{
            echo "<tr><td colspan='7'>No filter criteria set</td></tr>";
        }
        ?>
        </tbody>
    </table>
</div>
</body>
</html>
