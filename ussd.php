<?php 

  $sessionId = $_POST['sessionId'];
  $serviceCode = $_POST['serviceCode'];  
  $phoneNumber = $_POST['phoneNumber'];
  $text = $_POST['text']; 


  if($text == ""){
  	$response = "CON Welcome to Barry Financial Services\n\n";
  	$response .= "Please select option:\n";
  	$response .= "1. Our services\n";
  	$response .= "2. Make transaction\n";
  	$response .= "3. Make enquiry\n";
    $response .= "4. View branches\n";

  }

  echo $response;

?>
