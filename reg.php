<?php
error_reporting(0);

// Assuming $conn is the database connection variable
$conn = @mysqli_connect('localhost', 'root', '');

// Check if the connection is successful
if (!$conn) {
    die('Could not connect: ' . mysqli_error());
}

// Assuming 'cman' is the database name, select it
$db = mysqli_select_db($conn, 'cman');

if (!$db) {
    die('Could not select database: ' . mysqli_error($conn));
}

if (isset($_POST['submit'])) {
    $fname = $_POST['fname'];
    $sname = $_POST['sname'];
    $lname = $_POST['lname'];
    $Gender = $_POST['gender'];
    $birthday = $_POST['birthday'];
    $residence = $_POST['residence'];
    $pob = $_POST['pob'];
    $ministry = $_POST['ministry'];
    $mobile = $_POST['mobile'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    $query = mysqli_query($conn, "SELECT * FROM members WHERE mobile = '$mobile'") or die(mysqli_error($conn));
    $count = mysqli_num_rows($query);

    if ($count > 0) {
        echo '<script>alert("This Member Already Exists"); window.location = "index.php";</script>';
    } else {
        mysqli_query($conn, "INSERT INTO members (fname, sname, lname, Gender, birthday, residence, pob, ministry, mobile, email, thumbnail, password, id) 
            VALUES ('$fname', '$sname', '$lname', '$Gender', '$birthday', '$residence', '$pob', '$ministry', '$mobile', '$email', 'uploads/none.png', '$password', '$mobile')")
            or die(mysqli_error($conn));

        // Assuming $admin_username is defined somewhere
        mysqli_query($conn, "INSERT INTO activity_log (date, username, action) VALUES (NOW(), '$admin_username', 'Added member $mobile')")
            or die(mysqli_error($conn));

        echo '<script>window.location = "index.php"; $.jGrowl("Member Successfully added", { header: "Member add" });</script>';
    }
}

// Close the database connection
mysqli_close($conn);
?>
