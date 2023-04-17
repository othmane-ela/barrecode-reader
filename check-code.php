<?php
// Retrieve the code from the POST request
$code = $_POST['code'];

// Connect to the database
$servername = "eu-cdbr-west-03.cleardb.net";
$username = "b302dece02d1a0";
$password = "90e0f565";
$dbname = "heroku_0428dd0542836b5";

$conn = mysqli_connect($servername, $username, $password, $dbname);
if (!$conn) {
  die("Connection failed: " . mysqli_connect_error());
}

// Check if the code exists in the client table
$sql = "SELECT COUNT(*) AS count FROM client WHERE code = '$code'";
$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($result);

$response = array();
if ($row['count'] > 0) {
  $response['exists'] = true;
} else {
  $response['exists'] = false;
}

// Send the response as a JSON object
header('Content-Type: application/json');
echo json_encode($response);

// Close the database connection
mysqli_close($conn);
?>
