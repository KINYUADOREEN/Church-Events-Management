<?php 
session_start();

$errors  = array();
$errmsg  = '';

$config = array(
    "env"              => "sandbox",
    "BusinessShortCode"=> "174379",
    "key"              => "6ZTfjQGGySUWUxLnB4IUzmZy3AbD8Zkp", //Enter your consumer key here
    "secret"           => "E2fGPbNy9JzHC93N", //Enter your consumer secret here
    "username"         => "apitest",
    "TransactionType"  => "CustomerPayBillOnline",
    "passkey"          => "bfb279f9aa9bdbcf158e97dd71a467cd2e0c893059b10f78e6b72ada1ed2c919", //Enter your passkey here
    "CallBackURL"      => "https://mydomain.com.path", //When using Localhost, Use Ngrok to forward the response to your Localhost
    "AccountReference" => "CompanyXLTD",
    "TransactionDesc"  => "Payment of X" ,
);



if (isset($_POST['phone_number'])) {

    $phone = $_POST['phone_number'];
    $orderNo = $_POST['orderNo'];
    $amount = $_POST['amount']; // Get the amount entered by the user

    // Validate amount
    if (!is_numeric($amount) || $amount <= 0) {
        $errors['amount'] = "Invalid amount";
    }

    $phone = (substr($phone, 0, 1) == "+") ? str_replace("+", "", $phone) : $phone;
    $phone = (substr($phone, 0, 1) == "0") ? preg_replace("/^0/", "254", $phone) : $phone;
    $phone = (substr($phone, 0, 1) == "7") ? "254{$phone}" : $phone;



    $access_token = ($config['env']  == "live") ? "https://api.safaricom.co.ke/oauth/v1/generate?grant_type=client_credentials" : "https://sandbox.safaricom.co.ke/oauth/v1/generate?grant_type=client_credentials"; 
    $credentials = base64_encode($config['key'] . ':' . $config['secret']); 
    
    $ch = curl_init($access_token);
    curl_setopt($ch, CURLOPT_HTTPHEADER, ["Authorization: Basic " . $credentials]);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
    $response = curl_exec($ch);
    curl_close($ch);
    $result = json_decode($response); 
    $token = isset($result->{'access_token'}) ? $result->{'access_token'} : "N/A";

    $timestamp = date("YmdHis");
    $password  = base64_encode($config['BusinessShortCode'] . "" . $config['passkey'] ."". $timestamp);

    $curl_post_data = array( 
        "BusinessShortCode" => $config['BusinessShortCode'],
        "Password" => $password,
        "Timestamp" => $timestamp,
        "TransactionType" => $config['TransactionType'],
        "Amount" => $amount, // Use the amount entered by the user
        "PartyA" => $phone,
        "PartyB" => $config['BusinessShortCode'],
        "PhoneNumber" => $phone,
        "CallBackURL" => $config['CallBackURL'],
        "AccountReference" => $config['AccountReference'],
        "TransactionDesc" => $config['TransactionDesc'],
    ); 

    $data_string = json_encode($curl_post_data);

    $endpoint = ($config['env'] == "live") ? "https://api.safaricom.co.ke/mpesa/stkpush/v1/processrequest" : "https://sandbox.safaricom.co.ke/mpesa/stkpush/v1/processrequest"; 

    $ch = curl_init($endpoint );
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Authorization: Bearer '.$token,
        'Content-Type: application/json'
    ]);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    $response     = curl_exec($ch);
    curl_close($ch);

    $result = json_decode(json_encode(json_decode($response)), true);

    if(!preg_match('/^[0-9]{10}+$/', $phone) && array_key_exists('errorMessage', $result)){
        $errors['phone'] = $result["errorMessage"];
    }

    if($result['ResponseCode'] === "0"){
        //STK Push request successful
        
        $MerchantRequestID = $result['MerchantRequestID'];
        $CheckoutRequestID = $result['CheckoutRequestID'];

        $conn = mysqli_connect("localhost","root","","cman");
       
        $sql = "INSERT INTO `orders`(`ID`, `OrderNo`, `Amount`, `Phone`, `CheckoutRequestID`, `MerchantRequestID`) VALUES ('','".$orderNo."','".$amount."','".$phone."','".$CheckoutRequestID."','".$MerchantRequestID."');";
        
        if ($conn->query($sql) === TRUE){
            $_SESSION["MerchantRequestID"] = $MerchantRequestID;
            $_SESSION["CheckoutRequestID"] = $CheckoutRequestID;
            $_SESSION["phone"] = $phone;
            $_SESSION["orderNo"] = $orderNo;

            header('location: confirm-payment.php');
        }else{
            $errors['database'] = "Unable to initiate your order: ".$conn->error;;  
            foreach($errors as $error) {
                $errmsg .= $error . '<br />';
            } 
        }
        
    }else{
        $errors['mpesastk'] = $result['errorMessage'];
        foreach($errors as $error) {
            $errmsg .= $error . '<br />';
        }
    }
    
}

?>

<!DOCTYPE html>
<html>
<head>
    <style>
        /* Add your CSS styles here */
    </style>
</head>
<body>
    <!-- Your HTML content here -->
    <div class="container">
        <form action='<?php echo $_SERVER['PHP_SELF'] ?>' method='POST'>
            <div class="price">
                <h1>Enter the amount to pay:</h1>
            </div>
            <div class="card__container">
                <div class="card">
                    <div class="row">
                        <img src="mpesa.png" style="width:30%;margin: 0 35%;">
                        <p style="color:#8F92C3;line-height:1.7;">1. Enter the <b>phone number</b> and press "<b>Confirm and Pay</b>"</br>2. You will receive a popup on your phone. Enter your <b>MPESA PIN</b></p>
                        <?php if ($errmsg != ''): ?>
                            <p style="background: #cc2a24;padding: .8rem;color: #ffffff;"><?php echo $errmsg; ?></p>
                        <?php endif; ?>
                    </div>
                    <div class="row number">
                        <div class="info">
                            <input type="hidden" name="orderNo" value="#O2JDI2I3R" />
                            <label for="amount">Amount</label>
                            <input id="amount" type="number" name="amount" placeholder="Enter amount" required />
                            <label for="cardnumber">Phone number</label>
                            <input id="cardnumber" type="text" name="phone_number" maxlength="10" placeholder="0700000000"/>
                        </div>
                    </div>
                </div>
            </div>
            <div class="button">
                <button type="submit"><i class="ion-locked"></i> Confirm and Pay</button>
            </div>
        </form>
        <p style="color:#8F92C3;line-height:1.7;margin-top:5rem;">Copyright 2022 | All Rights Reserved | Made by MediaForce</p>
    </div>
</body>
</html>
