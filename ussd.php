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
$date = date('Y-m-d H:i:s');


$level = 0;

$ussd_string_exploded = explode ("*",$ussd_string);
$level = count($ussd_string_exploded);
$strl = strlen($ussd_string);
$match = preg_match("/[a-z]/i", $ussd_string);

$namespl = explode ("*",$ussd_string_exploded[1]);
$namesp = $namespl[0];

$check_num = is_numeric($namesp);


if($level == 1 && $ussd_string == ""){
    display_menu();
}else if($level == 1 && $ussd_string == "1"){
  display_register_info();
}else if($level == 1 && $ussd_string == "2"){
 
  $sql ="SELECT * FROM `new_account` WHERE `CONTACT` LIKE '%".$phone."%'";
  $result = $conn->query($sql);
     
  if($result ){
      
    $i=0; 
    $text = "Select account to receive deposit\n\n";
  while($row = mysqli_fetch_array($result))
    {
      $i++;
      $text .= $row['ID'].". ".$row['ACCOUNT_NUMBER']."(".$row['NAME'].")\n";   
    }
      ussd_proceed($text); 
      
  }else{
      
      $text = "No account found";
      ussd_proceed($text); 
  }
     

     
  
}else if ($level == 2 && $check_num){

  $_SESSION['id'] = $namesp;

   $text = "Enter amount (GH¢):";
   ussd_proceed($text); 

}else if ($level == 3){

  if($check_num){
    $_SESSION['amount'] = $namesp;
  }else{
    
  }

   $text = "ID saved: ".$_SESSION['id'];
   ussd_proceed($text); 

}else if ($level == 2 && isset($namesp) && $strl > 5 && $match)
{
    $ussd_string = $namesp;
    $date = date('Y-m-d H:i:s');
    $type = "Savings";
    $status = '0';
    $rand_no = rand(1111111111,9999999999);
    $acc_no = $rand_no;
    
  $sql = "INSERT INTO `new_account`(`NAME`, `CONTACT`, `DATE_CREATE`, `ACCOUNT_TYPE`, `ACCOUNT_STATUS`, `ACCOUNT_NUMBER`) VALUES ('$ussd_string', '$phone', '$date', '$type', '$status', '$acc_no')"; 
  $result = $conn->query($sql);

if($result) {
$text = "Account has been created successfully. Your account number is:\n".$acc_no.". \n\nPlease visit any nearest branch to validate your account. Thank you.";
ussd_stop($text);
  }else{
    $text = "Invalid name entered.\n\nPlease enter your full name:";
    ussd_proceed($text);
 }

    
    
}else if($level == 2 && $strl <= 5 && !$check_num){
   
display_register_info();
}

// else if($level == 3 && $strl > 4 && $match){
//     $namespl = explode ("*",$ussd_string_exploded[1]);
//     $ussd_string = $namespl[0];
    
//     $date = date('Y-m-d H:i:s');
//     $type = "Savings";
//     $status = '0';
//     $rand_no = rand(1111111111,9999999999);
//     $acc_no = $rand_no;
    

//   $sql = "INSERT INTO `new_account`(`NAME`, `CONTACT`, `DATE_CREATE`, `ACCOUNT_TYPE`, `ACCOUNT_STATUS`, `ACCOUNT_NUMBER`) VALUES ('$ussd_string', '$phone', '$date', '$type', '$status', '$acc_no')"; 
//   $result = $conn->query($sql);

// if($result) {
// $text = "Account has been created successfully. Your account number is:\n".$acc_no.". Please visit the nearest branch to validate your account. Thank you";
// ussd_proceed($text);
//   }else{
//     $text = "Invalid name entered.\n\nPlease enter your full name:";
//     ussd_proceed($text);
//  }
    

// }

// else if($level == 4 && is_numeric($ussd_string)){

//   $sql ="SELECT * FROM `new_account` WHERE `CONTACT` LIKE '%".$phone."%' ORDER BY ID DESC LIMIT 1";
//   $result = $conn->query($sql);
     
//   if($result ){
      
//   while($row = mysqli_fetch_array($result))
//     {
//       $acc_no= $row['ACCOUNT_NUMBER'];
//       $type= $row['ACCOUNT_TYPE']; 
//       $name= $row['NAME']; 
//       $contact= $row['CONTACT'];  
//     }

  
//   $sql = "INSERT INTO `deposit`(`ACCOUNT_TYPE`, `NAME`, `AMOUNT`, `ACCOUNT_NUMBER`, `DATE_OF_DEPOSIT`) VALUES ('$type', '$name', '$ussd_string', '$acc_no', '$date')"; 
//   $result = $conn->query($sql);

//     if($result) {


//   $sql ="SELECT COUNT(AMOUNT) as depamount FROM `deposit` WHERE `ACCOUNT_NUMBER` LIKE '%".$acc_no."%'";
//   $result = $conn->query($sql);
     
//   if($result ){
      
//   while($row = mysqli_fetch_array($result))
//     {
//       $depamount= $row['depamount']; 
//     }


//     $text = 'Amount has been deposited successfully\nYour new balance is: '.$depamount;
//     }



      
//   }else{
      
//       $text = "No account found";
//       ussd_proceed($text); 
//   }

// }
// }




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




function display_register_info()
{
    $ussd_text = "Please enter your full name";
    ussd_proceed($ussd_text);
}



// mysqli_close($conn);

// $dbh = null;

?>
