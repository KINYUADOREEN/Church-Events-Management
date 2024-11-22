// Redirect to home page
echo '<a href="index.php">Home<br /></a>';

// Check if there's content from Safaricom
$content = file_get_contents('php://input');
if(empty($content)) {
    exit("No data received from Safaricom.");
}

// Decode the JSON data if it's valid, otherwise treat it as plain text
$res = json_decode($content, true);
if($res === null) {
    // Handle plain text data
    $res = ['plaintext' => $content];
}

// Log the transaction data
$dataToLog = [
    date("Y-m-d H:i:s"), 
    "RawData: " . $content,
];
if(isset($res['Body']['stkCallback'])) {
    $dataToLog[] = "MerchantRequestID: " . $res['Body']['stkCallback']['MerchantRequestID'];
    $dataToLog[] = "CheckoutRequestID: " . $res['Body']['stkCallback']['CheckoutRequestID'];
    $dataToLog[] = "ResultCode: " . $res['Body']['stkCallback']['ResultCode'];
    $dataToLog[] = "ResultDesc: " . $res['Body']['stkCallback']['ResultDesc'];
} else {
    $dataToLog[] = "Data: " . $content; // Log the raw data if it's not in the expected format
}
$data = implode(" - ", $dataToLog) . PHP_EOL;
file_put_contents('transaction_log', $data, FILE_APPEND);

try {
    // Connect to the MySQL database
    $conn = new PDO("mysql:host=localhost;dbname=mpesa", "root", ""); // Adjust username and password if needed
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Fetch the latest order ID from the database
    $stmt = $conn->query("SELECT * FROM orders ORDER BY ID DESC LIMIT 1");
    $stmt->execute();
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Update the status of the latest order based on the ResultCode
    foreach($rows as $row) {
        $ID = $row['ID'];
        $resultCode = isset($res['Body']['stkCallback']['ResultCode']) ? $res['Body']['stkCallback']['ResultCode'] : null;
        $status = ($resultCode == '1032') ? 'CANCELLED' : 'SUCCESS';
        $sql = $conn->prepare("UPDATE orders SET Status = :status WHERE ID = :ID");
        $sql->bindParam(':status', $status);
        $sql->bindParam(':ID', $ID);
        $sql->execute();
    }

    file_put_contents('error_log', "Records Inserted", FILE_APPEND);
} catch(PDOException $e) {
    // Log database errors
    file_put_contents('error_log', "Failed to insert Records: " . $e->getMessage(), FILE_APPEND);
    exit("Database error: " . $e->getMessage());
}
