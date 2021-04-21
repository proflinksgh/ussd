<?php
header('Content-type: text/plain');
$url = parse_url(getenv("CLEARDB_DATABASE_URL"));

$server = $url["host"];
$username = $url["user"];
$password = $url["pass"];
$db = substr($url["path"], 1);

$conn = new mysqli($server, $username, $password, $db);

$phone = $_GET['phoneNumber'];
$session_id = $_GET['sessionId'];
$service_code = $_GET['serviceCode'];
$ussd_string= $_GET['text'];


$level = 0;

$ussd_string_exploded = explode ("*",$ussd_string);
$level = count($ussd_string_exploded);

if($level == 1 or $level == 0){
    
    display_menu();
}

if ($level == 2)
{

    if ($ussd_string_exploded[0] == "1")
    {
      display_register_info();
    }

  else if ($ussd_string_exploded[0] == "2"){
        about($ussd_string_exploded);
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
    ussd_stop($ussd_text);
}


function open_account($details,$phone, $dbh){
    if(count($details) == 2)
    {
        
        $ussd_text = "Please enter your Full Name and Email, each seperated by commas:";
        ussd_proceed($ussd_text); // ask user to enter registration details
    }
    if(count($details)== 3)
    {
        if (empty($details[1])){
                $ussd_text = "Sorry we do not accept blank values";
                ussd_proceed($ussd_text);
        } else {
        $input = explode(",",$details[1]);//store input values in an array
        $full_name = $input[0];//store full name
        $email = $input[1];//store email
        $phone_number =$phone;//store phone number 

        // build sql statement
        $sth = $dbh->prepare("INSERT INTO customer (full_name, email, phone) VALUES('$full_name','$email','$phone_number')");
        //execute insert query   
        $sth->execute();
        if($sth->errorCode() == 0) {
            $ussd_text = $full_name." your registration was successful. Your email is ".$email." and phone number is ".$phone_number;
            ussd_proceed($ussd_text);
        } else {
            $errors = $sth->errorInfo();
        }
    }
}
}
# close the pdo connection  
$dbh = null;
?>

