<?php
header('Content-type: text/plain');
$url = parse_url(getenv("CLEARDB_DATABASE_URL"));

$server = $url["host"];
$username = $url["user"];
$password = $url["pass"];
$db = substr($url["path"], 1);

$conn = new mysqli($server, $username, $password, $db);
$status = $conn?'connected':'Not connected';

$phone = $_POST['phoneNumber'];
$session_id = $_POST['sessionId'];
$service_code = $_POST['serviceCode'];
$ussd_string= $_POST['text'];


$level = 0;

$ussd_string_exploded = explode ("*",$ussd_string);
$level = count($ussd_string_exploded);
$strl = strlen($ussd_string);
$match = preg_match("/[a-z]/i", $ussd_string);


if($level == 1 && $ussd_string == ""){
    display_menu();
}else if($level == 1 && $ussd_string == "1"){
  display_register_info();
}else if($level == 1 && $ussd_string == "2"){
 
//     $text = "Phone is: ".$phone;
//     ussd_proceed($text);
    fetch_accounts();
  
}else if ($level == 2 && $strl > 5 && $match)
{
    $namespl = explode ("*",$ussd_string_exploded[1]);
    $ussd_string = $namespl[0];
    $date = date('Y-m-d H:i:s');
    $type = "Savings";
    $status = '0';
    $rand_no = rand(1111111111,9999999999);
    $acc_no = $rand_no;
    
  $sql = "INSERT INTO `new_account`(`NAME`, `CONTACT`, `DATE_CREATE`, `ACCOUNT_TYPE`, `ACCOUNT_STATUS`, `ACCOUNT_NUMBER`) VALUES ('$ussd_string', '$phone', '$date', '$type', '$status', '$acc_no')"; 
  $result = $conn->query($sql);

if($result) {
$text = "Account has been created successfully. Your account number is:\n".$acc_no.". Please keep your account number safe.\n\nSelect option:\n1. Make deposit\n2. Menu";
ussd_proceed($text);
  }else{
    $text = "Invalid name entered.\n\nPlease enter your full name:";
    ussd_proceed($text);
 }

    
    
}else if($level == 2 && $strl <= 5){
    display_register_info();
}else if($level == 3 && $strl > 4 && $match){
    $namespl = explode ("*",$ussd_string_exploded[1]);
    $ussd_string = $namespl[0];
    
    $date = date('Y-m-d H:i:s');
    $type = "Savings";
    $status = '0';
    $rand_no = rand(1111111111,9999999999);
    $acc_no = $rand_no;
    

  $sql = "INSERT INTO `new_account`(`NAME`, `CONTACT`, `DATE_CREATE`, `ACCOUNT_TYPE`, `ACCOUNT_STATUS`, `ACCOUNT_NUMBER`) VALUES ('$ussd_string', '$phone', '$date', '$type', '$status', '$acc_no')"; 
  $result = $conn->query($sql);

if($result) {
$text = "Account has been created successfully. Your account number is:\n".$acc_no.". Please keep your account number safe.\n\nSelect option:\n1. Make deposit\n2. Menu";
ussd_proceed($text);
  }else{
    $text = "Invalid name entered.\n\nPlease enter your full name:";
    ussd_proceed($text);
 }
    

}



function ussd_proceed($ussd_text){
    echo "CON $ussd_text";
}


function ussd_stop($ussd_text){
    echo "END $ussd_text";
}


function display_menu()
{
    $ussd_text =    "Welcome to Barry Financial Services\n\nPlease select an option: \n1. Open savings account\n2. Make deposit\n3. Make withdrawal\n4. Check balance"; // add \n so that the menu has new lines
    ussd_proceed($ussd_text);
}



function about($ussd_text)
{
    $ussd_text =    "This is a sample registration application";
    ussd_stop($ussd_text);
}

function display_register_info()
{
    $ussd_text = "Please enter your full name";
    ussd_proceed($ussd_text);
}


 function fetch_accounts(){

  $fetchacc ="SELECT * FROM `new_account` WHERE `CONTACT` LIKE '%+233247058668%'";
 // $result=$conn->query($fetchacc);
     
//    $i=0; 
//     $text = "Please select account\n\n";
//   while($row = mysqli_fetch_array($result))
//     {
//       $text .= $i." ".$row['CONTACT'];   
//       $i++;
//     }
     
     $result = "here now";
    
    ussd_proceed($result); 

     
     
     
     
     
     
// if($result) {
//   ussd_proceed($result);
//   }else{
//     $text = "No account found";
//     ussd_proceed($text);
//  }


  
}


// mysqli_close($conn);

// $dbh = null;

?>


