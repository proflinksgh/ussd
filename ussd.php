<?php
header('Content-type: text/plain');
$url = parse_url(getenv("CLEARDB_DATABASE_URL"));

$server = $url["host"];
$username = $url["user"];
$password = $url["pass"];
$db = substr($url["path"], 1);

$conn = new mysqli($server, $username, $password, $db);
$status = $conn?'connected':'Not connected';

// mysqli_select_db($conn,$db)or die("cannot select DB");
// mysqli_set_charset($conn,'utf8');

$phone = $_POST['phoneNumber'];
$session_id = $_POST['sessionId'];
$service_code = $_POST['serviceCode'];
$ussd_string= $_POST['text'];


$level = 0;

$ussd_string_exploded = explode ("*",$ussd_string);
$level = count($ussd_string_exploded);
$strl = strlen($ussd_string);


if($level == 1 && $ussd_string == ""){
    display_menu();
}else if($level == 1 && $ussd_string == "1"){
  display_register_info();
}else if ($level == 2 && $strl > 14)
{
    $explode_input = explode (",",$ussd_string_exploded[1]);
    $name = $explode_input[0];
    $contact = $explode_input[1];
    $_SESSION['name'] = $name;
    $_SESSION['contact'] = $contact;
    

    open_account($name, $contact);
  
}else if($level == 2 && $strl <= 10){
    display_register_info();
}

// else if($level == 3){
    
//     //Post into database
//     open_account();
    
// }



function ussd_proceed($ussd_text){
    echo "CON $ussd_text";
}


function ussd_stop($ussd_text){
    echo "END $ussd_text";
}


function display_menu()
{
    $ussd_text =    "Welcome to Barry Financial Services\n\n Please select an option: \n 1. Open savings account \n2. Make deposit \n3. Make withdrawal  \n4. Check balance"; // add \n so that the menu has new lines
    ussd_proceed($ussd_text);
}



function about($ussd_text)
{
    $ussd_text =    "This is a sample registration application";
    ussd_stop($ussd_text);
}

function display_register_info()
{
    $ussd_text = "Please enter your full name and phone number seperated by comma(,). For example: Joe Links, 0247058668";
    ussd_proceed($ussd_text);
}


function open_account($name, $contact){
 $date = date('Y-m-d H:i:s');
 $type = "Savings";
 $status = '0';
 
 $rand_no = rand(1111111111,9999999999);
 $acc_no = $rand_no;
    
  $sql = "INSERT INTO new_account (NAME, CONTACT, DATE_CREATE, ACCOUNT_TYPE, ACCOUNT_STATUS, ACCOUNT_NUMBER) VALUES ('$name', '$contact', '$date', '$type', '0', '$acc_no')"; 

  $result = $conn->query($sql);
  ussd_proceed("here now");

    


// if($result) {
//      ussd_stop("successful");
// $text = "Account has been created successfully. Your account number is: \n".$acc_no.". Please your account number safe. Thank you.\n\n1. Make deposit\n2. Menu";
// ussd_proceed($ussd_text);
//   }else{
//     ussd_stop("failed");
//     }
}

// mysqli_close($conn);

// $dbh = null;
?>

